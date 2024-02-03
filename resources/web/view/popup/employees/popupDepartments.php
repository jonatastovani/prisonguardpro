<div id="pop-popDepartments" class="body-popup">
    <div class="popup" id="popDepartments">
        <div class="close-btn">&times;</div>
        <div class="container mb-2">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center">Departamentos</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-sm-11 dataSearch">
                    <div class="row">
                        <div class="col-sm-6 mt-2">
                            <div class="input-group">
                                <div class="input-group-text"><label for="nameDepartments">Nome</label></div>
                                <input type="text" id="nameDepartments" class="form-control inputActionDepartments" name="name">
                            </div>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <div class="row h-100 align-items-center">
                                <div class="col-6">
                                    <div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, do menor para o maior">
                                        <input type="radio" class="form-check-input inputActionDepartments" id="rbAscDepartments" name="methodDepartments" value="asc" checked>
                                        <label class="form-check-label" for="rbAscDepartments">Ascendente</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
                                        <input type="radio" class="form-check-input inputActionDepartments" id="rbDescDepartments" name="methodDepartments" value="desc">
                                        <label class="form-check-label" for="rbDescDepartments">Descendente</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <div class="row h-100 align-items-center">
                                <div class="col-6">
                                    <div class="form-check" title="Data de Cadastro">
                                        <input type="radio" class="form-check-input inputActionDepartments" id="rbCreatedDepartments" name="dateDepartments" value="created" checked>
                                        <label class="form-check-label" for="rbCreatedDepartments">Cadastro</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check" title="Data de Atualização">
                                        <input type="radio" class="form-check-input inputActionDepartments" id="rbUpdatedDepartments" name="dateDepartments" value="updated">
                                        <label class="form-check-label" for="rbUpdatedDepartments">Atualização</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2 group-createdDepartments" title="Filtro para busca informando o intervalo de datas que o departamento foi cadastrado">
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <label class="input-group-text" for="createdAfterDepartments">Cadastrado de:</label>
                                        <input type="date" class="form-control inputActionDepartments" id="createdAfterDepartments" name="createdAfterDepartments">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label class="input-group-text" for="createdBeforeDepartments">até:</label>
                                        <input type="date" class="form-control inputActionDepartments" id="createdBeforeDepartments" name="createdBeforeDepartments">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2 group-updatedDepartments" title="Filtro para busca informando o intervalo de datas que o departamento foi atualizado pela última vez" hidden>
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <label class="input-group-text" for="updatedAfterDepartments">Atualizado de:</label>
                                        <input type="date" class="form-control inputActionDepartments" id="updatedAfterDepartments" name="updatedAfterDepartments" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label class="input-group-text" for="updatedBeforeDepartments">até:</label>
                                        <input type="date" class="form-control inputActionDepartments" id="updatedBeforeDepartments" name="updatedBeforeDepartments" disabled>
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

            <div class="table-responsive mt-2">
                <table class="table table-hover">
                    <thead class="sticky-top bg-white">
                        <tr class="text-center">
                            <th></th>
                            <th>Nome</th>
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
                            <h5 class="text-center titlePop">Novo Departamento</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="nameDepartments" class="form-label">Nome:</label>
                            <input type="text" class="form-control" name="name" id="nameDepartments">
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

<script type="module" src="/assets/js/popup/employees/popupDepartments.js"></script>