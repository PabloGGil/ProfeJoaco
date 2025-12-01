<?php
// routes/Seguimiento.php

return function($router) {
    // Rutas de Seguimiento
    $router->addRoute('GET', '/seguimiento', 'SeguimientoController', 'index');
    $router->addRoute('GET', '/seguimiento/{id}', 'SeguimientoController', 'Mostrar');
    $router->addRoute('POST', '/seguimiento', 'SeguimientoController', 'Crear');
    $router->addRoute('PUT', '/seguimiento/{id}', 'SeguimientoController', 'Actualizar');
    $router->addRoute('DELETE', '/seguimiento/{id}', 'SeguimientoController', 'Eliminar');
    $router->addRoute('GET', '/seguimiento/{id}/planes', 'SeguimientoController', 'getPlanes');
};