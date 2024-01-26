import { conectAjax } from "../../ajax/conectAjax.js";
import { funcoesComuns } from "../../comuns/funcoesComuns.js";
import { modalMessage } from "../../comuns/modalMessage.js";

$(document).ready(function () {

    const id = $('#id').val();
    const containerPresos = $('#containerPresos');

    function init() {

        funcoesComuns.configurarCampoSelect2($('#origem_idEntradasPresos'), `${urlRefIncOrigem}/busca/select`);
        $('#origem_idEntradasPresos').focus();

        console.log(id);
        buscarTodosDados();

    };

    $(window).on('resize', function () {

        funcoesComuns.configurarCampoSelect2($('#origem_idEntradasPresos'), `${urlRefIncOrigem}/busca/select`);

    });

    $('#btnInserirPreso').on("click", (event) => {

        const idDiv = inserirFormularioPreso();
        $(`#${idDiv}`).find('input[name="matricula"]').focus();

    });

    function buscarTodosDados() {

        const obj = new conectAjax(urlIncEntrada);
        obj.setParam(id);

        obj.getRequest()
            .then(function (response) {

                const data = response.data;
                $('#origem_idEntradasPresos').html(new Option(data.origem.nome, data.origem_id, true, true)).trigger('change');
                $('#data_entradaEntradasPresos').val(data.data_entrada);
                
            })
            .catch(function (error) {
                $('input, .btn, select').prop('disabled', true);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o programador.\nErro: ${error.message}`, 'error');
            });

    }

    function inserirFormularioPreso(id = '') {

        const idDiv = `${id}${Date.now()}`;
        const strDataId = id ? `data-id="${id}"` : '';
        let strPreso = `
            <div id="${idDiv}" ${strDataId}
                class="p-2 col-md-6 col-12 bg-info bg-opacity-10 border border-info rounded position-relative">
                <button type="button" ${strDataId} class="btn-close position-absolute top-0 end-0" aria-label="Close"></button>

                <div class="row">
                    <div class="col-3">
                        <label for="matricula${idDiv}" class="form-label">Matrícula</label>
                        <input type="text" class="form-control" name="matricula" id="matricula${idDiv}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="nome${idDiv}" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nome${idDiv}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12"
                        title="Nome pelo qual o preso deseja ser chamado. Este nome ficará mais aparente nos documentos, caso seja informado.">
                        <label for="nome${idDiv}" class="form-label">Nome social</label>
                        <input type="text" class="form-control" name="nome_social" id="nome_social${idDiv}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="rg${idDiv}" class="form-label">RG</label>
                        <input type="text" class="form-control" name="rg" id="rg${idDiv}">
                    </div>
                    <div class="col-6">
                        <label for="cpf${idDiv}" class="form-label">CPF</label>
                        <input type="text" class="form-control" name="cpf" id="cpf${idDiv}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto flex-fill text-end">
                        <button id="toggleCamposAdicionais${idDiv}" class="btn btn-outline-secondary btn-mini">
                            <i class="bi bi-view-list"></i>
                        </button>
                    </div>
                </div>
                <div id="camposAdicionais${idDiv}" style="display: none;">
                    <div class="row">
                        <div class="col-12">
                            <label for="mae${idDiv}" class="form-label">Mãe</label>
                            <input type="text" class="form-control" name="mae" id="mae${idDiv}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="pai${idDiv}" class="form-label">Pai</label>
                            <input type="text" class="form-control" name="pai" id="pai${idDiv}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="data_prisao${idDiv}" class="form-label">Data prisão</label>
                            <input type="date" class="form-control" name="data_prisao" id="data_prisao${idDiv}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="informacoes${idDiv}" class="form-label">Informações (Ex: link da
                                notícia)</label>
                            <textarea class="form-control" name="informacoes" id="informacoes${idDiv}" cols="30" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12"
                            title="Observações sobre o preso (este campo não é impresso na qualificativa)">
                            <label for="observacoes${idDiv}" class="form-label">Observações</label>
                            <textarea class="form-control" name="observacoes" id="observacoes${idDiv}" cols="30" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>`;

        containerPresos.append(strPreso);
        addQueryButtonEvents(idDiv);

        return idDiv;
    }

    function addQueryButtonEvents(idDiv) {

        funcoesComuns.aplicarMascaraNumero($(`#${idDiv}`).find('input[name="matricula"]'));
        funcoesComuns.eventoEsconderExibir($(`#camposAdicionais${idDiv}`), $(`#toggleCamposAdicionais${idDiv}`));

        $(`#${idDiv}`).find('.btn-close').on("click", function () {
            const idPreso = $(this).data('id');
            if (idPreso) {
                acaoBtnDeletar(idDiv, this);
            } else {
                $(`#${idDiv}`).remove();
            }
        });

    }

    function acaoBtnDeletar(idDiv, button) {

        const obj = new modalMessage();
        obj.setMessage(`Confirma a exclusão deste preso?`);
        obj.setTitle('Confirmação de exclusão de preso');
        obj.setElemFocusClose(button);
        obj.openModal().then(function (result) {

            if (result) {
                $(`#${idDiv}`).remove();
            }

        });

    }

    init();

});

// async function init() {

//     await buscarTodosDados();
//     // await funcoesComuns.configurarCampoSelect2($('#origem_idEntradasPresos'), `${urlRefIncOrigem}/busca/select`);
//     $('#origem_idEntradasPresos').focus();

// }

// async function buscarTodosDados() {
//     try {
//         const obj = new conectAjax(urlIncEntrada);
//         obj.setParam(id);

//         const response = await obj.getRequest();
//         const data = response.data;

//         const select = $('#origem_idEntradasPresos');

//         // Remover opções anteriores se necessário
//         select.find('option').remove();

//         // Adicionar uma nova opção
//         const novaOpcao = new Option(data.origem.nome, data.origem_id, true, true);
//         console.log(novaOpcao);
//         select.html(novaOpcao).trigger('change');
//         // select.select2('val', data.origem_id);
//     } catch (error) {
//         $('#form1 :input').prop('disabled', true);
//         $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o programador.\nErro: ${error}`, 'error');
//     }
// }