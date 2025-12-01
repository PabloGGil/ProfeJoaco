<?php
// routes/usuarios.php

return function($router) {
    // Rutas de Usuario
    $router->addRoute('GET', '/usuarios', 'UsuarioController', 'index');
    $router->addRoute('GET', '/usuarios/{id}', 'UsuarioController', 'Mostrar');
    $router->addRoute('POST', '/usuarios', 'UsuarioController', 'Crear');
    $router->addRoute('PUT', '/usuarios/{id}', 'UsuarioController', 'Actualizar');
    $router->addRoute('DELETE', '/usuarios/{id}', 'UsuarioController', 'Eliminar');
    $router->addRoute('GET', '/usuarios/{id}/planes', 'UsuarioController', 'getPlanes');
};