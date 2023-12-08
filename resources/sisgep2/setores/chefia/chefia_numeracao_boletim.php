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
    <h1 id="titulo">Boletim Informativo</h1>
</div>

<div class="form">
    <div id="botoesboletim">
        <button id="btnnovo" class="btngrande" title="Inicia um novo Boletim Informativo" style="background-color: lightgreen; color: black;" disabled>Iniciar Novo Boletim</button>
        <button id="btnliberar" class="btngrande" title="Libera a ação da contagem do fim de plantão nos raios. Não será possível efetuar movimentações que alteram a cela dos presos após liberar esta contagem." style="background-color: lightyellow; color: black;" disabled>Liberar contagem Fim de Plantão</button>
        <button id="btncancelar" class="btngrande" title="Cancela a liberação de contagem, excluindo todas confirmações de contagens já realizadas." style="background-color: lightcoral; color: black;" disabled>Cancelar contagem Fim de Plantão</button>
    </div>
    
    <div>
        <div class="grupo">
            <h4 class="titulo-grupo">Dados do Boletim vigente</h4>
            <div class="grupo">Número Boletim: <span id="numeracao" style="font-weight: bolder;"></span></div>
            <div class="grupo">Data: <span id="data" style="font-weight: bolder;"></span></div>
            <div class="grupo">Turno: <span id="turno" style="font-weight: bolder;"></span></div>
            <div class="grupo">Período: <span id="periodo" style="font-weight: bolder;"></span></div>
            <div>
                <div class="grupo">
                    Diretor: <select id="selectdiretor"></select>
                    <button id="atualizardiretores" class="margin-espaco-esq" title="Atualiza a lista de diretores caso tenha sido alterado os diretores">Atualizar Diretores</button>
                </div>
            </div>
        
            <div class="flex">
                <div  class="largura-restante">
                    <form action="principal.php?menuop=chef_funcionarios" method="get" target="_blank" class="inline">
                        <input type="hidden" name="menuop" value="chef_funcionarios">
                        <button type="submit" title="Incluir funcionários ou editar permissões">Adicionar Diretores</button>
                    </form>
                    <button id="imp_boletim" title="Imprimir Boletim Informativo Atual">Imp. Boletim</button>
                </div>
                <div>
                    <button id="salvarboletim" title="Salvar informações do Boletim">Salvar</button>
                </div>
            </div>
        </div>
    </div>    

    <div id="divtiposcontagem" class="grupo">
        <h4 class="titulo-grupo">Contagens</h4>
    </div>
    <div id="divacoestiposcontagem" class="grupo">
        <h4 class="titulo-grupo">Opções da Contagem da Passagem de Plantão</h4>
        <div class="divacoes"></div>
    </div>
    <div id="divcontagens" class="container-flex"></div>
    <datalist id="listafuncionario"></datalist>
</div>

<?php include_once('popups/func_autenticacao_popup.php')?>

<script src="js\chefia\chefia_numeracao_boletim.js"></script>