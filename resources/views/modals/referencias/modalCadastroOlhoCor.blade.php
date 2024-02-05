<div class="modal fade" id="modalCadastroOlhoCor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-reduce-padding">
                <h4 class="modal-title">Listagem de Cor de Olhos</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body position-relative pt-0">
                <div class="row sticky-top bg-white pt-2 pb-2">
                    <div class="col-12 dataSearch">
                        <div class="input-group">
                            <div class="input-group-text"><label for="nomeSearchModalCadastroOlhoCor">Busca</label>
                            </div>
                            <input type="text" id="nomeSearchModalCadastroOlhoCor"
                                class="form-control inputActionSearchModalCadastroOlhoCor" name="search">
                        </div>
                    </div>
                </div>
                <div class="row flex-fill overflow-auto">
                    <div class="table-responsive mt-2">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Ação</th>
                                    <th>Nome</th>
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
                                    <h5 class="register-title">Nova Cor de Olhos</h5>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="modalCadastroOlhoCor" class="form-label">Nome</label>
                                        <input type="text" class="form-control" name="nome"
                                            id="modalCadastroOlhoCor">
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

<script type="module" src="{{ asset('js/modals/referencias/modalCadastroOlhoCor.js') }}"></script>
