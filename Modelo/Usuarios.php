<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");

class Usuario{
    private $tipoBD;
    private $esquema;
    private $tabla="usuarios";

    public function __construct(){
        $conf= CargaConfiguracion::getInstance('');
		$this->tipoBD=$conf->leeParametro("tipobd");
		$this->esquema=$conf->leeParametro("schema");
        if($this->tipoBD=="postgres"){
            $this->tabla=$this->esquema .".".$this->tabla;
        }
    }
    public function ListarTodos(){
    try {
            $base=new BD();
            $resultado=$base->query("select * from {$this->tabla}");   
            return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function BuscarPorId($id){

    }
    public function ExisteUsuario($correo){
        $base=new BD(); 
        $correo=trim($correo);
        $resultado=$base->query("select count(*) as cuenta from {$this->tabla} where correo='{$correo}' ");
        $existe=$resultado->data[0]['cuenta'];
        if($existe)
            return true;
        else
            return false;
    }
    public function Crear($data){
        try     
        {
            $base=new BD();  
            // Verificar que el usuario no exista
            if(!$this->ExisteUsuario($data['correo'])){
                $resultado=$base->Insert($this->tabla,$data);
                if($resultado){
                    return  new Respuesta(true,"",$resultado->errorCode,$resultado->errorCode);
                }
            }else{
                return  new Respuesta(false,"","","El usuario ya existe");
            }
            // $fecha_obj = date_create($data['fechaNacimiento']);
            // $data['fechanac'] = $fecha_obj;
            
            
            // return  $resultado;
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