<!-- popUp para adicionar novo artigo -->

<div id="pop-atendenf" class="body-popup">
    <div class="popup" id="popatendenf">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Atendimentos Enfermaria</h2>

            <div>
                <div class="flex">
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="selecttipopopatendenf">Selecione o Atendimento</label></h4>
                        <label for="selecttipopopatendenf">Tipo: </label>
                        <select id="selecttipopopatendenf"></select>
                    </div>
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="datapopatendenf">Data atendimento</label></h4>
                        <input type="date" id="datapopatendenf">
                    </div>
                </div>
            </div>
            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="requisitantepopatendenf">Nome requisitante</label></h4>
                <input type="search" id="requisitantepopatendenf" list="listarequisitantepopatendenf" style="width: 100%;">
            </div>
            <datalist id="listarequisitantepopatendenf"></datalist>

            <div class="grupo-block" id="camposselectpresopopatendenf">
                <h4 class="titulo-grupo"><label for="selectpresopopatendenf">Pesquise o Preso</label></h4>
                <div class="flex">
                    <div>
                        <label for="searchpresopopatendenf">Cod.: </label>
                        <input type="search" id="searchpresopopatendenf" list="listapresospopatendenf" class="cod-search tempsearchpopatendenf" autocomplete="off">
                    </div>
                    <div class="largura-restante flex">
                        <div class="margin-espaco-esq">
                            <label for="selectpresopopatendenf">Preso: </label>
                        </div>
                        <div class="largura-restante">
                            <select id="selectpresopopatendenf" class="presos tempselectpopatendenf" style="width: 100%;"></select>
                        </div>
                        <div class="margin-espaco-esq">
                            <button id="btninserirpopatendenf">Inserir</button>
                        </div>
                    </div>
                </div>
                <datalist id="listapresospopatendenf"></datalist>
            </div>
            <div class="grupo-block listagem">
                <h4 class="titulo-grupo">Presos adicionados</h4>
                <table id="table-atendgerais">
                    <thead>
                        <tr>
                            <th>Ação</th>
                            <th class="centralizado min-width-100">Matrícula</th>
                            <th class="min-width-350">Nome</th>
                            <th>Horário</th>
                            <th class="centralizado min-width-100">Local</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <datalist id="listasituacaopopatendenf"></datalist>
            </div>

            <div class="ferramentas align-rig">
                <button id="salvarpopatendenf">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/saude_atendenf_popup.js"></script>