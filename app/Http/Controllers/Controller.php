<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct() {

        // $this->middleware('auth'); //Bloqueia todos se não tiver autenticado
        // $this->middleware('auth')->only('index'); //Bloqueia somente os que estão dentro do only. Pode ser um array ['index', 'home', [...]]
        // $this->middleware('auth')->except('index'); //Bloqueia todos, excluindo somente os que estão dentro do except. Pode ser um array ['index', 'home', [...]]

    }

}
