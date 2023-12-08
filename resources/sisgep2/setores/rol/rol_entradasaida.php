<?php
    $datainicio = date('Y-m-d');
    $datafinal = date('Y-m-d');
    // $datafinal = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),7,1));
    // //$datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('2022-04-29'),-1,1));
    // $datafinal = $datafinal->format('Y-m-d');
?>
<div class="titulo-pagina">
    <h1 id="titulo">Entrada de Visitantes</h1>
</div>

<div class="form">    
    <fieldset class="grupo">
        <legend>Tipo Movimetação</legend>
        <div>
            <input type="radio" name="tipomov" id="entrada" value="1" checked>
            <label for="entrada">Entrada</label>
            <input type="radio" name="tipomov" id="saida" value="2" class="margin-espaco-esq">
            <label for="saida">Saída</label>
        </div>
    </fieldset>
    <fieldset class="grupo" title="A 'Ação com um Click' efetua a entrada ou saída da visita caso haja somente um registro na busca. Se houver mais de um, é efetuado a pergunta de confirmação.">
        <legend>Ação com um Click</legend>
        <div class="flex">
            <div class="onoff">
                <input type="checkbox" class="toggle" id="onoffentradaclick">
                <label id="label" for="onoffentradaclick" title="Clique para ativar"></label>
            </div>
            <p id="estadoentradaclick" class="estadoonoff margin-espaco-esq" title="Clique para ativar">Desligado</p>
        </div>
    </fieldset>

    <div class="flex">

        <fieldset class="grupo opcoesentrada">
            <legend>Situação</legend>
            <div>
                <input type="radio" name="situacao" id="ativos" value="1" checked>
                <label for="ativos">Ativos</label>
                <input type="radio" name="situacao" id="inativos" value="2" class="margin-espaco-esq">
                <label for="inativos">Inativo</label>
                <input type="radio" name="situacao" id="todos" value="0" class="margin-espaco-esq">
                <label for="todos">Todos</label>
            </div>
        </fieldset>

        <fieldset class="grupo largura-restante">
            <legend>Ordenação</legend>
            <input type="radio" name="ordem" id="ordemmatricula" value="1" checked>
            <label for="ordemmatricula">Matrícula</label>
            <input type="radio" name="ordem" id="ordemnome" value="2" class="margin-espaco-esq">
            <label for="ordemnome">Nome do Preso</label>
            <input type="radio" name="ordem" id="ordemvisita" value="3" class="margin-espaco-esq">
            <label for="ordemvisita">Nome da Visita</label>
        </fieldset>
    </div>
    <div class="grupo-block">
        <label for="textobusca">Texto de Busca: </label>
        <input type="text" id="textobusca" class="tamanho-pequeno">
        <button id="pesquisar-gerenciar">Pesquisar</button>
    </div>

    <div class="ferramentas">
        <button id="opencelasvisitas">Configurações de Visitas</button>
    </div>

    <div class="listagem max-height-400">
        <table id="table-rol-entradasaida">
            <thead>
                <tr class="nowrap">
                    <th>Ação</th>
                    <th style="min-width: 77px;">Matricula</th>
                    <th>Nome Visitante</th>
                    <th>Nome Preso</th>
                    <th>Parentesco</th>
                    <th>Raio/Cela</th>
                    <th>RG</th>
                    <th>CPF</th>
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
    include_once "popups/rol_confirma_entrada_saida_popup.php";
    include_once("popups/rol_celas_visitas_popup.php");
?>
<script src="js/rol/rol_entradasaida.js"></script>
