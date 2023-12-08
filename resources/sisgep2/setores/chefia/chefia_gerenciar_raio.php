<?php

//Permissões necessárias para acessar a página de gerenciamento do raio
$permissoesNecessarias = [63,64,65,66,67,68];
$blnPermitidocomputador = verificaPermissaoComputador($permissoesNecessarias);
$blnPermitido = verificaPermissao($permissoesNecessarias);

// echo "<p>$blnPermitido ".__LINE__."</p>";
// echo "<p>$blnPermitidocomputador ".__LINE__."</p>";
if($blnPermitido!==true && $blnPermitidocomputador!==true){
    include_once "acesso_negado.php";
    exit();
}

?>

<div class="titulo-pagina">
    <h1 id="titulo">Gerenciar Raio</h1>
</div>

<div class="form">    
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <fieldset class="grupo">
            <legend>Visualização</legend>
            <select id="selectvisu"></select>
        </fieldset>
        <div class="largura-restante">
            <div class="flex">
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
                        <input type="radio" name="ordem" id="ordemhorario" value="3" checked class="margin-espaco-esq">
                        <label for="ordemhorario">Horário</label>
                    </div>
                </fieldset>
                <fieldset class="grupo">
                    <legend>Visibilidade</legend>
                    <div class="inline">
                        <input type="checkbox" id="emaberto" checked>
                        <label for="emaberto">Em aberto</label>
                    </div>
                    <div class="inline">
                        <input type="checkbox" id="encerrado" class="margin-espaco-esq">
                        <label for="encerrado">Encerrado</label>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    
    <div id="divbotoescontagens"></div>

    <div class="flex ferramentas">
        <div class="largura-restante">
            <div id="btnspop" class="inline relative"></div>
            <button id="opennovamudanca" title="Solicitar Mudança de Cela">Solic. Mudança Cela</button>
            <button class="margin-espaco-esq" id="opennovoatend" title="Solicitar Atendimento">Solic. Atendimento Enf.</button>
            <button class="margin-espaco-esq" id="openatend" title="Atendimentos do dia">Atendimentos Enf.</button>
        </div>
        <div class="align-rig">
            <button id="organizar-gerenciar" title="Clique para pesquisar">Pesquisar</button>
        </div>
    </div>
    <div id="divspop" class="relative"></div>

    <div class="listagem" style="height: 52vh;">
        <table id="table-mov-gerenciar">
            <thead>
                <tr>
                    <!-- <th><input type="checkbox" id="checkall"></th> -->
                    <th>Ação</th>
                    <th>Matricula</th>
                    <th>Nome</th>
                    <th>Horário</th>
                    <th>Cela</th>
                    <th>Destino</th>
                    <th>Tipo Movimentação</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php 
include_once "popups/chefia_book_popup.php";
include_once "popups/chefia_novamudanca_raio_popup.php";
include_once "popups/chefia_atend_raio_popup.php";
include_once "popups/chefia_novoatend_raio_popup.php";
include_once "popups/func_autenticacao_popup.php";
?>
<script src="js/chefia/chefia_gerenciar_raio.js"></script>
