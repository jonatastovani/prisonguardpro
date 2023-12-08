<?php

$idbancoatend = isset($_POST['idbancoatend'])?$_POST['idbancoatend']:0;

if($idbancoatend==0){
    echo "<h1>O ID do Atendimento não foi informado.</h1>";
    exit;
}?>

<div class="titulo-pagina">
    <h1 id="titulo">Atendimento Preso</h1>
    <input type="hidden" name="tipoacao" id="tipoacao" value="<?=$tipoacao?>">
</div>
<div class="form">
    <input type="hidden" id="idbancoatend" value="<?=$idbancoatend?>">
    <div class="grupo-block">
        <h4 class="titulo-grupo">Dados do Preso</h4>
        <span>Matrícula: <b><span id="matricula"></span></b></span>
        <span class="margin-espaco-esq">Nome: <b><span id="nome"></span></b></span><br>
        <span>Mãe: <b><span id="mae"></span></b></span>
        <span class="margin-espaco-esq">Pai: <b><span id="pai"></span></b></span><br>
        <span>Raio/Cela: <b><span id="raiocela"></span></b></span>
    </div>
    
    <div class="grupo-block">
        <h4 class="titulo-grupo">Informações do Atendimento</h4>

        <span>Data da Solicitação: <b><span id="datasolic"></span></b></span>
        <span class="margin-espaco-esq">Data do Atendimento: <b><span id="dataatend"></span></b></span><br>
        <span>Requisitante: <b><span id="requisitante"></span></b></span>

        <div class="grupo-block">
            <h4 class="titulo-grupo">Descrição da Solicitação (Pedido do preso no raio)</h4>
            <textarea id="descpedido" class="largura-total height-50" disabled></textarea>
        </div>

        <div class="grupo-block">
            <h4 class="titulo-grupo">Descrição do Atendimento (Informações sobre o atendimento)</h4>
            <textarea id="descatend" class="largura-total height-100"></textarea>

            <div id="mensagens-prontas">
                <button id="atendreal" title="Atendimento realizado.">Atendimento Realizado</button>
                <button id="aguardaragend" title="Não solicitar atendimento para o mesmo problema. Preso deve aguardar o agendamento da enfermaria.">Aguardando agendamento enfermaria</button>
            </div>
        </div>
        
        <div class="grupo-block">
            <h4 class="titulo-grupo">Observações (Observações somente para enfermaria)</h4>
            <textarea id="observacoes" class="largura-total height-50"></textarea>
        </div>

        <div class="grupo-block">
            <h4 class="titulo-grupo">Medicamentos Entregues ou Aplicados</h4>
            <div class="flex">
                <div class="inline">
                    <label for="searchmedic">Cod. </label>
                    <input type="search" id="searchmedic" class="cod-search" list="listamedicamentos">
                </div>
                <div class="largura-restante margin-espaco-esq">
                    <select id="selectmedic" style="width: 100%;" class="selectmedicamentos"></select>                    
                </div>
                <div class="inline margin-espaco-esq">
                    <button id="inserirmedic">Inserir</button>
                    <button id="openpopnovomedic" class="margin-espaco-esq">Novo Medicamento</button>
                </div>
                <datalist id="listamedicamentos" class="listamedicamentos"></datalist>
            </div>

            <div id="containermedicamentos" class="container-flex"></div>

        </div>
        
    </div>
    
    <div class="final-pagina">
        <button id="salvaratendimento">Salvar</button>
    </div>
</div>

<?php include_once "popups/sau_novomedicamento_popup.php"; ?>

<script src="js/saude/sau_atendimento.js"></script>

