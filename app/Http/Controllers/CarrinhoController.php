<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    public function carrinhoLista() {
        
        $itens = \Cart::getContent();
        return view('site.carrinho', compact('itens'));

    }

    public function adicionaCarrinho(Request $request) {

        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => abs($request->qnt),
            'attributes' => array(
                'image' =>$request->img
            ),
        ]);

        $data = array(
            'message' => 'Produto adicionado no carrinho com sucesso!',
            'type' => 'success',
        );

        return redirect()->route('site.carrinho')->with('notifyMessage', json_encode(array($data)));

    }

    public function removeCarrinho(Request $request) {

        \Cart::remove($request->id);

        $data = array(
            'message' => 'Produto removido do carrinho com sucesso!',
            'type' => 'success',
        );

        return redirect()->route('site.carrinho')->with('notifyMessage', json_encode(array($data)));

    }

    public function atualizaCarrinho(Request $request) {

        \Cart::update($request->id,
            ['quantity' => [
                'relative' => false,
                'value' => abs($request->quantity),
            ]
        ]);

        $data = array(
            'message' => 'Produto atualizado no carrinho com sucesso!',
            'type' => 'success',
        );

        return redirect()->route('site.carrinho')->with('notifyMessage', json_encode(array($data)));

    }

    public function limpaCarrinho(Request $request) {

        \Cart::clear();

        $data = array(
            'message' => 'Seu carrinho está vazio!',
            'type' => 'info',
        );

        return redirect()->route('site.carrinho')->with('notifyMessage', json_encode(array($data)));

    }

}
