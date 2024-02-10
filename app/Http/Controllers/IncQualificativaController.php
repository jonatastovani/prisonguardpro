<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\FuncoesPresos;
use App\Common\RestResponse;
use App\Common\ValidacoesReferenciasId;
use App\Models\IncEntradaPreso;
use App\Models\IncQualificativaProvisoria;
use App\Models\PresoPassagemArtigo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncQualificativaController extends Controller
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
            'estatura' => 'nullable|numeric|between:0,4',
            'peso' => 'nullable|numeric|between:0,400',
            'cutis_id' => 'nullable|integer',
            'cabelo_tipo_id' => 'nullable|integer',
            'cabelo_cor_id' => 'nullable|integer',
            'olho_cor_id' => 'nullable|integer',
            'olho_tipo_id' => 'nullable|integer',
            'crenca_id' => 'nullable|integer',
            'sinais' => 'nullable|string',
            'artigos' => 'nullable|array',
            'artigos.*.id' => 'nullable|integer',
            'artigos.*.artigo_id' => 'required|integer',
            'artigos.*.observacoes' => 'nullable|string',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outra qualificativa com o mesmo passagem_id
        $this->validarRecursoExistente($request);

        // Se a validação passou, atualize registro de passagem
        $passagem = FuncoesPresos::buscarRecursoPassagemPreso($request->input('passagem_id'));
        if ($passagem instanceof IncEntradaPreso) {
            $passagem->matricula = $request->input('matricula');
            $passagem->nome = $request->input('nome');
            $passagem->nome_social = $request->input('nome_social');
        }

        // Se a validação passou, crie um novo registro
        $novo = new IncQualificativaProvisoria();
        $novo->passagem_id = $request->input('passagem_id');

        $this->preencherCampos($novo, $request, $arrErrors);

        $arrArtigos = [];
        if ($request->has('artigos') && $request->input('artigos')) {
            foreach ($request->input('artigos') as $preso) {
                $retorno = $this->preencherArtigos($preso);

                if ($retorno instanceof PresoPassagemArtigo) {
                    $arrArtigos[] = $retorno;
                } else {
                    $arrErrors = array_merge($arrErrors, $retorno);
                }
            }
        }

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

        // Inicia a transação
        DB::beginTransaction();

        try {
            CommonsFunctions::inserirInfoUpdated($passagem);
            $passagem->save();

            CommonsFunctions::inserirInfoCreated($novo);
            $novo->save();

            // $artigos = [];
            foreach ($arrArtigos as $artigo) {
                if ($artigo instanceof PresoPassagemArtigo) {

                    $artigo['passagem_id'] = $passagem->id;
                    CommonsFunctions::inserirInfoCreated($artigo);

                    $artigo->save();
                    // $artigos[] = $artigo;
                }
            }

            // $novo['artigos'] = $artigos;
            $novo->refresh();

            DB::commit();

            // $this->executarEventoWebsocket();

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

    public function show($passagem_id)
    {
        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($passagem_id);

        // Carrega os presos relacionados
        $resource->load(['passagem.artigos_passagem']);

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function showProvisoria($passagem_id, $provisoria_id)
    {
        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($passagem_id);

        // Carrega os presos relacionados
        $resource->load(['passagem.artigos_passagem']);

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
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
            'estatura' => 'nullable|numeric|between:0,4',
            'peso' => 'nullable|numeric|between:0,400',
            'cutis_id' => 'nullable|integer',
            'cabelo_tipo_id' => 'nullable|integer',
            'cabelo_cor_id' => 'nullable|integer',
            'olho_cor_id' => 'nullable|integer',
            'olho_tipo_id' => 'nullable|integer',
            'crenca_id' => 'nullable|integer',
            'sinais' => 'nullable|string',
            'artigos' => 'nullable|array',
            'artigos.*.id' => 'nullable|integer',
            'artigos.*.artigo_id' => 'required|integer',
            'artigos.*.observacoes' => 'nullable|string',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outra qualificativa com o mesmo passagem_id
        $this->validarRecursoExistente($request, $request->id);

        // Se a validação passou, atualize registro de passagem
        $passagem = FuncoesPresos::buscarRecursoPassagemPreso($request->input('passagem_id'));
        if ($passagem instanceof IncEntradaPreso) {
            $passagem->matricula = $request->input('matricula');
            $passagem->nome = $request->input('nome');
            $passagem->nome_social = $request->input('nome_social');
        }

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($request->id);

        $this->preencherCampos($resource, $request, $arrErrors);

        $arrArtigos = [];
        if ($request->has('artigos') && $request->input('artigos')) {
            foreach ($request->input('artigos') as $preso) {
                $retorno = $this->preencherArtigos($preso);

                if ($retorno instanceof PresoPassagemArtigo) {
                    $arrArtigos[] = $retorno;
                } else {
                    $arrErrors = array_merge($arrErrors, $retorno);
                }
            }
        }

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

        // Inicia a transação
        DB::beginTransaction();

        try {
            CommonsFunctions::inserirInfoUpdated($passagem);
            $passagem->save();

            CommonsFunctions::inserirInfoUpdated($resource);
            $resource->save();

            foreach ($resource->artigos as $artigosExistente) {
                $artigoEnviado = collect($arrArtigos)->firstWhere('id', $artigosExistente->id);

                if (!$artigoEnviado) {
                    // Se o artigo existente não foi enviado, então excluímos
                    CommonsFunctions::inserirInfoDeleted($artigosExistente);
                    $artigosExistente->save();
                }
            }

            foreach ($arrArtigos as $artigo) {
                if ($artigo instanceof PresoPassagemArtigo) {

                    if (!$artigo->id) {
                        $artigo['passagem_id'] = $passagem->id;
                        CommonsFunctions::inserirInfoCreated($artigo);
                    } else {
                        CommonsFunctions::inserirInfoUpdated($artigo);
                    }

                    $artigo->save();
                }
            }

            $resource->refresh();

            DB::commit();

            // $this->executarEventoWebsocket();

            $response = RestResponse::createSuccessResponse($resource, 200, ['token' => true]);
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

    private function preencherArtigos($artigo, $passagem_id = null)
    {
        $retorno = new PresoPassagemArtigo();
        $camposValidos = ['passagem_id', 'artigo_id', 'observacoes'];

        if (isset($artigo['id'])) {
            $retorno = $this->buscarRecursoArtigo($artigo['id'], $passagem_id);
        }

        if ($retorno instanceof PresoPassagemArtigo) {
            foreach ($camposValidos as $campo) {
                if (isset($artigo[$campo]) && !empty($artigo[$campo])) {
                    $retorno->$campo = $artigo[$campo];
                } else {
                    if ($passagem_id && !in_array($campo, ['passagem_id'])) {
                        $retorno->$campo = null;
                    }
                }
            }
        }
        return $retorno;
    }

    private function buscarRecursoArtigo($id, $passagem_id)
    {
        $resource = FuncoesPresos::buscarRecursoPresoPassagemArtigo($id);

        if ($resource instanceof PresoPassagemArtigo) {
            if ($resource->entrada_id != $passagem_id) {
                // Gerar um log
                $codigo = 422;
                $mensagem = "O ID $id de artigo atribuído informado não pertence a passagem de preso $passagem_id.";
                $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

                return ["artigo_passagem.$id" => [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ]];
            }
        }

        return $resource;
    }

    private function buscarRecurso($passagem_id, $provisoria_id = null)
    {
        $resource = FuncoesPresos::buscarRecursoPassagemPreso($passagem_id);
        if(!$resource instanceof IncEntradaPreso){
            // Gerar um log
            $codigo = $resource["passagem.$passagem_id"]['code'];
            $mensagem = $resource["passagem.$passagem_id"]['error'];
            $traceId = $resource["passagem.$passagem_id"]['trace_id'];

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }

        $resource->load('preso.pessoa');
        
        return $resource;
    }

    private function validarRecursoExistente($request, $id = null): IncQualificativaProvisoria
    {
        $resource = IncQualificativaProvisoria::where('passagem_id', $request->input('passagem_id'));

        if ($id !== null) {
            $resource->whereNot('id', $id);
        }

        if ($resource->exists()) {
            $codigo = 409;
            $mensagem = "A Qualificativa Provisória para o ID Passagem informado já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private static function preencherCampos($resource, $request, &$arrErrors, $defaultNull = true)
    {
        if ($request->has('mae')) {
            $resource->mae = $request->input('mae');
        } else if ($defaultNull) {
            $resource->mae = null;
        }
        if ($request->has('pai')) {
            $resource->pai = $request->input('pai');
        } else if ($defaultNull) {
            $resource->pai = null;
        }
        if ($request->has('data_nasc')) {
            $resource->data_nasc = $request->input('data_nasc');
        } else if ($defaultNull) {
            $resource->data_nasc = null;
        }
        if ($request->has('sinais')) {
            $resource->sinais = $request->input('sinais');
        } else if ($defaultNull) {
            $resource->sinais = null;
        }
        if ($request->has('cidade_nasc_id')) {
            ValidacoesReferenciasId::cidade(
                $resource,
                $request,
                $arrErrors,
                ['input' => 'cidade_nasc_id', 'nome' => 'cidade de nascimento']
            );
        } else if ($defaultNull) {
            $resource->cidade_nasc_id = null;
        }
        if ($request->has('genero_id')) {
            ValidacoesReferenciasId::genero($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->genero_id = null;
        }
        if ($request->has('escolaridade_id')) {
            ValidacoesReferenciasId::escolaridade($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->escolaridade_id = null;
        }
        if ($request->has('estado_civil_id')) {
            ValidacoesReferenciasId::estadoCivil($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->estado_civil_id = null;
        }
        if ($request->has('cutis_id')) {
            ValidacoesReferenciasId::cutis($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->cutis_id = null;
        }
        if ($request->has('cabelo_tipo_id')) {
            ValidacoesReferenciasId::cabeloTipo($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->cabelo_tipo_id = null;
        }
        if ($request->has('cabelo_cor_id')) {
            ValidacoesReferenciasId::cabeloCor($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->cabelo_cor_id = null;
        }
        if ($request->has('olho_tipo_id')) {
            ValidacoesReferenciasId::olhoTipo($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->olho_tipo_id = null;
        }
        if ($request->has('olho_cor_id')) {
            ValidacoesReferenciasId::olhoCor($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->olho_cor_id = null;
        }
        if ($request->has('crenca_id')) {
            ValidacoesReferenciasId::crenca($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->crenca_id = null;
        }
    }
}
