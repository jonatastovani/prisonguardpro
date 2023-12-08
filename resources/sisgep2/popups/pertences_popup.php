<!-- popUp para gerenciar kits entregues ao preso -->

<div id="pop-pertencespreso" class="body-popup">
    <div class="popup" id="pertencespreso">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Pertences Retidos</h2>

            <div class="grupo-block" id="pertencespresoselect">
                <h4 class="titulo-grupo"><label for="selectpertences">Registros de Pertences</label></h4>
                <select id="selectpertences" style="width: 300px">
                </select>
                <button id="deletepertence" class="btn-excluir">Excluir Pertence</button>
            </div>
            
            <div class="grupo-block">
                <h4 class="titulo-grupo">Dados do Preso</h4>
                Nome: <b><span id="nomepresopertences">Nome do preso</span></b><br>
                Matrícula: <b><span id="matriculapresopertences">Matrícula</span></b>;
                Cela: <b><span id="raiocelapresopertences">Raio/Cela</span></b>
            </div>
            <div class="grupo-block">
                <div><b><span id="numeropertence">Número</span></b></div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="dataentrada">Data da Entrada</label></h4>
                    <input type="date" id="dataentrada" value="<?php echo date('Y-m-d')?>">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo">Tipo de Pertence</h4>
                    <b><span id="tipopertence" data-tipo="0">Tipo Pertence</span></b>
                </div>
            </div>
                        
            <div class="grupo-block" id="dadosretiradapertence">
                <div class="grupo-block">
                    <h4 class="titulo-grupo"><label for="nomeretiradapertence">Nome retirada</label></h4>
                    <input type="text" id="nomeretiradapertence" class="largura-total">                
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="dataretirada">Data da Retirada</label></h4>
                    <input type="date" id="dataretirada" value="<?php echo date('Y-m-d')?>">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="selectgrauparentesco">Grau de Parentesco</label></h4>
                    <select id="selectgrauparentesco"></select>
                </div>
            </div>
            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="obspertencespreso">Observações</label></h4>
                <textarea name="obspertencespreso" id="obspertencespreso" cols="30" rows="2" class="largura-total overflow-y"></textarea>
            </div>
            <div class="grupo-block">
                <input type="checkbox" id="ckbdescartado">
                <label for="ckbdescartado">Pertence descartado ou enviado para doação</label>
                <div id="datadescarte" hidden>
                    <label for="datadescartado">Data: </label>
                    <input type="date" id="datadescartado" value="<?php echo date('Y-m-d')?>">
                </div>
            </div>
            
            <div class="final-pagina">
                <!-- <button id="novograu">Gerenciar Grau Parentesco</button> -->
                <button id="salvarpertencespreso">Salvar</button>
                <button id="cancelarpertencespreso">Cancelar</button>
                <button id="opennovopertence">Novo Pertence</button>
            </div>

        </div>
    </div>
</div>

<?php //include_once "popups/pertences_novo_popup.php";?>
<script src="js/popups/pertences_popup.js"></script>