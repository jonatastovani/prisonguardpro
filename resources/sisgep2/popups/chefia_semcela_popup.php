<!-- popUp para adicionar novo artigo -->

<div id="pop-semcela" class="body-popup">
    <div class="popup" id="popsemcela">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Presos Sem Cela</h2>

            <div class="listagem max-height-700">
                <table id="table-presossemcela" class="nowrap">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Seguro</th>
                            <th>Raio</th>
                            <th>Cela</th>
                            <th>Origem</th>
                            <th>Data Inclusão</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <datalist id="listaraiospopsemcela"></datalist>
            <div class="ferramentas align-rig">
                <button id="salvarpopsemcela">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/popups/chefia_semcela_popup.js"></script>