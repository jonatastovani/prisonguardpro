@extends('site.layout')
@section('title', 'Inclusão Entradas')

@section('conteudo')
    <div class="row">
        <div class="col-12 mt-2">
            <h3 class="text-center">Gerenciar Entrada de Presos</h3>
        </div>
    </div>

    <div class="row">
        <div id="dataSearch" class="col-lg-12 col-sm-11 dataSearch">

            <div class="row">
                <div class="col-lg-3 col-sm-6 mt-2 order-sm-1">
                    <div class="row align-items-center h-100">
                        <div class="col-6" title="Data de Cadastro">
                            <div class="form-check">
                                <input type="radio" class="form-check-input inputActionGerEntradasPresosSearch"
                                    id="rbCreatedGerEntradasPresos" name="dateSearchClientsGerEntradasPresos" value="created" checked>
                                <label class="form-check-label" for="rbCreatedGerEntradasPresos">Cadastro</label>
                            </div>
                        </div>
                        <div class="col-6" title="Data de Atualização">
                            <div class="form-check">
                                <input type="radio" class="form-check-input inputActionGerEntradasPresosSearch"
                                    id="rbUpdatedGerEntradasPresos" name="dateSearchClientsGerEntradasPresos" value="updated">
                                <label class="form-check-label" for="rbUpdatedGerEntradasPresos">Atualização</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-2 group-createdGerEntradasPresos order-lg-2 order-sm-3"
                    title="Filtro para busca informando o intervalo de datas que o cliente foi cadastrado">
                    <div class="row">
                        <div class="col-lg-7 col-sm-6">
                            <div class="input-group">
                                <label class="input-group-text" for="createdAfterGerEntradasPresos">Cadastrado de:</label>
                                <input type="date" class="form-control inputActionGerEntradasPresosSearch" id="createdAfterGerEntradasPresos"
                                    name="createdAfterGerEntradasPresos">
                            </div>
                        </div>
                        <div class="col-lg-5 col-sm-6">
                            <div class="input-group">
                                <label class="input-group-text" for="createdBeforeGerEntradasPresos">até:</label>
                                <input type="date" class="form-control inputActionGerEntradasPresosSearch"
                                    id="createdBeforeGerEntradasPresos" name="createdBeforeGerEntradasPresos">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-2 group-updatedGerEntradasPresos order-lg-2 order-sm-3"
                    title="Filtro para busca informando o intervalo de datas que o orçamento foi atualizado pela última vez"
                    hidden>
                    <div class="row">
                        <div class="col-lg-7 col-sm-6">
                            <div class="input-group">
                                <label class="input-group-text" for="updatedAfterGerEntradasPresos">Atualizado de:</label>
                                <input type="date" class="form-control inputActionGerEntradasPresosSearch" id="updatedAfterGerEntradasPresos"
                                    name="updatedAfterGerEntradasPresos" disabled>
                            </div>
                        </div>
                        <div class="col-lg-5 col-sm-6">
                            <div class="input-group">
                                <label class="input-group-text" for="updatedBeforeGerEntradasPresos">até:</label>
                                <input type="date" class="form-control inputActionGerEntradasPresosSearch"
                                    id="updatedBeforeGerEntradasPresos" name="updatedBeforeGerEntradasPresos" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mt-2 order-sm-2">
                    <div class="row align-items-center h-100">
                        <div class="col-6">
                            <div class="form-check"
                                title="Forma de ordenação em ordem ascendente, ou seja, ou do menor para o maior">
                                <input type="radio" class="form-check-input inputActionGerEntradasPresosSearch" id="rbAscGerEntradasPresos"
                                    name="methodGerEntradasPresos" value="asc" checked>
                                <label class="form-check-label" for="rbAscGerEntradasPresos">Ascendente</label>
                            </div>
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-check"
                                title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
                                <input type="radio" class="form-check-input inputActionGerEntradasPresosSearch" id="rbDescGerEntradasPresos"
                                    name="methodGerEntradasPresos" value="desc">
                                <label class="form-check-label" for="rbDescGerEntradasPresos">Descendente</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-6 mt-2" title="Filtro para busca do orçamento selecionando o cliente">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="btnSearchClientesGerEntradasPresos">Cliente</label>
                        </div>
                        <button id="btnSearchClientesGerEntradasPresos" class="btn btn-outline-info"
                            title="Clique para busca avançada de cliente"><i class="bi bi-search"></i></button>
                        <select name="client_id" id="client_idGerEntradasPresos"
                            class="form-select inputActionGerEntradasPresosSearch"></select>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mt-2" title="Filtro para busca do orçamento informando o ID do Orçamento">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="budget_id">Orçamento</label>
                        </div>
                        <input id="budget_id" type="search" class="form-control inputActionGerEntradasPresosSearch"
                            list="listGerEntradasPresos">
                    </div>
                    <datalist id="listGerEntradasPresos"></datalist>
                </div>
            </div>

        </div>
        <div class="col-auto flex-fill text-end">
            <button id="toggleDataSearchButton"
                class="btn btn-outline-secondary btn-mini d-lg-none toggleDataSearchButton">
                <i class="bi bi-view-list"></i>
            </button>
        </div>
    </div>

    <div class="row flex-fill overflow-auto">
        <div class="table-responsive mt-2">
            <table id="table-gerentradaspresos" class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Ação</th>
                        <th>Matrícula</th>
                        <th>Nome Preso</th>
                        <th>RG</th>
                        <th>Data Entrada</th>
                        <th>Origem</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-2 mb-2">
        <div class="col-12">
            <button id="btnNewBudget" class="btn btn-primary" title="Inserir novo orçamento">Nova entrada</button>
        </div>
    </div>

    <?php // include_once 'view/popup/gerentradaspresos/popupNewGerEntradasPresos.php'; ?>
    <script type="module" src="{{asset('js/setores/inclusao/gerenciarEntradasPresos.js')}}"></script>

@endsection
