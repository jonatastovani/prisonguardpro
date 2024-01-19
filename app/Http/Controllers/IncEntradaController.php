<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use App\Models\IncEntrada;
use Illuminate\Http\Request;

class IncEntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

        // Carrega os presos relacionados
        // $resource->load('presos');

        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncEntrada $incEntrada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncEntrada $incEntrada)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncEntrada $incEntrada)
    {
        //
    }

    private function buscarRecurso($id)
    {
        $resource = IncEntrada::with('presos.preso')->find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            // Gerar um log
            $codigo = 404;
            $mensagem = "O ID da entrada de presos informada não existe ou foi excluída.";
            $traceId = CommonsFunctions::generateLog("$codigo | $mensagem | id: $id");

            $response = RestResponse::createErrorResponse($codigo, $mensagem, $traceId);
            return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
        }
        return $resource;
    }
}
