<?php

namespace app\Common;

use App\common\restResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Validator;

class CommonsFunctions {

    /**
     * Gera um ID para o Log
     */
    static function generateTraceId() {
        return uniqid('PGP|');
    }    

    /**
     * Gera Log de erro, retornando o id do Log
     */
    static function generateLog($mensagem) :string {
        
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
    
    /**
     * Retorna um array de mensagens para uso do Validator
     */
    static function getMessagesValidate() : array {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'integer' => 'O campo :attribute deve ser um número.',
            'boolean' => 'O campo :attribute deve ser booleano.',
            'date' => 'O campo :attribute deve ser uma data.',
        ];
    }
    
    /**
     * Efetua uma validação dos inputs da request enviada
     */
    static function validacaoRequest(Request $request, Array $rules, Array $attributeNames = [], Array $messages = []) {
        
        if (!count($messages)) {
            $messages = CommonsFunctions::getMessagesValidate();
        }

        // Valide os dados recebidos da requisição
        $validator = Validator::make($request->all(), $rules, $messages, $attributeNames);

        if ($validator->fails()) {
            // Gerar um log
            $traceId = CommonsFunctions::generateLog($validator->errors());

            // Se a validação falhar, retorne os erros em uma resposta JSON com código 422 (Unprocessable Entity)
            return response()->json(['errors' => $validator->errors(), 'trace_id' => $traceId], 422)->throwResponse();
        }

    }

}
