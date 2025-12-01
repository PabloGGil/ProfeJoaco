<?php

include_once '../Controladores/UsuarioController.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$accion=$_GET['accion'] ?? '';

class Respuesta{
    public $estado=false;
    public $mensaje="";
    public $error="";
    public $informacion="";
}
$respuesta=new Respuesta();

switch($accion)
{
    //Usuarios
    case 'ListarTodos':
        break;
    case 'MostrarUsuario':
        break;
    case 'CrearUsuario':
        $respuesta->estado=true;
        $respuesta->mensaje="usuario creado";
        $respuesta->error="";
        $respuesta->informacion="";
        break;
    case 'EditarUsuario':
        break;
    case 'EliminarUsuario':
        break;
    
    //Planes
    case 'ListarTodos':
        break;
    case 'MostrarPlan':
        break;
    case 'CrearPlan':
        break;
    case 'EditarPlan':
        break;
    case 'EliminarPlan':
        break;

    //Seguimiento
    case 'ListarTodos':
        break;
    case 'MostrarSeguimiento':
        break;
    case 'CrearSeguimiento':
        break;
    case 'EditarSeguimiento':
        break;
    case 'EliminarSeguimiento':
        break;

    //Asignacion
    case 'ListarTodos':
        break;
    case 'MostrarAsignacion':
        break;
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

echo json_encode($respuesta);

