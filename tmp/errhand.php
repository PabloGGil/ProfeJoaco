<?php


$handler = function (int $errno, string $errstr, ?string $errfile, ?int $errline) {
     echo "Error: " . $errstr . "\n";
};

$x=set_error_handler($handler);
var_dump($x); // NULL



var_dump( $handler); // bool(true)

?>