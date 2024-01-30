<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Common\ValidacoesReferenciasId;
use App\Models\IncQualificativaProvisoria;
use App\Models\PresoPassagemArtigo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncQualificativaProvisoriaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrErrors = [];

        // $this->authorize('store', IncQualificativaProvisoriaPreso::class);

        // Regras de validação
        $rules = [
            'passagem_id' => 'required|integer',
            'matricula' => 'nullable|regex:/^[0-9]+$/',
            'nome' => 'required|regex:/^[^0-9]+$/u',
            'nome_social' => 'nullable|regex:/^[^0-9]+$/u',
            'mae' => 'nullable|regex:/^[^0-9]+$/u',
            'pai' => 'nullable|regex:/^[^0-9]+$/u',
            'data_nasc' => 'nullable|date',
            'cidade_nasc_id' => 'nullable|integer',
            'genero_id' => 'nullable|integer',
            'escolaridade_id' => 'nullable|integer',
            'estado_civil_id' => 'nullable|integer',
            'estatura' => 'nullable|numeric|between:0,9.99',
            'peso' => 'nullable|numeric|between:0,999.9',
            'cutis_id' => 'nullable|integer',
            'cabelo_tipo_id' => 'nullable|integer',
            'cabelo_cor_id' => 'nullable|integer',
            'olho_cor_id' => 'nullable|integer',
            'olho_tipo_id' => 'nullable|integer',
            'crenca_id' => 'nullable|integer',
            'olho_cor_id' => 'nullable|integer',
            'olho_cor_id' => 'nullable|integer',
            'sinais' => 'nullable|string',
            'artigos' => 'nullable|array',
            'artigos.*.artigo_id' => 'integer',
            'artigos.*.observacoes' => 'nullable|string',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Se a validação passou, crie um novo registro
        $novo = new IncQualificativaProvisoria();

        if ($request->has('cidade_nasc_id')) {
            ValidacoesReferenciasId::cidade(
                $novo,
                $request,
                $arrErrors,
                ['input' => 'cidade_nasc_id', 'nome' => 'cidade de nascimento']
            );
        }
        if ($request->has('genero_id')) {
            ValidacoesReferenciasId::genero($novo, $request, $arrErrors);
        }
        if ($request->has('escolaridade_id')) {
            ValidacoesReferenciasId::escolaridade($novo, $request, $arrErrors);
        }
        if ($request->has('estado_civil_id')) {
            ValidacoesReferenciasId::estadoCivil($novo, $request, $arrErrors);
        }
        if ($request->has('cutis_id')) {
            ValidacoesReferenciasId::cutis($novo, $request, $arrErrors);
        }
        if ($request->has('cabelo_tipo_id')) {
            ValidacoesReferenciasId::cabeloTipo($novo, $request, $arrErrors);
        }
        if ($request->has('cabelo_cor_id')) {
            ValidacoesReferenciasId::cabeloCor($novo, $request, $arrErrors);
        }
        if ($request->has('olho_tipo_id')) {
            ValidacoesReferenciasId::olhoTipo($novo, $request, $arrErrors);
        }
        if ($request->has('olho_cor_id')) {
            ValidacoesReferenciasId::olhoCor($novo, $request, $arrErrors);
        }
        if ($request->has('crenca_id')) {
            ValidacoesReferenciasId::crenca($novo, $request, $arrErrors);
        }

        $arrArtigos = [];
        foreach ($request->input('artigos') as $preso) {
            $retorno = $this->preencherAtigos($preso);

            if ($retorno instanceof IncEntradaPreso) {
                $arrArtigos[] = $retorno;
            } else {
                $arrErrors = array_merge($arrErrors, $retorno);
            }
        }

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

        // Inicia a transação
        DB::beginTransaction();

        try {

            CommonsFunctions::inserirInfoCreated($novo);
            $novo->save();

            $presos = [];
            foreach ($arrArtigos as $preso) {
                if ($preso instanceof IncQualificativaProvisoriaPreso) {

                    $preso['entrada_id'] = $novo->id;
                    CommonsFunctions::inserirInfoCreated($preso);

                    $preso->save();
                    $presos[] = $preso;
                }
            }

            $novo['presos'] = $presos;

            DB::commit();

            $this->executarEventoWebsocket();

            $response = RestResponse::createSuccessResponse($novo, 200, ['token' => true]);
            return response()->json($response->toArray(), $response->getStatusCode());
        } catch (\Exception $e) {
            // Se ocorrer algum erro, fazer o rollback da transação
            DB::rollBack();

            // Gerar um log
            $codigo = 422;
            $mensagem = "A requisição não pôde ser processada.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Errors: " . json_encode($e->getMessage()));

            $response = RestResponse::createGenericResponse(['error' => $e->getMessage()], 422, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Carrega os presos relacionados
        $resource->load(['presos.preso.pessoa', 'origem']);

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    // private function buscarRecurso($id)
    // {
    //     $resource = IncEntrada::find($id);

    //     // Verifique se o modelo foi encontrado e não foi excluído
    //     if (!$resource || $resource->trashed()) {
    //         // Gerar um log
    //         $codigo = 404;
    //         $mensagem = "O ID da entrada de presos informada não existe ou foi excluída.";
    //         $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

    //         $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
    //         return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
    //     }
    //     return $resource;
    // }

    private function preencherArtigos($artigo, $passagem_id = null)
    {
        $retorno = new PresoPassagemArtigo();
        $camposValidos = ['passagem_id', 'artigo_id', 'observacoes'];

        if (isset($artigo['id'])) {
            $retorno = $this->buscarRecursoArtigo($artigo['id'], $passagem_id);
        }

        if ($retorno instanceof IncEntradaPreso) {
            foreach ($camposValidos as $campo) {
                if (isset($artigo[$campo]) && !empty($artigo[$campo])) {
                    $retorno->$campo = $artigo[$campo];
                } else {
                    if ($passagem_id && !in_array($campo, ['entrada_id'])) {
                        $retorno->$campo = null;
                    }
                }
            }
        }
        return $retorno;
    }

    private function buscarRecursoArtigo($id, $passagem_id)
    {
        $resource = PresoPassagemArtigo::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O artigo ID $id não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            return ["preso.$id" => [
                'error' => $mensagem,
                'trace_id' => $traceId
            ]];
        } else {

            if ($resource->entrada_id != $passagem_id) {
                // Gerar um log
                $codigo = 422;
                $mensagem = "O artigo ID $id não pertence a passagem de preso $passagem_id.";
                $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

                return ["preso.$id" => [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ]];
            }
        }

        return $resource;
    }
}
