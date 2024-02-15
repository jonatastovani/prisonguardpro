import { conectAjax } from "../../../ajax/conectAjax.js";
import { commonFunctions } from "../../../common/commonFunctions.js";
import { configuracoesApp } from "../../../common/configuracoesApp.js";
import { enumAction } from "../../../common/enumAction.js";
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
        preencherTodosSelects();

        commonFunctions.addEventsSelect2($('#cidade_nasc_id'), `${urlRefCidades}/search/select2`);

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
            commonFunctions.fillSelect($('#genero_id'), `${urlRefGenero}`, { idOpcaoSelecionada: 1 });
        }
        // preencherGenero();

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
            commonFunctions.fillSelect($('#escolaridade_id'), `${urlRefEscolaridade}`);
        }
        // preencherEscolaridade();

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
            commonFunctions.fillSelect($('#estado_civil_id'), `${urlRefEstadoCivil}`);
        }
        // preencherEstadoCivil();

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
            commonFunctions.fillSelect($('#cutis_id'), `${urlRefCutis}`);
        }
        // preencherCutis();

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
            commonFunctions.fillSelect($('#cabelo_tipo_id'), `${urlRefCabeloTipo}`);
        }
        // preencherCabeloTipo();

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
            commonFunctions.fillSelect($('#cabelo_cor_id'), `${urlRefCabeloCor}`);
        }
        // preencherCabeloCor();

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
            commonFunctions.fillSelect($('#olho_tipo_id'), `${urlRefOlhoTipo}/comdescricao`, { idOpcaoSelecionada: 1 });
        }
        // preencherOlhoTipo();

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
            commonFunctions.fillSelect($('#olho_cor_id'), `${urlRefOlhoCor}`);
        }
        // preencherOlhoCor();

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
            commonFunctions.fillSelect($('#crenca_id'), `${urlRefCrenca}`);
        }
        // preencherCrenca();

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

        buscarDados();

        inserirArtigos({
            artigo_id: 1,
            observacoes: 'Observacoes do artigo id 1'
        })
        inserirArtigos({
            artigo_id: 2,
            observacoes: 'Observacoes do artigo id 2'
        })
        inserirArtigos({
            artigo_id: 3,
            observacoes: `Observacoes do artigo id 3`
        })
        // inserirArtigos({
        //     artigo_id: 4,
        //     observacoes: 'Observacoes do artigo id 4'
        // })

    };

    async function preencherTodosSelects() {
        const requests = [
            commonFunctions.fillSelect($('#genero_id'), `${urlRefGenero}`, { idOpcaoSelecionada: 1 }),
            commonFunctions.fillSelect($('#escolaridade_id'), `${urlRefEscolaridade}`),
            commonFunctions.fillSelect($('#estado_civil_id'), `${urlRefEstadoCivil}`),
            commonFunctions.fillSelect($('#cutis_id'), `${urlRefCutis}`),
            commonFunctions.fillSelect($('#cabelo_tipo_id'), `${urlRefCabeloTipo}`),
            commonFunctions.fillSelect($('#cabelo_cor_id'), `${urlRefCabeloCor}`),
            commonFunctions.fillSelect($('#olho_tipo_id'), `${urlRefOlhoTipo}/comdescricao`, { idOpcaoSelecionada: 1 }),
            commonFunctions.fillSelect($('#olho_cor_id'), `${urlRefOlhoCor}`),
            commonFunctions.fillSelect($('#crenca_id'), `${urlRefCrenca}`)
        ];

        try {
            await Promise.all(requests);
        } catch (error) {
            console.error("Erro ao preencher os selects:", error);
        }
    }

    $('#btnInserirPreso').on("click", (event) => {

        const idDiv = inserirArtigos();
        $(`#${idDiv}`).find('input[name="matricula"]').focus();

    });

    $('#btnSalvar').on("click", (event) => {

        acaoBtnSalvar();

    });

    function buscarDados() {

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

                if (response.preso) {
                    preencherQualificativa(response.data);
                } else {
                    preencherQualificativaProvisoria(response.data);
                }

            })
            .catch(function (error) {
                $('input, .btn, select, textarea').prop('disabled', true);
                $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o programador.\nErro: ${error.message}`, 'error');
                console.log(error);
            });

        // }

    }

    function preencherQualificativa(data) {

        console.log('Normal = ', data);

        const preso = data.preso;

        $('#matricula').val(preso.matricula);
        $('#nome').val(preso.nome);
        $('#nome_social').val(preso.nome_social);
        $('#mae').val(preso.mae);
        $('#pai').val(preso.pai);
        if (preso.cidade) {
            $('#cidade_nasc_id').html(new Option(`${preso.cidade.nome} - ${preso.cidade.estado.sigla} | ${preso.cidade.estado.nacionalidade.pais}`, preso.cidade_id, true, true)).trigger('change');
        }
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
        $('#informacoes').val(data.informacoes);
        $('#observacoes').val(data.observacoes);

    }

    function preencherQualificativaProvisoria(data) {

        console.log('Provisória = ', data);

        $('#matricula').val(data.matricula);
        $('#nome').val(data.nome);
        $('#nome_social').val(data.nome_social);
        $('#mae').val(data.mae);
        $('#pai').val(data.pai);
        if (data.cidade) {
            $('#cidade_nasc_id').html(new Option(`${data.cidade.nome} - ${data.cidade.estado.sigla} | ${data.cidade.estado.nacionalidade.pais}`, data.cidade_id, true, true)).trigger('change');
        }
        $('#data_nasc').val(data.data_nasc);
        $('#genero_id').val(data.genero_id);
        $('#escolaridade_id').val(data.escolaridade_id);
        $('#estado_civil_id').val(data.estado_civil_id);
        $('#cutis_id').val(data.cutis_id);
        $('#cabelo_tipo_id').val(data.cabelo_tipo_id);
        $('#cabelo_cor_id').val(data.cabelo_cor_id);
        $('#olho_tipo_id').val(data.olho_tipo_id);
        $('#olho_cor_id').val(data.olho_cor_id);
        $('#crenca_id').val(data.crenca_id);
        $('#sinais').val(data.sinais);
        $('#informacoes').val(data.informacoes);
        $('#observacoes').val(data.observacoes);

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
        addEventosArtigos(arrData);

        return idDiv;
    }

    function addEventosArtigos(arrData) {

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

        let data = commonFunctions.getInputsValues($('#dadosQualificativa'));
        data['matricula'] = funcoesPresos.insereDigitoMatricula(data['matricula']);
        data['artigos'] = arrArtigos;
        data['passagem_id'] = passagem_id;
        data['qual_prov_id'] = qual_prov_id;
        data['preso_id_bln'] = preso_id_bln;
        data['perm_atribuir_matricula_bln'] = perm_atribuir_matricula_bln;

        salvar(data);
    }

    function salvar(data) {

        const obj = new conectAjax(urlIncQualificativa);
        let action = enumAction.POST;
        console.log(data);

        if (passagem_id) {
            obj.setParam(passagem_id);
            action = enumAction.PUT;
        }
        obj.setAction(action)

        const btn = $('#btnSalvar');
        commonFunctions.simulateLoading(btn);

        obj.setData(data);
        obj.envRequest()
            .then(function (result) {
                console.log(result);
                // const token = result.token;

                // let btn = funcoesComuns.formularioRedirecionamento(redirect, [
                //     { name: 'arrNotifyMessage', value: [{ message: `Entrada de Presos ${passagem_id} alterada com sucesso!`, type: 'success' }] },
                //     { name: '_token', value: token }
                // ]);
                // btn.click();

            })
            .catch(function (error) {

                console.error(error);
                $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');

            })
            .finally(function () {
                commonFunctions.simulateLoading(btn, false);
            });


    }

    init();

});