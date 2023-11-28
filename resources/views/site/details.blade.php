@extends('site.layout')
@section('title','Home')
    
@section('conteudo')

    <div class="row">
        <div class="col-lg-6">
            <img src=" {{ $produto->imagem }} " alt="">
        </div>
        <div class="col-lg-6">
            <h1> {{ $produto->nome }} </h1>
            <p> {{ $produto->descricao }} </p>
            <p> Postado por: {{ $produto->user->firstName }} </p>
            <p> Categoria: {{ $produto->categoria->nome }} </p>
            <button class="btn btn-primary">Comprar</button>
        </div>
    </div>

@endsection