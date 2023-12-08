<!-- popUp para adicionar novo artigo -->

<div id="pop-medass" class="body-popup">
    <div class="popup" id="popmedass">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Entrega de Medicamentos Assistidos</h2>

            <fieldset id="divselectpresopopmedass" class="grupo-block">
                <legend>Selecione o preso</legend>
                <div class="flex">
                    <div>
                        <label for="searchpresopopmedass">Cod.: </label>
                        <input type="search" id="searchpresopopmedass" class="cod-search" list="listapresospopmedass" autocomplete="off">
                    </div>
                    <div class="largura-restante margin-espaco-esq">
                        <select id="selectpresopopmedass" class="largura-total"></select>
                    </div>
                </div>
                <datalist id="listapresospopmedass"></datalist>
            </fieldset>

            <div id="divdadospresopopmedass" class="grupo-block">
                <h4 class="titulo-grupo">Dados do Preso</h4>
                Nome: <b><span id="nomepresopopmedass">Selecione o Preso</span></b><br>
                Matrícula: <b><span id="matriculapresopopmedass">Selecione o Preso</span></b>;
                Local: <b><span id="raiocelapresopopmedass">Selecione o Preso</span></b>
            </div>

            <fieldset class="grupo-block">
                <legend>Inserir/Alterar Assistidos</legend>
                
                <div class="flex" style="align-items: flex-end;">
                    <div class="largura-restante">
                        <fieldset class="grupo">
                            <legend><label for="searchmedicpopmedass">Medicamentos</label></legend>
                            <div class="flex">
                                <div>
                                    <label for="searchmedicpopmedass">Cod.: </label>
                                    <input type="search" id="searchmedicpopmedass" list="listamedicpopmedass" class="cod-search tempsearchpopmedass" autocomplete="off">
                                </div>
                                <div class="largura-restante flex">
                                    <div class="largura-restante">
                                        <select id="selectmedicpopmedass" class="selectmedicamentos margin-espaco-esq tempselectpopmedass"></select>
                                    </div>
                                </div>
                                <div class="margin-espaco-esq"><button id="openpopnovomedic" class="btnAcaoRegistro" alt="Ícone para adicionar medicamento" title="Novo Medicamento"><img src="imagens/adicionar.png" class="imgBtnAcao"></button></div>
                                <datalist id="listamedicpopmedass" class="listamedicamentos"></datalist>
                            </div>
                        </fieldset>

                        <fieldset class="grupo">
                            <legend>Períodos de Entrega</legend>
                            <div id="divfsperiodos" class="flex"></div>
                        </fieldset>

                    </div>
                    <div class="align-rig margin-espaco-dir">
                        <button id="btninserirpopmedass">Inserir</button>
                    </div>
                </div>

            </fieldset>

            <div id="divperiodospopmedass"></div>
            
            <div style="font-size: 8pt; line-height: 1em;">
                <p>*Medicamentos excluídos somente serão exclusos completamente desta lista caso não tenham sido entregue em nenhum momento para este preso, caso contrário será inserido a data término automaticamente para o último dia que foi realizada a entrega.</p>
            </div>
            <div class="ferramentas align-rig">
                <button id="salvarpopmedass">Confirmar</button>
                <button id="cancelarpopmedass" class="margin-espaco-esq">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php include_once "popups/sau_novomedicamento_popup.php"; ?>

<script src="js/popups/sau_medic_ass_popup.js"></script>