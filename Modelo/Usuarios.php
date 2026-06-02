<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");

class Usuario{
    private $tipoBD;
    private $esquema;
    private $tabla="usuarios";
    private $select;

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

    public function Login($data){
        $bd=new BD();
        $querySQL="select * from {$this->tabla} where correo='{$data['usuario']}'";
        //  and passwd='{$data['passwd']}'";
        $rta=$bd->query($querySQL);
        
        if(count($rta->data)==0){
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
            $base=new BD();  
            
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