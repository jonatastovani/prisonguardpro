<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Common\CommonsFunctions;
use App\Models\RefEstado;

class RefEstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados = RefEstado::all();
        return $estados;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Regras de validação
        $rules = [
            'sigla' => 'required|max:2',
            'nome' => 'required',
            'cadastro_id' => 'required',
            'cadastro_ip' => 'string',
            'cadastro_data' => 'date',
        ];

        // Mensagens de erro personalizadas
        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
        ];

        // Valide os dados recebidos da requisição
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Gere um trace ID
            $traceId = commonsFunctions::generateTraceId();

            // Registre o erro no log com o trace ID
            Log::error($validator->errors() . " Trace ID: $traceId");

            // Se a validação falhar, retorne os erros em uma resposta JSON com código 422 (Unprocessable Entity)
            return response()->json(['errors' => $validator->errors(), 'trace_id' => $traceId], 422);
        }

        // Se a validação passou, crie um novo registro de estado
        $estado = new RefEstado();
        $estado->sigla = $request->input('sigla');
        $estado->nome = $request->input('nome');
        $estado->cadastro_id = $request->input('cadastro_id');

        // Defina o cadastro_ip baseado na presença de 'cadastro_ip'
        $estado->cadastro_ip = $request->input('cadastro_ip');

        // Verifique se o campo 'cadastro_data' foi enviado
        if ($request->has('cadastro_data')) {
            $estado->cadastro_data = $request->input('cadastro_data');
        } else {
            // Caso contrário, defina a data atual
            $estado->cadastro_data = now();
        }

        $estado->save();

        // Retorne uma resposta de sucesso (status 201 - Created)
        return response()->json(['message' => 'Estado criado com sucesso', 'data' => $estado], 201);
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
    public function show(RefEstado $refEstado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RefEstado $refEstado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RefEstado $refEstado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RefEstado $refEstado)
    {
        //
    }
}
