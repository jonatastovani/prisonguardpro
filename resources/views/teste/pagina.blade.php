@extends('site.layout')
@section('title', 'Página Teste')

@section('conteudo')
    <div class="row mt-3">
        <div class="col-3">
            <label for="rg" class="form-label">RG</label>
            <input type="text" name="" id="rg" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-3 mt-3">
            <button id="btnModal" class="btn btn-outline-info">Modal</button>
        </div>
    </div>

    @include('modals.referencias.modalCadastroDocumento')

    <script type="module" src="{{ asset('js/teste/pagina.js') }}"></script>

@endsection
