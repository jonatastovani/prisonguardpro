<!-- popUp para adicionar novo artigo -->

<div id="pop-atend" class="body-popup">
    <div class="popup" id="popatend">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Atendimentos Enfermaria Solicitados</h2>

            <div>
                <div class="flex">
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="selecttipopopatend">Selecione o Atendimento</label></h4>
                        <label for="selecttipopopatend">Tipo: </label>
                        <select id="selecttipopopatend"></select>
                    </div>
                    <div class="largura-restante align-rig">
                        <button class="atualizar_lista">Atualizar</button>
                    </div>
                </div>
            </div>
            <div id="tableatendimentos" class="listagem max-height-700">
                <table id="table-atendimentos">
                    <thead>
                        <tr>
                            <th style="min-width: 50px;">Ação</th>
                            <th class="min-width-100">Matrícula</th>
                            <th class="min-width-350 max-width-450">Nome</th>
                            <th>Data Solicitação</th>
                            <th class="min-width-350 max-width-450">Descrição pedido</th>
                            <th>Cela</th>
                            <th>Data Atendimento</th>
                            <th class="min-width-350 max-width-450">Descrição Atendimento</th>
                            <th class="min-width-250 max-width-450">Tipo Atendimento</th>
                            <th class="min-width-250 max-width-450">Situação</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/chefia_atend_popup.js"></script>