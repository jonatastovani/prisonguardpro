<?php

namespace App\Common;

use App\common\restResponse;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

class CommonsFunctions
{

    /**
     * Gera um ID para o Log
     */
    static function generateTraceId()
    {
        return uniqid('PGP|');
    }

    /**
     * Gera Log de erro, retornando o id do Log
     */
    static function generateLog($mensagem): string
    {

        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        $chamador = end($trace);

        $diretorio = isset($chamador['file']) ? $chamador['file'] : null;
        $linha = isset($chamador['line']) ? $chamador['line'] : null;

        $traceId = CommonsFunctions::generateTraceId();

        // Registre o erro no log com o trace ID
        $mensagem .= $diretorio !== null ? " | Arquivo: $diretorio" : '';
        $mensagem .= $linha !== null ? " | Linha: $linha" : '';
        $mensagem .= " | UserId: " . auth()->id();
        $mensagem .= " | Trace ID: $traceId";

        // Log::error($mensagem);
        Log::channel('pgplog_file')->info($mensagem);
        return $traceId;
    }

    /**
     * Retorna um array de mensagens para uso do Validator
     */
    static function getMessagesValidate(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'boolean' => 'O campo :attribute deve ser booleano.',
            'array' => 'O campo :attribute deve ser array.',
            'string' => 'O campo :attribute deve ser um texto.',
            'date' => 'O campo :attribute deve ser uma data.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'date_format' => 'O campo :attribute deve possuir o formato :format.',
            'required_with' => 'O campo :attribute deve ser informado.',
            'between' => 'O campo :attribute deve estar entre :min e :max.',
            'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
        
            'presos.*.nome.regex' => 'O campo :attribute não deve conter números.',
            'presos.*.mae.regex' => 'O campo :attribute não deve conter números.',
            'presos.*.pai.regex' => 'O campo :attribute não deve conter números.',
            'presos.*.matricula.regex' => 'O campo :attribute deve conter somente números.',
        ];
    }

    /**
     * Retorna um array de mensagens para uso do Validator
     */
    static function getAttributeNamesValidate(): array
    {
        return [
            'presos.*.nome' => 'nome',
            'presos.*.mae' => 'mae',
            'presos.*.pai' => 'pai',
            'presos.*.matricula' => 'matricula',
        ];
    }

    /**
     * Efetua uma validação dos inputs da request enviada
     */
    static function validacaoRequest(Request $request, array $rules, array $attributeNames = [], array $messages = [])
    {
        if (!count($messages)) {
            $messages = CommonsFunctions::getMessagesValidate();
        } else {
            $messages = array_merge($messages, CommonsFunctions::getMessagesValidate());
        }

        if (!count($attributeNames)) {
            $attributeNames = CommonsFunctions::getAttributeNamesValidate();
        } else {
            $attributeNames = array_merge($attributeNames, CommonsFunctions::getAttributeNamesValidate());
        }

        // Valide os dados recebidos da requisição
        $validator = Validator::make($request->all(), $rules, $messages, $attributeNames);

        if ($validator->fails()) {
            // Gerar um log
            $mensagem = "A requisição não pôde ser processada.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()) . "Validator: " . json_encode($validator->errors()));

            // Se a validação falhar, retorne os erros em uma resposta JSON com código 422 (Unprocessable Entity)
            $response = restResponse::createGenericResponse(["errors" => $validator->errors()], 422, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }

    static function retornaErroQueImpedemProcessamento422($arrErrors)
    {
        // Erros que impedem o processamento
        if (count($arrErrors)) {
            // Gerar um log
            $codigo = 422;
            $mensagem = "A requisição não pôde ser processada.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Errors: " . json_encode($arrErrors));

            $response = RestResponse::createGenericResponse(["errors" => $arrErrors], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
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
        $novo->created_user_id = auth()->user()->id;
        $novo->created_ip = UserInfo::get_ip();
        $novo->created_at = self::formatarDataTimeZonaAmericaSaoPaulo(now());
        $novo->updated_at = null;
    }

    static function inserirInfoUpdated($resource)
    {
        $resource->updated_user_id = auth()->user()->id;
        $resource->updated_ip = UserInfo::get_ip();
        $resource->updated_at = self::formatarDataTimeZonaAmericaSaoPaulo(now());
    }

    static function inserirInfoDeleted($resource)
    {
        $resource->deleted_user_id = auth()->user()->id;
        $resource->deleted_ip = UserInfo::get_ip();
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
