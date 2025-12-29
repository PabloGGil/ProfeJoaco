<?php

include_once '../Controladores/UsuarioController.php';
include_once '../Controladores/EjercicioController.php';
include_once '../Sistema/Respuesta.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// $accion=$_GET['q'] ?? '';
$estado=false;
$mensaje="";
$error="";
$info=[];

// class Respuesta{
//     public $estado=false;
//     public $mensaje="";
//     public $error="";
//     public $info=[];
// }
$respuesta=new Respuesta(false,null,null,null);

class Usuario {
                public $id= 1;
                public $nombre= 'Ana';
                public $apellido= 'García';
                public $correo= 'ana.garcia@example.com';
                public $fechaNacimiento= '1990-05-15';
            }

if(isset($_GET['q'])){
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
            $ejercicio=new EjercicioController();
            $respuesta=$ejercicio->Index();
            $mensaje="Lista de usuarios"; 
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
            $ejercicio=new EjercicioController();
            $respuesta=$ejercicio->Crear($params);
            break;
        case 'EditarEjercicio':
            $ejercicio=new EjercicioController();
            $respuesta=$ejercicio->Actualizar($params);
            break;
        case 'EliminarEjercicio':
            $ejercicio=new EjercicioController();
            $respuesta=$ejercicio->Eliminar($params['id']);
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


header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

