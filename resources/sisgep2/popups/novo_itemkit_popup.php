<!-- popUp para adicionar novo itemkit -->

<div id="pop-itemkit" class="body-popup">
    <div class="popup" id="novo-itemkit">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Ítens do Pertence</h2>

            <div class="grupo-block">
                <h4 class="titulo-grupo"><label for="nomeitemkit"><span id="label-itemkit">Novo Ítem</span></label></h4>
                <input type="text" id="nomeitemkit" class="largura-total" placeholder="Digite o ítem. Ex: Sabonete, Pasta de Dente, Toalha...">
            </div>

            <div class="grupo-block">
                <h4 class="titulo-grupo">Especificações</h4>
                <div class="grupo">
                    <input type="checkbox" id="ckbitemnovo">
                    <label for="ckbitemnovo">Ítem Novo</label>
                </div>
                <div class="grupo flex">
                    <input type="checkbox" id="ckbpadrao">
                    <label for="ckbpadrao">Padrão Entrega</label>

                    <div id="divqtd" hidden>
                        <label for="qtdentrega">Qtd: </label>
                        <input type="number" id="qtdentrega" style="width: 30px; text-align: center;">
                    </div>
                </div>
            </div>
            <div class="final-pagina">
                <button id="salvaritemkit">Salvar</button>
                <button id="cancelaritemkit" hidden>Cancelar</button>
            </div>

            <div class="listagem max-height-200">
                <table id="table-pop-itemkit" class="largura-total">
                    <thead>
                        <tr>
                            <th style="width: 55%;">Ítem</th>
                            <th style="width: 15%;">Qtd</th>
                            <th style="width: 15%;">Padrão</th>
                            <th style="width: 15%;">Ação</th>
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

<script src="js/popups/novo_itemkit_popup.js"></script>