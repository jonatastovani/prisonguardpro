<!-- popUp para adicionar novo artigo -->

<div id="pop-unidades" class="body-popup">
    <div class="popup" id="popunidades">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Unidades Prisionais</h2>

            <div id="camposalterarpopuni" class="visibilidade1" hidden>
                <div class="container-flex">
                    <div class="div-metade-aling-esquerda">
                        <div class="grupo-block">
                            <h4 class="titulo-grupo"><label for="popuninome">Nome da Unidade</label></h4>
                            <input type="text" id="popuninome" class="largura-total campotexto" autocomplete="off" placeholder="Ex: Pinheiros I, Americana">
                        </div>
                    </div>
                    <div class="div-metade-aling-direita">
                        <div class="grupo-block">
                            <h4 class="titulo-grupo align-lef"><label for="popuniatribuido">Nome Atribuído</label></h4>
                            <input type="text" id="popuniatribuido" class="largura-total campotexto" autocomplete="off" placeholder="Ex: AEVP Renato Gonçalves Rodrigues">
                        </div>
                    </div>
                </div>
                <div class="container-flex">
                    <div class="div-metade-aling-esquerda">
                        <div class="grupo-block">
                            <h4 class="titulo-grupo"><label for="selectpopunitipo">Tipo de Unidade</label></h4>
                            <select id="selectpopunitipo" class="largura-total camposelect">
                                <option value="0">Selecione o Tipo de Unidade</option>
                            </select>
                        </div>
                    </div>
                    <div class="div-metade-aling-direita">
                        <div class="grupo-block">
                            <h4 class="titulo-grupo align-lef"><label for="selectpopunicoord">Coordenadoria</label></h4>
                            <select id="selectpopunicoord" class="largura-total camposelect">
                                <option value="0">Selecione a Coordenadoria</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="container-flex">
                    <div class="div-metade-aling-esquerda">
                        <div class="grupo-block">
                            <h4 class="titulo-grupo"><label for="selectpopuniperfil">Perfil</label></h4>
                            <select id="selectpopuniperfil" class="largura-total camposelect">
                                <option value="0">Selecione o Perfil</option>
                            </select>
                        </div>
                    </div>
                    <div class="div-metade-aling-direita">
                        <div class="grupo-block">
                            <h4 class="titulo-grupo align-lef"><label for="popunidiretor">Nome Diretor</label></h4>
                            <input type="text" id="popunidiretor" class="largura-total campotexto" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="container-flex">
                    <div class="div-metade-aling-esquerda">
                        <div class="grupo-block">
                            <h4 class="titulo-grupo"><label for="popuninotes">Email Notes</label></h4>
                            <input type="text" id="popuninotes" class="largura-total campotexto" autocomplete="off" placeholder="Ex: cdpamericana@cdpamericana.sap.sp.gov.br">
                        </div>
                    </div>
                    <div class="div-metade-aling-direita">
                        <div class="grupo-block">
                            <h4 class="titulo-grupo align-lef"><label for="popunicimic">Email Cimic</label></h4>
                            <input type="text" id="popunicimic" class="largura-total campotexto" autocomplete="off" placeholder="Ex: cimic_cdpamericana@sap.sp.gov.br">
                        </div>
                    </div>
                </div>
                <div class="container-flex">
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="popunicodigo">Código</label></h4>
                        <input type="text" id="popunicodigo" class="campotexto" style="width: 90px;" autocomplete="off" placeholder="Ex: CDAME">
                    </div>
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="popuniendereco">Endereço</label></h4>
                        <input type="text" id="popuniendereco" class="tamanho-grande campotexto" autocomplete="off" placeholder="Ex: Logradouro, 123 - Bairro">
                    </div>
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="popunicep">CEP</label></h4>
                        <input type="text" id="popunicep" class="inp-cep campotexto" autocomplete="off" placeholder="Ex: 12345-678">
                    </div>
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="popunitelefones">Telefones</label></h4>
                        <input type="text" id="popunitelefones" class="tamanho-medio campotexto" autocomplete="off" placeholder="Ex: (19)3469-6133/5727 ramais cimic - 106/217/218">
                    </div>
                    <div class="grupo">
                        <h4 class="titulo-grupo"><label for="selectpopunicidade">Cidade</label></h4>
                        <div class="inline">
                            <label for="searchpopunicidade">Cod.</label>
                            <input type="search" id="searchpopunicidade" class="cod-search campotexto" list="listpopunicidade">
                            <datalist id="listpopunicidade"></datalist>
                        </div>
                        <div class="inline">
                            <select id="selectpopunicidade" class="tamanho-medio camposelect">
                                <option value="0">Selecione a Cidade</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex" style="margin-top: 15px;">
                <div style="display: inline; flex-grow: 2;">
                    <div class="flex">
                        <label for="textobusca">Texto de Busca: </label>
                        <div style="flex-grow: 2;"><input type="text" id="textobusca" class="largura-total"></div>
                    </div>
                </div>
                <div class="align-rig" style="width: 35%; display: inline;">
                    <button id="novopopunidades" class="visibilidade2">Nova Unidade</button>
                    <button id="salvarpopuni" class="visibilidade1" hidden>Salvar</button>
                    <button id="cancelarpopunidades" class="visibilidade1" hidden>Cancelar</button>
                </div>
            </div>

            <div class="listagem max-height-500">
                <table id="table-pop-unidades" class="largura-total">
                    <thead>
                        <tr>
                            <th>Ação</th>
                            <th>Código</th>
                            <th style="min-width: 150px;">Nome da Unidade</th>
                            <th style="min-width: 150px;">Nome Atribuído</th>
                            <th style="min-width: 250px;">Diretor</th>
                            <th style="min-width: 150px;">Email Notes</th>
                            <th style="min-width: 150px;">Email CIMIC</th>
                            <th style="min-width: 500px;">Endereço</th>
                            <th style="min-width: 80px;">CEP</th>
                            <th style="min-width: 150px;">Telefones</th>
                            <th style="min-width: 150px;">Cidade</th>
                            <th style="min-width: 100px;">Perfil</th>
                            <th style="min-width: 50px;">Tipo</th>
                            <th style="min-width: 600px;">Coordenadoria</th>
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

<script src="js/popups/unidades_popup.js"></script>