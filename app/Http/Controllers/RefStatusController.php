<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\RefStatus;
use Illuminate\Http\Request;

class RefStatusController extends Controller
{
    public function index()
    {
        $resource = RefStatus::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    public function preencherSelect(Request $request)
    {
        // Regras de validação
        $rules = [
            'tipo' => 'required|integer',
        ];

        CommonsFunctions::validacaoRequest($request, $rules);

        $resources = RefStatus::with('nome')->where('tipo_id', $request->tipo)->get();

        $retorno = $resources->map(function ($status) {
            return [
                'id' => $status->id,
                'nome' => $status->nome->nome,
            ];
        });

        $response = RestResponse::createSuccessResponse($retorno->toArray(), 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    private function buscarRecurso($id)
    {
        $resource = RefStatus::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID do status informado não existe ou foi excluído.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }
}
