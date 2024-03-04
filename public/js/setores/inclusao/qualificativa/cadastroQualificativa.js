import { conectAjax } from "../../../ajax/conectAjax.js";
import { commonFunctions } from "../../../common/commonFunctions.js";
import { configuracoesApp } from "../../../common/configuracoesApp.js";
import { enumAction } from "../../../common/enumAction.js";
import { funcoesPresos } from "../../../common/funcoesPresos.js";
import { modalLoading } from "../../../common/modalLoading.js";
import { modalMessage } from "../../../common/modalMessage.js";
import { modalCadastroPessoaDocumento } from "../../../modals/pessoa/modalCadastroPessoaDocumento.js";
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
    let qual_prov_id = $('#qual_prov_id').val();
    let preso_id_bln = $('#preso_id_bln').val();
    let preso_id = undefined;
    let perm_atribuir_matricula_bln = $('#perm_atribuir_matricula_bln').val();
    const redirect = $('.redirectUrl').attr('href');
    const containerArtigos = $('#containerArtigos');
    const containerDocumentos = $('#containerDocumentos');
    let arrArtigos = [];
    let arrDocumentos = [];
    const loading = new modalLoading();

    async function init() {

        const matricula = $('#matricula');
        commonFunctions.applyCustomNumberMask(matricula, { format: configuracoesApp.mascaraMatriculaSemDigito(), reverse: true });

        loading.modalOpen();

        $("#modalLoading").modal('show');

        matricula.on('input', function () {
            $('#digito').val(funcoesPresos.retornaDigitoMatricula(matricula.val()));
        })

        commonFunctions.addEventsSelect2($('#cidade_nasc_id'), `${urlRefCidades}/search/select2`);
        await preencherTodosSelects();

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
                if (result && result.arrData.artigo_id) {
                    inserirArtigos(result.arrData);
                }
            });
        });

        $(`#btnAddDocumento`).on('click', function () {
            const obj = new modalCadastroPessoaDocumento();
            obj.setFocusElementWhenClosingModal = this;
            obj.modalOpen().then(function (result) {
                if (result && result.arrData.documento_id) {
                    inserirDocumentos(result.arrData);
                }
            });
        });

        buscarDados();

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

                if (response.data.preso) {
                    preencherQualificativa(response.data);
                } else {
                    preencherQualificativaProvisoria(response.data);
                }

                if (response.data.artigos) {
                    response.data.artigos.forEach(artigo => {
                        inserirArtigos(artigo)
                    });
                }

            })
            .catch(function (error) {
                $('input, .btn, select, textarea').prop('disabled', true);
                commonFunctions.generateNotification(error.message, 'error', { traceId: error.traceId ? error.traceId : undefined });
                console.log(error);
            })
            .finally(function () {
                loading.modalClose();
            });

        // }

    }

    function preencherQualificativa(data) {

        console.log('Normal = ', data);

        const preso = data.preso;
        preso_id = preso.id;

        $('#matricula').val(funcoesPresos.retornaMatriculaFormatada(preso.matricula, 2)).trigger('input');
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
        const qual_prov = data.qual_prov;

        let matricula = data.matricula ? funcoesPresos.retornaMatriculaFormatada(data.matricula, 2) : '';
        $('#matricula').val(matricula).trigger('input');
        $('#nome').val(data.nome);
        $('#nome_social').val(data.nome_social);
        $('#informacoes').val(data.informacoes);
        $('#observacoes').val(data.observacoes);

        if (qual_prov) {
            $('#mae').val(qual_prov.mae);
            $('#pai').val(qual_prov.pai);
            if (qual_prov.cidade_nasc) {
                $('#cidade_nasc_id').html(new Option(`${qual_prov.cidade_nasc.nome} - ${qual_prov.cidade_nasc.estado.sigla} | ${qual_prov.cidade_nasc.estado.nacionalidade.pais}`, qual_prov.cidade_nasc_id, true, true)).trigger('change');
            }
            $('#data_nasc').val(qual_prov.data_nasc);
            $('#genero_id').val(qual_prov.genero_id);
            $('#escolaridade_id').val(qual_prov.escolaridade_id);
            $('#estado_civil_id').val(qual_prov.estado_civil_id);
            $('#cutis_id').val(qual_prov.cutis_id);
            $('#cabelo_tipo_id').val(qual_prov.cabelo_tipo_id);
            $('#cabelo_cor_id').val(qual_prov.cabelo_cor_id);
            $('#olho_tipo_id').val(qual_prov.olho_tipo_id);
            $('#olho_cor_id').val(qual_prov.olho_cor_id);
            $('#crenca_id').val(qual_prov.crenca_id);
            $('#sinais').val(qual_prov.sinais);

            if (data.documentos) {
                data.documentos.forEach(documento => {
                    console.log(documento)
                    inserirDocumentos(documento)
                });
            }


        }

    }

    async function inserirArtigos(arrDataArtigo) {

        const id = arrDataArtigo.id ? arrDataArtigo.id : '';
        let idDiv = '';
        if (!arrDataArtigo.idDiv) {
            idDiv = `${id}${Date.now()}`;
        }
        const observacoes = arrDataArtigo.observacoes ? arrDataArtigo.observacoes : '';

        arrArtigos.push({
            id: id,
            artigo_id: arrDataArtigo.artigo_id,
            idDiv: idDiv,
            observacoes: observacoes
        })

        let nome = 'N/C'
        let descricao = 'N/C';

        try {
            const response = await commonFunctions.getRecurseWithTrashed(urlRefArtigos, { param: arrDataArtigo.artigo_id });
            nome = response.data.nome;
            descricao = response.data.descricao;
        } catch (error) {
            console.error(error);
            commonFunctions.generateNotification(`Não foi possível obter os dados do ID Artigo ${arrDataArtigo.artigo_id} para o preso. <br>Erro: ${error.message}`, 'error', { traceId: error.traceId ? error.traceId : undefined });
        }

        let strArtigo = `
            <div id="${idDiv}" class="col p-0">
                <div class="card p-0">
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
                            <div class="col-2 d-flex justify-content-end px-1">
                                <div class="btn-group-vertical" role="group">
                                    <button class="btn btn-mini btn-outline-primary btn-edit" title="Editar observação"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-mini btn-outline-danger btn-delete" title="Excluir artigo"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        containerArtigos.append(strArtigo);
        arrDataArtigo['idDiv'] = idDiv;
        addEventosArtigos(arrDataArtigo);

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
                commonFunctions.generateNotification(`Não foi possível editar o artigo. <br>Erro: ${message}`, 'error');
            }

        });


        div.find('.btn-delete').on("click", function () {
            if (arrData.id) {
                acaoBtnDeletarArtigo(idDiv, this);
            } else {
                div.remove();
            }
        });

    }

    function acaoBtnDeletarArtigo(idDiv, button) {

        const obj = new modalMessage();
        obj.setMessage = `Confirma a exclusão deste artigo para o preso?`;
        obj.setTitle = 'Confirmação de exclusão de artigo';
        obj.setFocusElementWhenClosingModal = button;
        obj.modalOpen().then(function (result) {

            if (result) {
                $(`#${idDiv}`).remove();
                arrArtigos = arrArtigos.filter((item) => item.idDiv != arrData.idDiv);
            }

        });

    }

    async function inserirDocumentos(arrDataDocumento) {

        const id = arrDataDocumento.id ? arrDataDocumento.id : '';
        let idDiv = '';
        if (!arrDataDocumento.idDiv) {
            idDiv = `${id}${Date.now()}`;
        }
        let numero = arrDataDocumento.numero;
        const digito = arrDataDocumento.digito ? arrDataDocumento.digito : '';
        const data_emissao = arrDataDocumento.data_emissao ? arrDataDocumento.data_emissao : '';

        arrDocumentos.push({
            id: id,
            documento_id: arrDataDocumento.documento_id,
            idDiv: idDiv,
            numero: numero,
            digito: digito,
            data_emissao: data_emissao,
        })

        let nome = 'N/C';
        let strDataEmissao = '';
        if(data_emissao) {
            strDataEmissao = `Emissão: ${moment(data_emissao).format('DD/MM/YYYY')}`;
        }

        try {
            const response = await commonFunctions.getRecurseWithTrashed(urlRefDocumentos, { param: arrDataDocumento.documento_id });
            nome = response.data.nome;
            if(digito) {
                numero += `${response.data.digito_separador}${digito}`
            }

        } catch (error) {
            console.error(error);
            commonFunctions.generateNotification(`Não foi possível obter os dados do ID Documento ${arrDataDocumento.documento_id} para o preso. <br>Erro: ${error.message}`, 'error', { traceId: error.traceId ? error.traceId : undefined });
        }

        let strDocumento = `
            <div id="${idDiv}" class="col p-0">
                <div class="card p-0">
                    <div class="card-header py-1">
                        ${nome}
                    </div>
                    <div class="card-body p-1">
                        <div class="row m-0 p-0">
                            <div class="col px-1" style="max-heigth: 100px;">
                                <h5 class="card-title">
                                    ${numero}
                                </h5>
                                <p class="card-text">${strDataEmissao}</p>
                            </div>
                            <div class="col-2 d-flex justify-content-end px-1">
                                <div class="btn-group-vertical" role="group">
                                    <button class="btn btn-outline-primary btn-mini btn-edit" data-bs-toggle="tooltip" data-bs-title="Editar documento"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger btn-delete" data-bs-toggle="tooltip" data-bs-title="Excluir documento"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        containerDocumentos.append(strDocumento);
        arrDataDocumento['idDiv'] = idDiv;
        addEventosDocumentos(arrDataDocumento);

        return idDiv;
    }

    function addEventosDocumentos(arrData) {

        const idDiv = arrData.idDiv;
        const div = $(`#${idDiv}`);

        div.find('.btn-edit').on('click', function () {
            const index = arrDocumentos.findIndex((item) => item.idDiv === arrData.idDiv);
            if (index != -1) {
                const obj = new modalCadastroPessoaDocumento();
                obj.setArrData = { ...arrDocumentos[index] };
                obj.modalOpen().then(async function (result) {
                    if (result && result.refresh) {
                        const response = await commonFunctions.getRecurseWithTrashed(urlRefDocumentos, { param: arrDocumentos[index].documento_id });
                        arrDocumentos[index].numero = result.arrData.numero;
                        arrDocumentos[index].digito = result.arrData.digito ? result.arrData.digito : '';
                        arrDocumentos[index].data_emissao = result.arrData.data_emissao ? result.arrData.data_emissao : '';

                        let numero = arrDocumentos[index].numero;
                        let strDataEmissao = ''
                        if(arrDocumentos[index].digito) {
                            numero += `${response.data.digito_separador}${arrDocumentos[index].digito}`
                        }
                        if(arrDocumentos[index].data_emissao) {
                            strDataEmissao = `Emissão: ${moment(arrDocumentos[index].data_emissao).format('DD/MM/YYYY')}`;
                        }

                        const card = $(`#${result.arrData.idDiv}`);
                        card.find('.card-header').html(response.data.nome);
                        card.find('.card-title').html(numero);
                        card.find('.card-text').html(strDataEmissao);
                    }
                });
            } else {
                message = 'Documento não encontrado no Array Documentos'
                console.error(message);
                console.error(arrDocumentos, `Index: ${index}`);
                commonFunctions.generateNotification(`Não foi possível editar o documento. <br>Erro: ${message}`, 'error');
            }

        });


        div.find('.btn-delete').on("click", function () {
            if (arrData.id) {
                acaoBtnDeletarDocumento(idDiv, this);
            } else {
                div.remove();
            }
        });

    }

    function acaoBtnDeletarDocumento(idDiv, button) {

        const obj = new modalMessage();
        obj.setMessage = `Confirma a exclusão deste documento para o preso?`;
        obj.setTitle = 'Confirmação de exclusão de documento';
        obj.setFocusElementWhenClosingModal = button;
        obj.modalOpen().then(function (result) {

            if (result) {
                $(`#${idDiv}`).remove();
                arrDocumentos = arrDocumentos.filter((item) => item.idDiv != idDiv);
            }

        });

    }

    function acaoBtnSalvar() {

        let data = commonFunctions.getInputsValues($('#dadosQualificativa'));
        data['matricula'] = funcoesPresos.insereDigitoMatriculaAoSalvar(data['matricula']);
        data['artigos'] = arrArtigos;
        data['documentos'] = arrDocumentos;
        data['qual_prov_id'] = qual_prov_id;
        data['preso_id'] = preso_id;
        // data['perm_atribuir_matricula_bln'] = perm_atribuir_matricula_bln;

        let blnSalvar = true;
        const idsEncontradosArtigos = new Set();
        const idsEncontradosDocumentos = new Set();
        let itemsMessage = [];
        
        data.artigos.forEach(artigo => {
            const cardArtigo = $(`#${artigo.idDiv}`).find('.card');
            if (idsEncontradosArtigos.has(String(artigo.artigo_id))) {
                blnSalvar = false;
                cardArtigo.addClass('text-bg-warning');
                itemsMessage.push(`O Artigo ${cardArtigo.find('.card-header').html()} está duplicado.`);
            } else {
                idsEncontradosArtigos.add(String(artigo.artigo_id));
                cardArtigo.find('.card').removeClass('text-bg-warning');
            }
        });

        data.documentos.forEach(documento => {
            const cardDocumento = $(`#${documento.idDiv}`).find('.card');
            if (idsEncontradosDocumentos.has(String(documento.documento_id))) {
                blnSalvar = false;
                cardDocumento.addClass('text-bg-warning');
                itemsMessage.push(`O Documento ${cardDocumento.find('.card-header').html()} está duplicado.`);
            } else {
                idsEncontradosDocumentos.add(String(documento.documento_id));
                cardDocumento.removeClass('text-bg-warning');
            }
        });

        if(blnSalvar){
            salvar(data);
        } else {
            commonFunctions.generateNotification('Os dados não foram enviados pelo(s) seguinte(s) motivo(s):','warning', {itemsArray: itemsMessage})
        }
    }

    function salvar(data) {

        const obj = new conectAjax(urlIncQualificativa);
        let action = enumAction.POST;
        console.log(data);

        if (preso_id || qual_prov_id) {
            action = enumAction.PUT
            obj.setParam(passagem_id);
        } else {
            action = enumAction.POST;
            data['passagem_id'] = passagem_id;
        }

        obj.setAction(action)

        const btn = $('#btnSalvar');

        commonFunctions.simulateLoading(btn);

        obj.setData(data);
        obj.envRequest()
            .then(function (response) {
                const token = response.token;
                console.log(response)
                const nome = response.data.nome_social ? response.data.nome_social : response.data.nome;
                const message = `Qualificativa do(a) preso(a) ${commonFunctions.cutText(nome, { firstLastName: true })} enviada com sucesso.`;

                let btn = commonFunctions.redirectForm(redirect, [
                    { name: 'arrNotifyMessage', value: [{ message: message, type: 'success' }] },
                    { name: '_token', value: token }
                ]);
                btn.click();

            })
            .catch(function (error) {

                console.error(error);
                const traceId = error.traceId ? error.traceId : undefined;
                commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });

            })
            .finally(function () {
                commonFunctions.simulateLoading(btn, false);
            });

    }

    init();

});