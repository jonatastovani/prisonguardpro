<?php
    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = buscaIDsSetorPai();
    //Adicionar as permissões do Penal
    // $resultado = retornaPermissaoPenal(3,2);
    // foreach($resultado as $perm){
    //     array_push($permissoesNecessarias,$perm); //
    // }
    $blnPermitido = verificaPermissao($permissoesNecessarias);
    if($blnPermitido!==true){
        include_once "acesso_negado.php";
        exit();
    }
?>

<div class="titulo-pagina">
    <h1 id="titulo">Todos Funcionários</h1>
</div>

<div class="form">
    <div class="grupo" style="border: none; padding: 0; display: flex;">
        <div class="grupo">
            <h4 class="titulo-grupo"><label for="selectturno">Selecione o Turno</label></h4>
            <label for="selectturno">Turno: </label>
            <select id="selectturno"></select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Status</h4>
            <div class="inline">
                <input type="radio" name="status" id="rbbativos" value="1" checked>
                <label for="rbbativos">Ativos</label>
            </div>
            <div class="inline">
                <input type="radio" name="status" id="rbbinativos" value="0" class="margin-espaco-esq">
                <label for="rbbinativos">Inativos</label>
            </div>
            <div class="inline">
                <input type="radio" name="status" id="rbtodos" value="X" class="margin-espaco-esq">
                <label for="rbtodos">Todos</label>
            </div>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Busca</h4>
            <label for="textobusca">Digite: </label>
            <input type="text" id="textobusca">
            <button id="pesquisar" class="margin-espaco-esq">Pesquisar</button>
        </div>
    </div>
    <div class="flex ferramentas">
        <button id="openpopfuncionario" title="Inserir novo funcionário">Novo Funcionário</button>
    </div>

    <div class="listagem" style="height: 57vh;">
        <table id="table-todosfuncionarios">
            <thead>
                <tr>
                    <!-- <th><input type="checkbox" id="checkall"></th> -->
                    <th style="min-width: 50px;">Ação</th>
                    <th>Nome</th>
                    <th>Turno</th>
                    <th>Escala</th>
                    <th>Situação</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="js/funcionarios/func_gerenciar.js"></script>
<?php
include_once "popups/func_funcionarios_popup.php";

echo "<script>".$_SESSION['id_usuario']."</script>";
