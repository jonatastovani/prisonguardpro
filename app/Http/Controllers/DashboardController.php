<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Categoria;
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
            $anos[] = $user->ano;
            $total[] = $user->total;
        }

        $userLabel = "'Comparativo de cadastros de usuários'";
        $userAno = implode(',', $anos);
        $userTotal = implode(',', $total);
        
        // Gráfico 2 - Categorias
        $catData = Categoria::with('produtos')->get();

        $catNome = [];
        $catTotal = [];
        foreach ($catData as $cat) {
            $catNome[] = "'".$cat->nome."'";
            $catTotal[] = $cat->produtos->count();
        }

        $catLabel = implode(',', $catNome);
        $catTotal = implode(',', $catTotal);
        
        return view('admin.dashboard', compact('usuarios', 'userLabel', 'userAno', 'userTotal', 'catLabel', 'catTotal'));

    }
}
