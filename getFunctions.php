<?php

require 'vendor/autoload.php'; // incluir lo bueno de Composer

$arrMensajeRespuesta = array();

function funcionesGet($coleccion){

    if(!isset($_GET['fecha']) && !isset($_GET['origen']) && !isset($_GET['destino'])){
        //echo "TODOS MIS VUELOS";
        $mensajeJSON = mostrarTodos($coleccion);        
        
    } else if(!isset($_GET['destino']) && isset($_GET['origen']) && isset($_GET['fecha']) ){
        //echo "VUELOS POR FECHA Y ORIGEN";
        $arrayParametros = array("fecha" => $_GET['fecha'], "origen" => strtoupper ( $_GET['origen'] ) );
        $busqueda = busquedaPorFiltros($coleccion, $arrayParametros);
        $mensajeJSON = json_decode($busqueda, true);

        if ($mensajeJSON['estado'] == true && $mensajeJSON['encontrados'] > 0) { 
            for ($i=0; $i < count($mensajeJSON['vuelos']) ; $i++) { 
                array_splice($mensajeJSON['vuelos'][$i], 7);
            }
        }

        $mensajeJSON = json_encode($mensajeJSON, JSON_PRETTY_PRINT);    

    } else if(isset($_GET['destino']) && isset($_GET['origen']) && isset($_GET['fecha']) ){
        //echo "VUELOS POR FECHA, ORIGEN Y DESTINO";
        $arrayParametros = array("fecha" => $_GET['fecha'], "origen" => strtoupper ( $_GET['origen'] ),"destino" => strtoupper ( $_GET['destino'] ));
        $busqueda = busquedaPorFiltros($coleccion, $arrayParametros);
        $mensajeJSON = json_decode($busqueda, true);  
        if ($mensajeJSON['estado'] == true && $mensajeJSON['encontrados'] > 0) { 
            for ($i=0; $i < count($mensajeJSON['vuelos']) ; $i++) { 
                array_splice($mensajeJSON['vuelos'][$i], 7);
            }
        }
        
        $mensajeJSON = json_encode($mensajeJSON, JSON_PRETTY_PRINT);
        //var_dump($mensajeJSON);
    }else if (!isset($_GET['origen']) || !isset($_GET['fecha'])){

        $mensajeJSON["estado"] = false;
        $mensajeJSON["mensaje"] = "Faltan parametros, se necesitan al menos el origen y la fecha para realizar una búsqueda, o limpie el formulario para mostrar todos los vuelos";   
        $mensajeJSON = json_encode($mensajeJSON, JSON_PRETTY_PRINT);
    }

    
    echo $mensajeJSON;   


    

}



function mostrarTodos($coleccion){

 
    try {
        $resultado = $coleccion->find();
    } catch (Exception $e) {
        $arrMensaje["estado"] = false;
        $arrMensaje["mensaje"] = "Error al conectarse a mongodb";
        $mensajeJSON = json_encode($arrMensaje,JSON_PRETTY_PRINT);
        return $mensajeJSON; 
        die();
    }
    $contador = 0;
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
        $vuelo['costeBillete'] = $entry['costeBillete'];
        $misVuelos[] =  $vuelo;
    
        $contador++;          
    } 

    if($contador == 0){
        $arrMensaje["estado"] = false;
        $arrMensaje["encontrados"] = $contador;     

    }else{
        $arrMensaje["estado"] = true;
        $arrMensaje["encontrados"] = $contador;
        $arrMensaje["vuelos"] = $misVuelos;    
    }

    $mensajeJSON = json_encode($arrMensaje,JSON_PRETTY_PRINT);
    return $mensajeJSON;
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
        $vuelo['costeBillete'] = $entry['costeBillete'];

        
        if (isset($entry['asientos_libres'])) {
            $vuelo['asientos_libres'] = $entry['asientos_libres'];
            $vuelo['vendidos'] = $entry['vendidos'];
        }
         
        //$vuelo['precio'] = $entry['precio'];
        $misVuelos[] =  $vuelo;
        $contador++;      
    } 

    if($contador == 0){
        
        $arrMensaje["estado"] = true;
        $arrMensaje["encontrados"] = $contador;

        if(isset($arrayParametros['destino'])){
            $arrMensaje["busqueda"] = array(
                "fecha"=> $_GET['fecha'],
                "origen" =>$_GET['origen'],
                "destino"=> $_GET['destino']
            );

        }else if(isset($arrayParametros['fecha'])){
            $arrMensaje["busqueda"] = array(
                "fecha"=> $_GET['fecha'],
                "origen" =>$_GET['origen']
            );
        }   

  
    
    }else{
        $arrMensaje["estado"] = true;
        $arrMensaje["encontrados"] = $contador;

        if(isset($arrayParametros['destino'])){
            $arrMensaje["busqueda"] = array(
                "fecha"=> $_GET['fecha'],
                "origen" =>$_GET['origen'],
                "destino"=> $_GET['destino']
            );

        }else if(isset($arrayParametros['fecha'])){
            $arrMensaje["busqueda"] = array(
                "fecha"=> $_GET['fecha'],
                "origen" =>$_GET['origen']
              
            );
        }     
        $arrMensaje["vuelos"] = $misVuelos;  
    }

    $mensajeJSON = json_encode($arrMensaje,JSON_PRETTY_PRINT);
    return $mensajeJSON;
    
}



?>