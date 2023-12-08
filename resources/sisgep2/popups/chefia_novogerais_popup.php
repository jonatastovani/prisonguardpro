<!-- popUp para adicionar novo artigo -->

<div id="pop-novogerais" class="body-popup">
    <div class="popup" id="popnovogerais">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Atendimentos Gerais</h2>

            <div>
                <div class="flex">
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="selecttipopopnovogerais">Selecione o Atendimento</label></h4>
                        <label for="selecttipopopnovogerais">Tipo: </label>
                        <select id="selecttipopopnovogerais"></select>
                    </div>
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="datapopnovogerais">Data atendimento</label></h4>
                        <input type="date" id="datapopnovogerais">
                        <input type="time" id="horapopnovogerais">
                    </div>
                </div>
            </div>
            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="requisitantepopnovogerais">Nome requisitante</label></h4>
                <input type="search" id="requisitantepopnovogerais" list="listarequisitantepopnovogerais" style="width: 100%;">
            </div>
            <datalist id="listarequisitantepopnovogerais"></datalist>

            <div class="grupo-block" id="camposselectpresopopnovogerais">
                <h4 class="titulo-grupo"><label for="selectpresopopnovogerais">Pesquise o Preso</label></h4>
                <div class="flex">
                    <div>
                        <label for="searchpresopopnovogerais">Cod.: </label>
                        <input type="search" id="searchpresopopnovogerais" list="listapresospopnovogerais" class="cod-search tempsearchpopnovogerais" autocomplete="off">
                    </div>
                    <div class="largura-restante flex">
                        <div class="margin-espaco-esq">
                            <label for="selectpresopopnovogerais">Preso: </label>
                        </div>
                        <div class="largura-restante">
                            <select id="selectpresopopnovogerais" class="presos tempselectpopnovogerais" style="width: 100%;"></select>
                        </div>
                        <div class="margin-espaco-esq">
                            <button id="btninserirpopnovogerais">Inserir</button>
                        </div>
                    </div>
                </div>
                <datalist id="listapresospopnovogerais"></datalist>
            </div>
            <div class="grupo-block listagem">
                <h4 class="titulo-grupo">Presos adicionados</h4>
                <table id="table-atendgerais">
                    <thead>
                        <tr>
                            <th>Ação</th>
                            <th class="centralizado min-width-100">Matrícula</th>
                            <th class="min-width-350">Nome</th>
                            <th class="centralizado min-width-100">Local</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <datalist id="listasituacaopopnovogerais"></datalist>
            </div>

            <div class="ferramentas align-rig">
                <button id="salvarpopnovogerais">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/chefia_novogerais_popup.js"></script>