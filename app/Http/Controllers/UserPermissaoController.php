<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\UserPermissao;
use Illuminate\Http\Request;
use App\Common\UserInfo;
use App\Models\RefPermissao;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;

class UserPermissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idUser)
    {
        $dataAtual = Carbon::now()->toDateString(); // Obtém a data atual no formato 'Y-m-d'

        $resource = UserPermissao::
        where('user_id', $idUser)
        // ->where('permissao_id', $registro->permissao_id)
        ->where(function ($query) use ($dataAtual) {
            $query->whereDate('data_termino', '>=', $dataAtual)
            ->orWhereNull('data_termino');
        })->get();

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrErrors = [];

        // Regras de validação
        $rules = [
            'user_id' => 'required|integer',
            'permissao_id' => 'required|integer',
            'substituto_bln' => 'boolean',
            'data_inicio' => 'required|date',
            'data_termino' => 'date',
        ];

        // Apelidos para os atributos
        $attributeNames = [
            'user_id' => 'ID do Usuário',
            'permissao_id' => 'ID da Permissão',
            'substituto_bln' => 'Substituto',
            'data_inicio' => 'Data de Início',
            'data_termino' => 'Data de Término',
        ];

        CommonsFunctions::validacaoRequest($request,$rules, $attributeNames);
        
        // Se a validação passou, crie um novo registro
        $novo = new UserPermissao();
        $novo->user_id = $request->input('user_id');

        $permissao_id = $request->input('permissao_id');

        $consultaPermissao = RefPermissao::where('id', $permissao_id);

        // Verifica se a permissão existe
        if (!$consultaPermissao->exists()) {
            // Gerar um log
            $mensagem = "A permissão informada não existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        $novo->permissao_id = $permissao_id;

        $dataAtual = Carbon::now()->toDateString(); // Obtém a data atual no formato 'Y-m-d'

        $consultaPermissaoAtiva = UserPermissao::
            where('user_id', $novo->user_id)
            ->where('permissao_id', $novo->permissao_id)
            ->where(function ($query) use ($dataAtual) {
                $query->whereDate('data_termino', '>=', $dataAtual)
                ->orWhereNull('data_termino');
            })
            ->whereNull('deleted_at');
        ;

        // Verifica se o usuário já tem a permissão ativa
        if ($consultaPermissaoAtiva->exists()) {
            // Gerar um log
            $mensagem = "A permissão já existe para o usuário informado.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $consultaPermissaoAtiva->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        //Verifica se a permissão permite substituto
        $this->validarPermissaoSubstituto($novo, $request, $arrErrors);

        // Verifica se a data de início é menor que a data de hoje
        $this->validarDataInicio($novo, $request, $arrErrors);

        // Verifique se o campo 'data_termino' foi enviado
        if ($request->has('data_termino')) {
            $this->validarDataTermino($novo, $request, $arrErrors);
        }

        // Erros que impedem o processamento
        if (count($arrErrors)){
            $response = RestResponse::createGenericResponse(["errors" => $arrErrors], 422, "A requisição não pôde ser processada.");
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        CommonsFunctions::inserirInfoCreated($novo);
        $novo->save();
        
        // Retorne uma resposta de sucesso (status 201 - Created)
        $response = RestResponse::createSuccessResponse($novo, 201, 'Permissão adicionada com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Display the specified resource.
     */
    public function show($idUser, $idPermissao)
    {
        $dataAtual = Carbon::now()->toDateString(); // Obtém a data atual no formato 'Y-m-d'

        $resource = UserPermissao::
        where('user_id', $idUser)
        ->where('permissao_id', $idPermissao)
        ->where(function ($query) use ($dataAtual) {
            $query->whereDate('data_termino', '>=', $dataAtual)
            ->orWhereNull('data_termino');
        })->first();

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "A Permissão de Usuário informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| idUser: $idUser | idPermissao: $idPermissao");

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }
    
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $arrErrors = [];

        // Regras de validação
        $rules = [
            'substituto_bln' => 'boolean',
            'data_inicio' => 'required|date',
            'data_termino' => 'date|nullable',
        ];

        CommonsFunctions::validacaoRequest($request,$rules);

        $resource = UserPermissao::find($request->id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "A Permissão de Usuário informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        //Verifica se a permissão permite substituto
        $this->validarPermissaoSubstituto($resource, $request, $arrErrors);

        // Verificar se a data_inicio foi alterada
        if ($request->has('data_inicio') && $resource->data_inicio != $request->input('data_inicio')) {
            // Verifica se a data de início é menor que a data de hoje
            $this->validarDataInicio($resource, $request, $arrErrors);
        }

        // Verifique se o campo 'data_termino' foi enviado
        if ($request->has('data_termino')) {
            $this->validarDataTermino($resource, $request, $arrErrors);
        }

        // Erros que impedem o processamento
        if (count($arrErrors)){
            $response = RestResponse::createGenericResponse(["errors" => $arrErrors], 422, "A requisição não pôde ser processada.");
            return response()->json($response->toArray(), $response->getStatusCode());
        }
        
        // Se as validações passaram, altere o registro
        CommonsFunctions::inserirInfoUpdated($resource);
        $resource->save();
        
        // Retorne uma resposta de sucesso (status 200 - OK)
        $response = RestResponse::createSuccessResponse($resource, 200, 'Alteração realizada com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $resource = UserPermissao::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "Permissão de usuário não encontrada.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| id: $id");

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Artigo excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function validarDataInicio($resource, $request, &$arrErrors)
    {
        $resource->data_inicio = $request->input('data_inicio');

        if (Carbon::parse($resource->data_inicio)->startOfDay()->isBefore(Carbon::now()->startOfDay())) {
            $mensagem = "A data de início não pode ser menor que a data de hoje.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $arrErrors[] = [
                'error' => $mensagem,
                'trace_id' => $traceId
            ];
        }
    }

    private function validarDataTermino($resource, $request, &$arrErrors)
    {
        $resource->data_termino = $request->input('data_termino');

        if (Carbon::parse($resource->data_termino)->startOfDay()->isBefore(Carbon::parse($resource->data_inicio)->startOfDay())) {
            $mensagem = "A data de término não pode ser menor que a data de início.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $arrErrors[] = [
                'error' => $mensagem,
                'trace_id' => $traceId
            ];
        }
    }

    private function validarPermissaoSubstituto($resource, $request, &$arrErrors)
    {
        // Verifique se o campo 'substituto_bln' foi enviado
        if ($request->has('substituto_bln') && $request->input('substituto_bln') == true) {
            $consultaPermissao = RefPermissao::find($resource->permissao_id);

            if (!$consultaPermissao || !$consultaPermissao->diretor_bln) {
                // Gerar um log
                $mensagem = "A permissão '". ($consultaPermissao ? $consultaPermissao->nome : 'Permissão não encontrada') ."' não é uma permissão de Diretoria que permite substituto.";
                $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

                $arrErrors[] = [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ];
            } else {
                $resource->substituto_bln = $request->input('substituto_bln');
            }
        }
    }

}
