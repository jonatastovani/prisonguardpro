import { conectAjax } from "../../ajax/conectAjax.js";
import { configuracoesApp } from "../../common/configuracoesApp.js";
import { enumAction } from "../../common/enumAction.js";
import { funcoesComuns } from "../../common/funcoesComuns.js";
import { funcoesPresos } from "../../common/funcoesPresos.js";
import { modalMessage } from "../../common/modalMessage.js";
import { modalAlterarPresoConvivio } from "../../modals/preso/modalAlterarPresoConvivio.js";

$(document).ready(function () {

    const id = $('#id').val();
    const containerPresos = $('#containerPresos');
    const redirecionamento = $('.redirecionamentoAnterior').attr('href');

    function init() {

        funcoesComuns.configurarCampoSelect2($('#origem_idEntradasPresos'), `${urlRefIncOrigem}/busca/select`);
        $('#origem_idEntradasPresos').focus();

        if (id) {
            buscarDadosTodos();
        }

    };

    $(window).on('resize', function () {

        funcoesComuns.configurarCampoSelect2($('#origem_idEntradasPresos'), `${urlRefIncOrigem}/busca/select`);

    });

    $('#btnInserirPreso').on("click", (event) => {

        const idDiv = inserirFormularioPreso();
        $(`#${idDiv}`).find('input[name="matricula"]').focus();

    });

    $('#btnSalvar').on("click", (event) => {

        acaoBtnSalvar();

    });

    function buscarDadosTodos() {

        const obj = new conectAjax(urlIncEntrada);
        obj.setParam(id);

        obj.getRequest()
            .then(function (response) {

                const data = response.data;
                const data_entrada = moment(data.data_entrada).format('yyyy-MM-DD');
                const hora_entrada = moment(data.data_entrada).format('HH:mm');

                $('#origem_idEntradasPresos').html(new Option(data.origem.nome, data.origem_id, true, true)).trigger('change');
                $('#data_entradaEntradasPresos').val(data_entrada);
                $('#hora_entradaEntradasPresos').val(hora_entrada);

                data.presos.forEach(preso => {

                    const idDiv = inserirFormularioPreso(preso.id);
                    const div = $(`#${idDiv}`);
                    const matricula = preso.matricula ? funcoesPresos.retornaMatriculaFormatada(preso.matricula, 2) : '';

                    div.find('input[name="id"]').val(preso.id);
                    div.find('.passagem_id').html(`ID Passagem ${preso.id}`);
                    div.find('input[name="matricula"]').val(matricula).trigger('input');
                    div.find('input[name="nome"]').val(preso.nome);
                    div.find('input[name="nome_social"]').val(preso.nome_social);
                    div.find('input[name="data_prisao"]').val(preso.data_prisao);
                    div.find('input[name="informacoes"]').val(preso.informacoes);
                    div.find('input[name="observacoes"]').val(preso.observacoes);
                    div.find('input[name="convivio_tipo_id"]').val(preso.convivio_tipo_id);

                    if (preso.convivio_tipo.cor){
                        div.removeClass('bg-info');
                        div.css('color', preso.convivio_tipo.cor.cor_texto);
                        div.css('background-color', preso.convivio_tipo.cor.cor_fundo);
                    } else {
                        div.addClass('bg-info');
                        div.removeAttr('style');
                    }

                    const nomeConvivio = !preso.convivio_tipo.convivio_padrao_bln ? preso.convivio_tipo.nome : null;
                    const campoInfo = div.find('.campoInfo');
                    const infoConvivio = campoInfo.find(`.convivio_tipo_nome`);
                    if(!infoConvivio.length && nomeConvivio) {
                        campoInfo.append(`<p class="convivio_tipo_nome mb-0"><b><i>${nomeConvivio}</i></b></p>`);
                    } else if (infoConvivio.length && nomeConvivio) {
                        infoConvivio.html(`<p class="convivio_tipo_nome mb-0"><b><i>${nomeConvivio}</i></b></p>`);
                    } else if (infoConvivio.length && !nomeConvivio) {
                        infoConvivio.remove();
                    }

                });
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
                class="p-2 col-md-6 col-12 bg-info bg-opacity-10 border rounded position-relative">
                <button type="button" ${strDataId} class="btn-close position-absolute top-0 end-0" aria-label="Close"></button>
                <input type="hidden" class="form-control" name="id" id="id${idDiv}">
                <input type="hidden" class="form-control" name="convivio_tipo_id" id="tipo_preso_id${idDiv}">

                <div class="row">
                    <div class="col">
                        <label for="matricula${idDiv}" class="form-label">Matrícula</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-end" name="matricula" id="matricula${idDiv}">
                            <input type="text" style="max-width: 40px;" class="form-control text-center" name="digito" id="digito${idDiv}" disabled>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-end align-items-center">
                        <span class="passagem_id mh-100"></span>
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
                    <div class="col-4">
                        <label for="data_prisao${idDiv}" class="form-label">Data prisão</label>
                        <input type="date" class="form-control" name="data_prisao" id="data_prisao${idDiv}">
                    </div>
                    <div class="col-8 d-flex justify-content-end align-items-center">
                        <button type="button" id="btnAlterarPresoConvivio${idDiv}" class="btn btn-outline-warning btn-mini" title="Clique para inserir ou retirar o preso do seguro"><i class="bi bi-shield-fill-exclamation"></i></button>
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
                <div class="row">
                    <div class="col-12 campoInfo"></div>
                </div>
            </div>`;

        containerPresos.append(strPreso);
        addEventosBotoesDaConsulta(idDiv);

        return idDiv;
    }

    function addEventosBotoesDaConsulta(idDiv) {

        const div = $(`#${idDiv}`);
        const matricula = div.find('input[name="matricula"]');
        const digito = div.find('input[name="digito"]');
        funcoesComuns.aplicarMascaraNumero(matricula, { formato: configuracoesApp.mascaraMatriculaSemDigito(), reverse: true });
        funcoesComuns.eventoEsconderExibir($(`#camposAdicionais${idDiv}`), $(`#toggleCamposAdicionais${idDiv}`));

        $(`#btnAlterarPresoConvivio${idDiv}`).on('click', function(){
            const obj = new modalAlterarPresoConvivio();
            obj.setFocoNoElementoAoFechar = this;
            obj.setIdDiv = idDiv;
            obj.modalAbrir().then(function (result) {
                
                if (result) {
                    if (result.cor){
                        div.removeClass('bg-info');
                        div.css('color', result.cor.cor_texto);
                        div.css('background-color', result.cor.cor_fundo);
                    } else {
                        div.addClass('bg-info');
                        div.removeAttr('style');
                    }
                    div.find('input[name="convivio_tipo_id"]').val(result.id);

                    const nomeConvivio = !result.convivio_padrao_bln ? result.nome : null;
                    const campoInfo = div.find('.campoInfo');
                    const infoConvivio = campoInfo.find(`.convivio_tipo_nome`);
                    if(!infoConvivio.length && nomeConvivio) {
                        campoInfo.append(`<p class="convivio_tipo_nome mb-0"><b><i>${nomeConvivio}</i></b></p>`);
                    } else if (infoConvivio.length && nomeConvivio) {
                        infoConvivio.html(`<p class="convivio_tipo_nome mb-0"><b><i>${nomeConvivio}</i></b></p>`);
                    } else if (infoConvivio.length && !nomeConvivio) {
                        infoConvivio.remove();
                    }

                }
            });
        });

        matricula.on('input', function () {
            digito.val(funcoesPresos.retornaDigitoMatricula(matricula.val()));
        })

        div.find('.btn-close').on("click", function () {
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

    function acaoBtnSalvar() {

        let data = funcoesComuns.obterValoresDosInputs($('#dadosEntradaEntradasPresos'));
        data['presos'] = [];
        data['data_entrada'] = `${data['data_entrada']} ${data['hora_entrada']}:00`;
        delete data['hora_entrada'];

        const presos = containerPresos.children();
        for (let i = 0; i < presos.length; i++) {

            let preso = funcoesComuns.obterValoresDosInputs($(presos[i]), 1, true);
            preso['matricula'] = preso['matricula'] + preso['digito'];
            preso['matricula'] = funcoesComuns.retornaSomenteNumeros(preso['matricula']);
            data['presos'].push(preso);

        }

        salvar(data);
    }

    function salvar(data) {

        const obj = new conectAjax(urlIncEntrada);
        let action = enumAction.POST;

        if (id) {
            obj.setParam(id);
            action = enumAction.PUT;
        }
        if (obj.setAction(action)) {

            const btn = $('#btnSalvar');
            funcoesComuns.simulacaoCarregando(btn);

            obj.setData(data);
            obj.envRequest()
                .then(function (result) {
                    const token = result.token;

                    let btn = funcoesComuns.formularioRedirecionamento(redirecionamento, [
                        { name: 'arrNotifyMessage', value: [{ message: `Entrada de Presos ${id} alterada com sucesso!`, type: 'success' }] },
                        { name: '_token', value: token }
                    ]);
                    btn.click();

                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');

                })
                .finally(function () {
                    funcoesComuns.simulacaoCarregando(btn, false);
                });
        }

    }

    init();

});