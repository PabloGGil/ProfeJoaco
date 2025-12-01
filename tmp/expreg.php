<?php
$patron='#usuarios/([^/]+)*#';
$requestUri='/rutas/web.php/usuarios/1';
$coincidencia= preg_match( $patron, $requestUri, $matches);
echo "encontrado: ".$coincidencia;
var_dump($matches);
$pattern = preg_replace( '/\{([^]+)\}/','([^/]+)', $requestUri);
echo "encontrado: ".$coincidencia;
var_dump($matches);
echo "resultado:". $pattern;