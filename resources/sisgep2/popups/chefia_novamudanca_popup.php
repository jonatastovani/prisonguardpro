<!-- popUp para adicionar novo artigo -->

<div id="pop-novamudanca" class="body-popup">
    <div class="popup" id="popnovamudanca">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Mudança de Cela</h2>

            <div class="grupo-block" id="camposselectpresopopnovamud">
                <h4 class="titulo-grupo"><label for="selectpresopopnovamud">Pesquise o Preso</label></h4>
                <div class="flex">
                    <div>
                        <label for="searchpresopopnovamud">Cod.: </label>
                        <input type="search" id="searchpresopopnovamud" list="listapresospopnovamud" class="cod-search tempsearchpopnovamud" autocomplete="off">
                    </div>
                    <div class="largura-restante flex">
                        <div>
                            <label for="selectpresopopnovamud" class="margin-espaco-esq">Preso: </label>
                        </div>
                        <div class="largura-restante">
                            <select id="selectpresopopnovamud" class="presos tempselectpopnovamud" style="width: 100%;"></select>
                        </div>
                    </div>
                </div>
                <datalist id="listapresospopnovamud"></datalist>
            </div>
            <div id="dadospresopopnovamud" class="grupo-block" hidden>
                <h4 class="titulo-grupo">Dados do Preso</h4>
                Nome: <b><span id="nomepopnovamud">Nome do preso</span></b><br>
                Matrícula: <b><span id="matriculapopnovamud">Matrícula</span></b>;
                Cela: <b><span id="raiocelapopnovamud">Raio/Cela</span></b>
            </div>
            <div>
                <div class="flex">
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="selectraiopopnovamud">Destino</label></h4>
                        <label for="selectraiopopnovamud">Raio: </label>
                        <select id="selectraiopopnovamud"></select>
                        <label for="selectcelapopnovamud">Cela: </label>
                        <select id="selectcelapopnovamud"></select>
                    </div>
                    <div class="grupo largura-restante">
                        <h4 class="titulo-grupo"><label for="situacaopopnovamud">Situação</label></h4>
                        <div class="flex">
                            <div>
                                <label for="situacaopopnovamud">Defina uma situação: </label>
                            </div>
                            <div class="largura-restante">
                                <select id="situacaopopnovamud" class="situacao tempselectpopnovamud" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="obspopnovamud">Observações</label></h4>
                <textarea id="obspopnovamud" style="width: 100%;" rows="2"></textarea>
            </div>
            <p style="font-size: 8pt; line-height: normal;">Obs.: Algumas situações são inseridas de maneiras automáticas conforme os dados inseridos. Ex: Se a cela destino for 0, automaticamente a situação é "Aguardando preenchimento de destino".</p>

            <div class="ferramentas align-rig">
                <button id="salvarpopnovamud">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/chefia_novamudanca_popup.js"></script>