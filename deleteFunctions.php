<?php

require 'getFunctions.php';


function delete($DATA, $coleccion){

    $arrMensaje = array();   

    if (isset($DATA['codigo']) && isset($DATA['dni'])  && isset($DATA['codigoVenta'])) {

        $codigo = $DATA['codigo'];
        $dni = $DATA['dni'];
        $codigoVenta = $DATA['codigoVenta'];        
        //si fuera borrar uno sería así, pero en este caso es borrar un elemento de un array lo que es actualizar el documento
        // $deleteResult = $coleccion->deleteOne(array('codigoVenta' => $codigoVenta, 'dni' => $dni, 'codigoVenta' => $codigoVenta));
        // printf("Deleted %d document(s)\n", $deleteResult->getDeletedCount());
            
        $vueloBuscado = array("codigo" => $codigo);
        $getResponse = busquedaPorFiltros($coleccion, $vueloBuscado);
        
        //db.vuelos.update({codigo:'IB706'}, {"$pull" : {vendidos: {codigoVenta: 'OR364109', dni: '51002637e'}}});
        $updateResult = $coleccion->updateOne(
            array('codigo' => $codigo),
            array(
                 '$pull'=> array('vendidos' => array('dni' => $dni, 'codigoVenta'=> $codigoVenta))
                 )
        );   
        // printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
        // printf("matched %d document(s)\n", $updateResult->getMatchedCount());  
        
        if ($updateResult->getModifiedCount() > 0) {
            $arrMensaje["estado"] = true;
            $arrMensaje["mensaje"] = 'Billete borrado correctamente.';  
            devolverAsientoAdisponibles($getResponse, $coleccion, $vueloBuscado , $codigoVenta, $dni);        
            
        } else {
            $arrMensaje["estado"] = false;
            $arrMensaje["mensaje"] = 'los datos no coinciden con ningun vuelo.';
        }    

    } else {
        $arrMensaje["estado"] = false;
        $arrMensaje["mensaje"] = 'Alguno de los datos enviados necesarios para borrar no han se han enviado correctamente';
        $arrMensaje["esperado"] = array('codigo' => 'IB706' , 'dni' => '44556677H' , 'codigoVenta' => 'GHJ77');
        $arrMensaje["recibido"] = $DATA;
    }

    $jsonstring = json_encode($arrMensaje, JSON_PRETTY_PRINT);
    echo $jsonstring;
}


function devolverAsientoAdisponibles($getResponse, $coleccion, $arrayParametros, $codigoVenta, $dni){
    
    $jsonResponse = json_decode($getResponse, true); 
    $infoVuelo = $jsonResponse['vuelos'];  
    $vuelo = $infoVuelo[0];
    $asientosRestantes = $vuelo['asientos_libres'];
    $asientoActual=0;

    for ($i=0; $i < count($vuelo['vendidos']) ; $i++) { 
       if($vuelo['vendidos'][$i]['codigoVenta'] == $codigoVenta && $vuelo['vendidos'][$i]['dni'] == $dni){
            $asientoActual = $vuelo['vendidos'][$i]['asiento'];
       } 
    }  

    if ($asientoActual != 0) {
        array_unshift($asientosRestantes, $asientoActual);
        $updateResultDos = $coleccion->updateOne(
            array('codigo' => $arrayParametros['codigo']),
            array(
                 '$set'=> array('plazas_disponibles' => ($vuelo['plazas_disponibles']+1), 'asientos_libres' => $asientosRestantes)
                 )
        ); 
    }   
}

?>