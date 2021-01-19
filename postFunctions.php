<?php


function funcionesPost($DATA, $coleccion){

    if (isset($DATA['datosViajeros'])) {
       
    }else {
        insertOne($DATA, $coleccion);        
    }

}

function insertOne($DATA, $coleccion){
    
        $arrMensaje = array();  
        
        var_dump($DATA);
        
        if (isset($DATA['codigo']) && isset($DATA['dni'])  && isset($DATA['nombre']) && isset($DATA['apellido']) && isset($DATA['tarjeta']) && isset($DATA['dniPagador'])  ) {
           
            $codigo = $DATA['codigo'];
            $dni = $DATA['dni'];
            $nombre = $DATA['nombre'];
            $apellido = $DATA['apellido'];
            $dniPagador = $DATA['dniPagador'];
            $tarjeta = $DATA['tarjeta'];    
            $codigoVenta = generarCodigo();
            
            //echo $codigoVenta;
           
            //db.vuelos.update({codigo:'IB706'}, {"$pull" : {vendidos: {codigoVenta: 'OR364109', dni: '51002637e'}}});
            $updateResult = $coleccion->updateOne(
                array('codigo' => $codigo),
                array(
                     '$push'=> array('vendidos' => array('asiento' => 6, 'dni' => $dni, 'apellido'=> $apellido,'nombre'=> $nombre,  'dniPagador'=> $dniPagador, 'tarjeta'=> $tarjeta, 'codigoVenta'=> $codigoVenta))
                     )
            );       
    
                // printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
                // printf("matched %d document(s)\n", $updateResult->getMatchedCount());        
    
            $arrMensaje["estado"] = true;
            $arrMensaje["mensaje"] = 'Billete  correctamente';
    
    
        } else {
            $arrMensaje["estado"] = false;
            $arrMensaje["mensaje"] = 'Alguno de los datos enviados necesarios para borrar no han se han enviado correctamente';
            $arrMensaje["esperado"] = array('codigo' => 'IB706' , 'dni' => '44556677H' , 'nombre' => 'Antonio', 'apellido' => 'Garcia', 'dniPagador' => '51202368C', 'tarjeta' => '038 0025 5553 5553');
            $arrMensaje["recibido"] = $DATA;
        
        }
    
        $jsonstring = json_encode($arrMensaje, JSON_PRETTY_PRINT);
        echo $jsonstring;
        die();

}


function generarCodigo(){

    $parteNumerica = random_int(1000, 999999);
    $cuantasLetras = random_int(1, 4);
    $parteLetras = "";

    for ($i=0; $i < $cuantasLetras; $i++) { 
        $parteLetras .= chr(rand(ord("a"), ord("z")));
    }

    return strtoupper($parteLetras).$parteNumerica;
    

}






?>