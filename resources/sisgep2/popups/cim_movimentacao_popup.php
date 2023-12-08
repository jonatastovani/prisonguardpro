<!-- popUp para gerenciar kits entregues ao preso -->

<div id="pop-movimentacaotrans" class="body-popup">
    <div class="popup" id="movimentacaotrans">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Prévia de Transferência</h2>

            <div class="grupo-block">
                <h4 class="titulo-grupo">Dados do Preso</h4>
                Nome: <b><span id="nomepresoprevia">Nome do preso</span></b><br>
                Matrícula: <b><span id="matriculaprevia">Matrícula</span></b><br>
                Unidade: <b><span id="unidadeprevia">Unidade Origem</span></b>
            </div>
            <div class="grupo-block">
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="datamov">Data Transferência</label></h4>
                    <input type="date" id="datamov">
                </div>
                <div class="grupo-block">
                    <h4 class="titulo-grupo"><label for="selectorigem">Origem</label></h4>
                    <label for="searchorigem">Cod.: </label>
                    <input type="search" id="searchorigem" list="listaunidades" style="width: 90px;" autocomplete="off"><br>
                    <label for="selectorigem">Origem: </label>
                    <select id="selectorigem" class="unidades" style="width: 100%; max-width: 334px;">
                        <option value="0">Selecione a Origem</option>
                    </select>
                    <datalist id="listaunidades"></datalist>
                </div>
                <div class="grupo-block">
                    <h4 class="titulo-grupo"><label for="selecttipo">Tipo</label></h4>
                    <label for="searchtipo">Cod.: </label>
                    <input type="search" id="searchtipo" list="listatipos" style="width: 90px;" autocomplete="off"><br>
                    <label for="selecttipo">Tipo: </label>
                    <select id="selecttipo" class="tipos" style="width: 100%; max-width: 356px;">
                        <option value="0">Selecione o Tipo</option>
                    </select>
                    <datalist id="listatipos"></datalist>
                </div>
                <div class="grupo-block">
                    <h4 class="titulo-grupo"><label for="selectmotivo">Motivo</label></h4>
                    <label for="searchmotivo">Cod.: </label>
                    <input type="search" id="searchmotivo" list="listamotivos" style="width: 90px;" autocomplete="off"><br>
                    <label for="selectmotivo">Motivo: </label>
                    <select id="selectmotivo" style="width: 100%; max-width: 340px;">
                        <option value="0">Selecione o Tipo</option>
                    </select>
                    <datalist id="listamotivos"></datalist>
                </div>
                <div>
                    <input type="checkbox" id="ckbpresoseguro">
                    <label for="ckbpresoseguro">Preso do Seguro (M.P.S.P.)</label>
                </div>
            </div>
            
            <div class="final-pagina">
                <button id="salvarmovimentacaotrans">Salvar</button>
                <button id="cancelarmovimentacaotrans">Cancelar</button>
            </div>

        </div>
    </div>
</div>

<script src="js/popups/cim_movimentacao_popup.js"></script>