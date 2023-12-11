<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Common\CommonsFunctions;
use App\Common\UserInfo;
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
            'descricao' => 'required',
        ];

        CommonsFunctions::validacaoRequest($request,$rules);

        // Valida se não existe outro com o mesmo nome
        $resource = RefArtigo::where('nome', $request->input('nome'));

        if ($resource->exists()) {
            // Gerar um log
            $mensagem = "O artigo informado já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            // Tratar o erro aqui
            return response()->json([
                'status' => 409,
                'errors' => [
                    'error' => $mensagem,
                ],
                'data' => $resource->first(),
                'trace_id' => $traceId,
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
            ], 409);
        }
        
        // Se a validação passou, crie um novo registro
        $novo = new RefArtigo();
        $novo->nome = $request->input('nome');
        $novo->descricao = $request->input('descricao');

        CommonsFunctions::inserirInfoCreated($novo);

        $novo->save();

        // Retorne uma resposta de sucesso (status 201 - Created)
        return response()->json([
            "status" => 201,
            'message' => 'Artigo adicionado com sucesso.',
            'data' => $novo,
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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
        $this->authorize('delete', RefArtigo::class);

        $resource = RefArtigo::find($id);

        // Verifique se o modelo foi encontrado e não foi excluído
        if (!$resource || $resource->trashed()) {
            return response()->json([
                'status' => 404,
                'errors' => [
                    'error' => 'Artigo não encontrado.'
                ],
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
                ], 404);
        }

        // Execute o soft delete
        $resource->id_user_deleted = auth()->user()->id;
        $resource->ip_deleted = UserInfo::get_ip();
        $resource->deleted_at = CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now());

        $resource->save();

        // Resposta de sucesso
        return response()->json([
            'status' => 200,
            'message' => 'Artigo excluído com sucesso.',
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ], 200);

    }
}
