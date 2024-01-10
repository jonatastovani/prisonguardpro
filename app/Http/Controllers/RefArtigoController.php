<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\CommonsFunctions;
use App\Common\RestResponse;

use App\Models\RefArtigo;

class RefArtigoController extends Controller
{

    // protected $user;

    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         $this->user = Auth::user();

    //         if (!$this->user) {
    //             if ($request->wantsJson()) {
    //                 return response()->json([
    //                     'message' => 'Unauthorized',
    //                     'status' => 403,
    //                     'data' => []], 403);
    //             } else {
    //                 abort(403, 'Unauthorized');
    //             }
    //         }

    //         return $next($request);
    //     });
    // }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefArtigo::all();
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
            'descricao' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request,$rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefArtigo::where('nome', $request->input('nome'));

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "O artigo informado já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));
    
            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }
        
        // Se a validação passou, crie um novo registro
        $novo = new RefArtigo();
        $novo->nome = $request->input('nome');
        $novo->descricao = $request->input('descricao');

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
        $resource = RefArtigo::find($id);
    
        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O artigo pesquisado não existe ou foi excluído.";
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
        $this->authorize('update', RefArtigo::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'descricao' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request,$rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefArtigo::
        where('nome', $request->input('nome'))
        ->whereNot('id', $request->id);

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "O nome do artigo informado já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], 409, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }
        
        $resource = RefArtigo::find($request->id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O artigo a ser alterado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Se passou pelas validações, altera o recurso
        $resource->nome = $request->input('nome');
        $resource->descricao = $request->input('descricao');

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
        $this->authorize('delete', RefArtigo::class);

        $resource = RefArtigo::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O artigo informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| id: $id");

            $response = RestResponse::createErrorResponse(404, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Artigo excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

}
