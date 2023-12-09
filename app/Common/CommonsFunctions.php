<?php

namespace app\Common;

use App\common\restResponse;
use Illuminate\Support\Facades\Log;

class CommonsFunctions {

    static function generateTraceId() {
        return uniqid('PGP|');
    }    

    static function generateLog($mensagem) {
        
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
        
        $chamador = end($trace);
    
        $diretorio = isset($chamador['file']) ? $chamador['file'] : null;
        $linha = isset($chamador['line']) ? $chamador['line'] : null;
    
        $traceId = CommonsFunctions::generateTraceId();
    
        // Registre o erro no log com o trace ID
        $mensagem .= $diretorio !== null ? " | Arquivo: $diretorio" : '';
        $mensagem .= $linha !== null ? " | Linha: $linha" : '';
        $mensagem .= " | Trace ID: $traceId";
    
        Log::error($mensagem);
        return $traceId;
        
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
