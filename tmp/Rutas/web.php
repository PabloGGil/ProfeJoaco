<?php
// routes/web.php

require_once '../Controladores/UsuarioController.php';
require_once '../Controladores/PlanesController.php';
require_once '../Controladores/AsignacionController.php';
require_once '../Controladores/SeguimientoController.php';

class Router {
    public  $routes = [];
    
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function handleRequest() {
        echo $_SERVER['REQUEST_METHOD'];
        echo $_SERVER['REQUEST_URI'];
        // if(isset($_SERVER['REQUEST_METHOD'])){
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            
            // $requestBody=
            echo "request Method: " .$requestMethod. "\n";
            echo "request URI:" .$requestUri. "\n";
        // }
        // else
        // {
        //     return $this->sendJsonResponse(400,"Request method esta vacio");

        // }       
        foreach ($this->routes as $route) {
            // Convertir parámetros dinámicos {id} a regex
            $patron = $this->buildPattern($route['path']);
            $coincidencia=preg_match($patron, $requestUri, $matches);
            // echo "metodo: " . $route['method'] . "\n";
            // echo "Controlador: ". $route['controller'] ."\n";
            echo "patron: ". $patron ."\n";
            echo "coincide?" . $coincidencia;
            // 
            if ($route['method'] === $requestMethod && $coincidencia) {
                 echo "patron: ". $patron ."\n";
                array_shift($matches); // Remover match completo
                $this->callController($route['controller'], $route['action'], $matches);
                // $this->callController($route['controller'], $route['action'], $matches);
               
                return;
            }
        }
        
        // Si no encuentra ruta
        $this->sendJsonResponse(404, ['error' => 'Endpoint no encontrado']);
    }
    
    private function buildPattern($path) {
        // Convertir /usuarios/{id} a #^/usuarios/([^/]+)$#
        // $pattern = preg_replace( '/\{([^}]+)\}/','([^/]+)', $path);
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/])*', $path);
        // return  $pattern ;
        return '#' . $pattern . '$#';
        // return  $pattern ;
    }
    
    private function callController($controllerClass, $action, $params = []) {
        $controller = new $controllerClass();
        $input = file_get_contents('php://input')();
        $requestData=json_decode($input, true);
        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], array_merge($params, [$requestData]));
        } else {
            $this->sendJsonResponse(500, ['error' => 'Método no implementado']);
        }
    }
    
    private function sendJsonResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

// Crear instancia del router
$router = new Router();

// // Cargar rutas desde archivos separados
$rutasUsuario = require 'UsuariosRoute.php';
$rutasPlan = require 'PlanesRoute.php';
$rutasAsignacion = require 'AsignacionRoute.php';
$rutasSeguimiento = require 'SeguimientoRoute.php';
// $router->addRoute('POST', '/usuarios', 'UsuarioController', 'crear');
// Ejecutar las funciones para registrar rutas
$rutasUsuario($router);
$rutasPlan($router);
$rutasAsignacion($router);
$rutasSeguimiento($router);
// // var_dump($router->routes);
// // Ruta de health check
// // $router->addRoute('GET', '/health', 'HealthController', 'check');

$router->handleRequest();