<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\FuncoesPresos;
use App\Common\RestResponse;
use App\Common\ValidacoesReferenciasId;
use App\Models\IncEntradaPreso;
use App\Models\IncQualificativaProvisoria;
use App\Models\Preso;
use App\Models\PresoPassagemArtigo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncQualificativaController extends Controller
{

    public function store(Request $request)
    {
        $arrErrors = [];

        // $this->authorize('store', IncQualificativaProvisoriaPreso::class);

        // Regras de validação
        $rules = [
            'passagem_id' => 'required|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Verifica se a passagem existe, se não existir já retorna o erro
        $passagem = FuncoesPresos::buscarRecursoPassagemPreso($request->input('passagem_id'));
        if (!$passagem instanceof IncEntradaPreso) {
            // Erros que impedem o processamento
            CommonsFunctions::retornaErroQueImpedemProcessamento422($passagem);
        }

        // Verifica se o usuário tem a permissão necessária
        // if (PermissaoService::temPermissaoRecursivaAcima(Auth::user(), [45, 68])) {
        $permAtribuirMatriculaBln = false;
        // }

        // Se o usuário tem permissão de atribuir matrícula, então verifica se já existe algum preso com a mesma matrícula informada
        if ($permAtribuirMatriculaBln) {
            // Valida se não existe outra qualificativa com o mesmo passagem_id, se existir retorna a mensagem automaticamente
            $this->validarRecursoExistentePreso($request);

            // Se passou pelas validações então insere os novos dados na qualificativa
            return $this->storeQualificativa($request, $passagem);
        } else {
            // Valida se não existe outra qualificativa provisória com o mesmo passagem_id, se existir retorna a mensagem automaticamente
            $this->validarRecursoExistenteProvisoria($request);

            // Se passou pelas validações então insere os novos dados na qualificativa
            return $this->storeQualificativaProvisoria($request, $passagem);
        }
    }

    public function storeQualificativa(Request $request, IncEntradaPreso $passagem)
    {
        $arrErrors = [];

        // $this->authorize('store', IncQualificativaProvisoriaPreso::class);

        // Regras de validação
        $rules = [
            'passagem_id' => 'required|integer',
            'qual_prov_id' => 'required|integer',
            'preso_id' => 'required|integer',
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

        RestResponse::createTesteResponse($request->all());

        $passagem = FuncoesPresos::buscarRecursoPassagemPreso($request->input('passagem_id'));
        if (!$passagem instanceof IncEntradaPreso) {
        }

        if ($passagem instanceof IncEntradaPreso) {
            $passagem->matricula = $request->input('matricula');
            $passagem->nome = $request->input('nome');
            $passagem->nome_social = $request->input('nome_social');
        }

        // Valida se não existe outra qualificativa com o mesmo passagem_id
        $this->validarRecursoExistentePreso($request);

        // Se a validação passou, crie um novo registro
        $novo = new IncQualificativaProvisoria();
        $novo->passagem_id = $request->input('passagem_id');

        $this->preencherCamposProvisoria($novo, $request, $arrErrors);

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
                }
            }

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

    public function storeQualificativaProvisoria(Request $request, IncEntradaPreso $passagem)
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
            'informacoes' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'artigos' => 'nullable|array',
            'artigos.*.id' => 'nullable|integer',
            'artigos.*.artigo_id' => 'required|integer',
            'artigos.*.observacoes' => 'nullable|string',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Atualizaça a passagem do preso
        $passagem->matricula = $request->input('matricula');
        $passagem->nome = $request->input('nome');
        $passagem->nome_social = $request->input('nome_social');
        $passagem->informacoes = $request->input('informacoes');
        $passagem->observacoes = $request->input('observacoes');

        // Cria um novo registro
        $novo = new IncQualificativaProvisoria();
        $novo->passagem_id = $request->input('passagem_id');

        $this->preencherCamposProvisoria($novo, $request, $arrErrors);

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
                }
            }

            $passagem->refresh();

            DB::commit();

            // $this->executarEventoWebsocket();

            $response = RestResponse::createSuccessResponse($passagem, 200, ['token' => true]);
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

        $resource->load('preso.cutis', 'preso.cabelo_tipo', 'preso.cabelo_cor', 'preso.olho_tipo', 'preso.olho_cor', 'preso.crenca');

        $resource->load('preso.pessoa', 'preso.pressoa.cidade_nasc', 'preso.pressoa.genero', 'preso.pressoa.escolaridade', 'preso.pressoa.estado_civil', 'preso.pressoa.documentos');

        $resource->load('artigos', 'qual_prov.cidade_nasc.estado.nacionalidade', 'qual_prov.genero', 'qual_prov.escolaridade', 'qual_prov.estado_civil', 'qual_prov.cutis', 'qual_prov.cabelo_tipo', 'qual_prov.cabelo_cor', 'qual_prov.olho_cor', 'qual_prov.olho_tipo', 'qual_prov.crenca', 'convivio_tipo');

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function update(Request $request)
    {
        // $this->authorize('update', IncQualificativaProvisoriaPreso::class);

        // Regras de validação
        $rules = [
            'qual_prov_id' => 'nullable|integer',
            'preso_id' => 'nullable|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Verifica se a passagem existe, se não existir já retorna o erro
        $passagem = FuncoesPresos::buscarRecursoPassagemPreso($request->passagem_id);
        if (!$passagem instanceof IncEntradaPreso) {
            // Erros que impedem o processamento
            CommonsFunctions::retornaErroQueImpedemProcessamento422($passagem);
        }

        // Verifica se o usuário tem a permissão necessária
        // if (PermissaoService::temPermissaoRecursivaAcima(Auth::user(), [45, 68])) {
        $permAtribuirMatriculaBln = false;
        // }

        if ($request->has('preso_id') && $request->input('preso_id')) {
            // Valida se não existe outra qualificativa com o mesmo passagem_id, se existir retorna a mensagem automaticamente
            $this->validarRecursoExistentePreso($request, $request->input('preso_id'));

            // Se passou pelas validações então insere os novos dados na qualificativa
            return $this->storeQualificativa($request, $passagem);
        } else if ($request->has('qual_prov_id') && $request->input('qual_prov_id')) {
            // Valida se não existe outra qualificativa provisória com o mesmo passagem_id, se existir retorna a mensagem automaticamente
            $this->validarRecursoExistenteProvisoria($request, $request->input('qual_prov_id'));

            // Se passou pelas validações então insere os novos dados na qualificativa
            return $this->updateQualificativaProvisoria($request, $passagem);
        }
    }

    public function updateQualificativaProvisoria(Request $request, IncEntradaPreso $passagem)
    {
        $arrErrors = [];

        // $this->authorize('store', IncQualificativaProvisoriaPreso::class);

        // Regras de validação
        $rules = [
            'qual_prov_id' => 'required|integer',
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
            'informacoes' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'artigos' => 'nullable|array',
            'artigos.*.id' => 'nullable|integer',
            'artigos.*.artigo_id' => 'required|integer',
            'artigos.*.observacoes' => 'nullable|string',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Atualizaça a passagem do preso
        $passagem->matricula = $request->input('matricula');
        $passagem->nome = $request->input('nome');
        $passagem->nome_social = $request->input('nome_social');
        $passagem->informacoes = $request->input('informacoes');
        $passagem->observacoes = $request->input('observacoes');

        // Cria um novo registro
        $resource = $this->buscarRecursoProvisoria($request->input('qual_prov_id'));;

        $this->preencherCamposProvisoria($resource, $request, $arrErrors);

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

            foreach ($passagem->artigos as $artigoExistente) {
                $artigoEnviado = collect($arrArtigos)->firstWhere('id', $artigoExistente->id);

                if (!$artigoEnviado) {
                    // Se o artigo existente não foi enviado, então excluímos
                    CommonsFunctions::inserirInfoDeleted($artigoExistente);
                    $artigoExistente->save();
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

            $passagem->refresh();

            DB::commit();

            // $this->executarEventoWebsocket();

            $response = RestResponse::createSuccessResponse($passagem, 200, ['token' => true]);
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

    private function buscarRecurso($passagem_id)
    {
        $resource = FuncoesPresos::buscarRecursoPassagemPreso($passagem_id);
        if (!$resource instanceof IncEntradaPreso) {
            // Gerar um log
            $status = $resource["passagem.$passagem_id"]['status'];
            $mensagem = $resource["passagem.$passagem_id"]['error'];
            $traceId = $resource["passagem.$passagem_id"]['trace_id'];

            $response = RestResponse::createErrorResponse($status, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }

        return $resource;
    }

    private function buscarRecursoProvisoria($qual_prov_id): IncQualificativaProvisoria
    {
        $resource = FuncoesPresos::buscarRecursoQualificativaProvisoria($qual_prov_id);
        if (!$resource instanceof IncQualificativaProvisoria) {
            // Gerar um log
            $status = $resource["passagem.$qual_prov_id"]['status'];
            $mensagem = $resource["passagem.$qual_prov_id"]['error'];
            $traceId = $resource["passagem.$qual_prov_id"]['trace_id'];

            $response = RestResponse::createErrorResponse($status, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }

        return $resource;
    }

    private function validarRecursoExistentePreso(Request $request, $id = null)
    {
        $resource = Preso::where('matricula', $request->input('matricula'));

        if ($id !== null) {
            $resource->whereNot('id', $id);
        }

        if ($resource->exists()) {
            $codigo = 409;
            $mensagem = "A Matrícula informada já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function validarRecursoExistenteProvisoria(Request $request, $id = null)
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

    private static function preencherCamposProvisoria($resource, $request, &$arrErrors, $defaultNull = true)
    {
        if ($request->has('mae') && $request->input('mae')) {
            $resource->mae = $request->input('mae');
        } else if ($defaultNull) {
            $resource->mae = null;
        }
        if ($request->has('pai') && $request->input('pai')) {
            $resource->pai = $request->input('pai');
        } else if ($defaultNull) {
            $resource->pai = null;
        }
        if ($request->has('data_nasc') && $request->input('data_nasc')) {
            $resource->data_nasc = $request->input('data_nasc');
        } else if ($defaultNull) {
            $resource->data_nasc = null;
        }
        if ($request->has('sinais') && $request->input('sinais')) {
            $resource->sinais = $request->input('sinais');
        } else if ($defaultNull) {
            $resource->sinais = null;
        }
        if ($request->has('cidade_nasc_id') && $request->input('cidade_nasc_id')) {
            ValidacoesReferenciasId::cidade(
                $resource,
                $request,
                $arrErrors,
                ['input' => 'cidade_nasc_id', 'nome' => 'cidade de nascimento']
            );
        } else if ($defaultNull) {
            $resource->cidade_nasc_id = null;
        }
        if ($request->has('genero_id') && $request->input('genero_id')) {
            ValidacoesReferenciasId::genero($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->genero_id = null;
        }
        if ($request->has('escolaridade_id') && $request->input('escolaridade_id')) {
            ValidacoesReferenciasId::escolaridade($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->escolaridade_id = null;
        }
        if ($request->has('estado_civil_id') && $request->input('estado_civil_id')) {
            ValidacoesReferenciasId::estadoCivil($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->estado_civil_id = null;
        }
        if ($request->has('cutis_id') && $request->input('cutis_id')) {
            ValidacoesReferenciasId::cutis($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->cutis_id = null;
        }
        if ($request->has('cabelo_tipo_id') && $request->input('cabelo_tipo_id')) {
            ValidacoesReferenciasId::cabeloTipo($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->cabelo_tipo_id = null;
        }
        if ($request->has('cabelo_cor_id') && $request->input('cabelo_cor_id')) {
            ValidacoesReferenciasId::cabeloCor($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->cabelo_cor_id = null;
        }
        if ($request->has('olho_tipo_id') && $request->input('olho_tipo_id')) {
            ValidacoesReferenciasId::olhoTipo($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->olho_tipo_id = null;
        }
        if ($request->has('olho_cor_id') && $request->input('olho_cor_id')) {
            ValidacoesReferenciasId::olhoCor($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->olho_cor_id = null;
        }
        if ($request->has('crenca_id') && $request->input('crenca_id')) {
            ValidacoesReferenciasId::crenca($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->crenca_id = null;
        }
    }
}
