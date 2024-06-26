import { conectAjax } from "../../ajax/conectAjax.js";
import { bootstrapFunctions } from "../../common/bootstrapFunctions.js";
import { commonFunctions } from "../../common/commonFunctions.js";
import { enumAction } from "../../common/enumAction.js";
import { modalMessage } from "../../common/modalMessage.js";
import { validations } from "../../common/validations.js";
import { modalCadastroDocumentoTipo } from "./modalCadastroDocumentoTipo.js";
import { modalCadastroEstado } from "./modalCadastroEstado.js";

export class modalCadastroDocumento {

    /**
     * URL do endpoint da Api
     */
    #urlApi;
    /**
     * URL do endpoint da Api (Documento Tipos)
     */
    #urlApiDocTipos;
    /**
     * ID do modal
     */
    #idModal;
    /** 
     * Conteúdo a ser retornado na promisse como resolve()
    */
    #promisseReturnValue;
    /**
     * Variável que executará o fim do setInterval de retoro da promisse com reject()
     */
    #endTimer;
    /**
     * Elemento foco ao fechar modal
     */
    #focusElementWhenClosingModal;
    /**
     * Elemento de foco ao fechar o modal
     */
    #action;
    /**
     * ID do cadastro que está sendo alterado
     */
    #idRegister;
    /**
     * Variável para reservar o timeOut da consulta pelo search
     */
    timerSearch;

    constructor() {
        this.#urlApi = urlRefDocumentos;
        this.#urlApiDocTipos = urlRefDocumentoTipos;
        this.#idModal = "#modalCadastroDocumento";
        this.#promisseReturnValue = undefined;
        this.#focusElementWhenClosingModal = null;
        this.#endTimer = false;
        this.#action = enumAction.POST;
        this.#idRegister = null;
        this.#addEventsDefault();
    }

    /**
     * Retorna o ID do Modal.
     */
    get getIdModal() {
        return this.#idModal;
    }

    /**
     * Define o elemento de foco de fechamento.
     * @param {jQuery} elem - O elemento jQuery a ser definido como foco de fechamento.
     */
    set setFocusElementWhenClosingModal(elem) {
        this.#focusElementWhenClosingModal = elem;
    }

    /**
     * Define o valor do timer de fim, utilizado na função modalOpen.
     * @param {Boolean} value - Novo valor para indicar o término do timer.
     */
    set setEndTimer(value) {
        this.#endTimer = value;
    }

    modalOpen() {

        const self = this;

        self.generateFilters();
        self.modalCancel();

        self.#promisseReturnValue = {
            refresh: false
        };
        $(self.#idModal).modal('show');

        return new Promise(function (resolve) {

            const checkConfirmation = setInterval(function () {
                if (self.#endTimer) {
                    clearInterval(checkConfirmation);
                    resolve(self.#promisseReturnValue);
                    self.#modalClose();
                    self.#endTimer = false;
                }
            }, 250);

        });

    }

    #modalHideShow(status = true) {
        if (status) {
            $(this.#idModal).modal('show')
        } else {
            $(this.#idModal).modal('hide')
        }
    }

    #modalClose() {

        const self = this;
        const modal = $(self.#idModal);

        $(self.#idModal).modal('hide');
        modal.find('.table tbody').html('');
        modal.find("*").off();
        modal.off('keydown');
        self.modalCancel();

        if (self.#focusElementWhenClosingModal !== null && $(self.#focusElementWhenClosingModal).length) {
            $(self.#focusElementWhenClosingModal).focus();
            self.#focusElementWhenClosingModal = null;
        }

    }

    modalCancel() {

        const self = this;

        self.#clearForm();
        self.#actionsHideShowRegistrationFields();
        setTimeout(() => {
            $(self.#idModal).find('.btnNewRegister').focus();
        }, 500);

    }

    #clearForm() {

        const self = this;
        const modal = $(self.#idModal);

        self.#idRegister = null;
        self.#action = enumAction.POST;
        modal.find('form')[0].reset();
        modal.find('form').find('select').val('').trigger('change');
        modal.find('input:checkbox').trigger('change');
        modal.find('select[name="documento_tipo_id"]').removeAttr('disabled');
    }

    #actionsHideShowRegistrationFields(status = false) {

        const self = this;
        const modal = $(self.#idModal);

        if (status) {
            modal.find('.divBtnAdd').slideUp();
            modal.find(".divRegistrationFields").slideDown();
        } else {
            modal.find('.divBtnAdd').slideDown();
            modal.find(".divRegistrationFields").slideUp();
        }

    }

    #addEventsDefault() {

        const self = this;
        const modal = $(self.#idModal);
        commonFunctions.eventDefaultModals(self, { formRegister: true, inputsSearchs: modal.find('.inputActionSearchModalCadastroDocumento') });
        
        modal.find('input[name="validade_emissao_int"]').mask('999');

        modal.find(".btnNewRegister").on("click", () => {
            self.#action = enumAction.POST;
            modal.find('.register-title').html('Novo Documento');
            self.#actionsHideShowRegistrationFields(true);
            modal.find('select[name="documento_tipo_id"]').focus();
        });

        commonFunctions.addEventsSelect2($('#estado_idModalCadastroDocumento'), `${urlRefEstados}/search/select2`, { dropdownParent: modal, minimum: 0 });
        commonFunctions.addEventsSelect2($('#orgao_emissor_idModalCadastroDocumento'), `${urlRefDocumentoOrgaoEmissor}/search/select2`, { dropdownParent: modal, minimum: 0 });
        commonFunctions.addEventsSelect2($('#nacionalidade_idModalCadastroDocumento'), `${urlRefNacionalidades}/search/select2`, { dropdownParent: modal, minimum: 0 });

        const preencherDocumentoTipo = () => {
            commonFunctions.fillSelect($('#documento_tipo_idModalCadastroDocumento'), `${urlRefDocumentoTipos}`);
        }
        preencherDocumentoTipo();

        let arrayValidationsTypes = validations.getArrayValidationsTypes.map((item) => ({ id: item, nome: item }));
        commonFunctions.fillSelectArray($('#validation_typeModalCadastroDocumento'), arrayValidationsTypes);

        modal.find(`.btnDocumentoTipoCadastro`).on('click', function (e) {
            e.preventDefault();
            const obj = new modalCadastroDocumentoTipo();
            obj.setFocusElementWhenClosingModal = this;
            self.#modalHideShow(false);
            obj.modalOpen().then(async function (result) {
                if (result && result.refresh) {

                    preencherDocumentoTipo();
                    setTimeout(() => {
                        modal.find('select[name="documento_tipo_id"]').focus();
                    }, 500);
                    self.#promisseReturnValue.refresh = true;

                }
                self.#modalHideShow();
            });
        });

        modal.find(`.btnEstadoCadastro`).on('click', function (e) {
            e.preventDefault();
            const obj = new modalCadastroEstado();
            obj.setFocusElementWhenClosingModal = this;
            self.#modalHideShow(false);
            obj.modalOpen().then(async function (result) {
                if (result && result.refresh) {

                    setTimeout(() => {
                        modal.find('select[name="estado_id"]').focus();
                    }, 500);
                    self.#promisseReturnValue.refresh = true;

                }
                self.#modalHideShow();
            });
        });

        $('#documento_tipo_idModalCadastroDocumento').on('change', async function () {
            if (!commonFunctions.getInvalidsDefaultValuesGenerateFilters().includes($(this).val())) {
                try {
                    const obj = new conectAjax(`${self.#urlApiDocTipos}`);
                    obj.setParam($(this).val());
                    const response = await obj.getRequest();
                    console.log(response)
                    if (response.data) {
                        if (response.data.doc_nacional_bln) {
                            modal.find('.rowNacionalidade').show('fast').find('input, select, button').removeAttr('disabled');
                            modal.find('.rowEstado').hide('fast').find('input, select, button').attr('disabled', true);
                        } else {
                            modal.find('.rowNacionalidade').hide('fast').find('input, select, button').attr('disabled', true);
                            modal.find('.rowEstado').show('fast').find('input, select, button').removeAttr('disabled');
                        }
                    }
                } catch (error) {
                    console.error(error);
                    const traceId = error.traceId ? error.traceId : undefined;
                    commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });
                }
            } else {
                modal.find('.rowNacionalidade').hide('fast').find('input, select, button').attr('disabled', true);
                modal.find('.rowEstado').hide('fast').find('input, select, button').attr('disabled', true);
            }
        })

        commonFunctions.eventCkbHidden(modal.find('input[name="digito_bln"]'), modal.find('.rowDigito'))

    }

    async generateFilters() {

        const self = this;
        const dataSearch = $(self.#idModal).find('.dataSearch');

        let data = {
            text: dataSearch.find('input[name="search"]').val()
        };
        await self.#getDataAll(data);

    }

    async #getDataAll(data) {

        const self = this;

        try {
            const obj = new conectAjax(`${self.#urlApi}/search/all`);
            const tabela = $(self.#idModal).find('.table tbody');
            tabela.html('');

            if (obj.setAction(enumAction.POST)) {
                obj.setData(data);
                const response = await obj.envRequest();
                if (response.data.length) {
                    for (let i = 0; i < response.data.length; i++) {
                        const item = response.data[i];

                        const documento_tipo = item.documento_tipo;

                        let nome = '';
                        let title = '';
                        if (documento_tipo.doc_nacional_bln) {
                            nome = `${documento_tipo.nome} - ${item.nacionalidade.sigla}`;
                            title = `Documento nacional do(a) ${item.nacionalidade.pais}`
                        } else {
                            nome = `${documento_tipo.nome} - ${item.estado.sigla}/${item.orgao_emissor.sigla}`;
                            title = `${item.estado.nome} - ${item.estado.nacionalidade.pais} / ${item.orgao_emissor.nome}`
                        }
                        const mascara = item.mask;

                        const idTr = `${item.id}${Date.now()}`;
                        tabela.append(`
                            <tr id=${idTr}>
                                <td class="text-center"><b>${item.id}</b></td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-outline-primary btn-mini-2 btn-edit" title="Editar cadastro"><i class="bi bi-pencil"></i> </button>
                                            <button class="btn btn-outline-danger btn-mini-2 btn-delete" title="Deletar cadastro"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </td>
                                <td data-bs-title="${title}" data-bs-toggle="tooltip">${nome}</td>
                                <td>${mascara}</td>
                            </tr>
                        `);

                        item['idTr'] = idTr;

                        self.#addQueryEvents(item);

                        // Adicionar atraso a cada 1000 registros
                        if ((i + 1) % 1000 === 0 && i !== response.data.length - 1) {
                            await new Promise(resolve => setTimeout(resolve, 10));
                        }
                    }
                }
                bootstrapFunctions.addEventTooltip();
            } else {
                throw new Error('Action inválido');
            }
        } catch (error) {
            self.#endTimer = true;
            console.error(error);
            const traceId = error.traceId ? error.traceId : undefined;
            commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });
        }

    }

    async #getRecurse() {

        const self = this;
        const obj = new conectAjax(`${self.#urlApi}`);
        obj.setParam(self.#idRegister);
        try {
            const response = await obj.getRequest();
            if (response.data) {
                const data = response.data;

                const form = $(self.#idModal).find('form');
                form.find('select[name="documento_tipo_id"]').val(data.documento_tipo_id).trigger('change').attr('disabled', true);
                form.find('input[name="mask"]').val(data.mask).focus();
                form.find('input[name="validade_emissao_int"]').val(data.validade_emissao_int);
                form.find('input[name="reverse_bln"]').prop('checked', data.reverse_bln);
                form.find('input[name="digito_bln"]').prop('checked', data.digito_bln).trigger('change');
                form.find('select[name="validation_type"]').val(data.validation_type);

                if (data.digito_bln) {
                    form.find('input[name="digito_mask"]').val(data.digito_mask);
                    form.find('input[name="digito_separador"]').val(data.digito_separador);
                }
                if (data.estado_id) {
                    form.find('select[name="estado_id"]').html(new Option(`${data.estado.nome} - ${data.estado.sigla} (${data.estado.nacionalidade.pais})`, data.estado_id, true, true)).trigger('change');
                    form.find('select[name="orgao_emissor_id"]').html(new Option(`${data.orgao_emissor.sigla} (${data.orgao_emissor.nome})`, data.orgao_emissor_id, true, true)).trigger('change');
                }
                if (data.nacionalidade_id) {
                    form.find('select[name="nacionalidade_id"]').html(new Option(`${data.nacionalidade.pais} - ${data.nacionalidade.sigla}`, data.nacionalidade_id, true, true)).trigger('change');
                }

                const documento_tipo = data.documento_tipo;
                let nome = '';
                let title = '';
                if (documento_tipo.doc_nacional_bln) {
                    nome = `${documento_tipo.nome} - ${data.nacionalidade.sigla}`;
                    title = `Documento nacional do(a) ${data.nacionalidade.pais}`
                } else {
                    nome = `${documento_tipo.nome} - ${data.estado.sigla}/${data.orgao_emissor.sigla}`;
                    title = `${data.estado.nome} - ${data.estado.nacionalidade.pais} / ${data.orgao_emissor.nome}`
                }

                form.find('.register-title').attr('title', title).html(`Editar Documento: ${response.data.id} - ${nome}`);
            }
        } catch (error) {
            console.error(error);
            const traceId = error.traceId ? error.traceId : undefined;
            commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });
            self.#modalClose();
        }

    }

    #addQueryEvents(item) {

        const self = this;
        const tr = $(`#${item.idTr}`);

        tr.find(`.btn-edit`).click(async function () {
            self.#idRegister = item.id
            self.#action = enumAction.PUT;
            self.#actionsHideShowRegistrationFields(true);
            self.#getRecurse();
        })

        tr.find(`.btn-delete`).click(async function () {
            self.#delButtonAction(item.id, item.nome, this);
        })

    }

    saveButtonAction() {

        const self = this;
        let data = commonFunctions.getInputsValues($(self.#idModal).find('form')[0]);
        self.#save(data);

    }

    async #save(data) {

        const self = this;
        const obj = new conectAjax(self.#urlApi);

        if (obj.setAction(self.#action)) {

            const btn = $(self.#idModal).find('.btn-save');
            try {

                commonFunctions.simulateLoading(btn);

                obj.setData(data);

                if (self.#action === enumAction.PUT) {
                    obj.setParam(self.#idRegister);
                }

                const response = await obj.envRequest();
                if (response.data) {
                    commonFunctions.generateNotification(`Dados enviados com sucesso!`, 'success');

                    self.#promisseReturnValue.refresh = true;
                    self.generateFilters();
                    if (self.#action === enumAction.PUT) {
                        self.modalCancel();
                    } else {
                        self.#clearForm();
                        $(self.#idModal).find('form').find('input[name="nome"]').focus();
                    }

                }
            } catch (error) {

                console.log(error);
                const traceId = error.traceId ? error.traceId : undefined;
                commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });
            }
            finally {
                commonFunctions.simulateLoading(btn, false);
            };
        }

    }

    async #delButtonAction(idDel, nameDel, button = null) {

        const self = this;

        try {
            const obj = new modalMessage();
            obj.setTitle = 'Confirmação de exclusão de Documento';
            obj.setMessage = `Confirma a exclusão do Documento <b>${nameDel}</b>?`;
            obj.setFocusElementWhenClosingModal = button;
            self.#modalHideShow(false);
            const result = await obj.modalOpen();
            if (result) {
                self.#delRecurse(idDel);
            }
            self.#modalHideShow(true);

        } catch (error) {
            console.log(error);
            self.#modalHideShow(true);
        }

    }

    async #delRecurse(idDel) {

        const self = this;
        const obj = new conectAjax(self.#urlApi);

        if (obj.setAction(enumAction.DELETE)) {
            obj.setParam(idDel);
            try {
                const response = await obj.deleteRequest();

                commonFunctions.generateNotification(`Documento deletado com sucesso!`, 'success');
                self.#promisseReturnValue.refresh = true;

                self.modalCancel();
                self.generateFilters();

            } catch (error) {
                console.error(error);
                const traceId = error.traceId ? error.traceId : undefined;
                commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });
            }
        }

    }

}