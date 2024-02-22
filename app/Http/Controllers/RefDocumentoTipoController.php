<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\PermissaoService;
use App\Common\Permissoes;
use App\Common\RestResponse;
use App\Models\RefDocumentoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefDocumentoTipoController extends Controller
{

    public function index()
    {
        $resource = RefDocumentoTipo::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function store(Request $request)
    {

        $this->authorize('store', [RefDocumentoTipo::class, $request]);

        // Regras de validação
        $rules = [
            'nome' => 'required|string',
            'doc_nacional_bln' => 'required|boolean',
            'bloqueado_perm_adm_bln' => 'nullable|boolean',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com as mesmas informações
        $this->validarRecursoExistente($request);

        // Se a validação passou, crie um novo registro
        $novo = new RefDocumentoTipo();
        $novo->nome = $request->input('nome');
        $novo->doc_nacional_bln = $request->input('doc_nacional_bln');
        if ($request->has('bloqueado_perm_adm_bln')) {
            // Verifica se o usuário tem a permissão necessária para inserir ou alterar o campo bloquear edição
            if (PermissaoService::temPermissaoRecursivaAcima(Auth::user(), [3])) {
                $novo->bloqueado_perm_adm_bln = $request->input('bloqueado_perm_adm_bln');
            }
        }

        CommonsFunctions::inserirInfoCreated($novo);

        $novo->save();

        $response = RestResponse::createSuccessResponse($novo, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function show($id)
    {
        $resource = $this->buscarRecurso($id);

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function update(Request $request)
    {
        $this->authorize('update', [RefDocumentoTipo::class, $request]);

        // Regras de validação
        $rules = [
            'nome' => 'required|string',
            'doc_nacional_bln' => 'required|boolean',
            'bloqueado_perm_adm_bln' => 'nullable|boolean',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        // Valida se não existe outro com as mesmas informações
        $this->validarRecursoExistente($request, $request->id);

        $resource = $this->buscarRecurso($request->id);

        // Se passou pelas validações, altera o recurso
        $resource->nome = $request->input('nome');
        $resource->doc_nacional_bln = $request->input('doc_nacional_bln');
        if ($request->has('bloqueado_perm_adm_bln')) {
            $resource->bloqueado_perm_adm_bln = $request->input('bloqueado_perm_adm_bln');
        }

        CommonsFunctions::inserirInfoUpdated($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 200 - OK)
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function destroy($id)
    {
        $this->authorize('delete', [RefDocumentoTipo::class, $id]);

        $resource = $this->buscarRecurso($id);

        // Execute o soft delete
        CommonsFunctions::inserirInfoDeleted($resource);
        $resource->save();

        // Retorne uma resposta de sucesso (status 204 - No Content)
        $response = RestResponse::createSuccessResponse([], 204, 'Tipo de documento excluído com sucesso.');
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function buscarRecurso($id)
    {
        $resource = RefDocumentoTipo::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O Tipo de Documento pesquisado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }

    private function validarRecursoExistente($request, $id = null)
    {
        $query = RefDocumentoTipo::where('nome', $request->input('nome'));

        if ($id !== null) {
            $query->whereNot('id', $id);
        }

        if ($query->exists()) {
            $codigo = 409;
            $mensagem = "O tipo de documento informado já existe.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | Request: " . json_encode($request->input()));

            $response = RestResponse::createGenericResponse(["resource" => $query->first()], $codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
    }
}
