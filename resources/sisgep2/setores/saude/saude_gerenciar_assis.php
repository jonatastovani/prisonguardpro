<?php
    $datainicio = date('Y-m-d');
    $datafinal = date('Y-m-d');
    // $datafinal = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),7,1));
    // $datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),-10,1));
    // $datainicio = $datainicio->format('Y-m-d');
?>
<div class="titulo-pagina">
    <h1 id="titulo">Gerenciar Medicamentos Assistidos</h1>
</div>

<div class="form">    
    <div class="flex">
        <fieldset class="grupo">
            <legend>Intervalo de busca</legend>
            <label for="datainicio">Início</label>
            <input type="date" id="datainicio" value="<?=$datainicio?>">
            <label for="datafinal" class="espaco-esq">Final</label>
            <input type="date" id="datafinal" value="<?=$datafinal?>">
            <div class="centralizado">
                <div class="inline">
                    <input type="radio" name="situacao" id="pendente" value="1">
                    <label for="pendente">Pendentes</label>
                </div>
                <div class="inline">
                    <input type="radio" name="situacao" id="entregue" value="2" class="margin-espaco-esq">
                    <label for="entregue">Entregues</label>
                </div>
                <div class="inline">
                    <input type="radio" name="situacao" id="todos" value="0" class="margin-espaco-esq" checked>
                    <label for="todos">Todos</label>
                </div>
            </div>
        </fieldset>
        <fieldset class="grupo">
            <legend>Ordenação</legend>
            <div class="inline">
                <input type="radio" name="ordem" id="ordemmatricula" value="1">
                <label for="ordemmatricula">Matrícula</label>
            </div>
            <div class="inline">
                <input type="radio" name="ordem" id="ordemnome" value="2" class="margin-espaco-esq">
                <label for="ordemnome">Nome do Preso</label>
            </div>
            <div class="inline">
                <input type="radio" name="ordem" id="ordemraio" value="3" class="margin-espaco-esq" checked>
                <label for="ordemraio">Raio</label>
            </div>
        </fieldset>
        <fieldset class="grupo">
            <legend>Período de entrega</legend>
            <div class="inline">
                <input type="radio" name="periodo" id="periodomanha" value="1">
                <label for="periodomanha">Manhã</label>
            </div>
            <div class="inline">
                <input type="radio" name="periodo" id="periodotarde" value="2" class="margin-espaco-esq">
                <label for="periodotarde">Tarde</label>
            </div>
            <div class="inline">
                <input type="radio" name="periodo" id="periodonoite" value="3" class="margin-espaco-esq">
                <label for="periodonoite">Noite</label>
            </div>
            <div class="inline">
                <input type="radio" name="periodo" id="periodotodos" value="0" class="margin-espaco-esq" checked>
                <label for="periodotodos">Todos</label>
            </div>
        </fieldset>
    </div>
    <div class="grupo-block">
        <label for="textobusca">Texto de Busca: </label>
        <input type="text" id="textobusca" class="tamanho-pequeno">
        <div class="inline">
            <input type="radio" name="opcaobusca" id="dividirtexto" value="1" class="margin-espaco-esq" checked>
            <label for="dividirtexto">Dividir palavra na busca</label>
            <input type="radio" name="opcaobusca" id="todotexto" value="2" class="margin-espaco-esq">
            <label for="todotexto">Texto completo para busca</label>            
        </div>
        <div>
            <input type="radio" name="filtrotexto" id="buscaparte" value="1" checked>
            <label for="buscaparte">Qualquer parte</label>
            <input type="radio" name="filtrotexto" id="buscaexata" value="2" class="margin-espaco-esq">
            <label for="buscaexata">Busca exata</label>
            <input type="radio" name="filtrotexto" id="buscainicio" value="3" class="margin-espaco-esq">
            <label for="buscainicio">Iniciado por</label>
            <input type="radio" name="filtrotexto" id="buscafinal" value="4" class="margin-espaco-esq">
            <label for="buscafinal">Encerrado por</label>
        </div>
    </div>
    <div class="ferramentas flex">
        <div class="largura-restante">
            <button id="openpopmedass" title="Adicionar medicamentos de entrega assistida">Adicionar Assistido</button>
            <button id="entsel" class="margin-espaco-esq" title="Entregar medicamento(s) para o(s) preso(s) no(s) respectivo(s) período(s) selecionado(s)">Entregar selecionado(s)</button>
            <button id="impsel" class="margin-espaco-esq" title="Imprimir medicamento(s) entregue(s) para o(s) preso(s) no(s) respectivo(s) período(s) selecionado(s)">Imprimir</button>
            <button id="implistaassistidos" class="margin-espaco-esq" title="Imprimir listagem de presos que recebem medicação de forma assistida">Relação de Assistidos</button>
        </div>
        <div class="align-rig">
            <button id="pesquisar-gerenciar">Pesquisar</button>
        </div>
    </div>

    <div class="listagem max-height-400">
        <table id="table-assistidos-gerenciar">
            <thead>
                <tr class="nowrap">
                    <th><input type="checkbox" id="checkall"></th>
                    <th>Ação</th>
                    <th style="min-width: 77px;">Matricula</th>
                    <th>Nome</th>
                    <th>Local</th>
                    <th>Período</th>
                    <th>Qtd Medicamentos</th>
                    <th>Data Entregue</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php 
    include_once "popups/sau_medic_ass_popup.php";
    include_once "popups/sau_visu_medic_ass_popup.php";
?>
<script src="js/saude/saude_gerenciar_assis.js"></script>
