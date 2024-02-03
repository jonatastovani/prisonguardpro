<div id="pop-popNewBudgets" class="body-popup">
    <div class="popup" id="popNewBudgets">
        <div class="close-btn">&times;</div>
        <div class="container">
            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center titlePop">Novo orçamento</h3>
                </div>
            </div>

            <form>

                <div class="row">
                    <div class="col-12 mt-2">

                        <label for="selectClientNewBudgets" class="form-label">Selecione o cliente:</label>
                        <div class="input-group">

                            <button class="btn btn-outline-info btnSearchClients" title="Clique para busca avançada de cliente"><i class="bi bi-search"></i></button>
                            <select name="client_id" id="selectClientNewBudgets" class="form-select"></select>
                            <button class="btn btn-outline-primary btnNewBasicClient" title="Clique para inserir um novo cliente com cadastro básico"><i class="bi bi-plus"></i></button>

                        </div>

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

<?php include_once "view/popup/clients/popupSearchClients.php" ?>
<?php include_once "view/popup/clients/popupNewBasicClient.php" ?>
<script type="module" src="/assets/js/popup/budgets/popupNewBudgets.js"></script>