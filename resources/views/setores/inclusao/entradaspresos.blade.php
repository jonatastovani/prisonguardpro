@extends('site.layout')
@section('title', 'Inclusão Entradas')

@section('conteudo')
    <div class="row">
        <div class="col-12 mt-2">
            <h3 class="text-center">Entrada de Presos</h3>
        </div>
    </div>

    <div class="row">
        <div id="dataSearch" class="col-lg-12 col-sm-11 dataSearch">

            <div class="row">
                <div class="col-lg-6 mt-2 group-EntradasPresos"
                    title="Filtro para busca informando o intervalo de datas que o cliente foi cadastrado">
                    <div class="row">
                        <div class="col-lg-7 col-sm-6">
                            <div class="input-group">
                                <label class="input-group-text" for="inicioEntradasPresos">Entrada de:</label>
                                <input type="date" class="form-control inputActionEntradasPresosSearch"
                                    id="inicioEntradasPresos" name="inicioEntradasPresos">
                            </div>
                        </div>
                        <div class="col-lg-5 col-sm-6">
                            <div class="input-group">
                                <label class="input-group-text" for="fimEntradasPresos">até:</label>
                                <input type="date" class="form-control inputActionEntradasPresosSearch"
                                    id="fimEntradasPresos" name="fimEntradasPresos">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 mt-2" title="Filtro para busca do orçamento selecionando o cliente">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="ordenacaoEntradasPresos">Ordenação</label>
                        </div>
                        <select name="ordenacao" id="ordenacaoEntradasPresos" class="form-select inputActionEntradasPresosSearch">
                            <option value="matricula">Matrícula</option>
                            <option value="nome">Nome do preso</option>
                            <option value="data_entrada">Data entrada</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 mt-2" title="Filtro para o status do(a) preso(a)">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="statusEntradasPresos">Status</label>
                        </div>
                        <select name="status" id="statusEntradasPresos"
                            class="form-select inputActionEntradasPresosSearch"></select>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 mt-2" title="Filtro para busca por palavras ou números">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="valorEntradasPresos">Busca</label>
                        </div>
                        <input type="text" name="valor" id="valorEntradasPresos" class="form-control inputActionEntradasPresosSearch">
                    </div>
                </div>

                <div class="col-lg-5 col-md-6 mt-2" title="Filtro para tratar o texto de busca informado">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="tratamentoEntradasPresos">Tratamento</label>
                        </div>
                        <select name="tratamento" id="tratamentoEntradasPresos" class="form-select inputActionEntradasPresosSearch">
                            <option value="1">Dividir palavra na busca</option>
                            <option value="2">Texto completo para busca</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 mt-2" title="Filtro para tratar o texto de busca informado">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="metodoEntradasPresos">Método</label>
                        </div>
                        <select name="metodo" id="metodoEntradasPresos" class="form-select inputActionEntradasPresosSearch">
                            <option value="1">Qualquer parte</option>
                            <option value="2">Busca exata</option>
                            <option value="3">Iniciado por</option>
                            <option value="4">Encerrado por</option>
                        </select>
                    </div>
                </div>

            </div>

        </div>
        <div class="col-auto flex-fill text-end">
            <button id="toggleDataSearchButton" class="btn btn-outline-secondary btn-mini d-lg-none toggleDataSearchButton">
                <i class="bi bi-view-list"></i>
            </button>
        </div>
    </div>

    <div class="row flex-fill overflow-auto">
        <div class="table-responsive mt-2">
            <table id="table-entradaspresos" class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Ação</th>
                        <th>Matrícula</th>
                        <th>Nome Preso</th>
                        <th>RG</th>
                        <th>Data Entrada</th>
                        <th>Origem</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-2 mb-2">
        <div class="col-12">
            <a href="{{route('inclusao.criarEntradasPresos')}}" class="btn btn-primary" title="Inserir nova Entrada de Presos">Nova entrada</a>
        </div>
    </div>

    <script type="module" src="{{ asset('js/setores/inclusao/entradasPresos.js') }}"></script>
    <script type="module" src="{{ asset('js/websocket/script.js') }}"></script>

@endsection
