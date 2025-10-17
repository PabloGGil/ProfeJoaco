<?php
// routes/planes.php

return function($router) {
    // Rutas de Plan
    $router->addRoute('GET', '/planes', 'PlanController', 'index');
    $router->addRoute('GET', '/planes/{id}', 'PlanController', 'Mostrar');
    $router->addRoute('POST', '/planes', 'PlanController', 'Crear');
    $router->addRoute('PUT', '/planes/{id}', 'PlanController', 'Actualizar');
    $router->addRoute('DELETE', '/planes/{id}', 'PlanController', 'Eliminar');
    $router->addRoute('GET', '/planes/{id}/usuarios', 'PlanController', 'getUsuarios');
};