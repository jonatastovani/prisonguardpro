<!-- Avisos e Lembretes -->
<!-- <div class="container mt-5">
    <div class="row">
        <div class="col-sm-6">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Aviso Importante</h4>
                <p>Aqui pode vir os primeiros lembretes ou entregas do início do expediente.</p>
                <hr>
                <p class="mb-0">Este é um aviso importante que requer sua atenção.</p>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Lembrete</h4>
                <p>Lembrete: Reunião de equipe amanhã às 10h.</p>
            </div>
        </div>
    </div>
</div> -->

<div id="home" class="row mt-1">

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

    <div class="col-sm-6 p-1">
        <a href="/orders" class="text-decoration-none text-dark fs-5">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de pedidos (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Pedidos</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-receipt"></i> <span id="ordersTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

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

    <div class="col-sm-6 p-1">
        <a href="/clients" class="text-decoration-none text-dark fs-5">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de clientes (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Clientes</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-people"></i> <span id="clientsTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 p-1">
        <form action="/clients/new" method="post" class="fs-5">
            <input type="hidden" name="redirect-previous" value="/clients/home">
            <button type="submit" class="alert alert-primary w-100 m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Incluir novo cliente">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Incluir cliente</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-person-plus"></i></p>
                </div>
            </button>
        </form>
    </div>

    <div class="col-sm-6 p-1">
        <a href="#" class="text-decoration-none text-dark fs-5" id="openPopupDepartments">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de departamentos (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Departamentos</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-building-fill"></i> <span id="departmentsTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 p-1">
        <a href="#" class="text-decoration-none text-dark fs-5" id="openPopupRoles">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de funções (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Funções</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-list-task"></i> <span id="rolesTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 p-1">
        <a href="#" class="text-decoration-none text-dark fs-5" id="openPopupEmployees">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de funcionários (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Funcionários</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-person-vcard"></i> <span id="employeesTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

</div>

<?php include_once "view/popup/budgets/popupNewBudgets.php"; ?>
<script type="module" src="/assets/js/budgets/budgetsHome.js"></script>

<script type="module" src="/assets/js/orders/ordersHome.js"></script>

<?php include_once "view/popup/products/popupItems.php" ?>
<script type="module" src="/assets/js/products/productsHome.js"></script>

<script type="module" src="/assets/js/clients/clientsHome.js"></script>

<?php include_once "view/popup/employees/popupEmployees.php" ?>
<?php include_once "view/popup/employees/popupDepartments.php" ?>
<?php include_once "view/popup/employees/popupRoles.php" ?>
<script type="module" src="/assets/js/employees/employeesHome.js"></script>