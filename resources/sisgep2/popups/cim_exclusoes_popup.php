<!-- popUp para adicionar novo artigo -->

<div id="pop-popexclusoes" class="body-popup">
    <div class="popup" id="popexclusoes">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2 id="titulopopexcl">Exclusão</h2>

            <div id="camposselectpreso">
                <div class="grupo-block">
                    <h4 class="titulo-grupo">Selecione o preso</h4>
                    <div class="flex">
                        <div class="inline">
                            <label for="searchpresospopexcl">ID Preso </label>
                            <input type="search" id="searchpresospopexcl" class="temppopexcl" list="listapresos" style="width: 90px;" autocomplete="off">
                        </div>
                        <div class="largura-restante">
                            <div class="flex">
                                <div><label for="selectpresospopexcl" class="espaco-esq">Preso:</label></div>
                                <div class="largura-restante">
                                    <select id="selectpresospopexcl" class="margin-espaco-esq" style="width: 100%;">
                                        <option value="0">Selecione o Preso</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <datalist id="listapresos"></datalist>
                </div>
            </div>
            <div id="dadospresopopexcl" class="grupo-block" hidden>
                <h4 class="titulo-grupo">Dados do Preso</h4>
                Nome: <b><span id="nomepopexcl">Nome do preso</span></b><br>
                Matrícula: <b><span id="matriculapopexcl">Matrícula</span></b>;
                Cela: <b><span id="raiocelapopexcl">Raio/Cela</span></b>
            </div>

            <div class="grupo-block">
                <h4 class="titulo-grupo">Tipo</h4>
                <label for="searchtipopopexcl">Cod.: </label>
                <input type="search" id="searchtipopopexcl" class="temppopexcl" list="listatipos" style="width: 90px;" autocomplete="off">
                <div class="inline">
                    <label for="selecttipopopexcl" class="espaco-esq">Tipo: </label>
                    <select id="selecttipopopexcl" class="margin-espaco-esq">
                        <option value="0">Selecione o Tipo</option>
                    </select>
                </div>
                <datalist id="listatipos"></datalist>
            </div>

            <div class="grupo-block">
                <h4 class="titulo-grupo">Motivo</h4>
                <div class="flex">
                    <div class="inline">
                        <label for="searchmotivopopexcl">Cod.: </label>
                        <input type="search" id="searchmotivopopexcl" class="temppopexcl" list="listamotivos" style="width: 90px;" autocomplete="off">
                    </div>
                    <div class="largura-restante">
                        <div class="flex">
                            <div><label for="selectmotivopopexcl" class="espaco-esq">Motivo: </label></div>
                            <div class="largura-restante">
                                <select id="selectmotivopopexcl" class="margin-espaco-esq" style="width: 100%;">
                                    <option value="0">Selecione o Motivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <datalist id="listamotivos"></datalist>
            </div>

            <div class="final-pagina">
                <button id="salvarpopexcl">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/cim_exclusoes_popup.js"></script>