
<div id="pop-novomedic" class="body-popup">
    <div class="popup" id="popnovomedic">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Medicamentos</h2>

            <div class="grupo-block">
                <h4 class="titulo-grupo"><label id="label-nomepopnovomedic" for="nomepopnovomedic">Novo Medicamento</label></h4>
                <input type="text" id="nomepopnovomedic" class="largura-total strtemppopnovomedic" placeholder="Digite o nome"><br>
            </div>
            <div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="selectunidadepopnovomedic" title="Selecione a unidade de fornecimento">Unidade</label></h4>
                    <select id="selectunidadepopnovomedic" class="seltemppopnovomedic" title="Selecione a unidade de fornecimento"></select>
                </div>

                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="qtdpadraopopnovomedic" title="Quantidade para preencher automáticamente na entrega. Não insira nada caso não opte para o preenchimento automático da quantidade entregue.">Qtd. Padrão</label></h4>
                   <input type="text" style="width: 90px;" id="qtdpadraopopnovomedic" class="num-inteiro strtemppopnovomedic" title="Quantidade para preencher automáticamente na entrega. Não insira nada caso não opte para o preenchimento automático da quantidade entregue.">
                </div>

                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="qtdestoquepopnovomedic" title="Quantidade em estoque">Qtd. Estoque</label></h4>
                   <input type="text" style="width: 90px;" id="qtdestoquepopnovomedic" class="num-inteiro strtemppopnovomedic" title="Quantidade em estoque">
                </div>

                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="qtdminimopopnovomedic" title="Quantidade mínima de Estoque para ser avisado. Não insira nada caso não queira ser avisado.">Mín. Estoque</label></h4>
                   <input type="text" style="width: 90px;" id="qtdminimopopnovomedic" class="num-inteiro strtemppopnovomedic" title="Quantidade mínima de Estoque para ser avisado. Não insira nada caso não queira ser avisado.">
                </div>

            </div>

            <div class="final-pagina">
                <button id="salvarpopnovomedic">Salvar</button>
                <button id="cancelarpopnovomedic">Cancelar</button>
            </div>

            <div class="listagem max-height-200">
                <table id="table-pop-novomedic" class="largura-total">
                    <thead>
                        <tr>
                            <th>Ação</th>
                            <th>Nome</th>
                            <th>Estoque</th>
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

<script src="js/popups/sau_novomedicamento_popup.js"></script>