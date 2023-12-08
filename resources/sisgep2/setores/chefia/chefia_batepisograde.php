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
    <h1 id="titulo">Bate Piso / Bate Grade</h1>
</div>

<div class="form">
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div id="divtiposproced" class="grupo">
            <h4 class="titulo-grupo">Tipo de Procedimento</h4>
        </div>
    </div>

    <div class="grupo-block" id="camposselectfuncionario" hidden>
        <h4 class="titulo-grupo"><label for="selectfuncionario">Pesquise o Funcionário</label></h4>
        <div class="flex">
            <div>
                <label for="searchfuncionario">Cod.: </label>
                <input type="search" id="searchfuncionario" list="listafuncionario" class="cod-search tempsearchfuncionario" autocomplete="off">
            </div>
            <div class="largura-restante flex">
                <div>
                    <label for="selectfuncionario" class="margin-espaco-esq">Funcionário: </label>
                </div>
                <div class="largura-restante flex">
                    <div class="largura-restante"><select id="selectfuncionario" style="width: 100%;"></select></div>
                    <div class="margin-espaco-esq">
                        <button id="inserir" title="Inserir funcionário na escala do procedimento">Inserir funcionário</button>
                        <button id="atualizarlista" class="margin-espaco-esq" title="Atualiza lista de funcionários">Atualizar Lista</button>
                    </div>
                </div>
            </div>
        </div>
        <datalist id="listafuncionario"></datalist>
    </div>

    <div id="divfuncionarios" class="container-flex" style="height: 48vh;"></div>

    <div class="flex ferramentas">
        <div class="largura-restante">
            <button id="imp_proced" title="Imprimir o procedimento que está em edição">Imprimir procedimento</button>
        </div>
        <div>
            <button id="salvarbatepisograde" title="Salvar procedimento em edição">Salvar Procedimento</button>
        </div>
    </div>
</div>

<?php include_once('popups/chefia_raios_selecionar_popup.php'); ?>
<script src="js/chefia/chefia_batepisograde.js"></script>

