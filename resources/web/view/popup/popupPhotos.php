
<div id="pop-popPhoto" class="body-popup">
    <div class="popup" id="popPhoto">
        <div class="close-btn">&times;</div>
        <div class="container mt-5">
            <div class="form">
                <h2 class="text-center" id="titlePopPhoto">Seleção de Foto</h2>

                <div class="form-group mt-5">
                    <div class="row">
                        <div class="col-md-8">
                            <span id="headerData"></span>
                        </div>
                        <div class="col-md-4 mx-auto d-flex align-items-center">
                            <div class="col-md-12">
                                <div class="embed-responsive embed-responsive-3by4" style="height: 200px;">
                                    <img id="photoPopPhoto" class="embed-responsive-item img-fluid" style="width: auto; max-height: 100%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <!-- <label for="uploaderPopPhoto">Selecione o arquivo:</label> -->
                    <input type="file" class="form-control-file" id="uploaderPopPhoto" accept="image/jpeg,image/png"><br><br>
                </div>

                <div id="divimgoriginal" class="text-center divscanvasPopPhoto" hidden>
                    <div class="form-group">
                        <h2>Imagem original</h2><br>
                        <div id="divcanvas" class="htmlTempPopPhoto centralizado block" style="overflow: auto; max-height: 600px;"></div>
                    </div>  
                </div>

                <div id="divimgpreview" class="text-center divscanvasPopPhoto" hidden>
                    <div class="form-group">
                        <h2>Imagem a ser salva</h2><br>
                        <canvas id="preview" class="foto" style="width:340px;height:460px; border: 1px solid black; box-shadow: 3px 3px 2px rgba(0, 0, 0, 0.448); border-radius: 4px;"></canvas>
                    </div>
                </div>

                <div class="text-center">
                    <div class="form-group">
                        <button id="downloadPhoto" class="btn btn-primary btnsActionPopPhoto" hidden>Baixar</button>
                        <button id="savePhoto" class="btn btn-primary btnsActionPopPhoto" hidden>Salvar</button>
                        <button id="cancelPopPhoto" class="btn btn-danger">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/popupPhotos.js"></script>
<script src="../js/jquery.Jcrop.min.js"></script>