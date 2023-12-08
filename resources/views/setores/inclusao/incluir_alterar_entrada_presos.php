<?php
    $acao = isset($_GET['acao'])?$_GET['acao']:'nova';
    $identradabuscar = isset($_POST['identradabuscar'])?$_POST['identradabuscar']:0;

    if($identradabuscar>0){
        $acao = 'alterar';
    }
    //$acao = 'alterar';
?>

<div class="titulo-pagina">
    <h1 id="titulo">Incluir Entrada de Presos</h1>
</div>

<div class="form">
    <input type="hidden" id="identradabuscar" value="<?=$identradabuscar?>">
    <input type="hidden" id="acao" value="<?=$acao?>">

    <div class="grupo">
        <label for="nova">Nova Entrada</label>
        <input type="radio" name="acao" id="nova" value="nova">
        <label for="alterar" class="espaco-esq">Alterar Entrada</label>
        <input type="radio" name="acao" id="alterar" value="alterar">
    </div>

    <div class="grupo">
        <div class="grupo" id="numeroentrada">
            <div class="inline">
                <label for="searchentrada">Cod. Entrada</label>
                <input type="search" id="searchentrada" list="listaentradas" class="cod-search">
                <datalist id="listaentradas"></datalist>
            </div>
            <div class="inline">
                <label for="selectentrada">Selecione a Entrada</label>
                <select id="selectentrada">
                    <option value="0">Selecione</option>
                </select>
            </div>
        </div>
        <div class="grupo">
            <label for="dataentrada">Data da Entrada</label>
            <input type="date" id="dataentrada">
            <input type="time" name="horaentrada" id="horaentrada">
        </div>
        <div class="grupo">
            <label for="searchorigem">Cód. Origem</label>
            <input type="search" name="searchorigem" id="searchorigem" list="listorigem" style="width: 90px;" autocomplete="off">
            <datalist id="listorigem"></datalist>
            <label for="selectorigem" class="espaco-esq">Origem</label>
            <select name="selectorigem" id="selectorigem">
                <option value="0">Selecione a Origem</option>
                <?php
                    echo $listaOrigem;
                ?>
            </select>
        </div>        
    </div>

    <div class="ferramentas">
        <button class="adicionarpreso">Novo Preso</button>
        <button id="novoartigo">Novo Artigo</button>
        <button class="salvarentrada">Salvar</button>
    </div>
    
    <!-- DIV para conter os presos a ser inserido -->
    <div id="presosinclusao" class="container-flex max-height-vh"></div>

    <!-- Datalist a ser usado nos artigos -->
    <datalist id="listaartigos"></datalist>
</div>

<?php 
    include_once "popups/novo_artigo_popup.php";
?>

<script src="js/inclusao/incluir_alterar_entrada_presos.js"></script>

