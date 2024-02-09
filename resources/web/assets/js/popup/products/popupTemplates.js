import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { modalMessage } from "../../commons/modalMessage.js";
import { productsHome } from "../../products/productsHome.js";

export class popTemplates {

    #urlApi;
    #idPop;
    #idItem;
    #action;
    #elemFocusClose;

    constructor(urlApi) {
        this.#urlApi = urlApi;
        this.#idPop = "#pop-popTemplates";
        this.#idItem = null;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
    }

    setId(id) {
        this.#idItem = id;
    }

    setAction(action) {
        this.#action = action;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    openPop() {

        this.addButtonsEvents();
        this.getDataAll();
        this.cancelPop();

        $(this.#idPop).addClass("active");
        $(this.#idPop).find(".popup").addClass("active");

    }

    closePop() {

        $(this.#idPop).removeClass("active");
        $(this.#idPop).find(".popup").removeClass("active");
        $(this.#idPop).find("*").off();
        $(this.#idPop).off('keydown');
        this.cancelPop();

        if (instanceManager.instanceVerification('productsHome')) {
            const obj = instanceManager.setInstance(('productsHome'), new productsHome());
            obj.getTemplatesTotal();
        }

        if (this.#elemFocusClose !== null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop() {

        // $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('form').find('select').val('');

    }

    cancelPop() {

        this.#action = enumAction.POST;
        if ($(this.#idPop).find(".hidden-fields").css("display") === "block") {
            this.registrationVisibility();
        }
        $(this.#idPop).find('.btnNewPop').focus();
        this.clearPop();

    }

    addButtonsEvents() {
        const self = this;

        $(self.#idPop).find(".close-btn").on("click", () => {
            self.closePop();
        });

        $(self.#idPop).find('.btnCancelPop').on('click', () => {
            self.cancelPop();
        });

        // $(self.#idPop).find(".btnNewPop").on("click", () => {
        //     let obj = instanceManager.setInstance('popNewTemplate', new popNewTemplate(urlApiProdTemplates));
        //     obj.setElemFocusClose($(self.#idPop).find(".btnNewPop"))
        //     obj.openPop()
        // });

        $(self.#idPop).find('.btnSavePop').on('click', (event) => {
            event.preventDefault();
            self.saveButtonAction();
        });

        $(self.#idPop).find('form').on('keydown', function (e) {
            if (e.key === 'Escape') {
                self.cancelPop();
                e.stopPropagation();
            }
        });

        $(self.#idPop).on('keydown', function (e) {
            if (e.key === 'Escape') {
                self.closePop();
                e.stopPropagation();
            }
        });

        self.adjustTableHeight();

    }

    addQueryButtonEvents() {
        const self = this;

        $(self.#idPop).find('.table').find('.edit').on("click", function () {
            self.#idItem = $(this).data('id');

            self.get();

        });

        $(self.#idPop).find('.table').find('.delete').on("click", function () {

            const idDel = $(this).data('id');
            const nameDel = $(this).data('name');
            self.delButtonAction(idDel, nameDel, this);

        });

    }

    adjustTableHeight() {

        const self = this;
        const screenHeight = $(window).height();
        const maxHeight = screenHeight - 360;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    getDataAll() {

        const obj = new conectAjax(this.#urlApi);
        const table = $(`${this.#idPop} .table tbody`);
        const self = this;

        obj.getData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(template => {

                    const parameters = template.parameters !== null ? template.parameters.join(', ') : 'N/C';
                    strHTML += `<tr>`;
                    strHTML += `<td><span>${template.name}</span></td>`;
                    strHTML += `<td class="text-center"><span>${template.item_refs.length}</span></td>`;
                    strHTML += `<td class="text-center"><span>${parameters}</span></td>`;
                    strHTML += `<td class="text-center"><button class="btn btn-primary btn-sm edit me-2" data-id="${template.id}" title="Editar este registro"><i class="bi bi-pencil"></i></button>`;
                    strHTML += `<button class="btn btn-danger btn-sm delete" data-id="${template.id}" data-name="${template.name}" title="Deletar este registro"><i class="bi bi-trash"></i></button></td>`;
                    strHTML += `</tr>`;

                });

                table.html(strHTML);
                $(self.#idPop).find('.totalRegisters').html(response.data.length)
                // self.addQueryButtonEvents();

            })
            .catch(function (error) {

                $(self.#idPop).find('.totalRegisters').html('0');
                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                table.html(`<td colspan=4>${description}</td>`);
                $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

    get() {

        const self = this;
        $(self.#idPop).find('.titlePop').html('Alterar item');
        $(self.#idPop).find('input[name="name"]').focus();
        self.#action = enumAction.PUT;

        const obj = new conectAjax(self.#urlApi);
        obj.setParam(self.#idItem);

        obj.getData()
            .then(function (response) {

                if ($(self.#idPop).find(".hidden-fields").css("display") === "none") {
                    self.registrationVisibility();
                }

                $(self.#idPop).find('input[name="name"]').val(response.name).focus();
                $(self.#idPop).find('input[name="quantity"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(response.quantity));
                $(self.#idPop).find('select[name="unit"]').val(response.unit);
                $(self.#idPop).find('input[name="price"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(response.price, { decimalPlaces: 2 }));

            })
            .catch(function (error) {

                $(self.#idPop).find('form1 :input').prop('disabled', true);
                console.log(error);
                $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

            });

    }

    saveButtonAction() {

        let data = commonFunctions.getInputsValues($(this.#idPop).find('form')[0], 1, false, false);
        data.quantity = commonFunctions.removeCommasFromCurrencyOrFraction(data.quantity);
        data.price = commonFunctions.removeCommasFromCurrencyOrFraction(data.price);

        this.save(data);

    }

    save(data) {

        const obj = new conectAjax(this.#urlApi);
        const self = this;

        if (obj.setAction(this.#action)) {
            obj.setData(data);

            if (this.#action == enumAction.PUT) {
                obj.setParam(this.#idItem);
            }

            obj.saveData()
                .then(function (result) {

                    $.notify(`Dados enviados com sucesso!`, 'success');
                    self.getDataAll();

                    if (instanceManager.instanceVerification('productsHome')) {
                        const obj = instanceManager.setInstance(('productsHome'), new productsHome());
                        obj.getTemplatesTotal();
                    }

                    if (self.#action == enumAction.PUT) {
                        self.cancelPop();
                    } else {
                        self.clearPop();
                        $(self.#idPop).find('form').find('input[name="name"]').focus();
                    }

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

    delButtonAction(idDel, nameDel, button = null) {

        const self = this;

        const obj = instanceManager.setInstance('modalMessage', new modalMessage());
        obj.setMessage(`Confirma a exclusão do item <b>${nameDel}</b>?`);
        obj.setTitle('Confirmação de exclusão de Item');
        obj.setElemFocusClose(button);

        obj.openModal().then(function (result) {

            if (result) {
                self.del(idDel);
            }

        });

    }

    del(idDel) {

        const obj = new conectAjax(this.#urlApi);
        const self = this;

        if (obj.setAction(enumAction.DELETE)) {

            obj.setParam(idDel);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Item deletado com sucesso!`, 'success');
                    self.cancelPop();
                    self.getDataAll();

                    if (instanceManager.instanceVerification('productsHome')) {
                        const obj = instanceManager.setInstance(('productsHome'), new productsHome());
                        obj.getTemplatesTotal();
                    }

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

}

