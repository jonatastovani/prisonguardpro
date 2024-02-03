<div id="pop-popSearchOrders" class="body-popup">
    <div class="popup" id="popSearchOrders">
        <div class="close-btn">&times;</div>
        <div class="container mt-2">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center">Busca de Pedidos</h3>
                </div>
            </div>

            <form>

                <div class="row">
                    <div class="col-lg-12 col-sm-11 dataSearch">
                        <div class="row">

                            <div class="col-lg-3 col-sm-6 mt-2 order-sm-1">
                                <div class="row h-100 align-items-center">
                                    <div class="col-6" title="Data de cadastro">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input inputActionSearchOrders" id="rbCreatedSearchOrders" name="dateSearch" value="created" checked>
                                            <label class="form-check-label" for="rbCreatedSearchOrders">Cadastro</label>
                                        </div>
                                    </div>
                                    <div class="col-6" title="Data de atualização">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input inputActionSearchOrders" id="rbUpdatedSearchOrders" name="dateSearch" value="updated">
                                            <label class="form-check-label" for="rbUpdatedSearchOrders">Atualização</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 mt-2 order-lg-2 order-sm-3 group-createdSearchOrders" title="Filtro para busca informando o intervalo de datas que o pedido foi cadastrado">
                                <div class="row">
                                    <div class="col-lg-7 col-sm-6">
                                        <div class="input-group">
                                            <label class="input-group-text" for="createdAfterSearchOrders">Cadastrado de:</label>
                                            <input type="date" class="form-control inputActionSearchOrders" id="createdAfterSearchOrders" name="createdAfterSearchOrders">
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-sm-6">
                                        <div class="input-group">
                                            <label class="input-group-text" for="createdBeforeSearchOrders">até:</label>
                                            <input type="date" class="form-control inputActionSearchOrders" id="createdBeforeSearchOrders" name="createdBeforeSearchOrders">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 mt-2 order-lg-2 order-sm-3 group-updatedSearchOrders" title="Filtro para busca informando o intervalo de datas que o pedido foi atualizado pela última vez" hidden>
                                <div class="row">
                                    <div class="col-lg-7 col-sm-6">
                                        <div class="input-group">
                                            <label class="input-group-text" for="updatedAfterSearchOrders">Atualizado de:</label>
                                            <input type="date" class="form-control inputActionSearchOrders" id="updatedAfterSearchOrders" name="updatedAfterSearchOrders" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-sm-6">
                                        <div class="input-group">
                                            <label class="input-group-text" for="updatedBeforeSearchOrders">até:</label>
                                            <input type="date" class="form-control inputActionSearchOrders" id="updatedBeforeSearchOrders" name="updatedBeforeSearchOrders" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6 mt-2 order-sm-2">
                                <div class="row h-100 align-items-center">
                                    <div class="col-6">
                                        <div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, do menor para o maior">
                                            <input type="radio" class="form-check-input inputActionSearchOrders" id="rbAscSearchOrders" name="method" value="asc" checked>
                                            <label class="form-check-label" for="rbAscSearchOrders">Ascendente</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
                                            <input type="radio" class="form-check-input inputActionSearchOrders" id="rbDescSearchOrders" name="method" value="desc">
                                            <label class="form-check-label" for="rbDescSearchOrders">Descendente</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 mt-2 order-sm-4">
                                <div class="input-group">
                                    <div class="input-group-text"><label for="statusSearchOrders">Status</label></div>
                                    <select name="status" id="statusSearchOrders" class="form-select inputActionSearchOrders"></select>
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

            </form>

            <div class="table-responsive mt-2">
                <table class="table table-hover">
                    <thead class="sticky-top bg-white">
                        <tr class="text-center">
                            <th></th>
                            <th>ID Pedido</th>
                            <th>Status</th>
                            <th>Cliente</th>
                            <th>ID Orçamento</th>
                            <th>Preço</th>
                            <th>Telefone</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-12">
                    <p class="text-end">Total de Registros: <span class="totalRegisters">0</span></p>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="module" src="/assets/js/popup/orders/popupSearchOrders.js"></script>