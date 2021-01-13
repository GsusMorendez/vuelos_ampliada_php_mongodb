<?php
require 'vendor/autoload.php'; // incluir lo bueno de Composer

$cliente = new MongoDB\Client("mongodb://localhost:27017");

function funcionesGet($coleccion){

    //Acceder al contenido de la url, para poder saber en que punto estoy del if
 
    
    

   /* if(){

        $coleccion = $cliente->vuelos2_0->vuelos;

        $resultado = $coleccion->find();
        
        foreach ($resultado as $entry) {
            echo $entry['codigo'], ': ', $entry['origen'], ' => ', $entry['destino'],  "\n";
        }
    }*/





}





?>