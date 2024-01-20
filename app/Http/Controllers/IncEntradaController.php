<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\IncEntrada;
use App\Models\IncEntradaPreso;
use App\Models\RefIncOrigem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class IncEntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

        // Inicia a transação
        DB::beginTransaction();

        try {

            CommonsFunctions::inserirInfoCreated($novo);
            $novo->save();

            $arrIncPreso = [];
            foreach ($request->input('presos') as $preso) {
                $preso['entrada_id'] = $novo->id;
                $preso = $this->preencherPreso($preso);
                CommonsFunctions::inserirInfoCreated($preso);
                $preso->save();
                $arrIncPreso[] = $preso;
            }

            $novo['presos'] = $arrIncPreso;

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
     * Show the form for editing the specified resource.
     */
    public function edit(IncEntrada $incEntrada)
    {
        //
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

            CommonsFunctions::inserirInfoUpdated($resource);
            $resource->save();

            $presos = [];
            foreach ($arrIncPreso as $preso) {
                if($preso instanceof IncEntradaPreso) {
                    $preso['entrada_id'] = $resource->id;

                    CommonsFunctions::inserirInfoUpdated($preso);

                    $preso->save();
                    $presos[] = $preso;
                }
            }

            $resource['presos'] = $presos;

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
    public function destroy(IncEntrada $incEntrada)
    {
        //
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

    private function preencherPreso($preso)
    {
        $retorno = new IncEntradaPreso();
        $camposValidos = ['entrada_id', 'nome', 'matricula', 'rg', 'cpf', 'mae', 'pai', 'data_prisao', 'informacoes', 'observacoes'];

        if (isset($preso['id'])) {
            $retorno = $this->buscarRecursoPreso($preso['id']);
        }

        if ($retorno instanceof IncEntradaPreso) {
            foreach ($camposValidos as $campo) {
                if (isset($preso[$campo]) && !empty($preso[$campo])) {
                    $retorno->$campo = $preso[$campo];
                }
            }
        }
        return $retorno;
    }

    private function buscarRecursoPreso($id)
    {
        $resource = IncEntradaPreso::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID inclusão $id informado não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            return ["preso.$id" => [
                'error' => $mensagem,
                'trace_id' => $traceId
            ]];
        }

        return $resource;
    }
}
