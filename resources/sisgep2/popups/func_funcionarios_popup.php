<!-- popUp para adicionar novo artigo -->

<div id="pop-popfuncionario" class="body-popup">
    <div class="popup" id="popfuncionario">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2 id="titulopopfunc">Funcionário</h2>

            <div id="camposdados">
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="nomepopfunc">*Nome</label></h4>
                    <input type="text" class="tamanho-grande temppopfunc" id="nomepopfunc">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="usuariopopfunc">*Usuário</label></h4>
                    <input type="text" id="usuariopopfunc" class="temppopfunc">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="rspopfunc">RS Servidor</label></h4>
                    <input type="text" class="num-rsusuario temppopfunc" id="rspopfunc">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="cpfpopfunc">*CPF</label></h4>
                    <input type="text" class="inp-cpf temppopfunc" id="cpfpopfunc">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="rgpopfunc">RG</label></h4>
                    <input type="text" id="rgpopfunc" class="temppopfunc">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="selectturnopopfunc">*Turno</label></h4>
                    <select id="selectturnopopfunc"></select>
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="selectescalapopfunc">Escala</label></h4>
                    <select id="selectescalapopfunc"></select>
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="selectstatuspopfunc">*Status</label></h4>
                    <select id="selectstatuspopfunc">
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="selectbloqueadopopfunc">*Situação</label></h4>
                    <select id="selectbloqueadopopfunc">
                        <option value="0">Normal</option>
                        <option value="1">Bloqueado</option>
                    </select>
                </div>
                <p style="font-size: 10pt; line-height: normal;">* = Campos obrigatórios.</p>
            </div>

            <div id="campotemporario">
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="datainiciopopfunc">Intervalo da Permissão Temporária</label></h4>
                    <label for="datainiciopopfunc">Data Início</label>
                    <input type="date" id="datainiciopopfunc">
                    <label for="dataterminopopfunc" class="margin-espaco-esq">Data Término</label>
                    <input type="date" id="dataterminopopfunc">
                </div>
            </div>

            <div class="grupo-block overflow-y" style="height: 45vh;">
                <h4 class="titulo-grupo" style="position: sticky; top: -10px;">Permissões</h4>
                <div id="permissoespopfunc">
                    <div class="grupo">
                        <h4 class="titulo-grupo">Administrador do Sistema</h4>
                        <input type="checkbox" name="" id="admin">
                        <label for="admin">Administrador Sistema</label>
                    </div><br>
                    <div class="grupo">
                        <h4 class="titulo-grupo">Gerenciar Administradores de Setores</h4>
                        <input type="checkbox" id="inc">
                        <label for="inc">Administrador Setor Inclusão</label>
                        <input type="checkbox" class="margin-espaco-esq" id="cimic">
                        <label for="cimic">Administrador Setor CIMIC</label>
                    </div>
                </div>
            </div>

            <div class="ferramentas flex">
                <div class="largura-restante">
                    <button id="exibirpermtemp" hidden>Exibir permissões temporárias</button>
                </div>
                <div class="align-rig">
                    <button id="salvarpopfunc">Confirmar</button>
                </div>
            </div>

            <div id="divpermtermporarias" class="grupo-block listagem" hidden>
                <table id="table-permtermporarias">
                    <thead>
                        <th>Ações</th>
                        <th>Permissão</th>
                        <th>Data Início</th>
                        <th>Data Término</th>
                        <th>Descrição</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/func_funcionarios_popup.js"></script>