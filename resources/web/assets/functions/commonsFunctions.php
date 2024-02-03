<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once "restResponse.php";

class commonsFunctions {
    public function curlConsult($arr) : restResponse {
        $urlApi = $arr['urlApi'];
        $id = $arr['id'];
        // echo URL_DOMAIN.$urlApi.$id;
        $urlConsult = URL_DOMAIN.$urlApi.$id;
    
        $ch = curl_init($urlConsult);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        if (isset($_SESSION['token'])) {
            $token = $_SESSION['token'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $token
            ));
        }
        
        // echo "URL da consulta: $urlConsult<br>";
        // echo "Cabeçalhos da solicitação: " . var_export(curl_getinfo($ch, CURLINFO_HEADER_OUT), true) . "<br>";

        $response = curl_exec($ch);
        // var_dump($response);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($response, true);

        if ($httpCode == 200) {
            return new RestResponse($result, 200, 'Success');
        } else {
            return new RestResponse([], $httpCode, 'Error');
        }
    }

    function includePage404($redirect, $message) {

        $strUrlReturn404 = isset($redirect) ? $redirect : '/';
        $strMessage404 = isset($message) ? $message : (isset($redirect) ? 'Voltar à página anterior' : 'Voltar à página inicial');

        include_once "view/site/page404.php";

    }

}
