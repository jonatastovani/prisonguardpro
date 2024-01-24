<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
