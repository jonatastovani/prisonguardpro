<!-- popUp para adicionar novo artigo -->

<div id="pop-confentsai" class="body-popup">
    <div class="popup" id="popconfentsai">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2 id="titulo-confentrsai" class="htmltemppopconfentsai">Confirmação de Entrada/Saída</h2>

            <fieldset class="grupo-block">
                <legend>Informações do Preso</legend>
                
                <span>Nome: <b><span id="nomepresoconfentrsai" class="htmltemppopconfentrsai"></span></b></span><br>
                <span>Matrícula: <b><span id="matriculaconfentrsai" class="htmltemppopconfentrsai"></span></b></span>
                <span class="margin-espaco-esq">Cela: <b><span id="raiocelaconfentrsai" class="htmltemppopconfentrsai"></span></b></span>
            </fieldset>
            
            <h3>Confirme a(s) entrada(s) do(s) visitante(s) abaixo:</h3>
            <div id="divvispopconfentsai" class="container htmltemppopconfentrsai">
                
            </div>
            

            
            <div class="align-rig">
                <button id="conftodospopconfentsai" class="btnverde" title="Confirmar entrada para todos os visitantes listados acima" hidden>Realizar entrada para todos</button>
                <button id="cancelarpopconfentsai">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/rol_confirma_entrada_saida_popup.js"></script>