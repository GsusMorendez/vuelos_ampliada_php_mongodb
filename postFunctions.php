<?php
require 'getFunctions.php';

function funcionesPost($DATA, $coleccion){

    if (isset($DATA['datosViajeros'])) {
       insertMany($DATA, $coleccion);
    }else {
        insertOne($DATA, $coleccion);        
    }
}

function insertOne($DATA, $coleccion){
        $arrMensaje = array();  
        //var_dump($DATA);
        if (isset($DATA['codigo']) && isset($DATA['dni']) && isset($DATA['nombre']) && isset($DATA['apellido']) && isset($DATA['tarjeta']) && isset($DATA['dniPagador'])  ) {
           
            $codigo = $DATA['codigo'];
            $dni = $DATA['dni'];
            $nombre = $DATA['nombre'];
            $apellido = $DATA['apellido'];
            $dniPagador = $DATA['dniPagador'];
            $tarjeta = $DATA['tarjeta'];    
            $codigoVenta = generarCodigo();

            $arrayParametros = array("codigo" => $codigo);
            $getResponse = busquedaPorFiltros($coleccion, $arrayParametros); 
            $jsonResponse = json_decode($getResponse, true); 
            $infoVuelo = $jsonResponse['vuelos'];  
            $vuelo = $infoVuelo[0];
            $nuevoNumPlazas = $vuelo['plazas_disponibles'] - 1;     

            if ($vuelo['plazas_disponibles'] > 0) {

                if (isset($vuelo['asientos_libres'])) {
                    $asientos = $vuelo['asientos_libres'];
                    $asientoAsginado = $asientos[0];
                    $asientosRestantes = array_splice($asientos, 1);
               
                }else{
                    $asientoAsginado = 1;
                    $asientosRestantes = array();
                    for ($i=2; $i <= $vuelo['plazas_totales'] ; $i++) { 
                        $asientosRestantes[] = $i;
                    }
                }

                $updateResult = $coleccion->updateOne(
                    array('codigo' => $codigo),
                    array(
                         '$push'=> array('vendidos' => array('asiento' => $asientoAsginado, 'dni' => $dni, 'apellido'=> $apellido,'nombre'=> $nombre,  'dniPagador'=> $dniPagador, 'tarjeta'=> $tarjeta, 'codigoVenta'=> $codigoVenta))
                         )
                );                 
                
                $updateResultDos = $coleccion->updateOne(
                    array('codigo' => $codigo),
                    array(
                         '$set'=> array('plazas_disponibles' =>  $nuevoNumPlazas, 'asientos_libres' => $asientosRestantes)
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
                $arrMensaje["costeBillete"] = $jsonResponse['vuelos']['0']['costeBillete'];              

                //$arrMensaje["asientos_libres"] = $vuelo['asientos_libres'];
                //$arrMensaje["asiento"] = $vuelo['asiento'];
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

function insertMany($DATA, $coleccion){
    $arrMensaje = array();  
    //var_dump($DATA);
    
    if (isset($DATA['codigo']) && isset($DATA['dniPagador']) && isset($DATA['tarjeta']) && isset($DATA['datosViajeros'])) {
        $codigo = $DATA['codigo'];
        $dniPagador = $DATA['dniPagador'];
        $tarjeta = $DATA['tarjeta'];
        $datosViajeros = $DATA['datosViajeros']; 
       
        $arrayParametros = array("codigo" => $codigo);
        $getResponse = busquedaPorFiltros($coleccion, $arrayParametros); 
        $jsonResponse = json_decode($getResponse, true); 
        $infoVuelo = $jsonResponse['vuelos'];  
        $vuelo = $infoVuelo[0];
        $pasajeros = array();
     
        if ($vuelo['plazas_disponibles'] > 0) {

            $countAsiento = 0;
            for ($i=0; $i < count($datosViajeros) ; $i++) { 
                $codigoVenta = generarCodigo();

                if (!isset($vuelo['asientos_libres']) && $i == 0) {
                    $asientoAsginado = 1;
                    $countAsiento = $asientoAsginado; 

                }else if(isset($vuelo['asientos_libres']) && $i == 0){
                    $asientos = $vuelo['asientos_libres'];
                    $asientoAsginado = $asientos[0];
                    $countAsiento = $asientoAsginado; 
                }else{
                    $asientoAsginado = $countAsiento + 1;
                }
                $pasajero = array('asiento' => $asientoAsginado, 'dni' => $datosViajeros[$i][0], 'apellido'=> $datosViajeros[$i][1],'nombre'=> $datosViajeros[$i][2],  'dniPagador'=> $dniPagador, 'tarjeta'=> $tarjeta, 'codigoVenta'=> $codigoVenta);
                $pasajeros[] = $pasajero;
            }
            $asientoDelUltimoPasajero = $pasajeros[count($datosViajeros)-1]['asiento'];          

            for ($i=$asientoDelUltimoPasajero+1; $i <= $vuelo['plazas_totales'] ; $i++) { 
                $asientosRestantes[] = $i;
            }         
            
            $updateResult = $coleccion->updateMany(
                array('codigo' => $codigo),
                array(
                     '$set'=> array('vendidos' => $pasajeros)
                ),
                array('multi' => true)
            );       
            $nuevoNumPlazas = ($vuelo['plazas_disponibles'] - count($datosViajeros));          
            
            $updateResultDos = $coleccion->updateOne(
                array('codigo' => $codigo),
                array(
                     '$set'=> array('plazas_disponibles' =>  $nuevoNumPlazas, 'asientos_libres' => $asientosRestantes)
                     )
            ); 
            $arrMensaje["estado"] = true;
            $arrMensaje["codigo"] = $codigo;
            $arrMensaje["origen"] = $vuelo['origen'];
            $arrMensaje["destino"] = $vuelo['destino'];
            $arrMensaje["fecha"] = $vuelo['codigo'];
            $arrMensaje["hora"] = $vuelo['hora'];
            $arrMensaje["dniPagador"] = $dniPagador;
            $arrMensaje["tarjeta"] = $tarjeta;
            $arrMensaje["codigoVenta"] = $codigoVenta;
            for ($i=0; $i < count($datosViajeros) ; $i++) { 
                $pasajeros[$i]['costeBillete'] =  $jsonResponse['vuelos']['0']['costeBillete']; 
            }
            $arrMensaje["datosBilletes"] = $pasajeros;
        } else {
            $arrMensaje["estado"] = false;
            $arrMensaje["mensaje"] = "Actualmente no existen plazas disponibles para ese vuelo";
        }       
        // printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
        // printf("matched %d document(s)\n", $updateResult->getMatchedCount());      
    } else {
        $arrMensaje["estado"] = false;
        $arrMensaje["mensaje"] = 'No se ha podido realizar la compra porque algun dato ingresado es incorrecto';
        $arrMensaje["esperado"] = array('codigo' => 'IB706' , 'dniPagador' => '44556677H' , 'tajeta' => '038 0025 5553 5553', 'datosViajeros' => array('dni' => '05554525A' , 'apellido' => 'Rodriguez' , 'nombre' => 'Alejandra'));
        $arrMensaje["recibido"] = $DATA;
    }
    $jsonstring = json_encode($arrMensaje, JSON_PRETTY_PRINT);
    echo $jsonstring;
}
?>