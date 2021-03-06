<?php
$method = $_SERVER['REQUEST_METHOD'];
$recibido = file_get_contents('php://input');
$primerCaracter = substr ($recibido,0,1);
$arrMensajeRespuesta = array();



if($primerCaracter == '{'){ 
    $_DATA = json_decode($recibido, true);
    
}else{
    parse_str($recibido, $_DATA);
}


if(isset($method) && ($method == "GET" ||$method == "POST" ||$method == "PUT"  || $method== "DELETE") && isset($_DATA)){

    require 'conexion.php';
    switch ($method) {
        case 'GET':
            require 'getFunctions.php';
            $arrMensajeRespuesta = funcionesGet($coleccion);
        break;
        case 'POST':
            require 'postFunctions.php';
            $arrMensajeRespuesta = funcionesPost($_DATA, $coleccion);
        break;
        case 'PUT':
            require 'putFunctions.php';
            $arrMensajeRespuesta = put($_DATA, $coleccion);
        break;
        case 'DELETE':
            require 'deleteFunctions.php';
            $arrMensajeRespuesta = delete($_DATA, $coleccion);                      
        break;
        default:
            $arrMensajeRespuesta = array(
                "estado" =>  "KO",
                "mensaje" => "Método $method no implementado"
            );
            break;
    }               

}else{  
    $arrMensajeRespuesta = array(
        "estado" =>  false,
        "mensaje" => "No se puede completar la accion con los datos recibidos"
    );
}



// $mensajeFinalJSON = json_encode($arrMensajeRespuesta, JSON_PRETTY_PRINT);
// echo $mensajeFinalJSON;


?>