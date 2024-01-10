<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\RefProfissao;
use Illuminate\Http\Request;

class RefProfissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefProfissao::all();
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

        CommonsFunctions::validacaoRequest($request,$rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefProfissao::where('nome', $request->input('nome'));

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "A profissão informada já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));
    
            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }
        
        // Se a validação passou, crie um novo registro
        $novo = new RefProfissao();
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
        $resource = RefProfissao::find($id);
    
        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "A profissão pesquisada não existe ou foi excluída.";
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
        $this->authorize('update', RefProfissao::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request,$rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefProfissao::
        where('nome', $request->input('nome'))
        ->whereNot('id', $request->id);

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "O nome da profissão informada já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }
        
        $resource = RefProfissao::find($request->id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "A profissão a ser alterada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

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
        $this->authorize('delete', RefProfissao::class);

        $resource = RefProfissao::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "A profissão informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| id: $id");

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Profissão excluída com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }
}

