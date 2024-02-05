<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\RefCrenca;
use Illuminate\Http\Request;

class RefCrencaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefCrenca::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function indexSearchAll(Request $request)
    {
        // Regras de validação
        $rules = [
            'text' => 'nullable|string',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);
        
        $resource = RefCrenca::where('nome','LIKE', '%'. $request->input('text') .'%')
        ->orderBy('nome')->get();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Regras de validação
        $rules = [
            'nome' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request);

        // Se a validação passou, crie um novo registro
        $novo = new RefCrenca();
        $novo->nome = $request->input('nome');

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
        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->authorize('update', RefCrenca::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request, $request->id);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($request->id);

        // Se passou pelas validações, altera o recurso
        $resource->nome = $request->input('nome');

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
        $this->authorize('delete', RefCrenca::class);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Crença excluída com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id)
    {
        $resource = RefCrenca::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID da crença informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function validarRecursoExistente($request, $id = null)
    {
        $query = RefCrenca::where('nome', $request->input('nome'));

        if ($id !== null) {
            $query->whereNot('id', $id);
        }

        if ($query->exists()) {
            $codigo = 409;
            $mensagem = "O nome da crença informada já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $query->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }
}