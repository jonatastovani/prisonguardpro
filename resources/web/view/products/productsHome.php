<div id="productsHome" class="row mt-1">

    <div class="col-sm-6 p-1">
        <a href="#" class="text-decoration-none text-dark fs-5" id="openPopupItems">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de itens (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Itens</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-puzzle"></i> <span id="itemsTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 p-1">
        <a href="/products/templates" class="text-decoration-none text-dark fs-5" id="openPopupTemplates">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de templates (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Modelos</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-box-fill"></i> <span id="templatesTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

</div>

<?php include_once "view/popup/products/popupItems.php" ?>
<script type="module" src="/assets/js/products/productsHome.js"></script>