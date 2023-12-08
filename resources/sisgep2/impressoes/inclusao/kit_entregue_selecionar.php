
<div class="titulo-pagina">
    <h1 id="titulo">Imprimir Kit Entregue</h1>
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
        <!--<div class='item-flex form-impr-kit-entregue'>
            Nome: <b>Preso da Silva</b> <br>
            Matrícula: <b>1173554-5</b> <br>
            <div class='container-flex height-200'>
                <div class='item-flex largura-total'>
                    <input type='checkbox' id='inc3' value='idpreso'>
                    <label for='inc3' class="espaco-esq">
                        INCLUSAO POR TRANSITO - <b>12/04/2022</b><br>
                        Origem da Inclusão
                    </label>
                    <div id="kitsinc3" class='container-flex largura-total'>
                        <div class='item-flex largura-total'>
                            <input type='checkbox' id='kit3' value='idkit'>
                            <label for='kit3' class="espaco-esq">
                                kit tal
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
    <div class="final-pagina espaco-top-margin">
        <button id="imprimir">Imprimir</button>
    </div>
</div>

<script src="js/impressao/inclusao/kit_entregue_selecionar.js"></script>
