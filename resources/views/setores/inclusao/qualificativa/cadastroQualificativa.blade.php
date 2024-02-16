@extends('site.layout')
@php
    $titulo = 'Qualificativa' . (!$preso_id_bln && !$permAtribuirMatriculaBln ? ' Provisória' : '');
@endphp
@section('title', $titulo)

@section('conteudo')

    <div class="row">
        <div class="col-12 mt-2 text-center">

            @php
                if (!$preso_id_bln) {
                    $titulo = "Nova $titulo";
                }
            @endphp

            <h3>{{ $titulo }}</h3>
        </div>
    </div>

    <div id="dadosQualificativa" class="row flex-fill overflow-auto">
        <div class="col-12">

            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label for="matricula" class="form-label">Matrícula</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-end" name="matricula" id="matricula">
                        <input type="text" style="max-width: 40px;" class="form-control text-center" name="digito"
                            id="digito" disabled>
                        <input type="hidden" id="preso_id_bln" value="{{ $preso_id_bln }}">
                        <input type="hidden" id="passagem_id" value="{{ $passagem_id }}">
                        <input type="hidden" id="qual_prov_id" value="{{ $qual_prov_id }}">
                        <input type="hidden" id="perm_atribuir_matricula_bln" value="{{ $permAtribuirMatriculaBln }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mt-2">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome">
                </div>
                <div class="col-md-6 mt-2"
                    title="Nome pelo qual o preso deseja ser chamado. Este nome ficará mais aparente nos documentos, caso seja informado.">
                    <label for="nome" class="form-label">Nome social</label>
                    <input type="text" class="form-control" name="nome_social" id="nome_social">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-2">
                    <label for="mae" class="form-label">Mãe</label>
                    <input type="text" class="form-control" name="mae" id="mae">
                </div>
                <div class="col-md-6 mt-2">
                    <label for="pai" class="form-label">Pai</label>
                    <input type="text" class="form-control" name="pai" id="pai">
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-9 col-md-8 mt-2">
                    <label for="cidade_nasc_id" class="form-label">Cidade Nascimento</label>
                    <div class="input-group">
                        <select name="cidade_nasc_id" id="cidade_nasc_id" style="width: 100%;"></select>
                        <button id="btnCidadeCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mt-2">
                    <label for="data_nasc" class="form-label">Data Nascimento</label>
                    <input type="date" class="form-control" name="data_nasc" id="data_nasc">
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="genero_id" class="form-label">Gênero / Orientação Sexual</label>
                    <div class="input-group">
                        {{-- <button id="btnGeneroConsulta" class="btn btn-outline-secondary"><i
                                class="bi bi-search"></i></button> --}}
                        <select name="genero_id" id="genero_id" class="form-select"></select>
                        <button id="btnGeneroCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="escolaridade_id" class="form-label">Escolaridade</label>
                    <div class="input-group">
                        <select name="escolaridade_id" id="escolaridade_id" class="form-select"></select>
                        <button id="btnEscolaridadeCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="estado_civil_id" class="form-label">Estado Civil</label>
                    <div class="input-group">
                        <select name="estado_civil_id" id="estado_civil_id" class="form-select"></select>
                        <button id="btnEstadoCivilCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="cutis_id" class="form-label">Cutis</label>
                    <div class="input-group">
                        <select name="cutis_id" id="cutis_id" class="form-select"></select>
                        <button id="btnCutisCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="cabelo_tipo_id" class="form-label">Tipo de Cabelo</label>
                    <div class="input-group">
                        <select name="cabelo_tipo_id" id="cabelo_tipo_id" class="form-select"></select>
                        <button id="btnCabeloTipoCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="cabelo_cor_id" class="form-label">Cor de Cabelo</label>
                    <div class="input-group">
                        <select name="cabelo_cor_id" id="cabelo_cor_id" class="form-select"></select>
                        <button id="btnCabeloCorCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="olho_tipo_id" class="form-label">Tipo de Olhos</label>
                    <div class="input-group">
                        <select name="olho_tipo_id" id="olho_tipo_id" class="form-select"></select>
                        <button id="btnOlhoTipoCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="olho_cor_id" class="form-label">Cor de Olhos</label>
                    <div class="input-group">
                        <select name="olho_cor_id" id="olho_cor_id" class="form-select"></select>
                        <button id="btnOlhoCorCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mt-2">
                    <label for="crenca_id" class="form-label">Crença</label>
                    <div class="input-group">
                        <select name="crenca_id" id="crenca_id" class="form-select"></select>
                        <button id="btnCrencaCadastro" class="btn btn-outline-secondary"><i
                                class="bi bi-pencil"></i></button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mt-2">
                    <label for="sinais" class="form-label">Sinais</label>
                    <textarea name="sinais" id="sinais" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <figure>
                <div class="row pb-0">
                    <div class="col-12 mt-2">
                        <h5 class="mb-0">Artigos</h5>
                    </div>
                </div>
                <div class="row ps-2 pe-2">
                    <div class="col-12">
                        <div id="containerArtigos" class="row overflow-auto border border-dark-subtle rounded"
                            style="min-height: 100px; max-height: 380px;"></div>
                    </div>
                </div>
            </figure>

            <div class="row mt-2">
                <div class="col-12 text-end">
                    <button id="btnAddArtigo" class="btn btn-outline-primary btn-sm">Adicionar Artigo</button>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6 mt-2">
                    <label for="informacoes" class="form-label">Informações (Ex: link da
                        notícia)</label>
                    <textarea class="form-control" name="informacoes" id="informacoes" cols="30" rows="2"></textarea>
                </div>
                <div class="col-md-6 mt-2" title="Observações sobre o preso (este campo não é impresso na qualificativa)">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" name="observacoes" id="observacoes" cols="30" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12">
            <div class="row text-end">
                <div class="col-12 mt-2">
                    <button type="submit" id="btnSalvar" class="btn btn-outline-success me-2 w-25"
                        title="Salvar alterações" style="max-width: 100px;">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Salvar
                    </button>
                    <a href="{{ URL::previous() }}" class="btn btn-outline-danger w-25 redirectUrl"
                        title="Sair da edição da Entrada de Presos" style="max-width: 100px;">
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('modals.preso.modalAlterarPresoConvivio')
    @include('modals.preso.modalCadastroPresoArtigo')
    @include('modals.referencias.modalCadastroCidade')
    @include('modals.referencias.modalCadastroGenero')
    @include('modals.referencias.modalCadastroEscolaridade')
    @include('modals.referencias.modalCadastroCutis')
    @include('modals.referencias.modalCadastroEstadoCivil')
    @include('modals.referencias.modalCadastroCabeloTipo')
    @include('modals.referencias.modalCadastroCabeloCor')
    @include('modals.referencias.modalCadastroOlhoTipo')
    @include('modals.referencias.modalCadastroOlhoCor')
    @include('modals.referencias.modalCadastroCrenca')

    <script type="module" src="{{ asset('js/setores/inclusao/qualificativa/cadastroQualificativa.js') }}"></script>

@endsection
