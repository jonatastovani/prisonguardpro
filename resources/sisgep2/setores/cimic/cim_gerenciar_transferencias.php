<?php
    $datainicio = date('Y-m-d');
    $datafinal = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),7,1));
    //$datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('2022-04-29'),-1,1));
    $datafinal = $datafinal->format('Y-m-d');
?>
<div class="titulo-pagina">
    <h1 id="titulo">Gerenciar Transferências</h1>
</div>

<div class="form">    
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div class="grupo">
            <h4 class="titulo-grupo">Intervalo de datas para a busca</h4>
            <label for="datainicio">Início</label>
            <input type="date" id="datainicio" value="<?=$datainicio?>">
            <label for="datafinal" class="espaco-esq">Final</label>
            <input type="date" id="datafinal" value="<?=$datafinal?>">
        </div>
        <div class="grupo largura-restante">
            <h4 class="titulo-grupo">Ordenação</h4>
            <input type="radio" name="ordem" id="ordemmatricula" value="1" checked>
            <label for="ordemmatricula">Matrícula</label>
            <input type="radio" name="ordem" id="ordemnome" value="2" class="margin-espaco-esq">
            <label for="ordemnome">Nome do Preso</label>
            <input type="radio" name="ordem" id="ordemdata" value="3" class="margin-espaco-esq">
            <label for="ordemdata">Data Movimentação</label>
        </div>
    </div>
    <div class="grupo-block">
        <label for="textobusca">Texto de Busca: </label>
        <input type="text" id="textobusca" class="tamanho-pequeno">
        <div class="inline">
            <input type="radio" name="texto" id="dividirtexto" value="1" class="margin-espaco-esq" checked>
            <label for="dividirtexto">Dividir palavra na busca</label>
            <input type="radio" name="texto" id="todotexto" value="2" class="margin-espaco-esq">
            <label for="todotexto">Texto completo para busca</label>            
        </div>
        <div>
            <input type="radio" name="filtrotexto" id="buscaparte" value="1" checked>
            <label for="buscaparte">Qualuer parte</label>
            <input type="radio" name="filtrotexto" id="buscaexata" value="2" class="margin-espaco-esq">
            <label for="buscaexata">Busca exata</label>
            <input type="radio" name="filtrotexto" id="buscainicio" value="3" class="margin-espaco-esq">
            <label for="buscainicio">Iniciado por</label>
            <input type="radio" name="filtrotexto" id="buscafinal" value="4" class="margin-espaco-esq">
            <label for="buscafinal">Encerrado por</label>
        </div>
    </div>
    <div class="ferramentas">
        <button id="imp_envio">Imp Envio</button>
        <button class="margin-espaco-esq" id="imp_receb">Imp Recebimento</button>
        <button id="imp_escolta" class="margin-espaco-esq">Imp Escolta</button>
        <button id="imp_ordem" class="margin-espaco-esq">Imp Ordem</button>
        <button id="openrecebimentotrans" class="margin-espaco-esq">Inserir Retorno / Recebimento</button>
    </div>
    <div class="align-rig">
        <button id="pesquisar-gerenciar">Pesquisar</button>
    </div>

    <div class="listagem max-height-400">
        <table id="table-mov-gerenciar">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkall"></th>
                    <th>Ação</th>
                    <th style="min-width: 77px;">Matricula</th>
                    <th>Nome</th>
                    <th>Data</th>
                    <th>Unidade</th>
                    <th>Ofício</th>
                    <th>Ordem</th>
                    <th>Tipo Movimentação</th>
                    <th>Retorno</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php 
include_once "popups/cim_movimentacao_popup.php";
include_once "popups/cim_recebimento_popup.php";
?>
<script src="js/cimic/cim_gerenciar_transferencias.js"></script>
