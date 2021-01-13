<?php
require 'vendor/autoload.php'; // incluir lo bueno de Composer

$cliente = new MongoDB\Client("mongodb://localhost:27017");
$coleccion = $cliente->vuelos2_0->vuelos;

$resultado = $coleccion->find();

foreach ($resultado as $entry) {
    echo $entry['codigo'], ': ', $entry['origen'], ' => ', $entry['destino'],  "\n";
}

//$resultado = $coleccion->insertOne( [ 'id' => 10, 'origen' => 'BrewDog', 'destino' => 'BrewDog', 'codigo' => 'AA123', 'fecha' => '2020-03-02', 'hora' => '13:02:02', 'plazas_totales' => 250, 'plazas_disponibles' => 50 ] );

//echo "Inserted with Object ID '{$resultado->getInsertedId()}'";
?>