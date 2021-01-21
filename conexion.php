<?php
require 'vendor/autoload.php'; 

try {
    $cliente = new MongoDB\Client("mongodb://localhost:27017");
    $coleccion = $cliente->vuelos2_0->vuelos;
} catch (Exception $e) {
    //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    echo "error";
    die();
}



?>