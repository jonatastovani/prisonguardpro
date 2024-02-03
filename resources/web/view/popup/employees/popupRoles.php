<div id="pop-popRoles" class="body-popup">
    <div class="popup" id="popRoles">
        <div class="close-btn">&times;</div>
        <div class="container mb-2">
            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center">Funções</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-sm-11 dataSearch">
                    <div class="row">
                        <div class="col-sm-6 mt-2">
                            <div class="input-group">
                                <div class="input-group-text"><label for="nameRoles">Nome</label></div>
                                <input type="text" id="nameRoles" class="form-control inputActionRoles" name="name">
                            </div>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <div class="row h-100 align-items-center">
                                <div class="col-6">
                                    <div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, do menor para o maior">
                                        <input type="radio" class="form-check-input inputActionRoles" id="rbAscRoles" name="methodRoles" value="asc" checked>
                                        <label class="form-check-label" for="rbAscRoles">Ascendente</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
                                        <input type="radio" class="form-check-input inputActionRoles" id="rbDescRoles" name="methodRoles" value="desc">
                                        <label class="form-check-label" for="rbDescRoles">Descendente</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <div class="row h-100 align-items-center">
                                <div class="col-6">
                                    <div class="form-check" title="Data de Cadastro">
                                        <input type="radio" class="form-check-input inputActionRoles" id="rbCreatedRoles" name="dateRoles" value="created" checked>
                                        <label class="form-check-label" for="rbCreatedRoles">Cadastro</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check" title="Data de Atualização">
                                        <input type="radio" class="form-check-input inputActionRoles" id="rbUpdatedRoles" name="dateRoles" value="updated">
                                        <label class="form-check-label" for="rbUpdatedRoles">Atualização</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2 group-createdRoles" title="Filtro para busca informando o intervalo de datas que a função foi cadastrada">
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <label class="input-group-text" for="createdAfterRoles">Cadastrado de:</label>
                                        <input type="date" class="form-control inputActionRoles" id="createdAfterRoles" name="createdAfterRoles">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label class="input-group-text" for="createdBeforeRoles">até:</label>
                                        <input type="date" class="form-control inputActionRoles" id="createdBeforeRoles" name="createdBeforeRoles">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2 group-updatedRoles" title="Filtro para busca informando o intervalo de datas que a função foi atualizada pela última vez" hidden>
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <label class="input-group-text" for="updatedAfterRoles">Atualizado de:</label>
                                        <input type="date" class="form-control inputActionRoles" id="updatedAfterRoles" name="updatedAfterRoles" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label class="input-group-text" for="updatedBeforeRoles">até:</label>
                                        <input type="date" class="form-control inputActionRoles" id="updatedBeforeRoles" name="updatedBeforeRoles" disabled>
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

            <div>
                <div class="col-12">
                    <input type="button" class="btnNewPop btn btn-primary" value="Adicionar">
                </div>
            </div>

            <form>
                <div class="hidden-fields" style="display: none;">

                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-center titlePop">Nova Função</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="nameRoles" class="form-label">Nome:</label>
                            <input type="text" class="form-control" name="name" id="nameRoles">
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

<script type="module" src="/assets/js/popup/employees/popupRoles.js"></script>