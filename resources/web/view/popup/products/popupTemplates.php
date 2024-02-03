<div id="pop-popTemplates" class="body-popup">
    <div class="popup" id="popTemplates">
        <div class="close-btn">&times;</div>
        <div class="container">
            <h2 class="text-center">Modelos</h2>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="sticky-top bg-white">
                        <tr class="text-center">
                            <th>Nome</th>
                            <th>Qtd. Itens</th>
                            <th>Parâmetros</th>
                            <th>Ação</th>
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

            <div class="row">
                <div class="col-12">
                    <input type="button" class="btnNewPop btn btn-primary" value="Adicionar">
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "view/popup/products/popupNewTemplate.php" ?>
<script type="module" src="/assets/js/popup/products/popupTemplates.js"></script>