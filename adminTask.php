<?php
    require 'conexion.php';
    //Ejecutar la primera vez para que se actualicen y coordinen los asientos libres y los vendidos

    $resultado = $coleccion->find();
    $misVuelos = array();
   
    foreach ($resultado as $entry) {
        $vuelo = array();
        if (isset($entry['vendidos'])) {
            $vuelo['vendidos'] = $entry['vendidos'];
            $vuelo['codigo'] = $entry['codigo'];
            $vuelo['plazas_totales'] = $entry['plazas_totales'];
        }
        $misVuelos[] = $vuelo;
    }
     for ($i=0; $i < count($misVuelos) ; $i++) { 
        if (count($misVuelos[$i]) != 0) {
            if(isset($misVuelos[$i]['vendidos'])){
                $asientos = array();
                $numAsientos = 0;
                for ($e=0; $e < count($misVuelos[$i]['vendidos']); $e++) { 
                    $asientos[] = $misVuelos[$i]['vendidos'][$e]['asiento'];
                    $numAsientos++;
                }
                $codigo = $misVuelos[$i]['codigo'];
                $nuevoNumPlazas = $misVuelos[$i]['plazas_totales'] - $numAsientos;
                $asientosRestantes = array();
                for ($j=1; $j <= $misVuelos[$i]['plazas_totales']; $j++) {
                    if (!in_array($j, $asientos)) {
                        $asientosRestantes[] = $j;
                    } 
                }
                //var_dump($asientosRestantes);               
                $updateResult = $coleccion->updateOne(
                    array('codigo' => $codigo),
                    array(
                         '$set'=> array('plazas_disponibles' =>  $nuevoNumPlazas, 'asientos_libres' => $asientosRestantes)
                         )
                );

            }
           
        }
   
    }

?>