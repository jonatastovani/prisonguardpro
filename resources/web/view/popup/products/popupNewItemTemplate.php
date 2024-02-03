<div id="pop-popNewItemTemplate" class="body-popup">
    <div class="popup" id="popNewItemTemplate">
        <div class="close-btn">&times;</div>
        <div class="container">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center nameType">Inserir item</h3>
                </div>
            </div>

            <div id="sel_type_div" class="row" style="display: none;">
                <div class="col-12">
                    <label for="sel_type_NewItemTemplate" class="form-label">Tipo de item (Não adicionados no template):</label>
                    <select name="sel_type" id="sel_type_NewItemTemplate" class="form-select"></select>
                </div>
            </div>

            <form>

                <div class="row">
                    <div class="col-12 mt-2">
                        <label for="default_item_idNewItemTemplate" class="form-label" title="Item padrão deste tipo">Item padrão:</label>
                        <select name="default_item_id" id="default_item_idNewItemTemplate" class="form-select"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mt-2">
                        <label for="fixed_discountNewItemTemplate" class="form-label">Desconto Fixo:</label>
                        <input type="text" class="form-control" name="fixed_discount" id="fixed_discountNewItemTemplate">
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="percentage_discountNewItemTemplate" class="form-label">Desconto porcentagem:</label>
                        <input type="text" class="form-control" name="percentage_discount" id="percentage_discountNewItemTemplate">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-2">
                        <label for="expressionNewItemTemplate" class="form-label" title="Expressão que definirá o cálculo de custo deste item neste modelo">Expressão de cálculo:</label>
                        <input type="text" name="expression" id="expressionNewItemTemplate" class="form-control">
                        <p class="mt-1 mb-1 expression_demonstration"></p>
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

<script type="module" src="/assets/js/popup/products/popupNewItemTemplate.js"></script>