<div class="modal fade" id="modalCadastroDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-reduce-padding">
                <h4 class="modal-title">Listagem de Documentos</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body position-relative pt-0">

                <div class="row sticky-top bg-white pt-2 pb-2">
                    <div class="col-12 dataSearch">
                        <div class="input-group">
                            <div class="input-group-text">
                                <label for="nomeSearchModalCadastroDocumento">Busca</label>
                            </div>
                            <input type="text" id="nomeSearchModalCadastroDocumento"
                                class="form-control inputActionSearchModalCadastroDocumento" name="search">
                        </div>
                    </div>
                </div>

                <div class="row flex-fill overflow-auto">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Ação</th>
                                    <th>Nome</th>
                                    <th>Máscara</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-reduce-padding">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="divBtnAdd">
                                <button type="button" class="btnNewRegister btn btn-outline-primary w-50"
                                    style="max-width: 100px;">Adicionar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <form>
                            <div class="col-12 divRegistrationFields" style="display: none;">
                                <div class="row">
                                    <h5 class="register-title">Novo Documento</h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label for="documento_tipo_idModalCadastroDocumento"
                                            class="form-label">Tipo</label>
                                        <div class="input-group">
                                            <select name="documento_tipo_id"
                                                id="documento_tipo_idModalCadastroDocumento"
                                                class="form-select"></select>
                                            <button type="button"
                                                class="btn btn-outline-secondary btnDocumentoTipoCadastro"><i
                                                    class="bi bi-pencil" tabindex="-1"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label for="maskModalCadastroDocumento" class="form-label">Máscara</label>
                                        <input type="text" class="form-control" name="mask"
                                            id="maskModalCadastroDocumento">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-6 d-flex align-items-end">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="reverse_bln" id="reverse_blnModalCadastroDocumento">
                                            <label class="form-check-label" for="reverse_blnModalCadastroDocumento"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Se a aplicação da máscara é aplicada da direita para a esqueda">Máscara
                                                Reversa</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-6 d-flex align-items-end">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="digito_bln" id="digito_blnModalCadastroDocumento">
                                            <label class="form-check-label" for="digito_blnModalCadastroDocumento"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Se o documento não tem por padrão o dígito, então um campo de dígito é criado, sendo opcional a inserção.">Campo
                                                Dígito</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-6 mt-2">
                                        <label for="validade_emissao_intModalCadastroDocumento" class="form-label"
                                            data-bs-title="Validade padrão do documento em meses. Deixe vazio para não aplicar a validade."
                                            data-bs-toggle="tooltip">Validade</label>
                                        <div class="input-group">
                                            <input type="text" name="validade_emissao_int"
                                                id="validade_emissao_intModalCadastroDocumento" class="form-control">
                                            <div class="input-group-text">meses(ês)</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row rowDigito" style="display: none;">
                                    <div class="col-6 mt-2" data-bs-toggle="tooltip"
                                        data-bs-title="Máscara que define os valores a serem aceitos no campo dígito.">
                                        <label for="digito_maskModalCadastroDocumento" class="form-label">Másc.
                                            dígito</label>
                                        <input type="text" class="form-control text-center" name="digito_mask"
                                            placeholder="Ex: X" id="digito_maskModalCadastroDocumento" disabled>
                                    </div>
                                    <div class="col-6 mt-2" data-bs-toggle="tooltip"
                                        data-bs-title="Caractere separador do dígito.">
                                        <label for="digito_separadorModalCadastroDocumento"
                                            class="form-label">Separador</label>
                                        <input type="text" class="form-control text-center"
                                            name="digito_separador" placeholder="Ex: -, /"
                                            id="digito_separadorModalCadastroDocumento" disabled>
                                    </div>
                                </div>

                                <div class="row rowEstado" style="display: none;">
                                    <div class="col-sm-6 mt-2">
                                        <label for="estado_idModalCadastroDocumento" class="form-label">Estado</label>
                                        <div class="input-group">
                                            <div class="input-group-select2-button-1">
                                                <select name="estado_id" id="estado_idModalCadastroDocumento"
                                                    class="form-select" disabled></select>
                                            </div>
                                            <button type="button"
                                                class="btn btn-outline-secondary btnEstadoCadastro"><i
                                                    class="bi bi-pencil" tabindex="-1"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label for="orgao_emissor_idModalCadastroDocumento" class="form-label">Órgão
                                            Emissor</label>
                                        <div class="input-group">
                                            <div class="input-group-select2-button-1">
                                                <select name="orgao_emissor_id"
                                                    id="orgao_emissor_idModalCadastroDocumento" class="form-select"
                                                    disabled></select>
                                            </div>
                                            <button type="button"
                                                class="btn btn-outline-secondary btnOrgaoEmissorCadastro"><i
                                                    class="bi bi-pencil" tabindex="-1"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row rowNacionalidade" style="display: none;">
                                    <div class="col-sm-6 mt-2">
                                        <label for="nacionalidade_idModalCadastroDocumento"
                                            class="form-label">Nacionalidade</label>
                                        <div class="input-group">
                                            <div class="input-group-select2-button-1">
                                                <select name="nacionalidade_id"
                                                    id="nacionalidade_idModalCadastroDocumento" class="form-select"
                                                    disabled></select>
                                            </div>
                                            <button type="button"
                                                class="btn btn-outline-secondary btnNacionalidadeCadastro"><i
                                                    class="bi bi-pencil" tabindex="-1"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row rowValidation">
                                    <div class="col-sm-6 mt-2">
                                        <label for="validation_typeModalCadastroDocumento" class="form-label"
                                            data-bs-toggle="tooltip"
                                            data-bs-title="O Tipo de validação de documento para ser aplicado.">Tipo de
                                            validação</label>
                                        <div class="input-group">
                                            <select name="validation_type" id="validation_typeModalCadastroDocumento"
                                                class="form-select"></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-end mt-2">
                                        <button type="submit" class="btn btn-outline-success btn-save w-50"
                                            style="max-width: 100px;">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                                aria-hidden="true"></span>
                                            Salvar
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-cancel w-50"
                                            style="max-width: 100px;">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.referencias.modalCadastroDocumentoTipo')
@include('modals.referencias.modalCadastroEstado')

<script type="module" src="{{ asset('js/modals/referencias/modalCadastroDocumento.js') }}"></script>
