@extends('site.layout')
@section('title','Home')
    
@section('conteudo')
    <h1>Essa é a home</h1>
    <div class="row">
        <div class="col-12">
            <button id="send">Teste</button>
        </div>
    </div>

    <script type="module" src="{{asset('js/site/home.js')}}"></script>
    
@endsection