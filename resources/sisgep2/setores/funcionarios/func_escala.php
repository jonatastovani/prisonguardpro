<?php
    
    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(58,59,60,61);
    $blnPermitido = verificaPermissao($permissoesNecessarias);
    if($blnPermitido!==true){
        include_once "acesso_negado.php";
        exit();
    }
    
    //Observação: a variável $idtipoescala é definida quando faz o include deste arquivo
?>

<div class="titulo-pagina">
    <h1 id="titulo">Escala de plantão</h1>
    <input type="hidden" id="idtipoescala" value="<?=$idtipoescala?>">
</div>

<div class="form">
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div class="grupo">
            <h4 class="titulo-grupo"><label for="selectturno">Selecione o Turno</label></h4>
            <label for="selectturno">Turno: </label>
            <select id="selectturno"></select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Tipo de Escala</h4>
            <input type="radio" name="modelo" id="rbdiaria" checked>
            <label for="rbdiaria">Escala Diária</label>
            <input type="radio" name="modelo" id="rbpadrao" class="margin-espaco-esq">
            <label for="rbpadrao">Escala Padrão</label>
        </div>
    </div>

    <div class="grupo-block" id="camposselectfuncionario">
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
                        <button id="inserir" title="Inserir funcionário na escala de plantão">Inserir funcionário</button>
                        <button id="atualizarlista" class="margin-espaco-esq" title="Atualiza lista de funcionários">Atualizar Lista</button>
                    </div>
                </div>
            </div>
        </div>
        <datalist id="listafuncionario"></datalist>
    </div>

    <div id="divfuncionarios" class="container-flex" style="height: 48vh;"></div>

   <datalist id="listapostos"></datalist>

    <div class="flex ferramentas">
        <div class="largura-restante">
            <button id="openpopfuncionario" title="Inserir novo funcionário no Banco de Dados">Novo Funcionário</button>
            <button id="imp_escala" class="margin-espaco-esq" title="Imprimir Escala">Imprimir</button>
        </div>
        <div>
            <button id="inserirpadrao" title="Inserir cópia da Escala Padrão">Inserir Escala Padrão</button>
            <button id="excluirescala" class="btn-excluir" title="Exclui a escala em edição" hidden>Escluir Escala</button>
            <button id="salvarescala" title="Salvar escala em edição">Salvar Escala</button>
        </div>
    </div>
</div>

<div id="pop-comentario" class="body-popup">
    <div class="popup" id="popcomentario">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Observações</h2>

            <input type="text" id="comentario" class="tamanho-medio">
        </div>
        <div class="final-pagina">
            <button id="salvarcomentario">Salvar</button>
        </div>
    </div>
</div>

<script src="js/funcionarios/func_escala.js"></script>
<?php
include_once "popups/func_funcionarios_popup.php";
