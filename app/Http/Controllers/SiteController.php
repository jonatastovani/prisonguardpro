<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Support\Facades\Gate;

class SiteController extends Controller
{

    public function index()
    {
        $produtos = Produto::paginate(3);
        return view('site.home', compact('produtos'));

    }

    public function details($slug) {

        $produto = Produto::where('slug', $slug)->first();

        // Gate::authorize('ver-produto', $produto);
        // $this->authorize('verProduto', $produto);

        // if(Gate::allows('ver-produto', $produto)){
        //     return view('site.details', compact('produto'));
        // }

        // if(Gate::denies('ver-produto', $produto)) {
        //     return redirect()->route('site.index');
        // }

        if(auth()->user()->can('verProduto', $produto)){
            return view('site.details', compact('produto'));
        }

        if(auth()->user()->cannot('ver-produto', $produto)) {
            return redirect()->route('site.index');
        }

    }

    public function categoria($id) {

        $categoria = Categoria::find($id);
        $produtos = Produto::where('id_categoria', $id)->paginate(3);
        return view('site.categoria', compact('produtos', 'categoria'));
        
    }
}
