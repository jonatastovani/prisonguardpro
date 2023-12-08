
<div id="pop-visumedicass" class="body-popup">
    <div class="popup" id="popvisumedicass">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2 id="titulopopvismed">Medicamentos Assistidos</h2>

            <fieldset class="grupo-block">
                <legend>Dados do Preso</legend>
                Nome: <b><span id="nomepopvismed" class="temphtmlpopvismed">Nome do preso</span></b><br>
                Matrícula: <b><span id="matriculapopvismed" class="temphtmlpopvismed">Matrícula</span></b>;
                Cela: <b><span id="raiocelapopvismed" class="temphtmlpopvismed">Raio/Cela</span></b><br>
                Data: <b><span id="datapopvismed" class="temphtmlpopvismed">Data</span></b>;
                Período: <b><span id="periodopopvismed" class="temphtmlpopvismed">Período</span></b>
            </fieldset>

            <div class="listagem max-height-400">
                <table id="table-pop-visumedicass" class="largura-total">
                    <thead>
                        <tr class="nowrap">
                            <th>Ação</th>
                            <th>ID</th>
                            <th>Medicamento</th>
                            <th>Qtd</th>
                            <th>Período</th>
                            <th>Data Entregue</th>
                            <th>Data Início</th>
                            <th>Data Término</th>
                        </tr>
                    </thead>
                    <tbody class="temphtmlpopvismed"></tbody>
                </table>
            </div>

            <div class="final-pagina" style="margin-top: 5px;">
                <button id="cancelarpopvismed">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/sau_visu_medic_ass_popup.js"></script>