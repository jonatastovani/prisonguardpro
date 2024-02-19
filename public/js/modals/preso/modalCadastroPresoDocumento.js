import { commonFunctions } from "../../common/commonFunctions.js";
import { modalCadastroArtigo } from "../referencias/modalCadastroArtigo.js";

export class modalCadastroPresoDocumento {

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
        this.#urlApi = urlRefArtigos;
        this.#idModal = "#modalCadastroPresoDocumento";
        this.#promisseReturnValue = undefined;
        this.#focusElementWhenClosingModal = null;
        this.#endTimer = false;
        this.#arrData = {
            idDiv: undefined,
            artigo_id: null,
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
        if(self.#arrData.idDiv!=undefined){
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
            $(self.#idModal).find('select[name="artigo_id"]').focus();
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
        const selectArtigo = modal.find('select[name="artigo_id"]');
        commonFunctions.addEventsSelect2(selectArtigo, `${self.#urlApi}/search/select2`, {
            dropdownParent: modal, minimum: 0
        });

        modal.find(`.btnArtigosCadastro`).on('click', function () {
            const obj = new modalCadastroArtigo();
            obj.setFocusElementWhenClosingModal = this;
            self.#modalHideShow(false);
            obj.modalOpen().then(async function (result) {
                if (result && result.refresh) {

                    const response = await commonFunctions.getRecurseWithTrashed(self.#urlApi, { param: self.#arrData.artigo_id });
                    const data = response.data;
                    modal.find('select[name="artigo_id"]').html(new Option(`${data.nome} (${data.descricao})`, data.id, true, true)).trigger('change');
                    
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
            const response = await commonFunctions.getRecurseWithTrashed(self.#urlApi, { param: self.#arrData.artigo_id });
            const data = response.data;
            const selectArtigo = modal.find('select[name="artigo_id"]');

            selectArtigo.html(new Option(`${data.nome} (${data.descricao})`, data.id, true, true)).trigger('change');
            selectArtigo.attr('disabled', true);
            modal.find('textarea[name="observacoes"]').val(self.#arrData.observacoes);
            $(self.#idModal).modal('show');

        } catch (error) {
            console.error(error);
            $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');
            self.#endTimer = true;
        }

    }

    saveButtonAction() {

        const self = this;
        let data = commonFunctions.getInputsValues($(self.#idModal).find('form')[0]);
        self.#promisseReturnValue.refresh = true;
        self.#promisseReturnValue.arrData.artigo_id = data.artigo_id
        self.#promisseReturnValue.arrData.observacoes = data.observacoes;
        self.#endTimer = true;

    }

}