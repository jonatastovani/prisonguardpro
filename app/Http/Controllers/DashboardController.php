<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    // public function __construct() {

    //     // $this->middleware('auth'); //Bloqueia todos se não tiver autenticado
    //     // $this->middleware('auth')->only('index'); //Bloqueia somente os que estão dentro do only. Pode ser um array ['index', 'home', [...]]
    //     // $this->middleware('auth')->except('index'); //Bloqueia todos, excluindo somente os que estão dentro do except. Pode ser um array ['index', 'home', [...]]

    // }

    public function index() {

        $usuarios = User::all()->count();

        // Gráfico 1 - Usuários
        $usersData = User::select([
            DB::raw('YEAR(created_at) as ano'),
            DB::raw('COUNT(*) as total')
        ])
        ->groupBy('ano')
        ->orderBy('ano', 'asc')
        ->get();

        $anos = [];
        $total = [];
        // Preparar arrays
        foreach ($usersData as $user) {
            $ano[] = $user->ano;
            $total[] = $user->total;
        }

        return view('admin.dashboard', compact('usuarios', 'anos', 'total'));

    }
}
