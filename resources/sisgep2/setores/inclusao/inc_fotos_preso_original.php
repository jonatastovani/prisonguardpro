<?php
    $matric = isset($_POST['matric'])?$_POST['matric']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;

    if($matric==0 || $idpreso==0){ ?>
        <h1>Matrícula ou IDPreso não informado</h1> <?php
    }else{

        $matricula = midMatricula($matric,3); ?>

        <div class="titulo-pagina">
            <h1 id="titulo">Fotos Matrícula: <?=$matricula?></h1>
        </div>
        <input type="hidden" name="matric" id="matric" value="<?=$matric?>">
        <input type="hidden" name="idpreso" id="idpreso" value="<?=$idpreso?>">
        <div class="centralizado">
            <div class="form grupo">
                <div class="grupo-block">
                    <h4 class="titulo-grupo">Câmera</h4>
                    <video id="cam1" autoplay></video>
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo">Tipos de Fotos</h4>
                    <input type="radio" name="tipofoto" id="frontal" checked>
                    <label for="frontal">Foto Frontal</label>
                    <input type="radio" name="tipofoto" id="perfildir" class="margin-espaco-esq">
                    <label for="perfildir">Lateral Direita</label>
                    <input type="radio" name="tipofoto" id="perfilesq" class="margin-espaco-esq">
                    <label for="perfilesq">Lateral Esquerda</label>
                    <input type="radio" name="tipofoto" id="adicionais" class="margin-espaco-esq">
                    <label for="adicionais">Fotos Adicionais</label>
                </div>
                <div style="display: flex; justify-content: center; align-items: center;">
                    <button id="tirarfoto">Tirar Foto</button>
                </div>
                <div class="grupo-block">
                    <h4 class="titulo-grupo">Fotos</h4>
                    <div class="grupo overflow-y max-height-700" id="fotostiradas">
                        <div class="grupo">
                            <h4 class="titulo-grupo">Frontal</h4>
                            <canvas id="foto1" class="foto"></canvas>
                            <div class="linkfoto"></div>
                        </div>
                        <div class="grupo relative">
                            <h4 class="titulo-grupo">Perfil Direito</h4>
                            <canvas id="foto2" class="foto"></canvas>
                            <div class="linkfoto"></div>
                        </div>
                        <div class="grupo">
                            <h4 class="titulo-grupo">Perfil Esquerdo</h4>
                            <canvas id="foto3" class="foto"></canvas>
                            <div class="linkfoto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="final-pagina"><button id="salvar">Salvar</button></div>

        <script src="js/inclusao/inc_foto_preso.js"></script> <?php
    }
