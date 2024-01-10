<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\RefDocumentoTipo;
use Illuminate\Http\Request;

class RefDocumentoTipoController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefDocumentoTipo::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->authorize('store', RefDocumentoTipo::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request,$rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefDocumentoTipo::where('nome', $request->input('nome'));

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "O tipo de documento informado já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));
    
            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }
        
        // Se a validação passou, crie um novo registro
        $novo = new RefDocumentoTipo();
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
        $resource = RefDocumentoTipo::find($id);
    
        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O tipo de documento pesquisado não existe ou foi excluído.";
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
        $this->authorize('update', RefDocumentoTipo::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request,$rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefDocumentoTipo::
        where('nome', $request->input('nome'))
        ->whereNot('id', $request->id);

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "O nome do tipo de documento informado já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }
        
        $resource = RefDocumentoTipo::find($request->id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O tipo de documento a ser alterado não existe ou foi excluído.";
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
        $this->authorize('delete', RefDocumentoTipo::class);

        $resource = RefDocumentoTipo::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O tipo de documento informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| id: $id");

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Tipo de documento excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

}
