<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\IncEntrada;
use App\Models\IncEntradaPreso;
use App\Models\RefIncOrigem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $ordenacao = 'matricula';
        switch ($request->ordenacao) {
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

        $resource = IncEntrada::with('presos.preso.pessoa')
            ->where('data_entrada', $request->filtros['data_entrada']['inicio'])
            ->when($request->filtros['data_entrada']['fim'], function ($query, $fim) {
                return $query->where('data_entrada', '<=', $fim);
            })
            ->when($request->filtros['status'], function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->filtros['texto']['valor'], function ($query, $valor) {
                // Adicione as condições relacionadas ao texto se valor estiver presente
                $query->where('presos.nome', 'LIKE', "%$valor%")
                    ->orWhere('presos.matricula', 'LIKE', "%$valor%");
            })
            ->orderBy($ordenacao)
            ->get();


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
            // 'presos.*' => 'present',
            'presos.*.nome' => 'required|regex:/^[^0-9]+$/u',
            'presos.*.matricula' => 'nullable|regex:/^[0-9]+$/',
            'presos.*.mae' => 'nullable|regex:/^[^0-9]+$/u',
            'presos.*.pai' => 'nullable|regex:/^[^0-9]+$/u',
            'presos.*.data_prisao' => 'nullable|date',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Se a validação passou, crie um novo registro
        $novo = new IncEntrada();

        // Valida se a origem existe e não está excluída
        $this->validarOrigemExistente($novo, $request, $arrErrors);

        $novo->data_entrada = $request->input('data_entrada');

        $arrIncPreso = [];
        foreach ($request->input('presos') as $preso) {
            $retorno = $this->preencherPreso($preso);

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

            $response = RestResponse::createSuccessResponse($novo, 200);
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
        $resource->load('presos.preso.pessoa');

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
            // 'presos.*' => 'present',
            'presos.*.nome' => 'required|regex:/^[^0-9]+$/u',
            'presos.*.matricula' => 'nullable|regex:/^[0-9]+$/',
            'presos.*.mae' => 'nullable|regex:/^[^0-9]+$/u',
            'presos.*.pai' => 'nullable|regex:/^[^0-9]+$/u',
            'presos.*.data_prisao' => 'nullable|date',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($request->id);

        // Valida se a origem existe e não está excluída
        $this->validarOrigemExistente($resource, $request, $arrErrors);

        $resource->data_entrada = $request->input('data_entrada');

        $arrIncPreso = [];
        foreach ($request->input('presos') as $preso) {
            $retorno = $this->preencherPreso($preso, $resource->id);

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

            $response = RestResponse::createSuccessResponse($resource, 200);
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

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Entrada de presos excluída com sucesso.');
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

    private function validarOrigemExistente($resource, $request, &$arrErrors)
    {
        $resource->origem_id = $request->input('origem_id');

        $resource = RefIncOrigem::find($resource->origem_id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "A origem informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $arrErrors['origem'] = [
                'error' => $mensagem,
                'trace_id' => $traceId
            ];
        }
    }

    private function preencherPreso($preso, $entradaId = null)
    {
        $retorno = new IncEntradaPreso();
        $camposValidos = ['entrada_id', 'nome', 'matricula', 'rg', 'cpf', 'mae', 'pai', 'data_prisao', 'informacoes', 'observacoes'];

        if (isset($preso['id'])) {
            $retorno = $this->buscarRecursoPreso($preso['id'], $entradaId);
        }

        if ($retorno instanceof IncEntradaPreso) {
            foreach ($camposValidos as $campo) {
                if (isset($preso[$campo]) && !empty($preso[$campo])) {
                    $retorno->$campo = $preso[$campo];
                } else {
                    if ($entradaId && !in_array($campo, ['entrada_id'])) {
                        $retorno->$campo = null;
                    }
                }
            }
        }
        return $retorno;
    }

    private function buscarRecursoPreso($id, $entradaId)
    {
        $resource = IncEntradaPreso::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID inclusão $id não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            return ["preso.$id" => [
                'error' => $mensagem,
                'trace_id' => $traceId
            ]];
        } else {

            if ($resource->entrada_id != $entradaId) {
                // Gerar um log
                $codigo = 422;
                $mensagem = "O ID Inclusão $id não pertence a Entrada de Presos $entradaId.";
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
