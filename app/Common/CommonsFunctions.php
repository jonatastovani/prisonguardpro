<?php

namespace app\Common;

use app\common\restResponse;

class CommonsFunctions {

    static function generateTraceId() {
        return uniqid('PGP|');
    }    

    // public function curlConsult($arr) : restResponse {
    //     $urlApi = $arr['urlApi'];
    //     $id = $arr['id'];
    
    //     $urlConsult = URL_DOMAIN.$urlApi.$id;
    
    //     $ch = curl_init($urlConsult);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     $response = curl_exec($ch);
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);
    //     $result = json_decode($response, true);

    //     // Handle exceptions or errors here if needed

    //     if ($httpCode == 200) {
    //         return new RestResponse($result, 200, 'Success');
    //     } else {
    //         return new RestResponse([], $httpCode, 'Error');
    //     }
    // }

    
}
