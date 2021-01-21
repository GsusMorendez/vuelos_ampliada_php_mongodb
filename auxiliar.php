<?php

function mensajeExcepcion(){
    $arrMensaje["estado"] = false;
    $arrMensaje["mensaje"] = "Error al conectarse a mongodb";
    $mensajeJSON = json_encode($arrMensaje,JSON_PRETTY_PRINT);
    return $mensajeJSON; 
}

?>