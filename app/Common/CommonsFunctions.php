<?php

namespace App\Common;

use App\common\restResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

class CommonsFunctions
{

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
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
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
            return response()->json([
                "status" => 422,
                'errors' => $validator->errors(),
                'trace_id' => $traceId,
                'timestamp' => now()->toDateTimeString(),
            ], 422)->throwResponse();
        }

    }

    static function formatarDataTimeZonaAmericaSaoPaulo($value)
    {
        if ($value) {
            return Carbon::parse($value)->timezone(config('app.timezone'))->toDateTimeString();
        }

        return null;
    }

    static function inserirInfoCreated($novo)
    {    
        $novo->id_user_created = auth()->user()->id;
        $novo->ip_created = UserInfo::get_ip();
        $novo->created_at = self::formatarDataTimeZonaAmericaSaoPaulo(now());
        $novo->updated_at = null;
    }

    static function inserirInfoUpdated($resource)
    {    
        $resource->id_user_updated = auth()->user()->id;
        $resource->ip_updated = UserInfo::get_ip();
        $resource->updated_at = self::formatarDataTimeZonaAmericaSaoPaulo(now());
    }

    static function inserirInfoDeleted($resource)
    {    
        $resource->id_user_deleted = auth()->user()->id;
        $resource->ip_deleted = UserInfo::get_ip();
        $resource->deleted_at = self::formatarDataTimeZonaAmericaSaoPaulo(now());
    }

    // /**
    //  * Retorna uma resposta JSON padronizada para solicitações da API.
    //  *
    //  * @param int $status O código de status da resposta.
    //  * @param mixed $traceId O identificador de rastreamento associado à resposta.
    //  * @param mixed|null $data Os dados a serem incluídos na resposta, podendo ser de erro ou sucesso.
    //  * @return \Illuminate\Http\JsonResponse A resposta JSON com os dados formatados e o código de status fornecido.
    //  */
    // static function retornoJson(Integer $status, $traceId, $data = null)
    // {
    //     $response = [
    //         'status' => $status,
    //         'trace_id' => $traceId,
    //         'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
    //     ];
    
    //     if (!is_null($data)) {
    //         if (isset($data['error'])) {
    //             $response['errors'] = [
    //                 'error' => $data['error'],
    //             ];
    //         } elseif (isset($data['errors'])) {
    //             $response['errors'] = $data['errors'];
    //         } elseif (isset($data['data'])) {
    //             $response['data'] = $data['data'];
    //         }
    //     }
    
    //     return response()->json($response, $status)->throwResponse();
    // }

}
