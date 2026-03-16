<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."Sistema/Respuesta.php");
include_once($path_cli."modelo/planes.php");

class PlanesController{

    public function Index(){
        $planes=new Planes();
        $ret = $planes->ListarTodos();
        if(!$ret->success){
            $ret=new Respuesta(false, null, "DB_ERROR", "No se pudo acceder a los planes");        
        }
        return $ret;
    }

    public function Mostrar($id){

    }

    public function Crear($request){

    }

    public function Actualizar($id, $request){

    }

    public function Eliminar($id){

    }

    public function getUsuarios($id_plan){

    }
}