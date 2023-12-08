
<div class="titulo-pagina">
    <h1 id="titulo">Imprimir Recibo Entrada de Presos</h1>
</div>

<div class="grupo form">
    <div class="grupo">
        <label for="" class="titulo-grupo">Pesquise a Entrada</label><br>
        <label for="searchentrada">Pesquisa</label>
        <input type="search" id="searchentrada" list="listaentradas">
        <datalist id="listaentradas"></datalist>
        <label for="selectentrada" class="espaco-esq">Selecione</label>
        <select name="selectentrada" id="selectentrada"></select>
        <button id="inserir">Inserir</button>
    </div>
    <div id="formularios" class="container-flex max-height-500">
        <!-- <div class='item-flex form-impr-entrada-presos'>
            <input type='checkbox' id='ent3' value='idpreso'>
            <label for='ent3' class="espaco-esq">
                Entrada nº: <b>9</b>; Presos inclusos: <b>4</b>; <br>
                Origem Entrada nº: <b>2819</b>, Nome Origem: <b>F.AUTOS ALAGOAS</b>; <br>
                Data/Hora Entrada: <b>20/04/2022 14:17</b>
            </label>
        </div>-->
    </div>
    <div class="final-pagina espaco-top-margin">
        <button id="imprimir">Imprimir</button>
    </div>
</div>

<script src="js/impressao/inclusao/entrada_presos_selecionar.js"></script>
