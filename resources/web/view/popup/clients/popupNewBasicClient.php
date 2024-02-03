<div id="pop-popNewBasicClient" class="body-popup">
    <div class="popup" id="popNewBasicClient">
        <div class="close-btn">&times;</div>
        <div class="container">
            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center">Novo Cliente</h3>
                </div>
            </div>

            <form>

                <div class="row">
                    <div class="col-12 mt-2">
                        <label for="nameNewBasicClient" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="name" id="nameNewBasicClient">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mt-2">
                        <label for="" class="form-label">Documento:</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="rbCpfNewBasicClient" name="document" value="cpf" checked>
                                    <label class="form-check-label" for="rbCpfNewBasicClient">CPF</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="rbCnpjNewBasicClient" name="document" value="cnpj">
                                    <label class="form-check-label" for="rbCnpjNewBasicClient">CNPJ</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <div>
                            <label for="cpfNewBasicClient" class="form-label">CPF:</label>
                            <input type="text" name="cpf" class="form-control" id="cpfNewBasicClient">
                        </div>
                        <div hidden>
                            <label for="cnpjNewBasicClient" class="form-label">CNPJ:</label>
                            <input type="text" name="cnpj" class="form-control" id="cnpjNewBasicClient" disabled>
                        </div>
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-6">
                        <label for="telNewBasicClient" class="form-label">Telefone:</label>
                        <input type="text" name="tel" class="form-control clstelefone" id="telNewBasicClient">
                    </div>

                </div>

                <div class="row mb-2">
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-success btnSavePop me-2">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span>Salvar</span>
                        </button>
                        <input type="button" class="btn btn-danger btnCancelPop" value="Cancelar">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script type="module" src="/assets/js/popup/clients/popupNewBasicClient.js"></script>