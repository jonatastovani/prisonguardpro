<?php
    // $datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),-3,1));
    //$datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('2022-04-29'),-1,1));
    // $datainicio = $datainicio->format('Y-m-d');
    $datainicio = date('Y-m-d');
    $datafinal = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),1,1));
    $datafinal = $datafinal->format('Y-m-d');
    // $datafinal = date('Y-m-d');
?>

<div class="titulo-pagina">
    <h1 id="titulo">Gerenciar Atendimentos</h1>
</div>

<div class="form">
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div class="grupo">
            <h4 class="titulo-grupo"><label for="selecttipoatend">Selecione o Atendimento</label></h4>
            <label for="selecttipoatend">Tipo: </label>
            <select id="selecttipoatend"></select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Intervalo de busca</h4>
            <label for="datainicio">Data Início</label>
            <input type="date" id="datainicio" value="<?=$datainicio?>">
            <label for="datafinal" class="espaco-esq">Data Final</label>
            <input type="date" id="datafinal" value="<?=$datafinal?>">
            <button id="pesquisar" class="inline margin-espaco-esq" title="Clique para pesquisar">Pesquisar</button>
        </div>
    </div>
    <div class="flex ferramentas">
        <button id="opennovogerais" title="Inserir Atendimento">Inserir Atendimento Gerais</button>
    </div>

    <div class="listagem" style="height: 50vh;">
        <table id="table-atend">
            <thead>
                <tr>
                    <!-- <th><input type="checkbox" id="checkall"></th> -->
                    <th style="min-width: 50px;">Ação</th>
                    <th>Data</th>
                    <th>Requisitante</th>
                    <th>Tipo de atendimento</th>
                    <th>Qtd. Presos</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="js/chefia/chefia_gerenciar_atend.js"></script>
<?php 
include_once "popups/chefia_novoatend_popup.php";
include_once "popups/chefia_novogerais_popup.php";
?>
