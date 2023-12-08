<!-- popUp para gerenciar kits entregues ao preso -->

<div id="pop-novopertence" class="body-popup">
    <div class="popup" id="novo-pertence">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Novo Pertence</h2>
            <div class="grupo-block" id="divselPresoNovoPert" hidden>
                <h4 class="titulo-grupo">Selecione o Preso</h4>
                <label for="searchpresosnovopertence">Pesq. ID Preso</label>
                <input type="search" id="searchpresosnovopertence" style="width: 100px;" list="listapresosnovopertence">
                <datalist id="listapresosnovopertence"></datalist>
                <select id="selectpresosnovopertence" class="largura-total"></select>
            </div>

            <div class="grupo-block">
                <h4 class="titulo-grupo">Dados do Preso</h4>
                Nome: <b><span id="nomepresonovopertence">Selecione o Preso</span></b><br>
                Matrícula: <b><span id="matriculapresonovopertence">Selecione o Preso</span></b>;
                Local: <b><span id="raiocelapresonovopertence">Selecione o Preso</span></b>
            </div>
            <div class="grupo-block">
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="novodataentrada">Data da Entrada</label></h4>
                    <input type="date" id="novodataentrada" value="<?php echo date('Y-m-d')?>">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo">Tipo de Pertence</h4>
                    <b><span id="tiponovopertence" data-tipo="0">Tipo Pertence</span></b>
                </div>
            </div>

            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="obsnovopertence">Observações</label></h4>
                <textarea name="obsnovopertence" id="obsnovopertence" cols="30" rows="2" class="largura-total overflow-y"></textarea>
            </div>
            
            <div class="final-pagina">
                <button id="salvarnovopertence">Salvar</button>
                <button id="cancelarnovopertence">Cancelar</button>
            </div>

        </div>
    </div>
</div>

<script src="js/popups/pertences_novo_popup.js"></script>