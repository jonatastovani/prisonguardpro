<div id="pop-popProducts" class="body-popup">
    <div class="popup" id="popProducts">
        <div class="close-btn">&times;</div>
        <div class="container mb-2">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center titleNameProduct">Produto</h3>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="sticky-top bg-white">
                        <tr class="text-center">
                            <th>Nome</th>
                            <th title="Quantidade">Quant.</th>
                            <th title="Desconto fixo">Desc. Fixo</th>
                            <th title="Desconto percentual">Desc. %</th>
                            <th title="Preço final">Final</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12 mt-2">
                    <p class="text-end">Total de Registros: <span class="totalRegisters">0</span></p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <input type="button" class="btnNewPop btn btn-primary" value="Adicionar">
                </div>
            </div>

            <form>

                <div class="hidden-fields" style="display: none;">

                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-center titlePop">Novo Item</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-9">
                            <label for="item_idProducts" class="form-label">Item:</label>
                            <select class="form-select" name="item_id" id="item_idProducts"></select>
                        </div>
                        <div class="col-sm-3">
                            <label for="quantityProducts" class="form-label" title="Desconto Fixo">Quantidade:</label>
                            <input type="text" class="form-control" name="quantity" id="quantityProducts">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 mt-2">
                            <label for="fixed_discountProducts" class="form-label" title="Desconto Fixo">Desc. Fixo:</label>
                            <input type="text" class="form-control" name="fixed_discount" id="fixed_discountProducts">
                        </div>
                        <div class="col-sm-3 mt-2">
                            <label for="percentage_discountProducts" class="form-label" title="Desconto por porcentagem">Desc. %:</label>
                            <input type="text" class="form-control" name="percentage_discount" id="percentage_discountProducts">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-success btnSavePop me-2">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span>Salvar</span>
                            </button>
                            <input type="button" class="btn btn-danger btnCancelPop" value="Cancelar">
                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

<script type="module" src="/assets/js/popup/products/popupProducts.js"></script>