<?php
$path_cli=__DIR__.'/../';
require_once $path_cli.'Sistema/Respuesta.php';
require_once $path_cli.'controladores/UsuarioController.php';
require_once $path_cli.'controladores/EjercicioController.php';

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
];
$input = file_get_contents('php://input');
$params = !empty($input) ? json_decode($input, true, 512, JSON_UNESCAPED_UNICODE) : [];
$accion = $_GET['q'] ?? null;
// $params = json_decode(file_get_contents('php://input'), true, 512, JSON_UNESCAPED_UNICODE);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
$segments = explode('/', trim($uri, '/')); 
// Primer segmento = módulo (usuarios, ventas, etc.) 
$modulo = $segments[2] ?? null; 
// Segundo segmento = acción (listar, crear, etc.) 
$accion = $segments[3] ?? null; 


if ($accion && isset($acciones[$modulo][$accion])) {
    [$class, $method] = $acciones[$modulo][$accion];

    try {
        $controller = new $class();
        $respuesta = call_user_func([$controller, $method], $params);

        // Siempre devolvemos JSON
        echo json_encode($respuesta);

    } catch (Exception $e) {
        $respuesta = new Respuesta(false, null, "SERVER_ERROR", "Error interno del servidor");
        error_log("Error en acción $accion: " . $e->getMessage());
        echo json_encode($respuesta);
    }

} else {
    $respuesta = new Respuesta(false, null, "NOT_FOUND", "Acción no encontrada");
    echo json_encode($respuesta);
}
