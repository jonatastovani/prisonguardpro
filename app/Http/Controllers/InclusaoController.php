<?php

namespace App\Http\Controllers;

use App\Common\FuncoesPresos;
use App\Common\PermissaoService;
use App\Models\IncEntradaPreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class InclusaoController extends Controller
{
    public function home()
    {
        return view('setores.inclusao.home');
    }

    public function entradasPresos()
    {
        return view('setores.inclusao.entradasPresos');
    }

    public function cadastroEntradasPresos(Request $request)
    {
        $id = $request->id ?? '';
        $redirecionamentoAnterior = $request->redirecionamentoAnterior ?? '';

        $dataToCompact = compact('id', 'redirecionamentoAnterior');

        return view('setores.inclusao.cadastroEntradasPresos', $dataToCompact);
    }

    public function cadastroQualificativa(Request $request)
    {
        $arrCompact = ['id'];
        $id = $request->id;

        $resource = FuncoesPresos::buscarRecursoPassagemPreso($id);

        if ($resource instanceof IncEntradaPreso) {
            if (PermissaoService::temPermissaoRecursivaAcima(Auth::user(), [45, 68])) {
                // Aqui faço o que eu quiser
            }
        } else {
            $motivo = $resource["passagem.$id"]['error'];
            $traceId = $resource["passagem.$id"]['trace_id'];

            $data = [
                'title' => 'Página não encontrada',
                'message' => "A qualificativa que você está tentando acessar não pôde ser encontrada.",
                'motive' => $motivo,
                'traceId' => $traceId,
                'httpStatus' => 404,
            ];

            return response()->view('errors.custom', $data);
        }

        $dataToCompact = compact($arrCompact);

        return view('setores.inclusao.qualificativa.cadastroQualificativa', $dataToCompact);
    }
}
