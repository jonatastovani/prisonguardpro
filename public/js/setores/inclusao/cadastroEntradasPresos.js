import { conectAjax } from "../../ajax/conectAjax.js";
import { configuracoesApp } from "../../comuns/configuracoesApp.js";
import { enumAction } from "../../comuns/enumAction.js";
import { funcoesComuns } from "../../comuns/funcoesComuns.js";
import { funcoesPresos } from "../../comuns/funcoesPresos.js";
import { modalMessage } from "../../comuns/modalMessage.js";

$(document).ready(function () {

    const id = $('#id').val();
    const containerPresos = $('#containerPresos');
    const redirecionamento = $('.redirecionamentoAnterior').attr('href');

    function init() {

        funcoesComuns.configurarCampoSelect2($('#origem_idEntradasPresos'), `${urlRefIncOrigem}/busca/select`);
        $('#origem_idEntradasPresos').focus();

        if (id) {
            buscarTodosDados();
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

    function buscarTodosDados() {

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
                    div.find('input[name="rg"]').val(preso.rg);
                    div.find('input[name="cpf"]').val(preso.cpf);
                    div.find('input[name="mae"]').val(preso.mae);
                    div.find('input[name="pai"]').val(preso.pai);
                    div.find('input[name="data_prisao"]').val(preso.data_prisao);
                    div.find('input[name="informacoes"]').val(preso.informacoes);
                    div.find('input[name="observacoes"]').val(preso.observacoes);

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
                class="p-2 col-md-6 col-12 bg-info bg-opacity-10 border border-info rounded position-relative">
                <button type="button" ${strDataId} class="btn-close position-absolute top-0 end-0" aria-label="Close"></button>
                <input type="hidden" class="form-control " name="id" id="id${idDiv}">

                <div class="row">
                    <div class="col-5">
                        <label for="matricula${idDiv}" class="form-label">Matrícula</label>
                        <div class="input-group">
                            <input type="text" class="form-control w-75" name="matricula" id="matricula${idDiv}">
                            <input type="text" class="form-control " name="digito" id="digito${idDiv}" disabled>
                        </div>
                    </div>
                    <div class="col-7 d-flex justify-content-end align-items-center">
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
        addEventosBotoesDaConsulta(idDiv);

        return idDiv;
    }

    function addEventosBotoesDaConsulta(idDiv) {

        const matricula = $(`#${idDiv}`).find('input[name="matricula"]');
        const digito = $(`#${idDiv}`).find('input[name="digito"]');
        funcoesComuns.aplicarMascaraNumero(matricula, { formato: configuracoesApp.mascaraMatriculaSemDigito(), reverse: true });
        funcoesComuns.eventoEsconderExibir($(`#camposAdicionais${idDiv}`), $(`#toggleCamposAdicionais${idDiv}`));

        matricula.on('input', function () {
            digito.val(funcoesPresos.retornaDigitoMatricula(matricula.val()));
        })

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