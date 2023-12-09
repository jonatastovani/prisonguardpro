<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Models\UserPermissao;
use Darryldecode\Cart\Validators\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Common\UserInfo;
use App\Models\RefPermissao;
use Carbon\Carbon;

class UserPermissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dados = UserPermissao::all();
        return $dados;
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
        $arrErrors = [];

        // Regras de validação
        $rules = [
            'user_id' => 'required|integer',
            'permissao_id' => 'required|integer',
            'substituto_bln' => 'boolean',
            'data_inicio' => 'required|date',
            'data_termino' => 'date',
        ];

        // Mensagens de erro personalizadas
        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'integer' => 'O campo :attribute deve ser um número.',
            'boolean' => 'O campo :attribute deve ser booleano.',
            'date' => 'O campo :attribute deve ser uma data.',
        ];

        // Valide os dados recebidos da requisição
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Gerar um log
            $traceId = CommonsFunctions::generateLog($validator->errors());

            // Se a validação falhar, retorne os erros em uma resposta JSON com código 422 (Unprocessable Entity)
            return response()->json(['errors' => $validator->errors(), 'trace_id' => $traceId], 422);
        }

        // Se a validação passou, crie um novo registro
        $novo = new UserPermissao();
        $novo->user_id = $request->input('user_id');

        $permissao_id = $request->input('permissao_id');

        $consultaPermissao = RefPermissao::where('id', $permissao_id);

        // Verifica se a permissão existe
        if (!$consultaPermissao->exists()) {
            // Gerar um log
            $mensagem = "A permissão informada não existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            // Tratar o erro aqui
            return response()->json([
                'error' => $mensagem,
                'trace_id' => $traceId
            ], 404);
        }

        $novo->permissao_id = $permissao_id;

        $dataAtual = Carbon::now()->toDateString(); // Obtém a data atual no formato 'Y-m-d'

        $consultaPermissaoAtiva = UserPermissao::
            where('user_id', $novo->user_id)
            ->where('permissao_id', $novo->permissao_id)
            ->where(function ($query) use ($dataAtual) {
                $query->whereDate('data_termino', '>=', $dataAtual)
                ->orWhereNull('data_termino');
            })
            ->whereNull('deleted_at');
        ;

        // Verifica se o usuário já tem a permissão ativa
        if ($consultaPermissaoAtiva->exists()) {
            // Gerar um log
            $mensagem = "A permissão já existe.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            // Tratar o erro aqui
            return response()->json([
                'error' => $mensagem,
                'trace_id' => $traceId,
                'data' => $consultaPermissaoAtiva->get()
            ], 404);
        }

        $novo->permissao_id = $permissao_id;

        // Verifique se o campo 'substituto_bln' foi enviado
        if ($request->has('substituto_bln') && $request->input('substituto_bln') == true) {
            // Obter a permissão encontrada
            $permissao = $consultaPermissao->first();
            if (!$permissao->diretor_bln) {
                // Gerar um log
                $mensagem = "A permissão '". $permissao->nome ."' não é uma permissão de Diretoria que permite substituto.";
                $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

                $arrErrors[] = [
                        'error' => $mensagem,
                        'trace_id' => $traceId
                    ];

            } else {
                $novo->substituto_bln = $request->input('substituto_bln');
            }
        }

        $novo->data_inicio = $request->input('data_inicio');

        // Verifica se a data de início é menor que a data de hoje
        if (Carbon::parse($novo->data_inicio)->startOfDay()->isBefore(Carbon::now()->startOfDay())) {
            // Gerar um log
            $mensagem = "A data de início não pode ser menor que a data de hoje.";
            $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

            $arrErrors[] = [
                'error' => $mensagem,
                'trace_id' => $traceId
            ];
        }

        // Verifique se o campo 'data_termino' foi enviado
        if ($request->has('data_termino')) {

            $novo->data_termino = $request->input('data_termino');

            // Verifica se a data de término é menor que a data de início
            if (Carbon::parse($novo->data_termino)->startOfDay()->isBefore(Carbon::parse($novo->data_inicio)->startOfDay())) {
                // Gerar um log
                $mensagem = "A data de término não pode ser menor que a data de início.";
                $traceId = CommonsFunctions::generateLog($mensagem . "| Request: " . json_encode($request->input()));

                $arrErrors[] = [
                    'error' => $mensagem,
                    'trace_id' => $traceId
                ];
            }
        }

        // Erros que impedem o processamento
        if (count($arrErrors)){
            return response()->json([
                'errors' => $arrErrors
            ], 422);
        }

        $novo->id_user_created = auth()->user()->id;
        $novo->ip_created = UserInfo::get_ip();
        $novo->updated_at = null;

        $novo->save();
        
        // Retorne uma resposta de sucesso (status 201 - Created)
        return response()->json([
            "status" => 201,
            'message' => 'Operação realizada com sucesso.',
            'data' => $novo,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(UserPermissao $userPermissao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserPermissao $userPermissao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserPermissao $userPermissao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPermissao $userPermissao)
    {
        //
    }
}
