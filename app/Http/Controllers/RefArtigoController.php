<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use app\Common\CommonsFunctions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\RefArtigo;
use App\Models\User;

class RefArtigoController extends Controller
{

    // protected $user;

    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         $this->user = Auth::user();

    //         if (!$this->user) {
    //             if ($request->wantsJson()) {
    //                 return response()->json([
    //                     'message' => 'Unauthorized',
    //                     'status' => 403,
    //                     'data' => []], 403);
    //             } else {
    //                 abort(403, 'Unauthorized');
    //             }
    //         }

    //         return $next($request);
    //     });
    // }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consulta = RefArtigo::all();
        return $consulta;

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Regras de validação
        $rules = [
            'nome' => 'required',
            'id_user_created' => 'required',
            'ip_created' => 'string',
            'created_at' => 'date',
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

        // Se a validação passou, crie um novo registro
        $novo = new RefArtigo();
        $novo->nome = $request->input('nome');
        $novo->descricao = $request->input('descricao');
        $novo->id_user_created = $request->input('id_user_created');

        // Defina o ip_created baseado na presença de 'ip_created'
        $novo->ip_created = $request->input('ip_created');

        // Verifique se o campo 'created_at' foi enviado
        if ($request->has('created_at')) {
            $novo->created_at = $request->input('created_at');
        } else {
            // Caso contrário, defina a data atual
            $novo->created_at = now();
        }

        $novo->save();

        // Retorne uma resposta de sucesso (status 201 - Created)
        return response()->json(['message' => 'Artigo criado com sucesso', 'data' => $novo], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RefArtigo $refArtigo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RefArtigo $refArtigo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'exists:ref_artigos,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        $refArtigo = RefArtigo::find($id);

        // Verifique se o modelo foi encontrado
        if (!$refArtigo) {
            return response()->json(['error' => 'Artigo não encontrado.'], 404);
        }

        // Execute o soft delete
        $refArtigo->delete();

        // Resposta de sucesso
        return response()->json(['message' => 'Artigo excluído com sucesso.'], 200);
    }
}
