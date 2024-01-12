<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\RefNacionalidade;
use Illuminate\Http\Request;

class RefNacionalidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefNacionalidade::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('store', RefNacionalidade::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'sigla' => 'required|min:3',
            'pais' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefNacionalidade::where('nome', $request->input('nome'))
            ->orWhere('sigla', $request->input('sigla'))
            ->orWhere('pais', $request->input('pais'));

        if ($resource->exists()) {
            // Gerar um log
            $codigo = 409;
            $mensagem = "A nome da nacionalidade, sigla ou País informado já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Se a validação passou, crie um novo registro
        $novo = new RefNacionalidade();
        $novo->nome = $request->input('nome');
        $novo->sigla = $request->input('sigla');
        $novo->pais = $request->input('pais');

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
        $resource = RefNacionalidade::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "A nacionalidade pesquisada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
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
        $this->authorize('update', RefNacionalidade::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'sigla' => 'required|min:3',
            'pais' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefNacionalidade::where(function ($query) use ($request) {
            $query->where('nome', $request->input('nome'))
                ->orWhere('sigla', $request->input('sigla'))
                ->orWhere('pais', $request->input('pais'));
        })
            ->whereNot('id', $request->id);

        if ($resource->exists()) {
            // Gerar um log
            $codigo = 409;
            $mensagem = "O nome da nacionalidade, sigla ou País informado já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $resource->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        $resource = RefNacionalidade::find($request->id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "A nacionalidade a ser alterada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Se passou pelas validações, altera o recurso
        $resource->nome = $request->input('nome');
        $resource->sigla = $request->input('sigla');
        $resource->pais = $request->input('pais');

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
        $this->authorize('delete', RefNacionalidade::class);

        $resource = RefNacionalidade::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "A nacionalidade informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode());
        }

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Nacionalidade excluída com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }
}
