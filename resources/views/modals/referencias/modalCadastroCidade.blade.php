<div class="modal fade" id="modalCadastroCidade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-reduce-padding">
                <h4 class="modal-title">Listagem de Cidades</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body position-relative pt-0">

                <div class="row sticky-top bg-white pt-2 pb-2">
                    <div class="col-12 dataSearch">
                        <div class="input-group">
                            <div class="input-group-text">
                                <label for="nomeSearchModalCadastroCidade">Busca</label>
                            </div>
                            <input type="text" id="nomeSearchModalCadastroCidade"
                                class="form-control inputActionSearchModalCadastroCidade" name="search">
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
                                    <th>Estado</th>
                                    <th>País</th>
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
                                    <h5 class="register-title">Nova Cidade</h5>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="nomeModalCadastroCidade" class="form-label">Nome</label>
                                        <input type="text" class="form-control" name="nome"
                                            id="nomeModalCadastroCidade">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="estado_idModalCadastroEstado" class="form-label">Estado</label>
                                        <div class="input-group">
                                            <select name="estado_id" id="estado_idModalCadastroEstado"></select>
                                            <button type="button"
                                            class="btn btn-outline-secondary btnEstadoCadastro"><i class="bi bi-pencil"></i></button>
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

@include('modals.referencias.modalCadastroEstado')

<script type="module" src="{{ asset('js/modals/referencias/modalCadastroCidade.js') }}"></script>
