<div id="pop-popOrders" class="body-popup">
    <div class="popup" id="popOrders">
        <div class="close-btn">&times;</div>
        <div class="container">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center titlePop">Alterar orçamento</h3>
                </div>
            </div>

            <form>

                <div class="data1">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <label for="descriptionOrders" class="form-label">Descrição (Opcional):</label>
                            <input type="text" class="form-control" name="description" id="descriptionOrders" placeholder="Ex: Pedido realizado por WhatsApp">
                        </div>
                    </div>
                </div>

                <div class="data2">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <label for="selectStatusOrders" class="form-label">Selecione o status:</label>
                            <select name="status" id="selectStatusOrders" class="form-select"></select>
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

<script type="module" src="/assets/js/popup/orders/popupOrders.js"></script>