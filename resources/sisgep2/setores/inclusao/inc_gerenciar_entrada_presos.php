<?php
    $datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),-3,1));
    //$datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('2022-04-29'),-1,1));
    $datainicio = $datainicio->format('Y-m-d');
    // $datainicio = date('Y-m-d');
    $datafinal = date('Y-m-d');
?>
<div class="titulo-pagina">
    <h1 id="titulo">Gerenciar Entrada de Presos</h1>
</div>

<div class="form">    
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div class="grupo">
            <h4 class="titulo-grupo">Intervalo de busca</h4>
            <label for="datainicio">Data Início</label>
            <input type="date" name="datainicio" id="datainicio" value="<?=$datainicio?>">
            <label for="datafinal" class="espaco-esq">Data Final</label>
            <input type="date" name="datafinal" id="datafinal" value="<?=$datafinal?>">
            <div>
                <input type="radio" name="situacao" id="pendente" value="1" class="margin-espaco-esq" checked>
                <label for="pendente">Pendentes</label>
                <input type="radio" name="situacao" id="encerrados" value="2" class="margin-espaco-esq">
                <label for="encerrados">Encerrados</label>
                <input type="radio" name="situacao" id="todos" value="0" class="margin-espaco-esq">
                <label for="todos">Todos</label>
            </div>
        </div>
        <div class="grupo largura-restante">
            <h4 class="titulo-grupo">Ordenação</h4>
            <input type="radio" name="ordem" id="ordemmatricula" value="1" checked>
            <label for="ordemmatricula">Matrícula</label>
            <input type="radio" name="ordem" id="ordemnome" value="2" class="margin-espaco-esq">
            <label for="ordemnome">Nome do Preso</label>
            <input type="radio" name="ordem" id="ordemdata" value="3" class="margin-espaco-esq">
            <label for="ordemdata">Data Inclusão</label>
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
            <button id="impr-recibopresos"><img src="imagens/recibo-entrada-presos.png" class="imgBtnAcao"> Recibo de Presos</button>
            <button id="impr-digitaispreso"><img src="imagens/digitais.png" class="imgBtnAcao"> Digitais</button>
            <button id="impr-termodeclaracao"><img src="imagens/termo-declaracao.png" class="imgBtnAcao"> Termo Declaração</button>
            <button id="impr-carteirinha"><img src="imagens/carteirinha.png" class="imgBtnAcao"> Carteirinha</button>
        </div>
        <div class="align-rig">
            <button id="pesquisar-gerenciar">Pesquisar</button>
        </div>
    </div>

    <div class="listagem max-height-400">
        <table id="table-presos-gerenciar">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkall"></th>
                    <th>Ação</th>
                    <th style="min-width: 77px;">Matricula</th>
                    <th>Nome Preso</th>
                    <th>RG</th>
                    <th>Data Entrada</th>
                    <th>Origem</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php
    include_once "popups/kitentregue_popup.php";
    include_once("popups/rol_novovisitante_popup.php");    
?>
<script src="js/inclusao/inc_gerenciar_entrada_presos.js"></script>
