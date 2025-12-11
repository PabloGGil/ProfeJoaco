<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");
class Ejercicios{

    public function __construct(){

    }
    public function ListarTodos(){
        try {
                $base=new BD();
                $resultado=$base->execSql("select * from joacosch.ejercicios");
                var_dump($resultado);
           
                return new Respuesta(true, $resultado);
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function BuscarPorId($id){

    }

    public function CrearUsuario($data){

    }

    public function ActualizarUsuario($id, $data){

    }

    public function EliminarUsuario($id){

    }

    public function getPlanes(){

    }

}

$ej=new Ejercicios();
var_dump($ej->ListarTodos());