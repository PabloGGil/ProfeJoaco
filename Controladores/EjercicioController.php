<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."Sistema/Respuesta.php");
include_once($path_cli."modelo/Ejercicios.php");

class EjercicioController{

    //Listar Todos los ejercicios
    public function Index(){
        $ejercicio=new Ejercicios();
        $ret = $ejercicio->ListarTodos();
        if(!$ret->success){
            $ret=new Respuesta(false, null, "DB_ERROR", "No se pudo guardar el Ejercicio.");        
        }
        return $ret;
    }
    //Muestra un ejercicio por ID
    public function Mostrar($id){
       

    }

   
    public function Crear($data){
        $ejercicio=new Ejercicios();
        unset($data['q']);
        $ret = $ejercicio->Crear($data);
        return $ret;
    }

    public function Actualizar( $data){
        $ejercicio=new Ejercicios();
        unset($data['q']);
        $ret = $ejercicio->Actualizar($data);
        return $ret;
    }

    public function Eliminar($id){
        $ejercicio=new Ejercicios();
       
        $ret = $ejercicio->Eliminar($id);
        return $ret;
    }

    public function getPlanes($id_ejercicio){

    }
}