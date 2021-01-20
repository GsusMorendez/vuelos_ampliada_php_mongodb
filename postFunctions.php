<?php

require 'getFunctions.php';



function funcionesPost($DATA, $coleccion){

    if (isset($DATA['datosViajeros'])) {
       
    }else {
        
        insertOne($DATA, $coleccion);        
    }

}

function insertOne($DATA, $coleccion){
    
        $arrMensaje = array();  
        
        //var_dump($DATA);
        
        if (isset($DATA['codigo']) && isset($DATA['dni'])  && isset($DATA['nombre']) && isset($DATA['apellido']) && isset($DATA['tarjeta']) && isset($DATA['dniPagador'])  ) {
           
            $codigo = $DATA['codigo'];
            $dni = $DATA['dni'];
            $nombre = $DATA['nombre'];
            $apellido = $DATA['apellido'];
            $dniPagador = $DATA['dniPagador'];
            $tarjeta = $DATA['tarjeta'];    
            $codigoVenta = generarCodigo();

            $arrayParametros = array("codigo" => $codigo);
            $getResponse = busquedaPorFiltros($coleccion, $arrayParametros); 
            //echo gettype($getResponse); 
            //var_dump( $getResponse);
            $jsonResponse = json_decode($getResponse, true); 
            $infoVuelo = $jsonResponse['vuelos'];  
            $vuelo = $infoVuelo[0];       
            /*var_dump($infoVuelo); 
            var_dump($infoVuelo[0]);
            echo $infoVuelo[0]['codigo'];*/

            if ($vuelo['plazas_disponibles'] > 0) {
                $asientos = $vuelo['asientos_libres'];
                $updateResult = $coleccion->updateOne(
                    array('codigo' => $codigo),
                    array(
                         '$push'=> array('vendidos' => array('asiento' => $asientos[0], 'dni' => $dni, 'apellido'=> $apellido,'nombre'=> $nombre,  'dniPagador'=> $dniPagador, 'tarjeta'=> $tarjeta, 'codigoVenta'=> $codigoVenta))
                         )
                ); 

                $asientosRestantes = array_splice($asientos, 1);
                
                $updateResultDos = $coleccion->updateOne(
                    array('codigo' => $codigo),
                    array(
                         '$set'=> array('plazas_disponibles' => ($vuelo['plazas_disponibles']-1), 'asientos_libres' => $asientosRestantes)
                         )
                ); 
                $arrMensaje["estado"] = true;
                $arrMensaje["codigo"] = $codigo;
                $arrMensaje["dni"] = $dni;
                $arrMensaje["nombre"] = $nombre;
                $arrMensaje["apellido"] = $apellido;
                $arrMensaje["codigoVenta"] = $codigoVenta;
                $arrMensaje["tarjeta"] = $tarjeta;
                $arrMensaje["dniPagador"] = $dniPagador;
    
                
                $arrMensaje["origen"] = $vuelo['origen'];
                $arrMensaje["destino"] = $vuelo['destino'];
                $arrMensaje["fecha"] = $vuelo['codigo'];
                $arrMensaje["hora"] = $vuelo['hora'];
                //$arrMensaje["asientos_libres"] = $vuelo['asientos_libres'];
                //$arrMensaje["asiento"] = $vuelo['asiento'];
                //$arrMensaje["costeBillete"] = $precio;
            } else {
                $arrMensaje["estado"] = false;
                $arrMensaje["mensaje"] = "Actualmente no existen plazas disponibles para ese vuelo";
            }       
          
            // printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
            // printf("matched %d document(s)\n", $updateResult->getMatchedCount());        
    
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