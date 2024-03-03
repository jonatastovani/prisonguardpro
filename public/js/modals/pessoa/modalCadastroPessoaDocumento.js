import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../common/commonFunctions.js";
import { modalCadastroDocumento } from "../referencias/modalCadastroDocumento.js";

export class modalCadastroPessoaDocumento {

    /**
     * URL do endpoint da Api
     */
    #urlApi;
    /**
     * ID do modal
     */
    #idModal;
    /** 
     * ID da Div que está sendo alterada
    */
    #arrData;
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
     * Variável para reservar o timeOut da consulta pelo search
     */
    timerSearch;

    constructor() {
        this.#urlApi = urlRefDocumentos;
        this.#idModal = "#modalCadastroPessoaDocumento";
        this.#promisseReturnValue = undefined;
        this.#focusElementWhenClosingModal = null;
        this.#endTimer = false;
        this.#arrData = {
            idDiv: undefined,
            documento_id: null,
            observacoes: null
        };
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
     * Define o array com os dados do artigo que está sendo alterado.
     * @param {Array} arrData - Array com os dados do artigo.
     */
    set setArrData(arrData) {
        this.#arrData = arrData;
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

        self.modalCancel();

        self.#promisseReturnValue = {
            refresh: false,
            arrData: self.#arrData
        };
        if (self.#arrData.idDiv != undefined) {
            self.#fillDataAll();
        } else {
            $(self.#idModal).modal('show');
        }

        return new Promise(function (resolve) {

            const checkConfirmation = setInterval(function () {

                // if (self.#promisseReturnValue !== undefined || self.#endTimer) {
                if (self.#endTimer) {

                    clearInterval(checkConfirmation);
                    // if (self.#promisseReturnValue !== undefined) {
                    //     resolve(self.#promisseReturnValue);
                    // }
                    resolve(self.#promisseReturnValue);

                    self.#modalClose();
                    // self.#promisseReturnValue = undefined;
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

        modal.modal('hide');
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
        setTimeout(() => {
            $(self.#idModal).find('select[name="documento_id"]').focus();
        }, 500);
    }

    #clearForm() {

        const self = this;
        const modal = $(self.#idModal);

        modal.find('form')[0].reset();
        modal.find('form').find('select').val('').prop('disabled', false).trigger('change');

    }

    #addEventsDefault() {

        const self = this;
        const modal = $(self.#idModal);
        commonFunctions.eventDefaultModals(self);

        const preencherDocumento = () => {
            commonFunctions.fillSelect(modal.find('select[name="documento_id"]'), self.#urlApi);
            modal.find('select[name="documento_id"]').trigger('change');
        }
        preencherDocumento();

        modal.find('select[name="documento_id"]').on('change', async function () {
            const id = $(this).val();
            const divDocumento = modal.find('.divDocumento');
            const divDigito = modal.find('.divDigito');

            if (!commonFunctions.getInvalidsDefaultValuesGenerateFilters().includes(id)) {
                const obj = new conectAjax(self.#urlApi);
                obj.setParam(id)
                try {
                    const response = await obj.getRequest();
                    console.log(response)
                    if (response.data) {
                        const data = response.data;
                        
                        if (data.mask) {
                            commonFunctions.applyCustomNumberMask(modal.find('input[name="numero"]'), { format: data.mask, reverse: data.reverse_bln, translation: 'docX' })
                        } else {
                            modal.find('input[name="numero"]').unmask();
                        }
                        divDocumento.show('fast').find('input, select, button').removeAttr('disabled');
                        
                        if (data.digito_bln) {
                            commonFunctions.applyCustomNumberMask(modal.find('input[name="digito"]'), { format: data.digito_mask, translation: 'docX' })
                            divDigito.show('fast').find('input, select, button').removeAttr('disabled');
                        } else {
                            modal.find('input[name="digito"]').unmask();
                            divDigito.hide('fast').find('input, select, button').attr('disabled', true);
                        }
                    }
                } catch (error) {
                    console.error(error);
                    const traceId = error.traceId ? error.traceId : undefined;
                    commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });
                }
            } else {
                modal.find('input[name="numero"]').unmask();
                modal.find('input[name="digito"]').unmask();
                divDocumento.hide('fast').find('input, select, button').attr('disabled', true);
                divDigito.hide('fast').find('input, select, button').attr('disabled', true);
            }
        });

        modal.find(`.btnDocumentosCadastro`).on('click', function () {
            const obj = new modalCadastroDocumento();
            obj.setFocusElementWhenClosingModal = this;
            self.#modalHideShow(false);
            obj.modalOpen().then(async function (result) {
                if (result && result.refresh) {
                    preencherDocumento();
                    self.#promisseReturnValue.refresh = true;
                }
                self.#modalHideShow();
            });
        });


    }

    async #fillDataAll() {

        const self = this;
        const modal = $(self.#idModal);

        try {
            const response = await commonFunctions.getRecurseWithTrashed(self.#urlApi, { param: self.#arrData.documento_id });
            const data = response.data;
            const selectArtigo = modal.find('select[name="documento_id"]');

            selectArtigo.html(new Option(`${data.nome} (${data.descricao})`, data.id, true, true)).trigger('change');
            selectArtigo.attr('disabled', true);
            modal.find('textarea[name="observacoes"]').val(self.#arrData.observacoes);
            $(self.#idModal).modal('show');

        } catch (error) {
            console.error(error);
            const traceId = error.traceId ? error.traceId : undefined;
            commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });
            self.#endTimer = true;
        }

    }

    saveButtonAction() {

        const self = this;
        let data = commonFunctions.getInputsValues($(self.#idModal).find('form')[0]);
        self.#promisseReturnValue.refresh = true;
        self.#promisseReturnValue.arrData.documento_id = data.documento_id
        self.#promisseReturnValue.arrData.observacoes = data.observacoes;
        self.#endTimer = true;

    }

}