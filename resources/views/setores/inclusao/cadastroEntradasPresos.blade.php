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
            <input type="date" name="data_entrada" id="data_entradaEntradasPresos" class="form-control"
                value="{{ now()->format('Y-m-d') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5>Presos</h5>
        </div>
    </div>
    {{$teste = 1234}}
    <div class="row flex-fill border border-dark-subtle rounded p-2 overflow-auto">
        <div class="col-12 p-0">
            <div id="containerPresos" class="d-flex flex-row flex-wrap">
                <div id="{{$teste}}" class="p-2 col-md-6 col-12 bg-info bg-opacity-10 border border-info rounded">
                    <div class="row">
                        <div class="col-3">
                            <label for="matricula{{$teste}}" class="form-label">Matrícula</label>
                            <input type="text" class="form-control" name="matricula" id="matricula{{$teste}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="nome{{$teste}}" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome{{$teste}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" title="Nome pelo qual o preso deseja ser chamado. Este nome ficará mais aparente nos documentos, caso seja informado.">
                            <label for="nome{{$teste}}" class="form-label">Nome social</label>
                            <input type="text" class="form-control" name="nome_social" id="nome_social{{$teste}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="rg{{$teste}}" class="form-label">RG</label>
                            <input type="text" class="form-control" name="rg" id="rg{{$teste}}">
                        </div>
                        <div class="col-6">
                            <label for="cpf{{$teste}}" class="form-label">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="cpf{{$teste}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto flex-fill text-end">
                            <button id="togglecamposAdicionais{{$teste}}" class="btn btn-outline-secondary btn-mini d-lg-none toggleDataSearchButton">
                                <i class="bi bi-view-list"></i>
                            </button>
                        </div>
                    </div>
                    <div id="camposAdicionais{{$teste}}" style="display: none" hidden>
                        <div class="row">
                            <div class="col-12">
                                <label for="mae{{$teste}}" class="form-label">Mãe</label>
                                <input type="text" class="form-control" name="mae" id="mae{{$teste}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="pai{{$teste}}" class="form-label">Pai</label>
                                <input type="text" class="form-control" name="pai" id="pai{{$teste}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="data_prisao{{$teste}}" class="form-label">Data prisão</label>
                                <input type="date" class="form-control" name="data_prisao" id="data_prisao{{$teste}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="informacoes{{$teste}}" class="form-label">Informações (Ex: link da notícia)</label>
                                <input type="date" class="form-control" name="informacoes" id="informacoes{{$teste}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="observacoes{{$teste}}" class="form-label">Observações</label>
                                <input type="date" class="form-control" name="observacoes" id="observacoes{{$teste}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row mb-2">
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
            <button id="btnInserirPreso" class="btn btn-primary me-2 w-sm" title="Inserir um preso">Inserir
                Preso</button>
            <a href="{{ !empty($redirecionamentoAnterior) ? $redirecionamentoAnterior : route('inclusao.entradasPresos') }}"
                class="btn btn-danger w-sm" title="Sair do orçamento" style="width: 100px;">Sair</a>
        </div>
    </div> --}}

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
