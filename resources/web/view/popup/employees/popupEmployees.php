<div id="pop-popEmployees" class="body-popup">
    <div class="popup" id="popEmployees">
        <div class="close-btn">&times;</div>
        <div class="container mb-2">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center">Funcionários</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-sm-11 dataSearch">
                    <div class="row">
                        <div class="col-sm-6 mt-2">
                            <div class="input-group">
                                <div class="input-group-text"><label for="nameEmployees">Nome</label></div>
                                <input type="text" id="nameEmployees" class="form-control inputActionEmployees" name="name">
                            </div>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <div class="row h-100 align-Employees-center">
                                <div class="col-6">
                                    <div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, do menor para o maior">
                                        <input type="radio" class="form-check-input inputActionEmployees" id="rbAscEmployees" name="methodEmployees" value="asc" checked>
                                        <label class="form-check-label" for="rbAscEmployees">Ascendente</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
                                        <input type="radio" class="form-check-input inputActionEmployees" id="rbDescEmployees" name="methodEmployees" value="desc">
                                        <label class="form-check-label" for="rbDescEmployees">Descendente</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <div class="input-group">
                                <div class="input-group-text"><label for="department_nameEmployees">Departamento</label></div>
                                <input type="text" id="department_nameEmployees" class="form-control inputActionEmployees" name="department_name">
                            </div>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <div class="input-group">
                                <div class="input-group-text"><label for="role_nameEmployees">Função</label></div>
                                <input type="text" id="role_nameEmployees" class="form-control inputActionEmployees" name="role_name">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 mt-2">
                            <div class="row h-100 align-Employees-center">
                                <div class="col-6">
                                    <div class="form-check" title="Data de Cadastro">
                                        <input type="radio" class="form-check-input inputActionEmployees" id="rbCreatedEmployees" name="dateEmployees" value="created" checked>
                                        <label class="form-check-label" for="rbCreatedEmployees">Cadastro</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check" title="Data de Atualização">
                                        <input type="radio" class="form-check-input inputActionEmployees" id="rbUpdatedEmployees" name="dateEmployees" value="updated">
                                        <label class="form-check-label" for="rbUpdatedEmployees">Atualização</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 mt-2 group-createdEmployees" title="Filtro para busca informando o intervalo de datas que o funcionário foi cadastrado">
                            <div class="row">
                                <div class="col-lg-7 col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-text" for="createdAfterEmployees">Cadastrado de:</label>
                                        <input type="date" class="form-control inputActionEmployees" id="createdAfterEmployees" name="createdAfterEmployees">
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-text" for="createdBeforeEmployees">até:</label>
                                        <input type="date" class="form-control inputActionEmployees" id="createdBeforeEmployees" name="createdBeforeEmployees">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 mt-2 group-updatedEmployees" title="Filtro para busca informando o intervalo de datas que o funcionário foi atualizado pela última vez" hidden>
                            <div class="row">
                                <div class="col-lg-7 col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-text" for="updatedAfterEmployees">Atualizado de:</label>
                                        <input type="date" class="form-control inputActionEmployees" id="updatedAfterEmployees" name="updatedAfterEmployees" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-text" for="updatedBeforeEmployees">até:</label>
                                        <input type="date" class="form-control inputActionEmployees" id="updatedBeforeEmployees" name="updatedBeforeEmployees" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto flex-fill text-end">
                    <button class="btn btn-outline-secondary btn-mini d-lg-none toggleDataSearchButton">
                        <i class="bi bi-view-list"></i>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="sticky-top bg-white">
                        <tr class="text-center">
                            <th></th>
                            <th>Nome</th>
                            <th>Departamento</th>
                            <th>Função</th>
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
                            <h5 class="text-center titlePop">Novo Funcionário</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="nameEmployees" class="form-label">Nome:</label>
                            <input type="text" class="form-control" name="name" id="nameEmployees">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <label for="department_idEmployees" class="form-label">Departamento:</label>
                            <div class="input-group">
                                <select class="form-select" name="department_id" id="department_idEmployees"></select>
                                <button class="btn btn-outline-primary btnNewDepartment" title="Inserir novo departamento"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="role_idEmployees" class="form-label">Função:</label>
                            <div class="input-group">
                                <select class="form-select" name="role_id" id="role_idEmployees"></select>
                                <button class="btn btn-outline-primary btnNewRole" title="Inserir nova função"><i class="bi bi-plus"></i></button>
                            </div>
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

<?php include_once "view/popup/employees/popupDepartments.php" ?>
<?php include_once "view/popup/employees/popupRoles.php" ?>
<script type="module" src="/assets/js/popup/employees/popupEmployees.js"></script>