import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../common/commonFunctions.js";
import { enumAction } from "../../common/enumAction.js";
import { modalMessage } from "../../common/modalMessage.js";

export class modalCadastroGenero {

    /**
     * URL do endpoint da Api
     */
    #urlApi;
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
        this.#urlApi = urlRefGenero;
        this.#idModal = "#modalCadastroGenero";
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

        self.#getDataAll();
        self.modalCancel();

        self.#promisseReturnValue = {
            refresh: false
        };
        $(self.#idModal).modal('show');

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
        modal.find('form').find('select').val('');

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
        commonFunctions.eventDefaultModals(self, { formRegister: true, inputsSearchs: modal.find('.inputActionSearchModalCadastroGenero') });

        modal.find(".btnNewRegister").on("click", () => {
            self.#action = enumAction.POST;
            modal.find('.register-title').html('Novo Gênero');
            self.#actionsHideShowRegistrationFields(true);
            modal.find('input[name="nome"]').focus();
        });

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

                        const idTr = `${item.id}${Date.now()}`;
                        tabela.append(`
                        <tr id=${idTr}>
                            <td class="text-center"><b>${item.id}</b></td>
                            <td>
                                <div class="d-flex wrap-nowrap justify-content-center">
                                    <button class="btn btn-outline-primary btn-mini-2 me-2 btn-edit" title="Editar cadastro">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-mini-2 btn-delete" title="Deletar cadastro">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                            <td>${item.nome}</td>
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
            } else {
                throw new Error('Action inválido');
            }
        } catch (error) {
            self.#endTimer = true;
            console.error(error);
            $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');
        }

    }

    async #getRecurse() {

        const self = this;
        const obj = new conectAjax(`${self.#urlApi}`);
        obj.setParam(self.#idRegister);
        try {
            const response = await obj.getRequest();
            if (response.data) {
                const form = $(self.#idModal).find('form');
                form.find('input[name="nome"]').val(response.data.nome).focus();
                form.find('.register-title').html(`Editar Gênero: ${response.data.id} - ${response.data.nome}`);
            }
        } catch (error) {
            console.error(error);
            $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');
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
                    $.notify(`Dados enviados com sucesso!`, 'success');

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
                $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');
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
            obj.setTitle = 'Confirmação de exclusão de Gênero';
            obj.setMessage = `Confirma a exclusão do Gênero <b>${nameDel}</b>?`;
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

                $.notify(`Gênero deletado com sucesso!`, 'success');
                self.#promisseReturnValue.refresh = true;

                self.modalCancel();
                self.generateFilters();

            } catch (error) {
                console.error(error);
                $.notify(`Não foi possível executar a ação.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');
            }
        }

    }

}