
<div class="titulo-pagina">
    <h1 id="titulo">Imprimir Carteirinhas</h1>
</div>

<div class="grupo form">
    <div class="grupo">
        <h4 class="titulo-grupo"><label for="searchpreso">Pesquise o Preso</label></h4>
        <label for="searchpreso">Matrícula: </label>
        <input type="search" id="searchpreso" list="listapresos">
        <datalist id="listapresos"></datalist>
        <label for="selectpreso" class="espaco-esq">Preso: </label>
        <select id="selectpreso"></select>
        <button id="inserir">Inserir</button>
    </div>
    <div id="formularios" class="container-flex max-height-500"></div>
    <div class="final-pagina espaco-top-margin">
        <button id="imprimir">Imprimir</button>
    </div>
</div>

<script src="js/impressao/inclusao/carteirinha_selecionar.js"></script>
