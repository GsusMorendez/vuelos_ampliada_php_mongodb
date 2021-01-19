<?php

function funcionesPost($_DATA, $coleccion){
    $arrMensaje = array();
    

    if (isset($DATA['codigo']) && isset($DATA['dni'])  && isset($DATA['codigoVenta']) && isset($DATA['nombre']) && isset($DATA['apellido'])) {
             
        $codigo = $DATA['codigo'];
        $dni = $DATA['dni'];
        $codigoVenta = $DATA['codigoVenta'];  
        $nombre = $DATA['nombre'];   
        $apellido = $DATA['apellido']; 
        
        $updateResult = $collection->updateOne(
            [ 'restaurant_id' => '40356151' ],
            [ '$set' => [ 'nombre' => $nombre, 'apellido' => $apellido  ]]
        );

        //printf("Matched %d document(s)\n", $updateResult->getMatchedCount());

        $arrMensaje["estado"] = true;
        $arrMensaje["mensaje"] = 'Billete modificado correctamente';


    } else {
        $arrMensaje["estado"] = false;
        $arrMensaje["mensaje"] = 'Alguno de los datos envíados necesarios para modificar no se han enviado correctamente';
        $arrMensaje["esperado"] = array('codigo' => 'IB706' , 'dni' => '44556677H' , 'codigoVenta' => 'GHJ77', 'nombre' => 'Arturo', 'apellido' => 'Garcia');
    }

    $jsonstring = json_encode($arrMensaje, JSON_PRETTY_PRINT);
    echo $jsonstring;



}

?>