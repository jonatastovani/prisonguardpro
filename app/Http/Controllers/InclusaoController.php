<?php

namespace App\Http\Controllers;

use App\Common\FuncoesPresos;
use App\Common\PermissaoService;
use App\Models\IncEntradaPreso;
use App\Models\IncQualificativaProvisoria;
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
        $passagem_id = $request->id;
        $permAtribuirMatricula = false;
        $idQualProv = null;

        $resource = FuncoesPresos::buscarRecursoPassagemPreso($passagem_id);

        if ($resource instanceof IncEntradaPreso) {
            // if (PermissaoService::temPermissaoRecursivaAcima(Auth::user(), [45, 68])) {
            $permAtribuirMatriculaBln = true;
            // }
            $preso_id_bln = $resource->preso_id ? true : false;
            if (!$preso_id_bln) {
                $qualProv = IncQualificativaProvisoria::where('passagem_id', $passagem_id);
                if ($qualProv->exists()) {
                    $idQualProv = $qualProv->first()->id;
                }
            }
        } else {
            $motivo = $resource["passagem.$passagem_id"]['error'];
            $traceId = $resource["passagem.$passagem_id"]['trace_id'];

            $data = [
                'title' => 'Página não encontrada',
                'message' => "A qualificativa que você está tentando acessar não pôde ser encontrada.",
                'motive' => $motivo,
                'traceId' => $traceId,
                'httpStatus' => 404,
            ];

            return response()->view('errors.custom', $data);
        }

        $dataToCompact = compact('passagem_id', 'permAtribuirMatriculaBln', 'preso_id_bln', 'idQualProv');

        return view('setores.inclusao.qualificativa.cadastroQualificativa', $dataToCompact);
    }
}
