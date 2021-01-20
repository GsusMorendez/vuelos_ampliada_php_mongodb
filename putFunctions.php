<?php

function put($_DATA, $coleccion){
    $arrMensaje = array();    

    if (isset($_DATA['codigo']) && isset($_DATA['dni']) && isset($_DATA['asiento']) && isset($_DATA['dniPagador']) && isset($_DATA['codigoVenta']) && isset($_DATA['nombre']) && isset($_DATA['apellido'])) {
             
        $codigo = $_DATA['codigo'];
        $dni = $_DATA['dni'];
        $codigoVenta = $_DATA['codigoVenta'];  
        $nombre = $_DATA['nombre'];   
        $apellido = $_DATA['apellido']; 
        $dniPagador = $_DATA['dniPagador'];
        $asiento = $_DATA['asiento'];

        $updateResult = $coleccion->updateOne(
            array( 'codigo' =>   $codigo ),
            array( '$set' => array( 'vendidos' => array('nombre' => $nombre, 'apellido' => $apellido , 'dni' => $dni, 'codigoVenta' => $codigoVenta, 'asiento' => $asiento , 'dniPagador' => $dniPagador)))
        );

        //printf("Matched %d document(s)\n", $updateResult->getMatchedCount());

        if($updateResult->getMatchedCount() > 0){
            $arrMensaje["estado"] = true;
            $arrMensaje["mensaje"] = 'Billete modificado correctamente';
        }else{
            $arrMensaje["estado"] = false;
            $arrMensaje["mensaje"] = 'No hay billetes con esos datos';
        }
    } else {
        $arrMensaje["estado"] = false;
        $arrMensaje["mensaje"] = 'Alguno de los datos enviados necesarios para modificar no se han enviado correctamente';
        $arrMensaje["esperado"] = array('nombre' => 'Alejandra', 'apellido' => 'Garcia', 'dni' => '58545545A', 'codigoVenta' => 'IB797', 'asiento' => '2' , 'dniPagador' => '5545455Q');
        $arrMensaje["recibido"] = array('nombre' => $nombre, 'apellido' => $apellido , 'dni' => $dni, 'codigoVenta' => $codigoVenta, 'asiento' => $asiento , 'dniPagador' => $dniPagador);
    }

    $jsonstring = json_encode($arrMensaje, JSON_PRETTY_PRINT);
    echo $jsonstring;      
}


?>