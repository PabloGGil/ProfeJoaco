<?php
// routes/web.php

require_once '../controladores/UsuarioController.php';
require_once '../controladores/PlanController.php';
require_once '../controladores/PlanUsuarioController.php';
require_once '../controladores/SeguimientoController.php';

class Router {
    private $routes = [];
    
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function handleRequest() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            // Convertir parámetros dinámicos {id} a regex
            $pattern = $this->buildPattern($route['path']);
            
            if ($route['method'] === $requestMethod && 
                preg_match($pattern, $requestUri, $matches)) {
                
                array_shift($matches); // Remover match completo
                $this->callController($route['controller'], $route['action'], $matches);
                return;
            }
        }
        
        // Si no encuentra ruta
        $this->sendJsonResponse(404, ['error' => 'Endpoint no encontrado']);
    }
    
    private function buildPattern($path) {
        // Convertir /usuarios/{id} a #^/usuarios/([^/]+)$#
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    private function callController($controllerClass, $action, $params = []) {
        $controller = new $controllerClass();
        
        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
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

// Cargar rutas desde archivos separados
$rutasUsuario = require 'UsuariosRoute.php';
$rutasPlan = require 'PlanesRoute.php';
$rutasAsignacion = require 'AsignacionRoute.php';
$rutasSeguimiento = require 'SeguimientoRoute.php';

// Ejecutar las funciones para registrar rutas
$rutasUsuario($router);
$rutasPlan($router);
$rutasAsignacion($router);

// Ruta de health check
$router->addRoute('GET', '/health', 'HealthController', 'check');

$router->handleRequest();