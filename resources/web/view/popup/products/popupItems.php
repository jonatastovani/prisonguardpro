<div id="pop-popItems" class="body-popup">
    <div class="popup top-sm-popup" id="popItems">
        <div class="close-btn">&times;</div>
        <div class="container mb-2">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center">Itens</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-sm-11 dataSearch">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 mt-2">
                            <div class="input-group">
                                <div class="input-group-text"><label for="nameItems">Nome</label></div>
                                <input type="text" id="nameItems" class="form-control inputActionItems" name="name">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 mt-2">
                            <div class="row h-100 align-items-center">
                                <div class="col-6">
                                    <div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, do menor para o maior">
                                        <input type="radio" class="form-check-input inputActionItems" id="rbAscItems" name="methodItems" value="asc" checked>
                                        <label class="form-check-label" for="rbAscItems">Ascendente</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
                                        <input type="radio" class="form-check-input inputActionItems" id="rbDescItems" name="methodItems" value="desc">
                                        <label class="form-check-label" for="rbDescItems">Descendente</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 mt-2">
                            <div class="input-group">
                                <div class="input-group-text"><label for="typeItems">Tipo</label></div>
                                <input type="text" id="typeItems" class="form-control inputActionItems" name="type">
                            </div>
                        </div>
                        <div class="col-lg-5 col-sm-6 mt-2">
                            <div class="row h-100 align-items-center">
                                <div class="col-6">
                                    <div class="form-check" title="Data de Cadastro">
                                        <input type="radio" class="form-check-input inputActionItems" id="rbCreatedItems" name="dateItems" value="created" checked>
                                        <label class="form-check-label" for="rbCreatedItems">Cadastro</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check" title="Data de Atualização">
                                        <input type="radio" class="form-check-input inputActionItems" id="rbUpdatedItems" name="dateItems" value="updated">
                                        <label class="form-check-label" for="rbUpdatedItems">Atualização</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 mt-2 group-createdItems" title="Filtro para busca informando o intervalo de datas que o item foi cadastrado">
                            <div class="row">
                                <div class="col-lg-7 col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-text" for="createdAfterItems">Cadastrado de:</label>
                                        <input type="date" class="form-control inputActionItems" id="createdAfterItems" name="createdAfterItems">
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-text" for="createdBeforeItems">até:</label>
                                        <input type="date" class="form-control inputActionItems" id="createdBeforeItems" name="createdBeforeItems">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 mt-2 group-updatedItems" title="Filtro para busca informando o intervalo de datas que o item foi atualizado pela última vez" hidden>
                            <div class="row">
                                <div class="col-lg-7 col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-text" for="updatedAfterItems">Atualizado de:</label>
                                        <input type="date" class="form-control inputActionItems" id="updatedAfterItems" name="updatedAfterItems" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-text" for="updatedBeforeItems">até:</label>
                                        <input type="date" class="form-control inputActionItems" id="updatedBeforeItems" name="updatedBeforeItems" disabled>
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
                            <th>Tipo</th>
                            <th title="Quantidade">Qtd</th>
                            <th title="Unidade">Un</th>
                            <th>Custo</th>
                            <th>Preço</th>
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
                        <div class="col-sm-8">
                            <label for="nameItems" class="form-label">Nome:</label>
                            <input type="text" class="form-control" name="name" id="nameItems">
                        </div>
                        <div class="col-sm-4">
                            <label for="typeItems" class="form-label">Tipo:</label>
                            <input type="text" class="form-control" name="type" id="typeItems">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 mt-2">
                            <label for="quantityItems" class="form-label">Quantidade:</label>
                            <input type="text" class="form-control" name="quantity" id="quantityItems">
                        </div>
                        <div class="col-sm-3 mt-2">
                            <label for="unitItems" class="form-label">Unidade:</label>
                            <select class="form-select" name="unit" id="unitItems">
                                <option value="m2">m²</option>
                                <option value="m">m</option>
                                <option value="cm">cm</option>
                                <option value="mm">mm</option>
                                <option value="un">un</option>
                            </select>
                        </div>
                        <div class="col-sm-3 mt-2">
                            <label for="cost_priceItems" class="form-label">Custo:</label>
                            <input type="text" class="form-control" name="cost_price" id="cost_priceItems">
                        </div>
                        <div class="col-sm-3 mt-2">
                            <label for="priceItems" class="form-label">Preço:</label>
                            <input type="text" class="form-control" name="price" id="priceItems">
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

<script type="module" src="/assets/js/popup/products/popupItems.js"></script>