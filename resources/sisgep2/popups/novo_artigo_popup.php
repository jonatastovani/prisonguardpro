<!-- popUp para adicionar novo artigo -->

<div id="pop-artigo" class="body-popup">
    <div class="popup" id="novo-artigo">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Artigos</h2>

            <div class="form-element">
                <label for="valornovoartigo"><span id="label-artigo">Novo Artigo:</span></label>
                <input type="text" id="valornovoartigo" placeholder="Digite o artigo. Ex: 121 ou 155 §1">
            </div>

            <div class="final-pagina">
                <button id="salvarartigo">Salvar</button>
                <button id="cancelarartigo" hidden>Cancelar</button>
            </div>

            <div class="listagem max-height-200">
                <table id="table-pop-artigo" class="largura-total">
                    <thead>
                        <tr>
                            <th style="width: 85%;">Artigo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- <tr>
                            <td>Teste 1</td>
                            <td>
                                <div class="centralizado">
                                    <button id="alt1" class="btnAcaoRegistro"><img src="imagens/alterar-16.png" alt="Alterar"></button>
                                    <button id="del1" class="btnAcaoRegistro"><img src="imagens/delete-16.png" alt="Deletar"></button>
                                </div>
                            </td>
                        </tr> --> 
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="js/popups/novo_artigo_popup.js"></script>