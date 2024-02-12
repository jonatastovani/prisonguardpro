import { conectAjax } from "../../../ajax/conectAjax.js";
import { commonFunctions } from "../../../common/commonFunctions.js";
import { configuracoesApp } from "../../../common/configuracoesApp.js";
import { enumAction } from "../../../common/enumAction.js";
import { funcoesComuns } from "../../../common/funcoesComuns.js";
import { funcoesPresos } from "../../../common/funcoesPresos.js";
import { modalMessage } from "../../../common/modalMessage.js";
import { modalCadastroPresoArtigo } from "../../../modals/preso/modalCadastroPresoArtigo.js";
import { modalCadastroCabeloCor } from "../../../modals/referencias/modalCadastroCabeloCor.js";
import { modalCadastroCabeloTipo } from "../../../modals/referencias/modalCadastroCabeloTipo.js";
import { modalCadastroCidade } from "../../../modals/referencias/modalCadastroCidade.js";
import { modalCadastroCrenca } from "../../../modals/referencias/modalCadastroCrenca.js";
import { modalCadastroCutis } from "../../../modals/referencias/modalCadastroCutis.js";
import { modalCadastroEscolaridade } from "../../../modals/referencias/modalCadastroEscolaridade.js";
import { modalCadastroEstadoCivil } from "../../../modals/referencias/modalCadastroEstadoCivil.js";
import { modalCadastroGenero } from "../../../modals/referencias/modalCadastroGenero.js";
import { modalCadastroOlhoCor } from "../../../modals/referencias/modalCadastroOlhoCor.js";
import { modalCadastroOlhoTipo } from "../../../modals/referencias/modalCadastroOlhoTipo.js";

$(document).ready(function () {

    let action = $('#preso_id_bln').val() ? enumAction.PUT : enumAction.POST;
    const passagem_id = $('#passagem_id').val();
    const qual_prov_id = $('#qual_prov_id').val();
    const preso_id_bln = $('#preso_id_bln').val();
    const perm_atribuir_matricula_bln = $('#perm_atribuir_matricula_bln').val();
    const redirect = $('.redirectUrl').attr('href');
    const containerArtigos = $('#containerArtigos');
    let arrArtigos = [];

    function init() {

        const matricula = $('#matricula');
        commonFunctions.applyCustomNumberMask(matricula, { format: configuracoesApp.mascaraMatriculaSemDigito(), reverse: true });

        matricula.on('input', function () {
            $('#digito').val(funcoesPresos.retornaDigitoMatricula(matricula.val()));
        })

        funcoesComuns.configurarCampoSelect2($('#cidade_nasc_id'), `${urlRefCidades}/search/select2`);

        $(`#btnCidadeCadastro`).on('click', function () {
            const obj = new modalCadastroCidade();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    setTimeout(() => {
                        $('#cidade_nasc_id').focus();
                    }, 500);
                }
            });
        });

        const preencherGenero = () => {
            funcoesComuns.preencherSelect($('#genero_id'), `${urlRefGenero}`, { idOpcaoSelecionada: 1 });
        }
        preencherGenero();

        $(`#btnGeneroCadastro`).on('click', function () {
            const obj = new modalCadastroGenero();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherGenero();
                }
            });
        });

        const preencherEscolaridade = () => {
            funcoesComuns.preencherSelect($('#escolaridade_id'), `${urlRefEscolaridade}`);
        }
        preencherEscolaridade();

        $(`#btnEscolaridadeCadastro`).on('click', function () {
            const obj = new modalCadastroEscolaridade();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherEscolaridade();
                }
            });
        });

        const preencherEstadoCivil = () => {
            funcoesComuns.preencherSelect($('#estado_civil_id'), `${urlRefEstadoCivil}`);
        }
        preencherEstadoCivil();

        $(`#btnEstadoCivilCadastro`).on('click', function () {
            const obj = new modalCadastroEstadoCivil();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherEstadoCivil();
                }
            });
        });

        const preencherCutis = () => {
            funcoesComuns.preencherSelect($('#cutis_id'), `${urlRefCutis}`);
        }
        preencherCutis();

        $(`#btnCutisCadastro`).on('click', function () {
            const obj = new modalCadastroCutis();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherCutis();
                }
            });
        });

        const preencherCabeloTipo = () => {
            funcoesComuns.preencherSelect($('#cabelo_tipo_id'), `${urlRefCabeloTipo}`);
        }
        preencherCabeloTipo();

        $(`#btnCabeloTipoCadastro`).on('click', function () {
            const obj = new modalCadastroCabeloTipo();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherCabeloTipo();
                }
            });
        });

        const preencherCabeloCor = () => {
            funcoesComuns.preencherSelect($('#cabelo_cor_id'), `${urlRefCabeloCor}`);
        }
        preencherCabeloCor();

        $(`#btnCabeloCorCadastro`).on('click', function () {
            const obj = new modalCadastroCabeloCor();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherCabeloCor();
                }
            });
        });

        const preencherOlhoTipo = () => {
            funcoesComuns.preencherSelect($('#olho_tipo_id'), `${urlRefOlhoTipo}/comdescricao`, { idOpcaoSelecionada: 1 });
        }
        preencherOlhoTipo();

        $(`#btnOlhoTipoCadastro`).on('click', function () {
            const obj = new modalCadastroOlhoTipo();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherOlhoTipo();
                }
            });
        });

        const preencherOlhoCor = () => {
            funcoesComuns.preencherSelect($('#olho_cor_id'), `${urlRefOlhoCor}`);
        }
        preencherOlhoCor();

        $(`#btnOlhoCorCadastro`).on('click', function () {
            const obj = new modalCadastroOlhoCor();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherOlhoCor();
                }
            });
        });

        const preencherCrenca = () => {
            funcoesComuns.preencherSelect($('#crenca_id'), `${urlRefCrenca}`);
        }
        preencherCrenca();

        $(`#btnCrencaCadastro`).on('click', function () {
            const obj = new modalCadastroCrenca();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    preencherCrenca();
                }
            });
        });

        $(`#btnAddArtigo`).on('click', function () {
            const obj = new modalCadastroPresoArtigo();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.refresh) {
                    inserirArtigos(result.arrData);
                }
            });
        });

        buscarDadosTodos();

        // inserirArtigos({
        //     artigo_id: 1,
        //     observacoes: 'Observacoes do artigo id 1'
        // })
        // inserirArtigos({
        //     artigo_id: 2,
        //     observacoes: 'Observacoes do artigo id 2'
        // })
        // inserirArtigos({
        //     artigo_id: 3,
        //     observacoes: `Observacoes do artigo id 3`
        // })
        // inserirArtigos({
        //     artigo_id: 4,
        //     observacoes: 'Observacoes do artigo id 4'
        // })

    };

    $('#btnInserirPreso').on("click", (event) => {

        const idDiv = inserirArtigos();
        $(`#${idDiv}`).find('input[name="matricula"]').focus();

    });

    $('#btnSalvar').on("click", (event) => {

        acaoBtnSalvar();

    });

    function buscarDadosTodos() {

        // if(preso_id_bln || qual_prov_id){

        // let url = `${urlIncQualificativa}/${passagem_id}`;

        // if(perm_atribuir_matricula_bln && !preso_id_bln && qual_prov_id ||
        //     !perm_atribuir_matricula_bln && qual_prov_id) {
        //     url += `/provisoria/${qual_prov_id}`
        // }

        const obj = new conectAjax(urlIncQualificativa);
        obj.setParam(passagem_id);
        obj.getRequest()
            .then(function (response) {
                console.log(response);

                preencherDados(response);

            })
            .catch(function (error) {
                $('input, .btn, select, textarea').prop('disabled', true);
                $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o programador.\nErro: ${error.message}`, 'error');
            });

        // }

    }

    function preencherDados(response) {

        const preso = response.preso;

        $('#nome').val(preso.nome);
        $('#nome_social').val(preso.nome_social);
        $('#mae').val(preso.mae);
        $('#pai').val(preso.pai);
        $('#cidade_nasc_id').html(new Option(`${preso.cidade.nome} - ${preso.cidade.estado.sigla} | ${preso.cidade.estado.nacionalidade.pais}`, preso.cidade_id, true, true)).trigger('change');
        $('#data_nasc').val(preso.data_nasc);
        $('#genero_id').val(preso.genero_id);
        $('#escolaridade_id').val(preso.escolaridade_id);
        $('#estado_civil_id').val(preso.estado_civil_id);
        $('#cutis_id').val(preso.cutis_id);
        $('#cabelo_tipo_id').val(preso.cabelo_tipo_id);
        $('#cabelo_cor_id').val(preso.cabelo_cor_id);
        $('#olho_tipo_id').val(preso.olho_tipo_id);
        $('#olho_cor_id').val(preso.olho_cor_id);
        $('#crenca_id').val(preso.crenca_id);
        $('#sinais').val(preso.sinais);
        $('#informacoes').val(response.informacoes);
        $('#observacoes').val(response.observacoes);

    }

    function preencherDadosProvisorio(response) {

        $('#nome').val(response.nome);
        $('#nome_social').val(response.nome_social);
        $('#mae').val(response.mae);
        $('#pai').val(response.pai);
        $('#cidade_nasc_id').html(new Option(`${response.cidade.nome} - ${response.cidade.estado.sigla} | ${response.cidade.estado.nacionalidade.pais}`, response.cidade_id, true, true)).trigger('change');
        $('#data_nasc').val(response.data_nasc);
        $('#genero_id').val(response.genero_id);
        $('#escolaridade_id').val(response.escolaridade_id);
        $('#estado_civil_id').val(response.estado_civil_id);
        $('#cutis_id').val(response.cutis_id);
        $('#cabelo_tipo_id').val(response.cabelo_tipo_id);
        $('#cabelo_cor_id').val(response.cabelo_cor_id);
        $('#olho_tipo_id').val(response.olho_tipo_id);
        $('#olho_cor_id').val(response.olho_cor_id);
        $('#crenca_id').val(response.crenca_id);
        $('#sinais').val(response.sinais);
        $('#informacoes').val(response.informacoes);
        $('#observacoes').val(response.observacoes);

    }

    async function inserirArtigos(arrData) {

        const id = arrData.id ? arrData.id : '';
        let idDiv = '';
        if (!arrData.idDiv) {
            idDiv = `${id}${Date.now()}`;
        }
        const observacoes = arrData.observacoes ? arrData.observacoes : '';

        arrArtigos.push({
            id: id,
            artigo_id: arrData.artigo_id,
            idDiv: idDiv,
            observacoes: observacoes
        })

        let nome = 'N/C'
        let descricao = 'N/C';

        try {
            const response = await commonFunctions.getRecurseWithTrashed(urlRefArtigos, { param: arrData.artigo_id });
            nome = response.data.nome;
            descricao = response.data.descricao;
        } catch (error) {
            console.error(error);
            $.notify(`Não foi possível obter os dados do ID Artigo ${arrData.artigo_id} para o preso.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');
        }
        let strPreso = `
            <div id="${idDiv}" class="card col-lg-4 col-sm-6 p-0">
                <div class="card-header py-1">
                    ${nome}
                </div>
                <div class="card-body p-1">
                    <div class="row m-0 p-0">
                        <div class="col px-1" style="max-heigth: 100px;">
                            <h5 class="card-title">
                                ${descricao}
                            </h5>
                            <p class="card-text">${commonFunctions.formatStringToHTML(observacoes)}</p>
                        </div>
                        <div class="col-sm-2 d-flex flex-sm-column px-1">
                            <button class="btn btn-sm btn-outline-primary btn-edit" title="Editar observação"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" title="Excluir artigo"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>`;

        containerArtigos.append(strPreso);
        arrData['idDiv'] = idDiv;
        addEventosBotoesDaConsulta(arrData);

        return idDiv;
    }

    function addEventosBotoesDaConsulta(arrData) {

        const idDiv = arrData.idDiv;
        const div = $(`#${idDiv}`);

        div.find('.btn-edit').on('click', function () {
            const index = arrArtigos.findIndex((item) => item.idDiv === arrData.idDiv);
            if (index != -1) {
                const obj = new modalCadastroPresoArtigo();
                obj.setArrData = { ...arrArtigos[index] };
                obj.modalOpen().then(async function (result) {
                    console.log(result)
                    if (result && result.refresh) {
                        const response = await commonFunctions.getRecurseWithTrashed(urlRefArtigos, { param: arrArtigos[index].artigo_id });
                        arrArtigos[index].observacoes = result.arrData.observacoes ? result.arrData.observacoes : '';

                        const card = $(`#${result.arrData.idDiv}`);
                        card.find('.card-header').html(response.data.nome);
                        card.find('.card-title').html(response.data.descricao);
                        card.find('.card-text').html(commonFunctions.formatStringToHTML(arrArtigos[index].observacoes));
                    }
                });
            } else {
                message = 'Artigo não encontrado no Array Artigos'
                console.error(message);
                console.error(arrArtigos, `Index: ${index}`);
                $.notify(`Não foi possível editar o artigo.\nSe o problema persistir consulte o programador.\nErro: ${message}`, 'error');
            }

        });


        div.find('.btn-delete').on("click", function () {
            console.log(arrArtigos)
            console.log(arrData.idDiv);
            arrArtigos = arrArtigos.filter((item) => item.idDiv != arrData.idDiv);

            if (arrData.id) {
                acaoBtnDeletar(idDiv, this);
            } else {
                div.remove();
            }
        });

    }

    function acaoBtnDeletar(idDiv, button) {

        const obj = new modalMessage();
        obj.setMessage(`Confirma a exclusão deste artigo para o preso?`);
        obj.setTitle('Confirmação de exclusão de artigo');
        obj.setFocusElementWhenClosingModal(button);
        obj.modalOpen().then(function (result) {

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

        const presos = containerArtigos.children();
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

        if (passagem_id) {
            obj.setParam(passagem_id);
            action = enumAction.PUT;
        }
        if (obj.setAction(action)) {

            const btn = $('#btnSalvar');
            funcoesComuns.simulacaoCarregando(btn);

            obj.setData(data);
            obj.envRequest()
                .then(function (result) {
                    const token = result.token;

                    let btn = funcoesComuns.formularioRedirecionamento(redirect, [
                        { name: 'arrNotifyMessage', value: [{ message: `Entrada de Presos ${passagem_id} alterada com sucesso!`, type: 'success' }] },
                        { name: '_token', value: token }
                    ]);
                    btn.click();

                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');

                })
                .finally(function () {
                    funcoesComuns.simulacaoCarregando(btn, false);
                });
        }

    }

    init();

});