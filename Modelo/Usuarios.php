<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");

class Usuario{
    private $tipoBD;
    private $esquema;
    private $tabla="usuarios";
    private $select;
    private $secretKey = 'pp234';

    public function __construct(){
        $conf= CargaConfiguracion::getInstance('');
		$this->tipoBD=$conf->leeParametro("tipobd");
		$this->esquema=$conf->leeParametro("schema");
        if($this->tipoBD=="postgres"){
            $this->tabla=$this->esquema .".".$this->tabla;
        }
        $this->select="SELECT * FROM {$this->tabla} ";
    }
    public function ListarTodos(){
    try {
            $base=new BD();
            $resultado=$base->query($this->select);   
            return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function getUsuarioCond($condicion){
        $where="";
        try{
            $base=new BD();
            foreach($condicion as $llave=>$valor){
                $where= "WHERE {$llave}='{$valor}'";
               
            }
            
            $resultado=$base->query($this->select .$where );
            return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }
    public function getUsuarioxID($id){

    }

    function desencriptarCryptoJS($textoCifrado, $claveSecreta) {
    // 1. Decodificar el texto cifrado que viene en Base64
    $datosFormateados = base64_decode($textoCifrado);
    
    // Validar que los datos decodificados contengan el prefijo "Salted__" (8 bytes)
    if (substr($datosFormateados, 0, 8) !== "Salted__") {
        return false; // No es el formato esperado de CryptoJS
    }
    
    // 2. Extraer la sal (salt) de los bytes 8 al 16 (8 bytes)
    $sal = substr($datosFormateados, 8, 8);
    
    // 3. Extraer el texto cifrado real (desde el byte 16 en adelante)
    $bloqueCifrado = substr($datosFormateados, 16);
    
    // 4. Reconstruir la Clave (Key) y el Vector de Inicialización (IV)
    // CryptoJS utiliza el método de derivación de clave EVP_BytesToKey (MD5 repetido)
    $claveDerivada = '';
    $datosPrevios = '';
    
    // Necesitamos generar suficiente material de clave para AES-256-CBC (32 bytes de clave + 16 bytes de IV = 48 bytes en total)
    while (strlen($claveDerivada) < 48) {
        $datosPrevios = md5($datosPrevios . $claveSecreta . $sal, true);
        $claveDerivada .= $datosPrevios;
    }
    
    // Separamos la clave y el IV derivados
    $key = substr($claveDerivada, 0, 32);
    $iv  = substr($claveDerivada, 32, 16);
    
    // 5. Desencriptar usando OpenSSL
    // CryptoJS usa por defecto el método AES-256-CBC con relleno PKCS7 (OPENSSL_RAW_DATA)
    $textoPlano = openssl_decrypt(
        $bloqueCifrado, 
        'aes-256-cbc', 
        $key, 
        OPENSSL_RAW_DATA, 
        $iv
    );
    
    return $textoPlano;
}

    public function CambioPassd($data){
        try{
            $bd=new BD();
            $querySQL="select * from {$this->tabla} where correo='{$data['usuario']}'";
            $rta=$bd->query($querySQL);
            if($rta->success){
                $decryptedPassword = $this->desencriptarCryptoJS($data['passwd'],$this->secretKey);
                $passwordHash = password_hash($decryptedPassword, PASSWORD_DEFAULT);
                $condicion['id']= $rta->data[0]['id'];
                $datax['passwd']=$passwordHash;   
                 
                $resultado=$bd->Update($this->tabla,$datax,$condicion);
            }
            return  $resultado;
        }catch(Exception $e){
             return new Respuesta(false, null, "MODEL-ERROR", $e->getMessage());
        }
    }

    public function Login($data){
        $bd=new BD();

        
        $decryptedPassword = $this->desencriptarCryptoJS($data['passwd'],$this->secretKey);
        $querySQL="select * from {$this->tabla} where correo='{$data['usuario']}'";
        
        $rta=$bd->query($querySQL);
        $hashGuardadoEnBD=$rta->data[0]['passwd'];
        $passwdOk= password_verify($decryptedPassword, $hashGuardadoEnBD);
        if(!$passwdOk){
            // usuario o contraseña incorrectos
            $rta->data[0]['acceso']=false;
            return  new Respuesta(true,$rta->data,$rta->errorCode,"Usuario o contraseña incorrecto");
        }
        $rta->data[0]['acceso']=true;
        return  new Respuesta(true,$rta->data,"","");;
    }
    
    public function Crear($data){
        try     
        {
            $decryptedPassword = $this->desencriptarCryptoJS($data['passwd'],$this->secretKey);
        // openssl_decrypt($data['passwd'], 'aes-128-cbc', $secretKey, 0, $secretKey);
            $passwordHash = password_hash($decryptedPassword, PASSWORD_DEFAULT);
            $base=new BD();  
            $data['passwd']=$passwordHash;
            $resultado=$base->Insert($this->tabla,$data);
            if(!$resultado->success){
                return  new Respuesta(false,"",$resultado->errorCode,$resultado->errorCode);
            }
            return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function ActualizarUsuario(array $data){
        try     
        {
            $decryptedPassword = $this->desencriptarCryptoJS($data['passwd'],$this->secretKey);
        // openssl_decrypt($data['passwd'], 'aes-128-cbc', $secretKey, 0, $secretKey);
            $passwordHash = password_hash($decryptedPassword, PASSWORD_DEFAULT);
              
            $data['passwd']=$passwordHash;
            $base=new BD(); 
            $condicion['id']= $data['id'];
            
            $resultado=$base->Update($this->tabla,$data,$condicion);
            return  $resultado;
            // return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    
    }

    public function EliminarUsuario($id){
     try     
        {
            $base=new BD();  
            $resultado=$base->Delete($this->tabla,$id);
            return  new Respuesta(true,"","","");
            // return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function getPlanes(){

    }

}