<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\RefCidade;
use App\Models\RefEstado;
use Illuminate\Http\Request;

class RefCidadeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefCidade::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function indexSelect2(Request $request)
    {
        // Regras de validação
        $rules = [
            'text' => 'required|string|min:3',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        $resources = RefCidade::select('ref_cidades.*')
        ->join('ref_estados', 'ref_estados.id', '=', 'ref_cidades.estado_id')
        ->where('ref_cidades.nome', 'LIKE', '%' . $request->input('text') . '%')
        ->orWhere('ref_estados.nome', 'LIKE', '%' . $request->input('text') . '%')
        ->orWhere('ref_estados.sigla', 'LIKE', '%' . $request->input('text') . '%')
        ->with('estado')
        ->get();

        // Mapear os resultados para criar um array com os campos id e text
        $mappedResults = $resources->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nome." - ".$item->estado->sigla." | ".$item->estado->nacionalidade->pais ,
            ];
        });

        $response = RestResponse::createSuccessResponse($mappedResults, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function indexSearchAll(Request $request)
    {
        // Regras de validação
        $rules = [
            'text' => 'nullable|string',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        $resource = RefCidade::select('ref_cidades.*')
            ->join('ref_estados', 'ref_estados.id', '=', 'ref_cidades.estado_id')
            ->join('ref_nacionalidades', 'ref_nacionalidades.id', '=', 'ref_estados.nacionalidade_id')
            ->where('ref_cidades.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_estados.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_estados.sigla', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.sigla', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.pais', 'LIKE', '%' . $request->input('text') . '%')
            ->with('estado.nacionalidade')
            ->orderBy('ref_cidades.nome')
            // ->toSql();
            ->get();


        // RestResponse::createTesteResponse($resource);

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrErrors = [];

        $this->authorize('store', RefCidade::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'estado_id' => 'required|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request);

        // Se a validação passou, crie um novo registro
        $novo = new RefCidade();
        $novo->nome = $request->input('nome');

        // Valida se o Estado existe e não está excluído
        $this->validarEstadoExistente($novo, $request, $arrErrors);

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

        CommonsFunctions::inserirInfoCreated($novo);
        $novo->save();

        // Busca o recurso para retornar com a referência de chave estrangeira correta
        $resource = $this->buscarRecurso($novo->id);

        $response = RestResponse::createSuccessResponse($resource, 200);
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
        $arrErrors = [];

        $this->authorize('update', RefCidade::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'estado_id' => 'required|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request, $request->id);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($request->id);

        // Se passou pelas validações, altera o recurso
        $resource->nome = $request->input('nome');

        // Valida se o Estado existe e não está excluído
        $this->validarEstadoExistente($resource, $request, $arrErrors);

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

        CommonsFunctions::inserirInfoUpdated($resource);
        $resource->save();

        // Busca o recurso para retornar com a referência de chave estrangeira correta
        $resource = $this->buscarRecurso($request->id);

        // Retorne uma resposta de sucesso (status 200 - OK)
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', RefCidade::class);

        $resource = RefCidade::find($id);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Cidade excluída com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id)
    {
        $resource = RefCidade::with('estado.nacionalidade')->find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID da cidade informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function validarRecursoExistente($request, $id = null)
    {
        $query = RefCidade::where('nome', $request->input('nome'))
            ->where('estado_id', $request->input('estado_id'));

        if ($id !== null) {
            $query->whereNot('id', $id);
        }

        if ($query->exists()) {
            $codigo = 409;
            $mensagem = "A cidade informada já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $query->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }

    private function validarEstadoExistente($resource, $request, &$arrErrors)
    {
        $resource->estado_id = $request->input('estado_id');

        $resource = RefEstado::find($resource->estado_id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O Estado informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $arrErrors[] = [
                'error' => $mensagem,
                'trace_id' => $traceId
            ];
        }
    }
}
