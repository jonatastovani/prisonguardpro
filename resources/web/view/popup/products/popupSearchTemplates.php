<div id="pop-popSearchTemplates" class="body-popup">
    <div class="popup" id="popSearchTemplates">
        <div class="close-btn">&times;</div>
        <div class="container mb-2">

            <div class="row">
                <div class="col-12 mt-2">
                    <h3 class="text-center">Busca de Modelos</h3>
                </div>
            </div>

            <form>

                <div class="row">

                    <div class="col-sm-6">
                        <label for="nameSearchTemplates" class="form-label">Nome:</label>
                        <input type="text" class="form-control inputActionSearch" name="name" id="nameSearchTemplates">
                    </div>

                    <div class="col-lg-3 col-sm-6 mt-2">
                        <div class="row h-100 align-items-center">
                            <div class="col-6">
                                <div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, do menor para o maior">
                                    <input type="radio" class="form-check-input inputActionSearch" id="rbAscSearchTemplates" name="method" value="asc" checked>
                                    <label class="form-check-label" for="rbAscSearchTemplates">Ascendente</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
                                    <input type="radio" class="form-check-input inputActionSearch" id="rbDescSearchTemplates" name="method" value="desc">
                                    <label class="form-check-label" for="rbDescSearchTemplates">Descendente</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <div class="table-responsive mt-2">
                <table class="table table-hover">
                    <thead class="sticky-top bg-white">
                        <tr class="text-center">
                            <th></th>
                            <th>Nome</th>
                            <th>Qtd. Itens</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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

<script type="module" src="/assets/js/popup/products/popupSearchTemplates.js"></script>