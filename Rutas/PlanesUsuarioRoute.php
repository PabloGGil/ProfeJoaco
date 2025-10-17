<?php
// routes/asignaciones.php

return function($router) {
    // Rutas de Asignación
    $router->addRoute('POST', '/asignaciones', 'AsignacionController', 'asignarPlan');
    $router->addRoute('DELETE', '/asignaciones', 'AsignacionController', 'eliminarAsignacion');
    $router->addRoute('GET', '/asignaciones/usuario/{usuario_id}', 'AsignacionController', 'getAsignacionesUsuario');
    $router->addRoute('GET', '/asignaciones/plan/{plan_id}', 'AsignacionController', 'getAsignacionesPlan');
};