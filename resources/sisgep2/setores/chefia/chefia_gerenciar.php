<?php
    //Adicionar as permissões do Penal do Turno atual
    $permissoesNecessarias = retornaPermissaoPenal(1,2);
    
    $blnPermitido = verificaPermissao($permissoesNecessarias);
    if($blnPermitido!==true){
        include_once "acesso_negado.php";
        exit();
    }
?>

<div class="titulo-pagina">
    <h1 id="titulo">Gerenciar Chefia</h1>
</div>

<div class="form">
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div class="grupo">
            <h4 class="titulo-grupo">Selecione a visualização</h4>
            <select id="selectvisu"></select>
        </div>
        <div class="largura-restante">
            <div class="flex">
                <div class="grupo">
                    <h4 class="titulo-grupo">Ordenação</h4>
                    <input type="radio" name="ordem" id="ordemmatricula" value="1">
                    <label for="ordemmatricula">Matrícula</label>
                    <input type="radio" name="ordem" id="ordemnome" value="2" class="margin-espaco-esq">
                    <label for="ordemnome">Nome do Preso</label>
                    <input type="radio" name="ordem" id="ordemhorario" value="3" checked class="margin-espaco-esq">
                    <label for="ordemhorario">Horário</label>
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo">Visibilidade</h4>
                    <div class="inline">
                        <input type="checkbox" id="emaberto" checked>
                        <label for="emaberto">Em aberto</label>
                    </div>
                    <div class="inline">
                        <input type="checkbox" id="encerrado" class="margin-espaco-esq">
                        <label for="encerrado">Encerrado</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex ferramentas">
        <div id="botoesferramentas" class="largura-restante">
            <button id="btnabrirbtnspop">Visualizar População</button>
            <div id="btnspop" class="" hidden></div>
            <button id="imp_req" title="Imprimir Requisição">Imp. Requisição</button>
            <button id="imp_desig" title="Imprimir Designação de Trabalho">Imp. Designação</button>
            <button id="opennovamudanca" class="" title="Inserir Mudança de Cela">Ins. Mud. Cela</button>
            <button class="" id="opennovogerais" title="Inserir Atendimento Gerais">Ins. Atend. Gerais</button>
            <button class="" id="opennovoatend" title="Inserir Solicitação de Atendimento Enfermaria">Ins. Atend. Enf.</button>
            <button class="" id="openatend" title="Listar Atendimentos da Enfermaria solicitados">Atend. Enf.</button>
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
                    <th><input type="checkbox" id="checkall"></th>
                    <th style="min-width: 70px;">Ação</th>
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

<script src="js/chefia/chefia_gerenciar.js"></script>
<?php 
include_once "popups/chefia_book_popup.php";
include_once "popups/chefia_novamudanca_popup.php";
include_once "popups/chefia_atend_popup.php";
include_once "popups/chefia_novoatend_popup.php";
include_once "popups/chefia_novogerais_popup.php";
include_once "popups/chefia_semcela_popup.php";
include_once "popups/chefia_inserir_horario_popup.php";
?>
