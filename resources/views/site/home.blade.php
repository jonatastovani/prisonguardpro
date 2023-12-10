@extends('site.layout')
@section('title','Home')
    
@section('conteudo')
    <h1>Essa é a home</h1>
    <div class="row">
        <div class="col-12">
            <button id="send">Teste</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <input type="date" class="form-control" id="data">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label for="">Data</label>
            <input type="text" class="form-control" id="dataimpressa">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button id="imp">Imprimir data</button>
        </div>
    </div>

    <script type="module" src="{{asset('js/site/home.js')}}"></script>
    
@endsection