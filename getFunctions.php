<?php

require 'vendor/autoload.php'; // incluir lo bueno de Composer

$arrMensajeRespuesta = array();

function funcionesGet($coleccion){


    if(!isset($_GET['fecha']) && !isset($_GET['origen']) && !isset($_GET['destino'])){
        echo "TODOS MIS VUELOS";
        mostrarTodos($coleccion);         
    } else if(!isset($_GET['destino'])){
        echo "VUELOS POR FECHA Y ORIGEN";
        $arrayParametros = array("fecha" => $_GET['fecha'], "origen" => $_GET['origen']);
        busquedaPorFiltros($coleccion, $arrayParametros);
    } else{
        echo "VUELOS POR FECHA, ORIGEN Y DESTINO";
        $arrayParametros = array("fecha" => $_GET['fecha'], "origen" => $_GET['origen'],"destino" => $_GET['destino']);
        busquedaPorFiltros($coleccion, $arrayParametros);
    }

}



function mostrarTodos($coleccion){

    $contador = 0;
    $resultado = $coleccion->find();

    $misVuelos= array();
   
    foreach ($resultado as $entry) {
        $vuelo = array();
        $vuelo['codigo'] = $entry['codigo'];
        $vuelo['origen'] = $entry['origen'];
        $vuelo['destino'] = $entry['destino'];
        $vuelo['fecha'] = $entry['fecha'];
        $vuelo['hora'] = $entry['hora'];
        $vuelo['plazas_totales'] = $entry['plazas_totales'];
        $vuelo['plazas_disponibles'] = $entry['plazas_disponibles'];
        //$vuelo['precio'] = $entry['precio'];
        $misVuelos[] =  $vuelo;
    
        $contador++;
       
       
    } 

    if($contador == 0){

        echo "<pre>";  // Descomentar si se quiere ver resultado "bonito" en navegador. Solo para pruebas
        $arrMensaje["estado"] = false;
        $arrMensaje["encontrados"] = $contador;
      
    
        $mensajeJSON = json_encode($arrMensaje,JSON_PRETTY_PRINT);
        echo $mensajeJSON;
        echo "<pre>";  // Descomentar si se quiere ver resultado "bonito" en navegador. Solo para pruebas

    }else{
        echo "<pre>";  // Descomentar si se quiere ver resultado "bonito" en navegador. Solo para pruebas
        $arrMensaje["estado"] = true;
        $arrMensaje["encontrados"] = $contador;
        $arrMensaje["vuelos"] = $misVuelos;
    
        $mensajeJSON = json_encode($arrMensaje,JSON_PRETTY_PRINT);
        echo $mensajeJSON;
        echo "<pre>";  // Descomentar si se quiere ver resultado "bonito" en navegador. Solo para pruebas
    }




}

function busquedaPorFiltros($coleccion, $arrayParametros){

   // db.vuelos.find({"fecha": "2020-12-17", "origen":"MADRID", "destino":"MURCIA"}).pretty()
    $resultado = $coleccion->find($arrayParametros);
    $misVuelos= array();
    $contador = 0;
    
    foreach ($resultado as $entry) {
        $vuelo = array();
        $vuelo['codigo'] = $entry['codigo'];
        $vuelo['origen'] = $entry['origen'];
        $vuelo['destino'] = $entry['destino'];
        $vuelo['fecha'] = $entry['fecha'];
        $vuelo['hora'] = $entry['hora'];
        $vuelo['plazas_totales'] = $entry['plazas_totales'];
        $vuelo['plazas_disponibles'] = $entry['plazas_disponibles'];
        //$vuelo['precio'] = $entry['precio'];
        $misVuelos[] =  $vuelo;
    
        $contador++;
       
       
    } 

    if($contador == 0){
        
        echo "<pre>";  // Descomentar si se quiere ver resultado "bonito" en navegador. Solo para pruebas
        $arrMensaje["estado"] = false;
        $arrMensaje["encontrados"] = $contador;
        if(isset($arrayParametros['destino'])){
            $arrMensaje["busqueda"] = array(
                "fecha"=> $_GET['fecha'],
                "origen" =>$_GET['origen'],
                "destino"=> $_GET['destino']
            );

        }else{
            $arrMensaje["busqueda"] = array(
                "fecha"=> $_GET['fecha'],
                "origen" =>$_GET['origen']
              
            );

        }     
    
        $mensajeJSON = json_encode($arrMensaje,JSON_PRETTY_PRINT);
        echo $mensajeJSON;
        echo "<pre>";  // Descomentar si se quiere ver resultado "bonito" en navegador. Solo para pruebas

    }else{
        echo "<pre>";  // Descomentar si se quiere ver resultado "bonito" en navegador. Solo para pruebas
        $arrMensaje["estado"] = true;
        $arrMensaje["encontrados"] = $contador;

        if(isset($arrayParametros['destino'])){
            $arrMensaje["busqueda"] = array(
                "fecha"=> $_GET['fecha'],
                "origen" =>$_GET['origen'],
                "destino"=> $_GET['destino']
            );

        }else{
            $arrMensaje["busqueda"] = array(
                "fecha"=> $_GET['fecha'],
                "origen" =>$_GET['origen']
              
            );

        }     
        
        $arrMensaje["vuelos"] = $misVuelos;
    
        $mensajeJSON = json_encode($arrMensaje,JSON_PRETTY_PRINT);
        echo $mensajeJSON;
        echo "<pre>";  // Descomentar si se quiere ver resultado "bonito" en navegador. Solo para pruebas
    }
    
}



?>