<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Common\ValidacoesReferenciasId;
use App\Models\RefDocumentoConfig;
use App\Models\RefDocumentoTipo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefDocumentoConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefDocumentoConfig::select('ref_documento_configs.*')
            ->join('ref_documento_tipos', 'ref_documento_tipos.id', '=', 'ref_documento_configs.documento_tipo_id')
            ->with('documento_tipo', 'estado.nacionalidade', 'nacionalidade', 'orgao_emissor')
            ->orderBy('ref_documento_tipos.nome')->get();

        // Mapear os resultados para inserir o nome personalizado
        $mappedResults = $resource->map(function ($item) {
            $nome = $item->documento_tipo->nome;
            if ($item->documento_tipo->doc_nacional_bln) {
                $nome .= " - {$item->nacionalidade->sigla}";
            } else {
                $nome .= " - {$item->estado->sigla}/{$item->orgao_emissor->sigla}";
            }
            $item['nome'] = $nome;
            return $item;
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

        $resource = RefDocumentoConfig::select('ref_documento_configs.*')
            ->join('ref_documento_tipos', 'ref_documento_tipos.id', '=', 'ref_documento_configs.documento_tipo_id')
            ->leftJoin('ref_nacionalidades', 'ref_nacionalidades.id', '=', 'ref_documento_configs.nacionalidade_id')
            ->leftJoin('ref_estados', 'ref_estados.id', '=', 'ref_documento_configs.estado_id')
            ->leftJoin('ref_documento_orgao_emissor', 'ref_documento_orgao_emissor.id', '=', 'ref_documento_configs.orgao_emissor_id')
            ->where('ref_documento_tipos.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_nacionalidades.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_estados.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_estados.sigla', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_documento_orgao_emissor.nome', 'LIKE', '%' . $request->input('text') . '%')
            ->orWhere('ref_documento_orgao_emissor.sigla', 'LIKE', '%' . $request->input('text') . '%')
            ->with('documento_tipo', 'estado.nacionalidade', 'nacionalidade', 'orgao_emissor')
            ->orderBy('ref_documento_tipos.nome')->get();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $this->authorize('store', RefDocumentoConfig::class);

        $arrErrors = [];

        // Regras de validação
        $rules = [
            'documento_tipo_id' => 'required|integer',
            'mask' => 'nullable|string',
            'validade_emissao_int' => 'nullable|integer',
            'reverse_bln' => 'nullable|boolean',
            'digito_bln' => 'required|boolean',
            'digito_mask' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('digito_bln') === true;
                }),
                'string',
            ],
            'digito_separador' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('digito_bln') === true;
                }),
                'string',
            ],
            'estado_id' => 'nullable|integer',
            'orgao_emissor_id' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('estado_id') !== null;
                }),
                'integer',
            ],
            'nacionalidade_id' => 'nullable|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request);
        // Se a validação passou, crie um novo registro
        $novo = new RefDocumentoConfig();

        ValidacoesReferenciasId::documentoTipo($novo, $request, $arrErrors);

        $this->preencherCampos($novo, $request, $arrErrors);

        $this->validacoesDocumentoTipo($novo, $request->documento_tipo_id, $arrErrors);

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

        $resource->load('documento_tipo', 'estado.nacionalidade', 'orgao_emissor', 'nacionalidade');

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // $this->authorize('update', RefDocumentoConfig::class);

        $arrErrors = [];

        // Regras de validação
        $rules = [
            'mask' => 'nullable|string',
            'validade_emissao_int' => 'nullable|integer',
            'reverse_bln' => 'nullable|boolean',
            'digito_bln' => 'required|boolean',
            'digito_mask' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('digito_bln') === true;
                }),
                'string',
            ],
            'digito_separador' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('digito_bln') === true;
                }),
                'string',
            ],
            'estado_id' => 'nullable|integer',
            'orgao_emissor_id' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('estado_id') !== null;
                }),
                'integer',
            ],
            'nacionalidade_id' => 'nullable|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request, $request->id);

        // Se a validação passou, busca o registro
        $resource = $this->buscarRecurso($request->id);;

        $this->preencherCampos($resource, $request, $arrErrors);

        $this->validacoesDocumentoTipo($resource, $resource->documento_tipo_id, $arrErrors);

        // Erros que impedem o processamento
        CommonsFunctions::retornaErroQueImpedemProcessamento422($arrErrors);

        CommonsFunctions::inserirInfoUpdated($resource);

        $resource->save();

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $this->authorize('delete', RefDocumentoConfig::class);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Orgão emissor excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id): RefDocumentoConfig
    {
        $resource = RefDocumentoConfig::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O Documento informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function buscarRecursoDocumentoTipo($id)
    {
        $resource = RefDocumentoTipo::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O Tipo de Documento não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function validarRecursoExistente($request, $id = null)
    {
        $query = RefDocumentoConfig::select('ref_documento_configs.*')
            ->join('ref_documento_tipos', 'ref_documento_tipos.id', '=', 'ref_documento_configs.documento_tipo_id')
            ->where('ref_documento_configs.documento_tipo_id', $request->input('documento_tipo_id'))
            ->where(function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->when($request, function ($query, $request) {
                        if ($request->has('estado_id') && $request->input('orgao_emissor_id')) {
                            $query->where('ref_documento_configs.estado_id', $request->input('estado_id'))
                                ->where('ref_documento_configs.orgao_emissor_id', $request->input('orgao_emissor_id'))
                                ->where('ref_documento_configs.nacionalidade_id', null);
                        }
                    });
                })->orWhere(function ($query) use ($request) {
                    $query->when($request, function ($query, $request) {
                        if ($request->has('nacionalidade_id')) {
                            $query->where('ref_documento_configs.nacionalidade_id', $request->input('nacionalidade_id'))
                                ->where('ref_documento_tipos.doc_nacional_bln', true);
                        }
                    });
                });
            });


        if ($id !== null) {
            $query->whereNot('ref_documento_configs.id', $id);
        }

        if ($query->exists()) {
            $codigo = 409;
            $mensagem = "A configuração deste documento já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $query->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }

    private function preencherCampos(RefDocumentoConfig $resource, Request $request, &$arrErrors, $defaultNull = true)
    {
        if ($request->has('mask')) {
            $resource->mask = $request->input('mask');
        } else if ($defaultNull) {
            $resource->mask = null;
        }
        if ($request->has('validade_emissao_int')) {
            $resource->validade_emissao_int = $request->input('validade_emissao_int');
        } else if ($defaultNull) {
            $resource->validade_emissao_int = null;
        }
        if ($request->has('reverse_bln')) {
            $resource->reverse_bln = $request->input('reverse_bln');
        } else if ($defaultNull) {
            $resource->reverse_bln = null;
        }
        if ($request->has('digito_bln')) {
            $resource->digito_bln = $request->input('digito_bln');
        } else if ($defaultNull) {
            $resource->digito_bln = null;
        }
        if ($request->has('digito_mask')) {
            $resource->digito_mask = $request->input('digito_mask');
        } else if ($defaultNull) {
            $resource->digito_mask = null;
        }
        if ($request->has('digito_separador')) {
            $resource->digito_separador = $request->input('digito_separador');
        } else if ($defaultNull) {
            $resource->digito_separador = null;
        }
        if ($request->has('estado_id') && $request->input('estado_id')) {
            ValidacoesReferenciasId::estado($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->estado_id = null;
        }
        if ($request->has('orgao_emissor_id') && $request->input('orgao_emissor_id')) {
            ValidacoesReferenciasId::documentoOrgaoEmissor($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->orgao_emissor_id = null;
        }
        if ($request->has('nacionalidade_id') && $request->input('nacionalidade_id')) {
            ValidacoesReferenciasId::nacionalidade($resource, $request, $arrErrors);
        } else if ($defaultNull) {
            $resource->nacionalidade_id = null;
        }
    }

    private function validacoesDocumentoTipo(RefDocumentoConfig $resource, Int $documento_tipo_id, &$arrErrors)
    {

        $documento_tipo = $this->buscarRecursoDocumentoTipo($documento_tipo_id);

        if ($documento_tipo->doc_nacional_bln) {
            if (!$resource->nacionalidade_id) {
                // Gerar um log
                $codigo = 422;
                $mensagem = "A nacionalidade_id do documento não foi informada.";
                $traceId = CommonsFunctions::generateLog($codigo . " | " . $mensagem . " | RefDocumentoTipo: " . json_encode($documento_tipo) . " | RefDocumentoConfig: " . json_encode($resource));

                $arrErrors['nacionalidade_id'] = [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ];
            } else {
                $resource->estado_id = null;
                $resource->orgao_emissor_id = null;
            }
        } else {
            if (!$resource->estado_id) {
                // Gerar um log
                $codigo = 422;
                $mensagem = "O estado_id do documento não foi informado.";
                $traceId = CommonsFunctions::generateLog($codigo . " | " . $mensagem . " | RefDocumentoTipo: " . json_encode($documento_tipo) . " | RefDocumentoConfig: " . json_encode($resource));

                $arrErrors['estado_id'] = [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ];
            } else {
                $resource->nacionalidade_id = null;
            }
        }
    }
}
