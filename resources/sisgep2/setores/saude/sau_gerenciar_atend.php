<?php
    $datainicio = date('Y-m-d');
    $datafinal = date('Y-m-d');
    // $datafinal = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),7,1));
    // //$datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('2022-04-29'),-1,1));
    // $datafinal = $datafinal->format('Y-m-d');
?>
<div class="titulo-pagina">
    <h1 id="titulo">Gerenciar Atendimentos</h1>
</div>

<div class="form">    
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div class="grupo">
            <h4 class="titulo-grupo">Intervalo de datas para a busca</h4>
            <div>
                <div class="inline">
                    <label for="datainicio">Início</label>
                    <input type="date" id="datainicio" value="<?=$datainicio?>">
                </div>
                <div class="inline">
                    <label for="datafinal" class="espaco-esq">Final</label>
                    <input type="date" id="datafinal" value="<?=$datafinal?>">
                </div>
            </div>
            <div>
                <input type="radio" name="tipodata" id="tipodataatend" value="1" class="margin-espaco-esq">
                <label for="tipodataatend">Data Agendamento</label>
                <input type="radio" name="tipodata" id="tipodatasolicitacao" value="2" checked class="margin-espaco-esq">
                <label for="tipodatasolicitacao">Data Solicitação</label>
            </div>
        </div>
        <div class="grupo largura-restante">
            <h4 class="titulo-grupo">Ordenação</h4>
            <input type="radio" name="ordem" id="ordemmatricula" value="1" checked>
            <label for="ordemmatricula">Matrícula</label>
            <input type="radio" name="ordem" id="ordemnome" value="2" class="margin-espaco-esq">
            <label for="ordemnome">Nome do Preso</label>
            <input type="radio" name="ordem" id="ordemdata" value="3" class="margin-espaco-esq">
            <label for="ordemdata">Data Agendamento</label>
            <input type="radio" name="ordem" id="ordemsolicitacao" value="4" class="margin-espaco-esq">
            <label for="ordemsolicitacao">Data Solicitação</label>
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
            <button id="openatendenf">Novo Atendimento</button>
            <button id="imp_req">Imp. Requisição</button>
        </div>
        <div class="align-rig">
            <button id="pesquisar-gerenciar">Pesquisar</button>
        </div>
    </div>

    <div class="listagem max-height-400">
        <table id="table-atend-gerenciar">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkall"></th>
                    <th>Ação</th>
                    <th style="min-width: 77px;">Matricula</th>
                    <th>Nome</th>
                    <th>Descrição Pedido</th>
                    <th>Raio/Cela</th>
                    <th>Data Atendimento</th>
                    <th>Tipo Atendimento</th>
                    <th>Data Solicitação</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php 
include_once "popups/saude_atendenf_popup.php";
?>
<script src="js/saude/sau_gerenciar_atend.js"></script>
