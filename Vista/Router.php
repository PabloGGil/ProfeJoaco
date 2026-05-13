<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."Sistema/logger.php");
require_once $path_cli.'Sistema/Respuesta.php';
require_once $path_cli.'Controladores/UsuarioController.php';
require_once $path_cli.'Controladores/EjercicioController.php';
require_once $path_cli.'Controladores/PlanesController.php';
require_once $path_cli.'Controladores/TestBD.php';

// Definimos el mapa de acciones
$acciones = [
    'Usuario'=>[
        'ListarUsuarios' => [UsuarioController::class, 'Index'],
        'CrearUsuario'   => [UsuarioController::class, 'Crear'],
        'EliminarUsuario'=> [UsuarioController::class, 'Eliminar'],
        'EditarUsuario'  => [UsuarioController::class, 'Actualizar'],
        'PlanesUsuario'  => [UsuarioController::class, 'getPlanes']
    ],
    'Ejercicio'=>[
        'ListarEjercicios' => [EjercicioController::class, 'Index'],
        'CrearEjercicio'   => [EjercicioController::class, 'Crear'],
        'EliminarEjercicio'=> [EjercicioController::class, 'Eliminar'],
        'EditarEjercicio'  => [EjercicioController::class, 'Actualizar'],
        // 'PlanesUsuario'  => [UsuarioController::class, 'getPlanes']
    ],
     'Plan'=>[
        'ListarPlanes' => [PlanesController::class, 'Index'],
        'CrearPlan'   => [PlanesController::class, 'Crear'],
        'EliminarPlan'=> [PlanesController::class, 'Eliminar'],
        'EditarPlan'  => [PlanesController::class, 'Actualizar'],
        // 'PlanesUsuario'  => [UsuarioController::class, 'getPlanes']
    ],
     'Test'=>[
        'testdirecto' => [test::class, 'testdirecto'],
        'testClasebd' => [test::class, 'testClasebd'],
       // 'PlanesUsuario'  => [UsuarioController::class, 'getPlanes']
    ],
];
$Log=new Logger();

$input = file_get_contents('php://input');
$Log->log("Iniciando router--- ","INFO",$input);
$params = !empty($input) ? json_decode($input, true, 512, JSON_UNESCAPED_UNICODE) : [];
// $accion = $_GET['q'] ?? null;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

$modulo = $_GET['controlador'] ?? null;
$accion = $_GET['accion'] ?? null;
if (!$modulo || !$accion) {
    http_response_code(400);
    echo json_encode([
        "error" => "Faltan parámetros de ruteo",
        "recibido" => $_GET // Esto te ayudará a ver qué está llegando
        
    ]);
    $Log->error("Faltan parámetros de ruteo");
    exit;
}
if ($accion && isset($acciones[$modulo][$accion])) {
    [$class, $method] = $acciones[$modulo][$accion];

    try {
        $controller = new $class();
        $Log->log("router--- direccionando a:","INFO",$class."---". $method);
        $respuesta = call_user_func([$controller, $method], $params);

        // Siempre devolvemos JSON
        echo json_encode($respuesta);

    } catch (Exception $e) {
        $respuesta = new Respuesta(false, null, "SERVER_ERROR", "Error interno del servidor");
        $Log->error("Error en acción $accion: " . $e->getMessage());

        echo json_encode($respuesta);
    }

} else {
    $respuesta = new Respuesta(false, null, "NOT_FOUND", "Acción no encontrada");
    $Log->error("Acción no encontrada");
    echo json_encode($respuesta);
}
