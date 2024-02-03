<div id="employeesHome" class="row mt-1">

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

<?php include_once "view/popup/employees/popupEmployees.php" ?>
<?php include_once "view/popup/employees/popupDepartments.php" ?>
<?php include_once "view/popup/employees/popupRoles.php" ?>
<script type="module" src="/assets/js/employees/employeesHome.js"></script>