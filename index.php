<?php
require 'vendor/autoload.php'; // incluir lo bueno de Composer

$method = $_SERVER['REQUEST_METHOD'];
$recibido = file_get_contents('php://input');
$primerCaracter = substr ($recibido,0,1);
$arrMensajeRespuesta = array();

if($primerCaracter == '{'){ 
  $_DATA = json_decode($recibido, true);
    if(isset($method) && ($method == "GET" ||$method == "POST" ||$method == "PUT"  || $method== "DELETE") && isset($_DATA)){  
        
        require 'crud.php';

        switch ($method) {
            case 'GET':
                $arrMensajeRespuesta = funcionesGet($_DATA, $conn);
            break;
            case 'POST':
                $arrMensajeRespuesta = funcionesPost($_DATA, $conn);
            break;
            case 'PUT':
                $arrMensajeRespuesta = put($_DATA, $conn);
            break;
            case 'DELETE':
                $arrMensajeRespuesta = delete($_DATA, $conn);                      
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
            "estado" =>  "KO",
            "mensaje" => "No se puede completar la accion con los datos recibidos"
        );
    }
}else{
  //parse_str($recibido, $_DATA);
  $arrMensajeRespuesta = array(
    "estado" =>  false,
    "mensaje" => "Se esperaba recibir un objeto JSON, formato recibido erroneo."
  );
}


$mensajeFinalJSON = json_encode($arrMensajeRespuesta, JSON_PRETTY_PRINT);
echo $mensajeFinalJSON;


?>