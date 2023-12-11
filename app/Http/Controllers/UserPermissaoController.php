<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
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

        $dados = UserPermissao::
        where('user_id', $idUser)
        // ->where('permissao_id', $registro->permissao_id)
        ->where(function ($query) use ($dataAtual) {
            $query->whereDate('data_termino', '>=', $dataAtual)
            ->orWhereNull('data_termino');
        })->get();
        return response()->json([
            "status" => 200,
            'message' => 'Permissões do usuário encontradas com sucesso.',
            'data' => $dados,
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ], 200);
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

            // Tratar o erro aqui
            return response()->json([
                'status' => 404,
                'errors' => [
                    'error' => $mensagem,
                ],
                'trace_id' => $traceId,
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
            ], 404);
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
            $mensagem = "A permissão já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            // Tratar o erro aqui
            return response()->json([
                'status' => 409,
                'errors' => [
                    'error' => $mensagem,
                ],
                'trace_id' => $traceId,
                'data' => $consultaPermissaoAtiva->first(),
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
            ], 409);
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
            return response()->json([
                "status" => 422,
                'errors' => $arrErrors,
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
            ], 422);
        }

        $novo->id_user_created = auth()->user()->id;
        $novo->ip_created = UserInfo::get_ip();
        $novo->created_at = CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now());
        $novo->updated_at = null;

        $novo->save();
        
        // Retorne uma resposta de sucesso (status 201 - Created)
        return response()->json([
            "status" => 201,
            'message' => 'Permissão adicionada com sucesso.',
            'data' => $novo,
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($idUser, $idPermissao)
    {
        $dataAtual = Carbon::now()->toDateString(); // Obtém a data atual no formato 'Y-m-d'

        $dados = UserPermissao::
        where('user_id', $idUser)
        ->where('permissao_id', $idPermissao)
        ->where(function ($query) use ($dataAtual) {
            $query->whereDate('data_termino', '>=', $dataAtual)
            ->orWhereNull('data_termino');
        })->first();

        return response()->json([
            "status" => 200,
            'message' => 'Permissão de usuário encontrada com sucesso.',
            'data' => $dados,
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ], 200);

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
            $mensagem = "A Permissão de Usuário informada não existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            return response()->json([
                'status' => 404,
                'errors' => [
                    'error' => $mensagem,
                ],
                'trace_id' => $traceId,
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
            ], 404);
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
            return response()->json([
                'status' => 422,
                'errors' => $arrErrors,
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
            ], 422);
        }
        
        // Se as validações passaram, altere o registro

        $resource->id_user_updated = auth()->user()->id;
        $resource->ip_updated = UserInfo::get_ip();

        $resource->save();
        
        // Retorne uma resposta de sucesso (status 201 - Created)
        return response()->json([
            "status" => 201,
            'message' => 'Alteração realizada com sucesso.',
            'data' => $resource,
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $resource = UserPermissao::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            return response()->json([
                'status' => 404,
                'errors' => [
                    'error' => 'Permissão de usuário não encontrada.'
                ],
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
                ], 404);
        }

        // Execute o soft delete
        $resource->id_user_deleted = auth()->user()->id;
        $resource->ip_deleted = UserInfo::get_ip();
        $resource->deleted_at = CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now());

        $resource->save();

        // Resposta de sucesso
        return response()->json([
            'status' => 200,
            'message' => 'Permissão de usuário excluída com sucesso.',
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ], 200);
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
