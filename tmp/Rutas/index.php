<?php
// public/index.php

// Configuración básica
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Cargar configuración y autoloader
require_once '../config/database.php';
require_once '../Rutas/web.php';

// Manejar la request
$router->handleRequest();