<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\IncEntrada;
use App\Models\IncEntradaPreso;
use App\Models\Preso;
use App\Models\RefIncOrigem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncEntradaPresoController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = IncEntradaPreso::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Display a listing of the all resource.
     */
    public function indexBusca(Request $request)
    {
        $arrErrors = [];

        // $this->authorize('store', IncEntradaPreso::class);

        // Regras de validação
        $rules = [
            'filtros' => 'required|array',
            'filtros.data_entrada' => 'required|array',
            'filtros.data_entrada.inicio' => 'required|date_format:Y-m-d',
            'filtros.data_entrada.fim' => 'nullable|date_format:Y-m-d',
            'filtros.ordenacao' => 'required|string|in:matricula,nome,data_entrada',
            'filtros.status' => 'nullable|integer',
            'filtros.texto' => 'nullable|array',
            'filtros.texto.valor' => 'nullable|string',
            'filtros.texto.tratamento' => 'required_with:filtros.texto.valor|integer|in:1,2',
            'filtros.texto.metodo' => 'required_with:filtros.texto.valor|integer|between:1,4',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        $arrFiltosTexto = [];
        if ($request->filtros['texto']) {
            if ($request->filtros['texto']['tratamento'] == 1) {
                $arrFiltosTexto['palavras'] = explode(' ', $request->filtros['texto']['valor']);
            } else {
                $arrFiltosTexto['palavras'] = $request->filtros['texto']['valor'];
            }

            switch ($request->filtros['texto']['metodo']) {
                    // Busca em qualquer parte
                case 1:
                    $arrFiltosTexto['likeInicio'] = '%';
                    $arrFiltosTexto['likeFim'] = '%';
                    break;
                    // Iniciado por
                case 3:
                    $arrFiltosTexto['likeInicio'] = '';
                    $arrFiltosTexto['likeFim'] = '%';
                    break;
                    // Encerrado por
                case 4:
                    $arrFiltosTexto['likeInicio'] = '%';
                    $arrFiltosTexto['likeFim'] = '';
                    break;
                    // Padrão é a Busca exata
                case 2:
                default:
                    $arrFiltosTexto['likeInicio'] = '';
                    $arrFiltosTexto['likeFim'] = '';
            }
        }

        $resource = IncEntradaPreso::whereHas('entrada', function ($query) use ($request) {
            $query->where('data_entrada', '>=', $request->filtros['data_entrada']['inicio'] . " 00:00:00")
                ->when($request, function ($query, $request) {
                    if (isset($request->filtros['data_entrada']['fim'])) {
                        $query->where('data_entrada', '<=', $request->filtros['data_entrada']['fim']);
                    }
                })
                ->when($request->filtros['ordenacao'], function ($query, $campo) {
                    if (in_array($campo, ['data_entrada'])) {
                        $query->orderBy($campo);
                    }
                });
        })
            ->when($request, function ($query, $request) {
                if (isset($request->filtros['status'])) {
                    $query->where('status_id', $request->filtros['status']);
                }
            })->when($arrFiltosTexto, function ($query, $arrFiltosTexto) {
                if (isset($arrFiltosTexto['palavras'])) {

                    $likeInicio = $arrFiltosTexto['likeInicio'];
                    $likeFim = $arrFiltosTexto['likeFim'];

                    foreach ($arrFiltosTexto['palavras'] as $palavra) {
                        $query->where(function ($query) use ($palavra, $likeInicio, $likeFim) {
                            $arrCampos = ['nome', 'nome_social', 'matricula', 'mae', 'pai', 'rg', 'cpf', 'data_prisao', 'informacoes', 'observacoes'];
                            foreach ($arrCampos as $campo) {
                                $query->orWhere($campo, 'LIKE', "$likeInicio" . "$palavra" . "$likeFim");
                                $query->orWhereHas('preso', function ($query) use ($palavra, $likeInicio, $likeFim) {
                                    $arrCampos = ['matricula', 'sinais'];
                                    foreach ($arrCampos as $campo) {
                                        $query->orWhere($campo, 'LIKE', "$likeInicio" . "$palavra" . "$likeFim");
                                    }
                                });
                                $query->orWhereHas('preso', function ($query) use ($palavra, $likeInicio, $likeFim) {
                                    $arrCampos = ['matricula'];
                                    foreach ($arrCampos as $campo) {
                                        $query->orWhere($campo, 'LIKE', "$likeInicio" . "$palavra" . "$likeFim");
                                    }
                                });
                                $query->orWhereHas('preso.pessoa', function ($query) use ($palavra, $likeInicio, $likeFim) {
                                    $arrCampos = ['nome', 'nome_social', 'mae', 'pai', 'data_nasc'];
                                    foreach ($arrCampos as $campo) {
                                        $query->orWhere($campo, 'LIKE', "$likeInicio" . "$palavra" . "$likeFim");
                                    }
                                });
                                $query->orWhereHas('preso.pessoa.documentos', function ($query) use ($palavra, $likeInicio, $likeFim) {
                                    $arrCampos = ['numero'];
                                    foreach ($arrCampos as $campo) {
                                        $query->orWhere($campo, 'LIKE', "$likeInicio" . "$palavra" . "$likeFim");
                                    }
                                });
                                $query->orWhereHas('preso.pessoa.escolaridade', function ($query) use ($palavra, $likeInicio, $likeFim) {
                                    $arrCampos = ['nome'];
                                    foreach ($arrCampos as $campo) {
                                        $query->orWhere($campo, 'LIKE', "$likeInicio" . "$palavra" . "$likeFim");
                                    }
                                });
                                $query->orWhereHas('preso.pessoa.genero', function ($query) use ($palavra, $likeInicio, $likeFim) {
                                    $arrCampos = ['nome'];
                                    foreach ($arrCampos as $campo) {
                                        $query->orWhere($campo, 'LIKE', "$likeInicio" . "$palavra" . "$likeFim");
                                    }
                                });
                                $query->orWhereHas('preso.pessoa.cidade_nasc', function ($query) use ($palavra, $likeInicio, $likeFim) {
                                    $arrCampos = ['nome'];
                                    foreach ($arrCampos as $campo) {
                                        $query->orWhere($campo, 'LIKE', "$likeInicio" . "$palavra" . "$likeFim");
                                    }
                                });
                            }
                        });
                    }
                }
            })
            ->when($request->filtros['ordenacao'], function ($query, $campo) {
                if (in_array($campo, ['nome', 'matricula'])) {
                    $query->orderBy($campo);
                }
            })
            ->with(['entrada', 'preso.pessoa'])
            ->get();

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
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
        // 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $this->authorize('delete', IncEntradaPreso::class);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Passagem de preso excluída com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id)
    {
        $resource = IncEntradaPreso::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID inclusão $id não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }
}
