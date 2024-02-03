<div id="pop-popNewProduct" class="body-popup">
    <div class="popup" id="popNewProduct">
        <div class="close-btn">&times;</div>
        <div class="container">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center">Novo Produto</h3>
                </div>
            </div>

            <form>
                <div class="data1">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <label for="nameNewProduct" class="form-label">Nome do produto:</label>
                            <input type="text" class="form-control" name="name" id="nameNewProduct">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <label for="from_templateNewProduct" class="form-label">Selecione o modelo:</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-info btnSearchTemplates" title="Clique para busca avançada de modelos"><i class="bi bi-search"></i></button>
                                <select name="from_template" id="from_templateNewProduct" class="form-select"></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="data2">
                    <div class="row mt-2 dynamic_parameters"></div>
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

<?php include_once "view/popup/products/popupSearchTemplates.php" ?>
<script type="module" src="/assets/js/popup/products/popupNewProduct.js"></script>