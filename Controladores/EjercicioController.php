<?php
    // $data = json_decode(file_get_contents('php://input'), true);
    // var_dump($data);
class EjercicioController{

    //Listar Todos los ejercicios
    public function Index(){

    }
    //Muestra un ejercicio por ID
    public function Mostrar($id){
        $ejercicio=new Ejercicios();
        $data = $ejercicio->ListarTodos();
        $retorno = array('rc' => '0', 'msg' => 'ejercicio encontrado ');
        return $retorno;

    }

    // public function Crear($request){
    public function Crear($request){
        // $data = json_decode(file_get_contents('php://input'), true);
        var_dump($request);
        $retorno = array('rc' => '0', 'msg' => 'ejercicio agregado ');
        echo json_encode($retorno);
    }

    public function Actualizar($id, $request){

    }

    public function Eliminar($id){

    }

    public function getPlanes($id_ejercicio){

    }
}