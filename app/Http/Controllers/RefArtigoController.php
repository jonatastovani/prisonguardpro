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

    public function indexSelect(Request $request)
    {
        // Regras de validação
        $rules = [
            'text' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        $resources = RefArtigo::where('nome', 'LIKE', "%{$request->texto}%")
            ->orWhere('descricao', 'LIKE', "%{$request->texto}%")
            ->get();

        // Mapear os resultados para criar um array com os campos id e text
        $mappedResults = $resources->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nome . "(" . $item->descricao . ")",
            ];
        });

        $response = RestResponse::createSuccessResponse($mappedResults, 200);
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

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request);

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
        $this->authorize('update', RefArtigo::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'descricao' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request, $request->id);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($request->id);

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

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Artigo excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id)
    {
        $resource = RefArtigo::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID do artigo informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function validarRecursoExistente($request, $id = null)
    {
        $query = RefArtigo::where('nome', $request->input('nome'));

        if ($id !== null) {
            $query->whereNot('id', $id);
        }

        if ($query->exists()) {
            $codigo = 409;
            $mensagem = "O nome do artigo informado já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $query->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }
}
