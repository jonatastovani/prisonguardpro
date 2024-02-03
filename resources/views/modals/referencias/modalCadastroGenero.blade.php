<div class="modal fade" id="modalCadastroGenero" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastro de Gêneros</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            <form>
                <div class="modal-footer">
                    <div class="col-12 divRegistrationFields" style="display: none;">
                        <div class="row">
                            <h5 class="register-title">Novo Gênero</h5>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="nomeModalCadastroGenero" class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome" id="nomeModalCadastroGenero">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="divBtnAdd">
                            <input type="button" class="btnNewRegister btn btn-outline-primary w-25" value="Adicionar">
                        </div>
                    </div>
                    <div class="col-12 text-end divBtnRegister" style="display: none;">
                        <button type="submit" class="btn btn-outline-success btn-save w-25">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            Salvar
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-cancel w-25">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script type="module" src="{{ asset('js/modals/referencias/modalCadastroGenero.js') }}"></script>
