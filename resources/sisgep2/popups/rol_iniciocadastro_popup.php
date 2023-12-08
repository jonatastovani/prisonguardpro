<!-- popUp para adicionar novo artigo -->

<div id="pop-popcadvisi" class="body-popup">
    <div class="popup" id="popcadvisi">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Início do cadastro de visitante</h2>

            <fieldset class="grupo-block">
                <legend>Informações do Preso</legend>
                
                <span>Nome: <b><span id="nomepresopopcadvisi" class="htmltemppopcadvisi"></span></b></span><br>
                <span>Matrícula: <b><span id="matriculapopcadvisi" class="htmltemppopcadvisi"></span></b></span>
                <span class="margin-espaco-esq">Cela: <b><span id="raiocelapopcadvisi" class="htmltemppopcadvisi"></span></b></span>
            </fieldset>

            <fieldset class="grupo-block">
                <legend>Informações do visitante</legend>

                <span>Nome: <b><span id="nomevisitantepopcadvisi" class="htmltemppopcadvisi"></span></b></span><br>
                <span>Data da Cadastro: <b><span id="datacadastropopcadvisi" class="htmltemppopcadvisi"></span></b></span>
                <span class="margin-espaco-esq">Parentesco: <b><span id="parentescopopcadvisi" class="htmltemppopcadvisi"></span></b></span>
            </fieldset>

            <fieldset class="grupo-block centralizado">
                <legend>CPF Visitante</legend>
                <input type="text" id="cpfpopcadvisi" class="inp-cpf strtemppopcadvisi">
                <button id="conferirpopcadvisi">Conferir CPF</button>
            </fieldset>
        
            <div class="listagem max-height-200">
                <table id="table-pop-popcadvisi" class="largura-total" hidden>
                    <thead>
                        <tr>
                            <th>Ação</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>RG</th>
                            <!-- <th>Situação Visitante</th> -->
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="final-pagina">
                <button id="continuarpopcadvisi" class="btnverde" hidden>Continuar para novo visitante</button>
                <button id="cancelarpopcadvisi">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/rol_iniciocadastro_popup.js"></script>