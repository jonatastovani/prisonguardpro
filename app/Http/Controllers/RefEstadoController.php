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
        $resource = RefEstado::orderBy('nome')->get();
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

        $resource = RefEstado::select('ref_estados.*')
            ->join('ref_nacionalidades', 'ref_nacionalidades.id', '=', 'ref_estados.nacionalidade_id')
            ->where('ref_estados.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_estados.sigla', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.sigla', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.pais', 'LIKE', '%' . $request->input('text') . '%')
            ->with('nacionalidade')
            ->orderBy('ref_estados.nome')
            // ->toSql();
            ->get();


        // RestResponse::createTesteResponse($resource);

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function indexSelect2(Request $request)
    {
        // Regras de validação
        $rules = [
            'text' => 'nullable|string',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        $resources = RefEstado::select('ref_estados.*')
            ->join('ref_nacionalidades', 'ref_nacionalidades.id', '=', 'ref_estados.nacionalidade_id')
            ->where('ref_estados.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_estados.sigla', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.sigla', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.pais', 'LIKE', '%' . $request->input('text') . '%')
            ->with('nacionalidade')
            ->orderBy('ref_estados.nome')
            // ->toSql();
            ->get();

        // Mapear os resultados para criar um array com os campos id e text
        $mappedResults = $resources->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nome . " - " . $item->sigla . " (" . $item->nacionalidade->pais . ")",
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
        $arrErrors = [];

        $this->authorize('store', RefEstado::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'sigla' => 'required|min:2',
            'nacionalidade_id' => 'required|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request);

        // Se a validação passou, crie um novo registro
        $novo = new RefEstado();
        $novo->sigla = $request->input('sigla');
        $novo->nome = $request->input('nome');

        // Valida se o país existe e não está excluído
        $this->validarPaisExistente($novo, $request, $arrErrors);

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

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
        $resource->load('nacionalidade');

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $arrErrors = [];

        $this->authorize('update', RefEstado::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'sigla' => 'required|min:2',
            'nacionalidade_id' => 'required|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request, $request->id);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($request->id);

        // Se passou pelas validações, altera o recurso
        $resource->sigla = $request->input('sigla');
        $resource->nome = $request->input('nome');

        // Valida se o país existe e não está excluído
        $this->validarPaisExistente($resource, $request, $arrErrors);

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

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

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Estado excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id)
    {
        $resource = RefEstado::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID do Estado informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function validarRecursoExistente($request, $id = null)
    {
        $query = RefEstado::where('sigla', $request->input('sigla'))
            ->where('nome', $request->input('nome'))
            ->where('nacionalidade_id', $request->input('nacionalidade_id'));

        if ($id !== null) {
            $query->whereNot('id', $id);
        }

        // Verificar se o nome já existe no país
        $query->orWhere(function ($query) use ($request, $id) {
            $query->where('nome', $request->input('nome'))
                ->where('nacionalidade_id', $request->input('nacionalidade_id'));
            if ($id !== null) {
                $query->whereNot('id', $id);
            }
        });

        if ($query->exists()) {
            $codigo = 409;
            $mensagem = "O Estado informado já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $query->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }

    private function validarPaisExistente($resource, $request, &$arrErrors)
    {
        $resource->nacionalidade_id = $request->input('nacionalidade_id');

        $resource = RefNacionalidade::find($resource->nacionalidade_id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $mensagem = "O País informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $arrErrors[] = [
                'error' => $mensagem,
                'trace_id' => $traceId
            ];
        }
    }
}
