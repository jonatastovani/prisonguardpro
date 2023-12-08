<!-- popUp para adicionar novo artigo -->

<div id="pop-novovis" class="body-popup">
    <div class="popup" id="popnovovis">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Inserir Visitante</h2>

            <fieldset id="divselectpresopopnovovis" class="grupo-block">
                <legend>Selecione o preso</legend>
                <div class="flex">
                    <div>
                        <label for="searchpresopopnovovis">Cod.: </label>
                        <input type="search" id="searchpresopopnovovis" class="cod-search" list="listapresospopnovovis" autocomplete="off">
                    </div>
                    <div class="largura-restante margin-espaco-esq">
                        <select id="selectpresopopnovovis" class="largura-total"></select>
                    </div>
                </div>
                <datalist id="listapresospopnovovis"></datalist>

            </fieldset>

            <div id="divdadospresopopnovovis" class="grupo-block">
                <h4 class="titulo-grupo">Dados do Preso</h4>
                Nome: <b><span id="nomepresopopnovovis">Selecione o Preso</span></b><br>
                Matrícula: <b><span id="matriculapresopopnovovis">Selecione o Preso</span></b>;
                Local: <b><span id="raiocelapresopopnovovis">Selecione o Preso</span></b>
            </div>

            <fieldset class="grupo-block">
                <legend>Dados do novo visitante</legend>

                <div class="grupo-block">
                    <h4 class="titulo-grupo"><label for="nomevisitantepopnovovis">Nome Visitante</label></h4>
                    <input type="search" id="nomevisitantepopnovovis" class="strpopnovovis" style="width: 100%;">
                </div>
                
                <div class="flex" style="align-items: baseline;">
                    <div class="largura-restante">
                        <div class="grupo">
                            <h4 class="titulo-grupo"><label for="searchgraupopnovovis">Grau de Parentesco</label></h4>
                            <div class="flex">
                                <div>
                                    <label for="searchgraupopnovovis">Cod.: </label>
                                    <input type="search" id="searchgraupopnovovis" list="listagraupopnovovis" class="cod-search tempsearchpopnovovis" autocomplete="off">
                                </div>
                                <div class="largura-restante flex">
                                    <div class="largura-restante">
                                        <select id="selectgraupopnovovis" class="grau margin-espaco-esq tempselectpopnovovis" style="width: 100%;"></select>
                                    </div>
                                </div>
                                <datalist id="listagraupopnovovis"></datalist>
                            </div>
                        </div>
                    </div>
                    <div class="align-rig margin-espaco-dir">
                        <button id="btninserirpopnovovis">Inserir</button>
                    </div>
                </div>

            </fieldset>

            <div class="grupo-block">
                <h4 class="titulo-grupo">Visitantes</h4>
                <div class="listagem max-height-200">
                    <table id="table-visitantesadicionados" class="nowrap">
                        <thead>
                            <tr>
                                <th>Ação</th>
                                <th>Nome</th>
                                <th>Parentesco</th>
                                <th>Situação Visitante</th>
                                <th>Situação Visitação</th>
                                <th>Data Cadastro</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div id="divvisitantesatigos" class="grupo-block">
                <h4 class="titulo-grupo">Visitantes passagem(ens) anterior(es)</h4>
                <div class="listagem max-height-200">
                    <table id="table-visitantesantigos">
                        <thead>
                            <tr>
                                <th>Ação</th>
                                <th>Nome</th>
                                <th>Parentesco</th>
                                <th>Situação Visitante</th>
                                <th>Situação Visitação</th>
                                <th>Data Cadastro</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="ferramentas align-rig">
                <button id="salvarpopnovovis">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/rol_novovisitante_popup.js"></script>