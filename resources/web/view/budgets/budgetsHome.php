<div id="budgetsHome" class="row mt-1">
    
    <div class="col-sm-6 p-1">
        <a href="/budgets" class="text-decoration-none text-dark fs-5">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de orçamentos (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Orçamentos</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-receipt"></i> <span id="budgetsTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 p-1 m-0">
        <a href="#" class="text-decoration-none text-dark fs-5" id="btnNewBudget">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Incluir novo orçamento">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Incluir Orçamento</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-plus-lg"></i></p>
                </div>
            </div>
        </a>
    </div>

</div>

<?php include_once "view/popup/budgets/popupNewBudgets.php"; ?>
<script type="module" src="/assets/js/budgets/budgetsHome.js"></script>