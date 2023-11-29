@extends('site.layout')
@section('title','Home')
    
@section('conteudo')

    <div class="row">
        <div class="col-lg-6">
            <img src=" {{ $produto->imagem }} " alt="">
        </div>
        <div class="col-lg-6">
            <h4> {{ $produto->nome }} </h4>
            <p> R$ {{ number_format($produto->preco,2,',','.') }} </p>
            <p> {{ $produto->descricao }} </p>
            <p> Postado por: {{ $produto->user->firstName }} </p>
            <p> Categoria: {{ $produto->categoria->nome }} </p>
            <form action=" {{ route('site.addCarrinho') }} " method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value=" {{ $produto->id }} ">
                <input type="hidden" name="name" value=" {{ $produto->nome }} ">
                <input type="hidden" name="price" value=" {{ $produto->preco }} ">
                <input type="number" name="qnt" value="1">
                <input type="hidden" name="img" value=" {{ $produto->imagem }} ">
                <button class="btn btn-primary">Comprar</button>
            </form>
        </div>
    </div>

@endsection