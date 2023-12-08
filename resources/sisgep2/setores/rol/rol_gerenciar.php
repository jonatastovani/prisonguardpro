<?php
    $datainicio = date('Y-m-d');
    $datafinal = date('Y-m-d');
    // $datafinal = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),7,1));
    // //$datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('2022-04-29'),-1,1));
    // $datafinal = $datafinal->format('Y-m-d');
?>
<div class="titulo-pagina">
    <h1 id="titulo">Gerenciar Visitantes</h1>
</div>

<div class="form">    
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div class="grupo">
            <h4 class="titulo-grupo">Situação</h4>
            <div>
                <input type="radio" name="situacao" id="ativos" value="1" class="margin-espaco-esq">
                <label for="ativos">Ativos</label>
                <input type="radio" name="situacao" id="inativos" value="2" class="margin-espaco-esq">
                <label for="inativos">Inativo</label>
                <input type="radio" name="situacao" id="todos" value="0" class="margin-espaco-esq" checked>
                <label for="todos">Todos</label>
            </div>
        </div>
        <div class="grupo largura-restante">
            <h4 class="titulo-grupo">Ordenação</h4>
            <input type="radio" name="ordem" id="ordemmatricula" value="1">
            <label for="ordemmatricula">Matrícula</label>
            <input type="radio" name="ordem" id="ordemnome" value="2" class="margin-espaco-esq">
            <label for="ordemnome">Nome do Preso</label>
            <input type="radio" name="ordem" id="ordemvisita" value="3" class="margin-espaco-esq" checked>
            <label for="ordemvisita">Nome da Visita</label>
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
        <div class="largura-restante"><button id="opennovovis">Adicionar Visitante</button></div>
        <div class="align-rig">
            <button id="pesquisar-gerenciar">Pesquisar</button>
        </div>
    </div>

    <div class="listagem max-height-400">
        <table id="table-rol-gerenciar">
            <thead>
                <tr class="nowrap">
                    <th>Ação</th>
                    <th style="min-width: 77px;">Matricula</th>
                    <th>Nome Visitante</th>
                    <th>Nome Preso</th>
                    <th>Parentesco</th>
                    <th>Raio/Cela</th>
                    <th>Situação Visitante</th>
                    <th>Situação para a Visitação</th>
                    <th>Data Aprovado</th>
                    <th>Data Cadastro</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php 
include_once "popups/rol_iniciocadastro_popup.php";
include_once "popups/rol_novovisitante_popup.php";
include_once "popups/rol_foto_visitante_popup.php";
?>
<script src="js/rol/rol_gerenciar.js"></script>
