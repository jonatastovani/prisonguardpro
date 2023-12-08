<!-- popUp para adicionar novo artigo -->

<div id="pop-novoatend" class="body-popup">
    <div class="popup" id="popnovoatend">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Inserir Atendimento Enfermaria</h2>

            <div class="grupo-block" id="camposselectpresopopnovoatend">
                <h4 class="titulo-grupo"><label for="selectpresopopnovoatend">Pesquise o Preso</label></h4>
                <div class="flex">
                    <div>
                        <label for="searchpresopopnovoatend">Cod.: </label>
                        <input type="search" id="searchpresopopnovoatend" list="listapresospopnovoatend" class="cod-search tempsearchpopnovoatend" autocomplete="off">
                    </div>
                    <div class="largura-restante flex">
                        <div>
                            <label for="selectpresopopnovoatend" class="margin-espaco-esq">Preso: </label>
                        </div>
                        <div class="largura-restante">
                            <select id="selectpresopopnovoatend" class="presos tempselectpopnovoatend" style="width: 100%;"></select>
                        </div>
                    </div>
                </div>
                <datalist id="listapresospopnovoatend"></datalist>
            </div>
            <div id="dadospresopopnovoatend" class="grupo-block" hidden>
                <h4 class="titulo-grupo">Dados do Preso</h4>
                Nome: <b><span id="nomepopnovoatend">Nome do preso</span></b><br>
                Matrícula: <b><span id="matriculapopnovoatend">Matrícula</span></b>;
                Cela: <b><span id="raiocelapopnovoatend">Raio/Cela</span></b>
            </div>
            <div>
                <div class="flex">
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="selecttipopopnovoatend">Selecione o Atendimento</label></h4>
                        <label for="selecttipopopnovoatend">Tipo: </label>
                        <select id="selecttipopopnovoatend"></select>
                    </div>
                    <div class="grupo largura-restante">
                        <h4 class="titulo-grupo">Observações</h4>
                        <textarea id="obspopnovoatend" style="width: 100%;" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div id="historicoatend" class="grupo-block">
                <div><button id="btnhistoricoatend">Ver Histórico</button></div>
                <div id="tablehistoricoatend" class="listagem max-height-300" hidden>
                    <table id="table-hist-atend">
                        <thead>
                            <tr>
                                <th>Data Solicitação</th>
                                <th style="min-width: 350px;">Descrição pedido</th>
                                <th>Data Atendimento</th>
                                <th style="min-width: 350px;">Descrição Atendimento</th>
                                <th style="min-width: 200px;">Tipo Atendimento</th>
                                <th style="min-width: 350px;">Situação</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="ferramentas align-rig">
                <button id="salvarpopnovoatend">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/chefia_novoatend_popup.js"></script>