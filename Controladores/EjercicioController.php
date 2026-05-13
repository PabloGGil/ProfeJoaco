<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."Sistema/Respuesta.php");
include_once($path_cli."Modelo/Ejercicios.php");
include_once($path_cli."Sistema/logger.php");

class EjercicioController{
    private $log;

    public function __construct() {
        $this->log = new Logger();
    }
    //Listar Todos los ejercicios
    public function Index(){
        $ejercicio=new Ejercicios();
        $ret = $ejercicio->ListarTodos();
        $this->log->log("data ejercicio controller","INFO",$ret);
        if(!$ret->success){
            $this->log->error("No se pudo listar el Ejercicio.",$ret);
            $ret=new Respuesta(false, null, "DB_ERROR", "No se pudo listar el Ejercicio.");        
        } 
        // $ret=new Respuesta(false, null, "DB_ERROR", "No se pudo guardar el Ejercicio.");
        return $ret;
    }
    //Muestra un ejercicio por ID
    public function Mostrar($id){
       

    }

   
    public function Crear(array $data){
        $ejercicio=new Ejercicios();
        unset($data['q']);
        $ret = $ejercicio->Crear($data);
        return $ret;
    }

    public function Actualizar( array $data){
        $ejercicio=new Ejercicios();
        unset($data['q']);
        $ret = $ejercicio->Actualizar($data);
        return $ret;
    }

    public function Eliminar( $id){
        $ejercicio=new Ejercicios();
       
        $ret = $ejercicio->Eliminar(['id'=>$id]);
        return $ret;
    }

    public function getPlanes($id_ejercicio){

    }
}