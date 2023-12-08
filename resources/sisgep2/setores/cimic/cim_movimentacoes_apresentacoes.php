<?php
    //ID para buscar caso venha direcionado por outra página
    $idbuscar = isset($_POST['ordempost'])?$_POST['ordempost']:0;
?>

<div class="titulo-pagina">
    <h1 id="titulo">Incluir Apresentação Externa</h1>
</div>

<div class="form">
    <div class="grupo">
        <label for="incluir">Nova Apresentação</label>
        <input type="radio" name="acao" id="incluir" value="incluir" checked>
        <label for="alterar" class="espaco-esq">Alterar Apresentação</label>
        <input type="radio" name="acao" id="alterar" value="alterar">
        <input type="hidden" id="ordempost" value="<?=$idbuscar?>">
    </div>

    <div class="grupo-block">
        <h4 class="titulo-grupo">Dados da Ordem de Saída</h4>
        <div class="grupo-block">
            <label for="searchordem">Cód. Ordem</label>
            <input type="search" id="searchordem" list="listaordem" style="width: 90px;" autocomplete="off">
            <datalist id="listaordem"></datalist>
            <label for="selectordem" class="margin-espaco-esq">Selecione a Ordem</label>
            <select id="selectordem" style="width: 100%; max-width: 460px;">
                <option value="0">Selecione</option>
            </select>
        </div>
        <div class="grupo-block">
            <label for="searchdestino">Cód. Destino</label>
            <input type="search" name="searchdestino" id="searchdestino" list="listadestino" style="width: 90px;" autocomplete="off">
            <datalist id="listadestino"></datalist>
            <label for="selectdestino" class="espaco-esq">Destino</label>
            <select id="selectdestino" class="locaisdestinos" style="width: 100%; max-width: 540px;">
                <option value="0">Selecione o Destino</option>
            </select>
        </div>
        <div class="grupo">
            <label for="datasaida">Data da Saída</label>
            <input type="date" id="datasaida">
            <input type="time" name="horasaida" id="horasaida">
        </div>
        <div>
            <span id="ordemsaida"></span>
        </div>
        <div>
            <span id="oficioescolta"></span>
        </div>
        <div id="botoesimpressao"></div>
    </div>

    <div class="grupo-block">
        <h4 class="titulo-grupo">Selecione o preso</h4>
        <label for="searchpresos">ID Preso </label>
        <input type="search" id="searchpresos" list="listapresos" style="width: 90px;" autocomplete="off">
        <div class="inline">
            <label for="selectpresos" class="espaco-esq">Preso </label>
            <select id="selectpresos">
                <option value="0">Selecione o Preso</option>
            </select>
        </div>
        <button class="adicionarpreso">Inserir Preso</button>
    </div>

    <div class="ferramentas">
        <button id="novolocal">Inserir/Alterar Local Apres.</button>
        <button class="salvarordemsaida margin-espaco-esq
margin-espaco-esq">Salvar</button>
    </div>

    <!-- DIV para conter os presos a ser inserido -->
    <div id="presosordemsaida" class="container-flex max-height-vh"></div>

    <datalist id="listapresos"></datalist>
    <datalist id="listatipos"></datalist>
    <datalist id="listaapresentacao"></datalist>
    <datalist id="listamotivosapres"></datalist>
</div>

<?php 
    //include_once "popups/novo_artigo_popup.php";
?>

<script src="js/cimic/cim_movimentacoes_apresentacoes.js"></script>

