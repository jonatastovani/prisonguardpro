<!-- popUp para gerenciar kits entregues ao preso -->

<div id="pop-kitentregue" class="body-popup">
    <div class="popup" id="kitentregue">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Kit Pertences</h2>

            <div class="grupo">
                <input type="radio" name="acao-kitentregue" id="novo-kitentregue">
                <label for="novo-kitentregue">Novo Kit</label>
                <input type="radio" name="acao-kitentregue" id="alterar-kitentregue" class="margin-espaco-esq" checked>
                <label for="alterar-kitentregue">Alterar Kit</label>
            </div>
            <div class="grupo-block" id="kitentregueselect">
                <h4 class="titulo-grupo"><label for="selectkit">Kits Entregues</label></h4>
                <select name="selectkit" id="selectkit" style="width: 265px">
                </select>
                <button id="deletekit" class="btn-excluir">Excluir Kit</button>
            </div>
            
            <div class="grupo">
                <div style="display: flex;">
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="selectitenskit">Ítens</label></h4>
                        <select name="selectitenskit" id="selectitenskit" class="itemkits">
                        </select>
                    </div>
                    <div class="grupo margin-espaco-esq margin-espaco-dir">
                        <h4 class="titulo-grupo"><label for="quantidade">Qtd</label></h4>
                        <input type="number" name="quantidade" id="quantidade" value="0" style="width: 40px; text-align: center;">
                    </div>
                    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; margin-left: 5px;">
                        <button id="inseriritem">Inserir</button>
                        <button id="inserirpadrao">Inserir Padrão</button>
                    </div>
                </div>
            </div>

            <div class="listagem max-height-200">
                <table id="table-pop-kitentregue" class="largura-total">
                    <thead>
                        <tr>
                            <th style="width: 85%;">Ítem</th>
                            <th style="width: 10%;">Qtd</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr id="item1">
                            <td>Teste 1</td>
                            <td><input type="number" style="width: 90%;" class="centralizado num-inteiro" id="valitem1" value="0"></td>
                            <td>
                                <div class="centralizado">
                                    <button id="del1" class="btnAcaoRegistro"><img src="imagens/delete-16.png" alt="Deletar"></button>
                                </div>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
            
            <div class="grupo-block">
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="dataentrega">Data da Entrega</label></h4>
                    <input type="date" name="dataentrega" id="dataentrega" value="<?php echo date('Y-m-d')?>">
                </div>
                <div class="grupo-block">
                    <h4 class="titulo-grupo"><label for="obskitentregue">Observações</label></h4>
                    <textarea name="obskitentregue" id="obskitentregue" cols="30" rows="2" class="largura-total"></textarea>
                </div>
            </div>
            <div class="final-pagina">
                <button id="novoitem">Gerenciar Ítens</button>
                <button id="salvarkitentregue">Salvar</button>
                <button id="cancelarkitentregue">Cancelar</button>
                <button id="imprimirkit">Imprimir</button>
            </div>

        </div>
    </div>
</div>

<?php include_once "popups/novo_itemkit_popup.php";?>
<script src="js/popups/kitentregue_popup.js"></script>