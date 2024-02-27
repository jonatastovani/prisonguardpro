<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\RefDocumentoConfig;
use App\Models\RefDocumentoTipo;
use Illuminate\Http\Request;

class RefDocumentoConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefDocumentoConfig::all();
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
            'tipo_id' => 'required|integer',
            'mask' => 'nullable|string',
            'comprimento' => 'nullable|integer',
            'validade_emissao' => 'nullable|integer',
            'estado_id' => 'nullable|integer',
            'orgao_exp_id' => 'required_with:estado_id|integer',
            'nacionalidade_id' => 'nullable|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request);

        // Se a validação passou, crie um novo registro
        $novo = new RefDocumentoConfig();
        $novo->tipo_id = $request->input('tipo_id');

        if($request->has(''))
        $novo->sigla = $request->input('sigla');


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
        $this->authorize('update', RefDocumentoConfig::class);

        // Regras de validação
        $rules = [
            'nome' => 'required',
            'sigla' => 'required|min:2',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com o mesmo nome
        $this->validarRecursoExistente($request, $request->id);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($request->id);

        // Se passou pelas validações, altera o recurso
        $resource->nome = $request->input('nome');
        $resource->sigla = $request->input('sigla');

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
        $this->authorize('delete', RefDocumentoConfig::class);

        // Verifica se o modelo existe
        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Orgão emissor excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id)
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

    private function validarRecursoExistente($request, $id = null)
    {
        $query = RefDocumentoConfig::join('ref_documento_tipos', 'ref_documento_tipos.id', '=', 'ref_documento_configs.tipo_id')
            ->where('ref_documento_configs.tipo_id', $request->input('tipo_id'))
            ->where(function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->when($request, function($query, $request){
                        if($request->has('estado_id') && $request->input('orgao_exp_id')){
                            $query->where('ref_documento_configs.estado_id', $request->input('estado_id'))
                            ->where('ref_documento_configs.orgao_exp_id', $request->input('orgao_exp_id'))
                            ->where('ref_documento_configs.nacionalidade_id', null);
                        }
                    });
                })->orWhere(function ($query) use ($request) {
                    $query->when($request, function($query, $request){
                        if($request->has('nacionalidade_id')){
                            $query->where('ref_documento_configs.nacionalidade_id', $request->input('nacionalidade_id'))
                        ->where('ref_documento_tipos.doc_nacional', true);
                        }
                    });
                });
            });


        if ($id !== null) {
            $query->whereNot('ref_documento_configs.id', $id);
        }

        if ($query->exists()) {
            $codigo = 409;
            $mensagem = "O nome do orgão emissor informado já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $query->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }

    private function VerificaDocumentoTipo(RefDocumentoConfig $retorno, Request $request, &$arrErrors)
    {
        $retorno->tipo_id = $request['tipo_id'];

        $resource = RefDocumentoTipo::find($retorno->tipo_id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O Tipo de documento informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $retorno");

            $arrErrors['documento_tipo'] = [
                'error' => $mensagem,
                'trace_id' => $traceId
            ];
            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            
        }

    }

}
