@extends('site.layout')
@php
    $titulo = !empty($id) ? "Entrada $id" : 'Nova Entrada';
@endphp
@section('title', $titulo)

@section('conteudo')

    <div class="row">
        <div class="col-10 mt-2 text-center">
            <h3>{{ !empty($id) ? 'Alterar Entrada de Presos' : 'Nova Entrada de Presos' }}</h3>
        </div>
        <div class="col-2 mt-2 text-end">
            <button class="btn btn-outline-info btn-sm" id="editBudget" title="Editar este orçamento"><i
                    class="bi bi-pencil"></i></button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-8 mt-2" title="Selecione a Origem da Entrada">
            <label class="form-label" for="origem_idEntradasPresos">Origem</label>
            <select name="origem_id" id="origem_idEntradasPresos" class="form-select"></select>
        </div>
        <div class="col-md-3 col-sm-4 mt-2">
            <label class="form-label" for="data_entradaEntradasPresos">Data Entrada</label>
            <input type="date" name="data_entrada" id="data_entradaEntradasPresos" class="form-control" value="{{now()->format('Y-m-d')}}">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5>Produtos</h5>
        </div>
    </div>

    <div class="row flex-fill border border-dark-subtle rounded p-2 overflow-auto">
        <div class="col-12 p-0">
            <div id="containerProducts" class="d-flex flex-row flex-wrap">

            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-7 d-flex justify-content-end mt-2 order-md-2">
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group me-2" title="Preço de custo deste orçamento">
                        <label class="input-group-text">Custo R$</label>
                        <input type="password" class="form-control" id="cost_priceBudget" disabled>
                        <button class="btn btn-outline-secondary" type="button" id="show_cost_price"
                            title="Exibir preço de custo"><i class="bi bi-eye-fill"></i></button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group me-2" title="Preço final deste orçamento">
                        <label class="input-group-text">Preço final R$</label>
                        <input type="text" class="form-control" id="priceBudget" disabled>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-5 mt-2">
            <button id="btnInserirPreso" class="btn btn-primary me-2 w-sm" title="Inserir um preso">Inserir Preso</button>
            <a href="{{ !empty($redirecionamentoAnterior) ? $redirecionamentoAnterior : route('inclusao.entradasPresos') }}"
                class="btn btn-danger w-sm" title="Sair do orçamento" style="width: 100px;">Sair</a>
        </div>
    </div>

    <input type="hidden" id="id" {{ isset($id) ? 'value="' . $id . '"' : '' }}>

    <?php // include_once "view/popup/budgets/popupEditBudgets.php"
    ?>
    <?php // include_once "view/popup/orders/popupOrders.php"
    ?>
    <?php // include_once "view/popup/products/popupProducts.php"
    ?>
    <?php // include_once "view/popup/products/popupNewProduct.php"
    ?>

    <script type="module" src="{{ asset('js/setores/inclusao/cadastroEntradasPresos.js') }}"></script>

@endsection
