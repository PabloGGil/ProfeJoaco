<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."Sistema/Respuesta.php");
include_once($path_cli."Modelo/Usuarios.php");
session_start();
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

    public function cambioPwd($dataUsuario){
         $usr=new Usuario();
 
        $ret=$usr->CambioPassd(['passwd'=>$dataUsuario['password'],'usuario'=>$dataUsuario['usuario']]);
        if($ret->success){
            return $ret;
        }
    }
    public function Login($dataLogin){
        $usr=new Usuario();
 
        $ret=$usr->Login(['passwd'=>$dataLogin['password'],'usuario'=>$dataLogin['usuario']]);
        $_SESSION['rol']=isset($ret->data[0]['rol'])?$ret->data[0]['rol']:'anonimo';
        if($ret->data[0]['acceso'] ){
            
            $_SESSION['logueado']=true;
            $_SESSION['usuario']=$dataLogin['usuario'];
            $_SESSION['login_time'] = time();
        
        // Forzar escritura de sesión
            session_write_close();
            return ['loginOK'=>true, 'rol'=>$_SESSION['rol']];
        }else{
            $_SESSION['logueado']=false;
            return ['loginOK'=>false, 'rol'=>$_SESSION['rol']];
        }
        // return ['loginOK'=>false, 'rol'=>$ret->data['rol']];
    }

    // Verificar que el usuario no exista
    public function ExisteUsuario($correo){
        $usr=new Usuario(); 
        // $data=[];
        $existe=$usr->getUsuarioCond(["correo"=>$correo]);

        if(count($existe->data)>0){
            // $data[]=false;
            return true;
        }
        else{
            // $data[]=true;
            return false;
        }
    }
 
    public function Crear($data){
        $usuario=new Usuario();
        // Verificar que el usuario no exista
        $existe=$this->ExisteUsuario($data['correo']);
        if($existe){
            return new Respuesta(false,null,"", "El usuario ya esta registrado");
        }
        $timestamp_unix = time();
        $data['fecharegistro'] = date('Y-m-d H:i:s', $timestamp_unix);
        $data['rol']='usuario';
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