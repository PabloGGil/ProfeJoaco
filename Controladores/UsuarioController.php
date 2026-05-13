<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."Sistema/Respuesta.php");
include_once($path_cli."Modelo/Usuarios.php");

class UsuarioController{

    //Listar Todos los usuarios
    public function Index(){
       $usuario=new Usuario();
       $ret=$usuario->ListarTodos();
       if(!$ret->success){
            $ret=new Respuesta(false, null, "DB_ERROR", "Error en la lectura.");        
        }
        return $ret;
    }
    //Muestra un usuario por ID
    public function Mostrar($id){
 

    }

    // public function Crear($request){
    public function Crear($data){
        $usuario=new Usuario();
        // unset($data['q']);
        
        $timestamp_unix = time();
        $data['fecharegistro'] = date('Y-m-d H:i:s', $timestamp_unix);
        $ret = $usuario->Crear($data);
        return $ret;
    }

    public function Actualizar(array $data){
        $usuario=new Usuario();
        unset($data['edad']);
        $ret = $usuario->ActualizarUsuario($data);
        return $ret;
    }

    public function Eliminar($id){
       $usuario=new Usuario();
       
       $ret = $usuario->EliminarUsuario(['id'=>$id]);
       return $ret;
    }

    public function getPlanes($id_usuario){

    }
}