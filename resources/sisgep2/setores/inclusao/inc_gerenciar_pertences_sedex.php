<?php
    $datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('Y-m-d'),-30,1));
    //$datainicio = DateTime::createFromFormat('Y-m-d H:i:s',retornaSomaDataEHora(date('2022-04-15'),-1,1));
    $datainicio = $datainicio->format('Y-m-d');
    $datafinal = date('Y-m-d');

    if($menuop=='inc_gerenciar_pertences'){
        //id dos tipos de pertences da tabela inc_pertencestipopertence
        $tipopertence = 2;
        $titulo = 'Gerenciar Pertences Guardados';
    }
    elseif($menuop=='inc_gerenciar_sedex'){
        //id dos tipos de pertences da tabela inc_pertencestipopertence
        $tipopertence = 3;
        $titulo = 'Gerenciar Sedex Retidos';
    }
?>
<div class="titulo-pagina">
    <h1 id="titulo"><?=$titulo?></h1>
</div>

<div class="form">
    <input type="hidden" id="tipopertencesedex" value="<?=$tipopertence?>">
    <div class="grupo-block">
        <h4 class="titulo-grupo">Intervalo de busca</h4>
        <label for="datainicio">Data Início</label>
        <input type="date" name="datainicio" id="datainicio" value="<?=$datainicio?>">
        <label for="datafinal" class="espaco-esq">Data Final</label>
        <input type="date" name="datafinal" id="datafinal" value="<?=$datafinal?>">
        <input type="radio" name="situacao" id="filtropendentes" value="1" class="margin-espaco-esq" checked>
        <label for="filtropendentes">Pendentes</label>
        <input type="radio" name="situacao" id="filtroretirados" value="2" class="margin-espaco-esq">
        <label for="filtroretirados">Retirados</label>
        <input type="radio" name="situacao" id="filtrodescartados" value="3" class="margin-espaco-esq">
        <label for="filtrodescartados">Doados</label>
        <input type="radio" name="situacao" id="filtrotodos" value="0" class="margin-espaco-esq">
        <label for="filtrotodos">Todos</label>
    </div>
    <div class="grupo-block relative">
        <h4 class="titulo-grupo">Filtro</h4>
        <label for="textobuscapertences">Texto Busca:</label>
        <input type="text" id="textobuscapertences" class="tamanho-pequeno">
        <input type="radio" name="filtrotexto" id="parcial" value="1" class="margin-espaco-esq" checked>
        <label for="parcial">Qualquer parte</label>
        <input type="radio" name="filtrotexto" id="exato" value="2" class="margin-espaco-esq">
        <label for="exato">Correspondência Exata</label>
        <button style="position: absolute; bottom: 0px; right: 5px;" id="pesq-gerenciar-pertences">Pesquisar</button>
    </div>

     <div class="ferramentas">
        <button id="novopertence"><img src="imagens/pertences-preso.png" class="imgBtnAcao"> Novo Pertence</button>
        <button id="selvencidos"><img src="imagens/checked.png" class="imgBtnAcao"> Selecionar Vencidos</button>
        <button id="descartartodos"><img src="imagens/lixeira.png" class="imgBtnAcao"> Descartar/Doar Selecionados</button>
        <!--<button id="impr-carteirinha"><img src="imagens/carteirinha.png" class="imgBtnAcao"> Carteirinha</button> -->
    </div>
    <div class="listagem max-height-400">
        <table id="table-pertences-gerenciar">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkall"></th>
                    <th>Ação</th>
                    <th>Número Pertence</th>
                    <th>Data Entrada</th>
                    <th>Nome Preso</th>
                    <th>Matricula</th>
                    <th>Raio</th>
                    <th>Nome Retirada</th>
                    <th>Grau Parentesco</th>
                    <th>Data Retirada</th>
                    <th>Observações</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php include_once "popups/pertences_popup.php";?>
<?php include_once "popups/pertences_novo_popup.php";?>
<?php include_once "popups/kitentregue_popup.php"; ?>
<script src="js/inclusao/inc_gerenciar_pertences.js"></script>
