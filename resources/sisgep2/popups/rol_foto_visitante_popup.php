<!-- popUp para adicionar novo artigo -->

<div id="pop-popfotovisitante" class="body-popup">
    <div class="popup" id="popfotovisitante">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Foto Visitante</h2>

            <div class="flex" style="align-items: center; justify-content: center;">
                <fieldset class="grupo largura-restante">
                    <legend>Informações do visitante</legend>

                    <span>Nome: <b><span id="nomevisitantepopfotovisitante" class="htmltemppopfotovisitante"></span></b></span><br>
                    <span>Data da Cadastro: <b><span id="datacadastropopfotovisitante" class="htmltemppopfotovisitante"></span></b></span>
                </fieldset>
                <div class="grupo centralizado">
                    <img id="fotovisitapopfotovisitante" class="fotovisita max-height-150" alt="foto_visitante">
                </div>
            </div>

            <fieldset class="grupo-block">
                <legend><label for="uploader">Selecione o arquivo:</label></legend>
                <input type="file" id="uploader" accept="image/jpeg,image/png"><br><br>
            </fieldset>

            <div id="divimgoriginal" class="centralizado divscanvas" hidden>
                <div class="grupo-block">
                    <h2>Imagem original</h2><br>
                    <div id="divcanvas" class="htmltemppopfotovisitante centralizado block" style="overflow: auto; max-height: 600px;"></div>
                </div>
            </div>

            <div id="divimgpreview" class="divscanvas centralizado" hidden>
                <div class="grupo">
                    <h2>Imagem a ser salva</h2><br>
                    <canvas id="preview" class="foto" style="width:340px;height:460px; border: 1px solid black; box-shadow: 3px 3px 2px rgba(0, 0, 0, 0.448); border-radius: 4px;"></canvas>
                </div>
            </div>


            <div class="ferramentas align-rig">
                <button id="baixar" class="btnsacaopopfotovisitante" hidden>Baixar</button>
                <button id="salvarfotovisitante" class="btnsacaopopfotovisitante" hidden>Salvar</button>
                <button id="cancelarpopfotovisitante">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/rol_foto_visitante_popup.js"></script>