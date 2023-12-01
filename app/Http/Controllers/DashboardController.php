<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    // public function __construct() {

    //     // $this->middleware('auth'); //Bloqueia todos se não tiver autenticado
    //     // $this->middleware('auth')->only('index'); //Bloqueia somente os que estão dentro do only. Pode ser um array ['index', 'home', [...]]
    //     // $this->middleware('auth')->except('index'); //Bloqueia todos, excluindo somente os que estão dentro do except. Pode ser um array ['index', 'home', [...]]

    // }

    public function index() {

        return view('admin.dashboard');

    }
}
