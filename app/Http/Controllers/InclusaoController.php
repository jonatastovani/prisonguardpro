<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InclusaoController extends Controller
{
    public function home() {
        return view('setores.inclusao.home');
    }

    public function entradaspresos() {
        return view('setores.inclusao.entradaspresos');
    }

}
