<?php

include_once '../Controladores/UsuarioController.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// $accion=$_GET['q'] ?? '';
$estado=false;
$mensaje="";
$error="";
$info=[];

class Respuesta{
    public $estado=false;
    public $mensaje="";
    public $error="";
    public $info=[];
}
$respuesta=new Respuesta();

class Usuario {
                public $id= 1;
                public $nombre= 'Ana';
                public $apellido= 'García';
                public $correo= 'ana.garcia@example.com';
                public $fechaNacimiento= '1990-05-15';
            }

if($_GET['q']){
   switch($_GET['q'])
    {
        //Usuarios
        case 'ListarUsuarios':
            $usuario=new Usuario();
            $info[]=$usuario;
            $mensaje="Lista de usuarios";
            break;
        case 'MostrarUsuario':
            break;
        //Ejercicios
        case 'ListarEjercicios':
            break;
        case 'MostrarEjercicio':
            break;
        //Planes
        case 'ListarPlanes':
            break;
        case 'MostrarPlan':
            break;
        //Seguimiento
        case 'ListarSeguimiento':
            break;
        case 'MostrarSeguimiento':
            break;
        //Asignacion
        case 'ListarAsignacion':
            break;
        case 'MostrarAsignacion':
            break;
    }
}else
{
    $params = json_decode(file_get_contents('php://input'), true, 512, JSON_UNESCAPED_UNICODE);

    switch ($params['q']) {
        case 'CrearUsuario':
           
            break;
        case 'EditarUsuario':
            break;
        case 'EliminarUsuario':
            break;
        //Ejercicios
        case 'CrearEjercicio':
          
            break;
        case 'EditarEjercicio':
            break;
        case 'EliminarEjercicio':
            break;
        //Planes

        case 'CrearPlan':
            break;
        case 'EditarPlan':
            break;
        case 'EliminarPlan':
            break;

        //Seguimiento

        case 'CrearSeguimiento':
            break;
        case 'EditarSeguimiento':
            break;
        case 'EliminarSeguimiento':
            break;

        //Asignacion

        case 'CrearAsignacion':
            break;
        case 'EditarAsignacion':
            break;
        case 'EliminarAsignacion':
            break;
        default :
            $estado='error';
            $error='Accion Invalida';
            break;
    }
}

$respuesta->estado=$estado;
$respuesta->mensaje=$mensaje;
$respuesta->error=$error;
$respuesta->informacion=$data;
echo json_encode($respuesta);

