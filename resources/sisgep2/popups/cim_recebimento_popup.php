<!-- popUp para adicionar novo artigo -->

<div id="pop-recebimentotrans" class="body-popup">
    <div class="popup" id="poprecebimentotrans">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Recebimento / Retorno de Trânsito</h2>

            <div class="grupo-block">
                <h4  class="titulo-grupo"><label for="selectpresopopreceb">Pesquise o Preso</label></h4>
                <div class="flex">
                    <div>
                        <label for="searchpresopopreceb">Cod.: </label>
                        <input type="search" id="searchpresopopreceb" class="cod-search tempsearchpopreceb" list="listapresospopreceb">
                    </div>
                    <div class="largura-restante margin-espaco-esq flex">
                        <div>
                            <label for="selectpresopopreceb" class="margin-espaco-esq">Preso: </label>
                        </div>
                        <div class="largura-restante">
                            <select id="selectpresopopreceb" class="presos  tempselectpopreceb" style="width: 100%;"></select>
                        </div>
                    </div>
                </div>
                <datalist id="listapresospopreceb"></datalist>
            </div>
            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="selecttipopopreceb">Tipo de Movimentação</label></h4>
                <div class="flex">
                    <div>
                        <label for="searchtipopopreceb">Cod.: </label>
                        <input type="search" id="searchtipopopreceb" list="listatipospopreceb" class="cod-search tempsearchpopreceb" autocomplete="off"><br>
                    </div>
                    <div class="largura-restante margin-espaco-esq flex">
                        <div>
                            <label for="selecttipopopreceb" class="margin-espaco-esq">Tipo: </label>
                        </div>
                        <div class="largura-restante">
                            <select id="selecttipopopreceb" class="tipos  tempselectpopreceb" style="width: 100%;">
                                <option value="0">Selecione o Tipo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <datalist id="listatipospopreceb"></datalist>
            </div>
            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="selectmotivopopreceb">Motivo da Movimentação</label></h4>
                <div class="flex">
                    <div>
                        <label for="searchmotivopopreceb">Cod.: </label>
                        <input type="search" id="searchmotivopopreceb" list="listamotivospopreceb" class="cod-search tempsearchpopreceb" autocomplete="off"><br>
                    </div>
                    <div class="largura-restante margin-espaco-esq flex">
                        <div>
                            <label for="selectmotivopopreceb" class="margin-espaco-esq">Motivo: </label>
                        </div>
                        <div class="largura-restante">
                            <select id="selectmotivopopreceb" class=" tempselectpopreceb" style="width: 100%;">
                                <option value="0">Selecione o Tipo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <datalist id="listamotivospopreceb"></datalist>
            </div>
            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="selectorigempopreceb">Origem</label></h4>
                <div class="flex">
                    <div>
                        <label for="searchorigempopreceb">Cod.: </label>
                        <input type="search" id="searchorigempopreceb" list="listaunidadespopreceb" class="cod-search tempsearchpopreceb" autocomplete="off"><br>
                    </div>
                    <div class="largura-restante margin-espaco-esq flex">
                        <div>
                            <label for="selectorigempopreceb" class="margin-espaco-esq">Origem: </label>
                        </div>
                        <div class="largura-restante">
                            <select id="selectorigempopreceb" class="unidades  tempselectpopreceb" style="width: 100%;">
                                <option value="0">Selecione a Origem</option>
                            </select>
                        </div>
                    </div>
                </div>
                <datalist id="listaunidadespopreceb">
                <option value="0">Selecione a Origem</option>
                    <optgroup label="Retorno">
                        <option value="1">Teste</option>
                    </optgroup>
                </datalist>
            </div>
            <div class="flex" style="align-items: center;">
                <div class="div-metade-aling-esquerda">
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="datamovpopreceb">Data Recebimento</label></h4>
                        <input type="date" id="datamovpopreceb">
                    </div>
                        <input type="checkbox" id="ckbseguropopreceb" class="margin-espaco-esq">
                        <label for="ckbseguropopreceb">Preso Seguro</label>
                </div>
                <div class="div-metade-aling-direita">
                    <button id="salvarpopreceb">Inserir Recebimento</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/cim_recebimento_popup.js"></script>