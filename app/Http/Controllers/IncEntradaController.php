<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\FuncoesPresos;
use App\Common\RestResponse;
use App\Common\ValidacoesReferenciasId;
use App\Events\EntradasPresos;
use App\Models\IncEntrada;
use App\Models\IncEntradaPreso;
use App\Models\RefPresoConvivioTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use restResponse as GlobalRestResponse;

class IncEntradaController extends Controller
{
    /**
     * Display a listing of the all resource.
     */
    public function indexBusca(Request $request)
    {
        $arrErrors = [];

        // $this->authorize('store', IncEntradaPreso::class);

        // Regras de validação
        $rules = [
            'filtros' => 'required|array',
            'filtros.data_entrada' => 'required|array',
            'filtros.data_entrada.inicio' => 'required|date_format:Y-m-d',
            'filtros.data_entrada.fim' => 'nullable|date_format:Y-m-d',
            'filtros.ordenacao' => 'required|string',
            'filtros.status' => 'nullable|integer',
            'filtros.texto' => 'nullable|array',
            'filtros.texto.valor' => 'nullable|string',
            'filtros.texto.tratamento' => 'required_with:filtros.texto.valor|integer',
            'filtros.texto.metodo' => 'required_with:filtros.texto.valor|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);
        $resource = IncEntrada::with([
            'presos' => function ($query) use ($request) {
                // Condição para filtrar por status se o filtro estiver presente
                $query->when($request->filtros['status'], function ($query, $status) {
                    $query->where('status_id', $status);
                })
                    ->when($request->filtros['texto']['valor'], function ($query, $valor) {
                        $arrCampos = ['nome', 'matricula', 'mae', 'rg', 'cpf', 'pai', 'data_prisao', 'informacoes', 'observacoes'];

                        for ($i = 0; $i < count($arrCampos); $i++) {
                            if ($i == 0) {
                                // Adicione as condições relacionadas ao texto se valor estiver presente
                                $query->where($arrCampos[$i], 'LIKE', "%$valor%");
                            } else {
                                $query->orWhere($arrCampos[$i], 'LIKE', "%$valor%");
                            }
                        }
                    })->when($request->filtros['ordenacao'], function ($query, $campo) {
                        if (in_array($campo, ['matricula', 'nome'])) {
                            $query->orderBy($campo);
                        }
                    });
            }, 'presos.preso.pessoa' => function ($query) use ($request) {
                $query->when($request->filtros['texto']['valor'], function ($query, $valor) {
                    $arrCampos = ['nome', 'matricula', 'mae', 'rg', 'cpf', 'pai', 'data_prisao', 'informacoes', 'observacoes'];

                    for ($i = 0; $i < count($arrCampos); $i++) {
                        if ($i == 0) {
                            // Adicione as condições relacionadas ao texto se valor estiver presente
                            $query->where($arrCampos[$i], 'LIKE', "%$valor%");
                        } else {
                            $query->orWhere($arrCampos[$i], 'LIKE', "%$valor%");
                        }
                    }
                })->when($request->filtros['ordenacao'] == 'matricula', function ($query) {
                    $query->orderBy('matricula');
                });
            }
        ])
            ->where('data_entrada', '>=', $request->filtros['data_entrada']['inicio'] . " 00:00:00")
            ->when($request->filtros['data_entrada']['fim'], function ($query, $fim) {
                return $query->where('data_entrada', '<=', "$fim 23:59:59");
            })
            ->when($request->filtros['ordenacao'], function ($query, $campo) {
                if (in_array($campo, ['data_entrada'])) {
                    $query->orderBy($campo);
                }
            })
            ->get();

        $ordenacao = 'preso.matricula';
        switch ($request->filtros['ordenacao']) {
            case 'matricula':
                $ordenacao = 'preso.matricula';
                break;
            case 'nome':
                $ordenacao = 'preso.nome';
                break;
            case 'data_entrada':
                $ordenacao = 'data_entrada';
                break;
        }

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrErrors = [];

        // $this->authorize('store', IncEntradaPreso::class);

        // Regras de validação
        $rules = [
            'origem_id' => 'required|integer',
            'data_entrada' => 'required|date_format:Y-m-d H:i:s',
            'presos' => 'required|array',
            'presos.*.nome' => 'required|regex:/^[^0-9]+$/u',
            'presos.*.nome_social' => 'nullable|regex:/^[^0-9]+$/u',
            'presos.*.matricula' => 'nullable|regex:/^[0-9]+$/',
            'presos.*.convivio_tipo_id' => 'nullable|integer',
            'presos.*.data_prisao' => 'nullable|date',
            'presos.*.informacoes' => 'nullable|text',
            'presos.*.observacoes' => 'nullable|text',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Se a validação passou, crie um novo registro
        $novo = new IncEntrada();

        // Valida se a origem existe e não está excluída
        ValidacoesReferenciasId::incOrigem($novo, $request, $arrErrors);

        $novo->data_entrada = $request->input('data_entrada');

        $arrIncPreso = [];
        foreach ($request->input('presos') as $preso) {
            $retorno = $this->preencherPreso($preso, null, $arrErrors);

            if ($retorno instanceof IncEntradaPreso) {
                $arrIncPreso[] = $retorno;
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
            foreach ($arrIncPreso as $preso) {
                if ($preso instanceof IncEntradaPreso) {

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
        $resource->load(['presos.preso.pessoa', 'presos.convivio_tipo.cor', 'origem']);

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $arrErrors = [];

        // $this->authorize('store', IncEntradaPreso::class);

        // Regras de validação
        $rules = [
            'origem_id' => 'required|integer',
            'data_entrada' => 'required|date_format:Y-m-d H:i:s',
            'presos' => 'required|array',
            'presos.*.nome' => 'required|regex:/^[^0-9]+$/u',
            'presos.*.nome_social' => 'nullable|regex:/^[^0-9]+$/u',
            'presos.*.matricula' => 'nullable|regex:/^[0-9]+$/',
            'presos.*.convivio_tipo_id' => 'nullable|integer',
            'presos.*.data_prisao' => 'nullable|date',
            'presos.*.informacoes' => 'nullable|alpha_dash',
            'presos.*.observacoes' => 'nullable|alpha_dash',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($request->id);

        // Valida se a origem existe e não está excluída
        ValidacoesReferenciasId::incOrigem($resource, $request, $arrErrors);

        $resource->data_entrada = $request->input('data_entrada');

        $arrIncPreso = [];
        foreach ($request->input('presos') as $preso) {
            $retorno = $this->preencherPreso($preso, $resource->id, $arrErrors);

            if ($retorno instanceof IncEntradaPreso) {
                $arrIncPreso[] = $retorno;
            } else {
                $arrErrors = array_merge($arrErrors, $retorno);
            }
        }

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

        // Inicia a transação
        DB::beginTransaction();

        try {

            CommonsFunctions::inserirInfoUpdated($resource);
            $resource->save();

            foreach ($resource->presos as $presoExistente) {
                $presoEnviado = collect($arrIncPreso)->firstWhere('id', $presoExistente->id);

                if (!$presoEnviado) {
                    // O preso existente não foi enviado, então excluímos
                    CommonsFunctions::inserirInfoDeleted($presoExistente);
                    $presoExistente->save();
                }
            }

            foreach ($arrIncPreso as $preso) {
                if ($preso instanceof IncEntradaPreso) {

                    if (!$preso->id) {
                        $preso['entrada_id'] = $resource->id;
                        CommonsFunctions::inserirInfoCreated($preso);
                    } else {
                        CommonsFunctions::inserirInfoUpdated($preso);
                    }

                    $preso->save();
                }
            }

            $resource->refresh();

            DB::commit();

            $this->executarEventoWebsocket();

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $this->authorize('delete', IncEntradaPreso::class);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        $this->executarEventoWebsocket();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, ['message' => 'Entrada de presos excluída com sucesso.', 'token' => true]);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id)
    {
        $resource = IncEntrada::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID da entrada de presos informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function preencherPreso($preso, $entradaId = null, &$arrErrors)
    {
        $retorno = new IncEntradaPreso();
        $camposValidos = ['entrada_id', 'nome', 'nome_social', 'convivio_tipo_id', 'matricula', 'data_prisao', 'informacoes', 'observacoes'];

        if (isset($preso['id'])) {
            $retorno = $this->buscarRecursoPreso($preso['id'], $entradaId);
        }

        if ($retorno instanceof IncEntradaPreso) {
            foreach ($camposValidos as $campo) {
                if ($campo === 'convivio_tipo_id') {
                    $this->verificaConvivioTipo($retorno, $preso, $arrErrors);
                } else {
                    if (isset($preso[$campo]) && !empty($preso[$campo])) {
                        $retorno->$campo = $preso[$campo];
                    } else {
                        if ($entradaId && !in_array($campo, ['entrada_id'])) {
                            $retorno->$campo = null;
                        }
                    }
                }
            }
        }
        return $retorno;
    }

    private function buscarRecursoPreso($id, $entradaId): IncEntradaPreso | array
    {
        $resource = FuncoesPresos::buscarRecursoPassagemPreso($id);

        if ($resource instanceof IncEntradaPreso) {
            if ($resource->entrada_id != $entradaId) {
                // Gerar um log
                $codigo = 422;
                $mensagem = "O ID Passagem $id não pertence a Entrada de Presos $entradaId.";
                $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

                return ["preso.$id" => [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ]];
            }
        }

        return $resource;
    }

    private function executarEventoWebsocket()
    {
        event(new EntradasPresos);
    }

    private static function verificaConvivioTipo(IncEntradaPreso $retorno, $preso, &$arrErrors)
    {
        if (isset($preso['convivio_tipo_id'])) {
            $retorno->convivio_tipo_id = $preso['convivio_tipo_id'];

            $resource = RefPresoConvivioTipo::find($retorno->convivio_tipo_id);

            // Verifique se o modelo foi encontrado e não foi excluído
            if (!$resource || $resource->trashed()) {
                // Gerar um log
                $mensagem = "O tipo de preso informado não existe ou foi excluído.";
                $traceId = CommonsFunctions::generateLog($mensagem . "| Preso: " . json_encode($preso));

                $arrErrors['preso_tipo'] = [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ];
            }
        } else {
            // Busca padrão para tipo de preso da unidade
            $convivio = RefPresoConvivioTipo::where('convivio_padrao_bln', true)->first();
            // Verifique se o modelo foi encontrado e não foi excluído
            if (!$convivio || $convivio->trashed()) {
                // Gerar um log
                $mensagem = "O Tipo de Convivio padrão não foi encontrado para ser inserido.";
                $traceId = CommonsFunctions::generateLog($mensagem . "| Preso: " . json_encode($preso));

                $arrErrors['convivio_padrao'] = [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ];
            } else {
                $retorno->convivio_tipo_id = $convivio->id;
            }
        }
    }
}
