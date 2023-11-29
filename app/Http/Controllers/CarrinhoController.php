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
            'quantity' => $request->qnt,
            'attributes' => array(
                'image' =>$request->img
            ),
        ]);

        $notifyMessage = 'Produto adicionado no carrinho com sucesso!';
        $notifyType = 'success';
        $data = array(
            'message' => $notifyMessage,
            'type' => $notifyType,
        );

        return redirect()->route('site.carrinho')->with('notifyMessage', json_encode(array($data)));

    }

    public function removeCarrinho(Request $request) {

        \Cart::remove($request->id);

        $notifyMessage = 'Produto removido do carrinho com sucesso!';
        $notifyType = 'success';
        $data = array(
            'message' => $notifyMessage,
            'type' => $notifyType,
        );

        return redirect()->route('site.carrinho')->with('notifyMessage', json_encode(array($data)));

    }

    public function atualizaCarrinho(Request $request) {

        \Cart::update($request->id, ['quantity' => $request->quantity]);

        $notifyMessage = 'Produto atualizado no carrinho com sucesso!';
        $notifyType = 'success';
        $data = array(
            'message' => $notifyMessage,
            'type' => $notifyType,
        );

        return redirect()->route('site.carrinho')->with('notifyMessage', json_encode(array($data)));

    }

}
