<?php
    // $data = json_decode(file_get_contents('php://input'), true);
    // var_dump($data);
class UsuarioController{

    //Listar Todos los usuarios
    public function Index(){

    }
    //Muestra un usuario por ID
    public function Mostrar($id){
       $data = json_decode(file_get_contents('php://input'), true);
        $retorno = array('rc' => '0', 'msg' => 'usuario encontrado ');
        echo json_encode($retorno);

    }

    // public function Crear($request){
    public function Crear($request){
        // $data = json_decode(file_get_contents('php://input'), true);
        var_dump($request);
        $retorno = array('rc' => '0', 'msg' => 'usuario agregado ');
        echo json_encode($retorno);
    }

    public function Actualizar($id, $request){

    }

    public function Eliminar($id){

    }

    public function getPlanes($id_usuario){

    }
}