@extends('site.layout')
@section('title','Categoria')
    
@section('conteudo')

    <div class="row">
        <div class="col-12">
            <h5>Categoria: {{ $categoria->nome }} </h5>
        </div>
    </div>
    <div class="row flex-row flex-wrap justify-content-around p-0 m-0">

        @foreach($produtos as $produto)
            <div class="p-0 m-0 m-2" style="display: inline; width: 18rem;">
                <div class="card p-0" style="width: 18rem;">
                    <img src=" {{ $produto->imagem }} " class="card-img-top" alt="...">
                    <div class="card-body" style="height: 190px;">
                        <h5 class="card-title" title=" {{ $produto->nome }} "> {{ Str::limit($produto->nome, 40, '...') }} </h5>
                        <p class="card-text" title=" {{ $produto->descricao }} "> {{ Str::limit($produto->descricao, 20, '...') }} </p>
                        <a href=" {{ route('site.details', $produto->slug) }} " class="btn btn-primary">Ver produto</a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center">
            {{ $produtos->links('custom.pagination') }}
        </div>
    </div>
    
    
@endsection