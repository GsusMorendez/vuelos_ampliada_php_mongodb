<?php

function delete($DATA, $coleccion){
    $arrMensaje = array();   
    
    if (isset($DATA['codigo']) && isset($DATA['dni'])  && isset($DATA['codigoVenta']) ) {
       
        $codigo = $DATA['codigo'];
        $dni = $DATA['dni'];
        $codigoVenta = $DATA['codigoVenta'];        
        //si fuera borrar uno sería así, pero en este caso es borrar un elemento de un array lo que es actualizar el documento
        // $deleteResult = $coleccion->deleteOne(array('codigoVenta' => $codigoVenta, 'dni' => $dni, 'codigoVenta' => $codigoVenta));
        // printf("Deleted %d document(s)\n", $deleteResult->getDeletedCount());

    
        //db.vuelos.update({codigo:'IB706'}, {"$pull" : {vendidos: {codigoVenta: 'OR364109', dni: '51002637e'}}});
        $updateResult = $coleccion->updateOne(
            array('codigo' => $codigo),
            array(
                 '$pull'=> array('vendidos' => array('dni' => $dni, 'codigoVenta'=> $codigoVenta))
                 )
        );        

        // printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
        // printf("matched %d document(s)\n", $updateResult->getMatchedCount());        

        $arrMensaje["estado"] = true;
        $arrMensaje["mensaje"] = 'Billete borrado correctamente';


    } else {
        $arrMensaje["estado"] = false;
        $arrMensaje["mensaje"] = 'Alguno de los datos enviados necesarios para borrar no han se han enviado correctamente';
        $arrMensaje["esperado"] = array('codigo' => 'IB706' , 'dni' => '44556677H' , 'codigoVenta' => 'GHJ77');
        $arrMensaje["recibido"] = $DATA;
    
    }

    $jsonstring = json_encode($arrMensaje, JSON_PRETTY_PRINT);
    echo $jsonstring;

    die(); 

}

?>