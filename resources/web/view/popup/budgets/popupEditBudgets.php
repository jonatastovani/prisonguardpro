<div id="pop-popEditBudgets" class="body-popup">
    <div class="popup max-h" id="popEditBudgets">
        <div class="close-btn">&times;</div>
        <div class="container">
            <div class="row mt-2">
                <h2 class="text-center titlePop">Alterar orçamento</h2>
            </div>

            <form>

                <div class="row">
                    <div class="col-12 mt-2">

                        <label for="selectClientEditBudgets" class="form-label">Selecione o cliente:</label>
                        <div class="input-group">

                            <button type="button" class="btn btn-outline-info btnSearchClients" title="Clique para busca avançada de cliente"><i class="bi bi-search"></i></button>
                            <select name="client_id" id="selectClientEditBudgets" class="form-select"></select>
                            <button type="button" class="btn btn-outline-primary btnNewBasicClient" title="Clique para inserir um novo cliente com cadastro básico"><i class="bi bi-plus"></i></i></button>

                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mt-2">
                        <label for="searchOrdersEditBudgets" class="form-label">Selecione o pedido:</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-info btnSearchOrders" title="Clique para busca avançada de pedidos"><i class="bi bi-search"></i></button>
                            <input type="search" name="order_id" id="searchOrdersEditBudgets" class="form-control" list="listOrdersEditBudgets">
                            <button type="button" class="btn btn-outline-primary btnNewOrder" title="Clique para inserir um novo pedido"><i class="bi bi-plus"></i></i></button>
                        </div>
                    </div>
                </div>

                <datalist id="listOrdersEditBudgets"></datalist>

                <div class="row">
                    <div class="col-12 mt-2 mb-2">
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
<?php include_once "view/popup/orders/popupSearchOrders.php" ?>
<?php include_once "view/popup/orders/popupOrders.php" ?>
<script type="module" src="/assets/js/popup/budgets/popupEditBudgets.js"></script>