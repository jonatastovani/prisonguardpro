<!-- popUp para adicionar novo artigo -->

<div id="pop-celasvisitas" class="body-popup">
    <div class="popup" id="popcelasvisitas">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Raios e Locais</h2>
            <div id="divcelasvisitas" class="overflow-y" style="max-height: 80vh;">
                <!-- <fieldset class="grupo-block">
                    <legend>Raio A</legend>
                    <div>
                        <label for="selectopcoes">Selecionar para todas as celas: </label>
                        <select name="" id="selectopcoes">
                            <option value="0">Opções para todos</option>
                        </select>
                    </div>
                    <div class="listagem max-height-400">
                        <table id="table-pop-itemkit" class="largura-total">
                            <thead>
                                <tr>
                                    <th>Cela</th>
                                    <th>Permitido</th>
                                    <th>Turno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="cor-fundo-comum-tr">
                                    <td class="centralizado">A/1</td>
                                    <td class="centralizado">
                                        <div class="onoff">
                                            <input type="checkbox" class="toggle" id="onoffcela1">
                                            <label id="label" for="onoffcela1" title="Clique para ativar"></label>
                                        </div>
                                    </td>
                                    <td class="centralizado">
                                        <select name="" id="selectopcoesind">
                                            <option value="0">Opções para cela</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="cor-fundo-comum-tr">
                                    <td class="centralizado">A/2</td>
                                    <td class="centralizado">
                                        <div class="onoff">
                                            <input type="checkbox" class="toggle" id="onoffcela2">
                                            <label id="label" for="onoffcela2" title="Clique para ativar"></label>
                                        </div>
                                    </td>
                                    <td class="centralizado">
                                        <select name="" id="selectopcoesind">
                                            <option value="0">Opções para cela</option>
                                        </select>
                                    </td>
                                </tr>                            </tbody>
                        </table>
                    </div>
                </fieldset> -->
            </div>
            <datalist id="listaturnospopcelvis"></datalist>

            <div class="final-pagina">
                <button id="salvarpopcelvis">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/rol_celas_visitas_popup.js"></script>