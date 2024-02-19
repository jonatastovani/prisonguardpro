<div class="modal fade" id="modalCadastroPresoDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-reduce-padding">
                <h4 class="modal-title">Documento Preso</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label for="artigo_idModalCadastroPresoDocumento" class="form-label">Artigo</label>
                            <br>
                            <div class="input-group">
                                <select name="artigo_id" id="artigo_idModalCadastroPresoDocumento" class="form-select">
                                </select>
                                <button type="button"
                                    class="btn btn-outline-secondary btnArtigosCadastro"><i class="bi bi-pencil"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="observacoesModalCadastroPresoDocumento" class="form-label">Observações
                                (Opcional)</label>
                            <textarea name="observacoes" id="observacoesModalCadastroPresoDocumento" cols="12" rows="2" class="form-control"
                                placeholder="Nome da vítima em caso de esposa/companheira, quantidade de drogas..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-reduce-padding">
                    <div class="col-12 text-end mt-2">
                        <button type="submit" class="btn btn-outline-success btn-save w-50" style="max-width: 100px;">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            Salvar
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-cancel w-50"
                            style="max-width: 100px;">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('modals.referencias.modalCadastroArtigo')

<script type="module" src="{{ asset('js/modals/preso/modalCadastroPresoDocumento.js') }}"></script>
