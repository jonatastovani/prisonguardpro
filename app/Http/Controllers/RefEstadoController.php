<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\RefEstado;
use App\Models\RefNacionalidade;

class RefEstadoController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefEstado::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrErrors = [];

        $this->authorize('store', RefEstado::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'sigla' => 'required|min:2',
            'pais_id' => 'required|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome e país
        $resource = RefEstado::where('sigla', $request->input('sigla'))
            ->where('nome', $request->input('nome'))
            ->where('pais_id', $request->input('pais_id'));

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "O Estado informado já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Se a validação passou, crie um novo registro
        $novo = new RefEstado();
        $novo->sigla = $request->input('sigla');
        $novo->nome = $request->input('nome');

        // Valida se o país existe e não está excluído
        $paisValidationResult = $this->validarPaisExistente($novo, $request, $arrErrors);

        // Erros que impedem o processamento
        if (count($arrErrors)) {
            $response = RestResponse::createGenericResponse(["errors" => $arrErrors], 422, "A requisição não pôde ser processada.");
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        CommonsFunctions::inserirInfoCreated($novo);
        $novo->save();

        $response = RestResponse::createSuccessResponse($novo, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $resource = RefEstado::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O Estado pesquisado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| id: $id");

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
        $this->authorize('update', RefEstado::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'pais_id' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefEstado::where('sigla', $request->input('sigla'), 'nome', $request->input('nome'), 'pais_id', $request->input('pais_id'))
            ->whereNot('id', $request->id);

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "O nome do Estado informado já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        $resource = RefEstado::find($request->id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O Estado a ser alterado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Se passou pelas validações, altera o recurso
        $resource->sigla = $request->input('sigla');
        $resource->nome = $request->input('nome');
        $resource->pais_id = $request->input('pais_id');

        CommonsFunctions::inserirInfoUpdated($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 200 - OK)
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', RefEstado::class);

        $resource = RefEstado::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O Estado informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| id: $id");

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Estado excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function validarPaisExistente($resource, $request, &$arrErrors)
    {
        $resource->pais_id = $request->input('pais_id');

        $resource = RefNacionalidade::find($resource->pais_id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O País informado não existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $arrErrors[] = [
                'error' => $mensagem,
                'trace_id' => $traceId
            ];
        }
    }
    
}
