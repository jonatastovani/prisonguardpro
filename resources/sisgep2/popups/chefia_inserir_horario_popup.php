<!-- popUp para adicionar novo artigo -->

<div id="pop-pophorario" class="body-popup">
    <div class="popup" id="pophorario">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2 id="titulo" >Alterar horário</h2>

            <div class="grupo-block">
                <div class="align-rig">
                    Horário Atual: <b><span id="horarioatualpoptime"></span></b>
                </div>
                <div class="align-rig">
                    <label for="usuariopoptime">Horário: </label>
                    <input type="time" id="horariopoptime" class="temppoptime">
                </div>
                <div class="align-rig">
                    <button id="confirmarpoptime">Confirmar</button>
                    <button id="cancelarpoptime">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/chefia_inserir_horario_popup.js"></script>