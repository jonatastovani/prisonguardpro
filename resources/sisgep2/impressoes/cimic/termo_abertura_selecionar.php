
<div class="titulo-pagina">
    <h1 id="titulo">Imprimir Termo de Abertura</h1>
</div>

<div class="grupo form">
    <div class="grupo">
        <label for="" class="titulo-grupo">Pesquise o Preso</label><br>
        
        <label for="searchmatricula">Pesquisa</label>
        <input type="search" id="searchmatricula" list="listapresos">
        <datalist id="listapresos"></datalist>

        <label for="selectmatricula" class="espaco-esq">Selecione</label>
        <select id="selectmatricula">
            <option value="0">Selecione o preso</option>
        </select>

        <button id="inserir">Inserir</button>
        
    </div>
    <div id="formularios" class="container-flex max-height-500">
        <!-- <div class='item-flex form-impr-termo-abertura'>
            Nome: <b>Preso da Silva</b> <br>
            Matrícula: <b>1173554-5</b> <br>
            <div class='container-flex height-200'>
                <div class='item-flex largura-total'>
                    <input type='checkbox' id='inc3' value='idpreso'>
                    <label for='inc3' class="espaco-esq">
                        INCLUSAO POR TRANSITO - <b>12/04/2022</b><br>
                        Origem da Inclusão
                    </label>
                </div>
            </div>
        </div> -->
    </div>
    <div class="final-pagina espaco-top-margin">
        <button id="imprimir">Imprimir</button>
    </div>
</div>

<script src="js/impressao/cimic/termo_abertura_selecionar.js"></script>
