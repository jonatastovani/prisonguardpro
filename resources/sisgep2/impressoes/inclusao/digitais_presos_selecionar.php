
<div class="titulo-pagina">
    <h1 id="titulo">Imprimir Ficha de Digitais</h1>
</div>

<div class="grupo form">
    <div class="grupo">
        <label for="" class="titulo-grupo">Pesquise o Preso</label><br>
        <label for="searchmatricula">Pesquisa</label>
        <input type="search" name="searchmatricula" id="searchmatricula" list="listapresos">
        <datalist id="listapresos"></datalist>
        <label for="selectmatricula" class="espaco-esq">Selecione</label>
        <select name="selectmatricula" id="selectmatricula"></select>
        <button id="inserir">Inserir</button>
    </div>
    <div id="formularios" class="container-flex max-height-500">
        <!-- <div class='item-flex form-impr-termo-abertura' id='"+result[0].MATRICULA+"'>Nome: <b>"+result[0].NOME+"</b> <br>Matrícula: <b>"+midMatricula(result[0].MATRICULA,3)+"</b> <br><div class='container-flex height-200'>"+inclusoes+"</div></div>
        </div> -->
    </div>
    <div class="final-pagina espaco-top-margin">
        <button id="imprimir">Imprimir</button>
    </div>
</div>

<script src="js/impressao/inclusao/digitais_presos_selecionar.js"></script>
