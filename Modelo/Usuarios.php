<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");

class Usuario{

    public function __construct(){

    }
    public function ListarTodos(){
    try {
            $base=new BD();
            $resultado=$base->query("select * from joacosch.usuarios");   
            return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function BuscarPorId($id){

    }

    public function Crear($data){
        try     
        {
            $base=new BD();  
            // Verificar que el usuario no exista
            
            $timestamp_unix = time();
            $data['fecharegistro'] = date('Y-m-d H:i:s', $timestamp_unix);
            $fecha_obj = date_create($data['fechanac']);
            // $data['fechanac'] = $fecha_obj;
            $resultado=$base->Insert("joacosch.usuarios",$data);
            return  new Respuesta(true,"","","");
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
            
            $resultado=$base->Update("joacosch.Usuarios",$data,$condicion);
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
            $resultado=$base->Delete("joacosch.usuarios",$id);
            return  new Respuesta(true,"","","");
            // return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function getPlanes(){

    }

}