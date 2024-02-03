import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { modalMessage } from "../../commons/modalMessage.js";

export class popProducts {

    #urlApi;
    #urlApiItems;
    #idPop;
    #idItem;
    #action;
    #elemFocusClose;

    constructor(urlApi, urlApiItems) {
        this.#urlApi = urlApi;
        this.#urlApiItems = urlApiItems;
        this.#idPop = "#pop-popProducts";
        this.#idItem = null;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;

    }

    setId(idProduct) {
        this.#idItem = idProduct;
    }

    getIdPop() {
        return this.#idPop;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    setUrlApi(urlApi) {
        this.#urlApi = urlApi;
    }

    openPop() {

        this.cancelPop()
        this.addButtonsEvents();
        this.getDataAll();
        this.fillSelectItems();

        commonFunctions.applyCustomNumberMask($(this.#idPop).find('input[name="fixed_discount"]'), { format: '#.##0,00', reverse: true });
        commonFunctions.applyCustomNumberMask($(this.#idPop).find('input[name="percentage_discount"]'), { format: '99,99' });
        commonFunctions.applyCustomNumberMask($(this.#idPop).find('input[name="quantity"]'), { format: '#.##0,00', reverse: true });

        $(this.#idPop).addClass("active");
        $(this.#idPop).find(".popup").addClass("active");

    }

    closePop() {

        $(this.#idPop).removeClass("active");
        $(this.#idPop).find(".popup").removeClass("active");
        $(this.#idPop).find("*").off();
        $(this.#idPop).off('keydown');

        this.cancelPop();

        if (instanceManager.instanceVerification('registerBudgets')) {
            const obj = instanceManager.setInstance(('registerBudgets'));
            obj.getDataAll();
        }

        if (this.#elemFocusClose !== null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop() {

        $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('form').find('select').val('').attr('disabled', false);
        this.#idItem = '';

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

        commonFunctions.eventDefaultPopups(self, { formRegister: true });

        $(self.#idPop).find(".btnNewPop").on("click", () => {
            $(self.#idPop).find('.titlePop').html('Novo item');
            self.registrationVisibility();
            $(self.#idPop).find('select[name="item_id"]').focus();
            self.#action = enumAction.POST;
        });

        self.adjustTableHeight();

    }

    addQueryButtonEvents(idTr) {

        const self = this;
        const tr = $(self.#idPop).find(`#${idTr}`);

        tr.find('.edit').on("click", function (event) {
            event.preventDefault();

            self.#idItem = $(this).data('id');
            self.get();

        });

        tr.find('.delete').on("click", function (event) {
            event.preventDefault();

            const idDel = $(this).data('id');
            const nameDel = $(this).data('name');
            self.delButtonAction(idDel, nameDel, this);

        });

    }

    registrationVisibility() {

        $(this.#idPop).find('.btnNewPop').parent().parent().slideToggle();
        $(this.#idPop).find(".hidden-fields").slideToggle();
        setTimeout(() => {
            this.adjustTableHeight();
        }, 500);

    }

    adjustTableHeight() {

        const self = this;
        const screenHeight = $(window).height();
        const sizeDiscount = $(self.#idPop).find(".hidden-fields").css("display") === "block" ? 325 : 150;
        const maxHeight = screenHeight - sizeDiscount;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    fillSelectItems() {

        commonFunctions.fillSelect($(this.#idPop).find('select[name="item_id"]'), this.#urlApiItems);

    }

    getDataAll() {

        const self = this;
        const obj = new conectAjax(this.#urlApi);
        const table = $(`${self.#idPop} .table tbody`);
        self.countItems();

        table.html('');
        obj.getData()
            .then(function (response) {

                $(self.#idPop).find('.titleNameProduct').html(response.name);

                response.item_refs.forEach(item => {

                    self.getItem(item.item_id).then(function (result) {

                        const quantity = commonFunctions.formatWithCurrencyCommasOrFraction(item.quantity);
                        const percentage_discount = `${commonFunctions.formatWithCurrencyCommasOrFraction(item.percentage_discount)}%`;
                        const fixed_discount = commonFunctions.formatNumberToCurrency(item.fixed_discount);
                        const price = commonFunctions.formatNumberToCurrency(item.price);

                        const tr = $(self.#idPop).find(`#${result}`);
                        tr.find('.quantity').html(quantity);
                        tr.find('.percentage_discount').html(percentage_discount);
                        tr.find('.fixed_discount').html(fixed_discount);
                        tr.find('.price').html(price);

                        self.countItems();

                    }).catch(function (error) {

                        console.error(error);
                        console.error(`ID Item: ${item.item_id}`);
                        $.notify(`Não foi possível recuperar os dados do item. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}\nID Item: ${item.item_id}`, 'error');

                    });

                });

            })
            .catch(function (error) {

                self.countItems();
                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                table.html(`<td colspan=6>${description}</td>`);
                $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

    get() {

        const self = this;
        $(self.#idPop).find('.titlePop').html('Alterar item');
        $(self.#idPop).find('select[name="item_id"]').focus();
        self.#action = enumAction.PUT;

        const obj = new conectAjax(self.#urlApi);

        obj.getData()
            .then(function (response) {

                response.item_refs.forEach(item => {

                    if (item.item_id == self.#idItem) {

                        if ($(self.#idPop).find(".hidden-fields").css("display") === "none") {
                            self.registrationVisibility();
                        }

                        $(self.#idPop).find('select[name="item_id"]').val(item.item_id).attr('disabled', 'disabled');
                        $(self.#idPop).find('input[name="quantity"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(item.quantity)).focus();
                        $(self.#idPop).find('input[name="fixed_discount"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(item.fixed_discount));
                        $(self.#idPop).find('input[name="percentage_discount"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(item.percentage_discount));

                    }

                });

            })
            .catch(function (error) {

                $(self.#idPop).find('form1 :input').prop('disabled', true);
                console.error(error);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

            });

    }

    getItem(idItem) {

        const self = this;
        const table = $(`${self.#idPop} .table tbody`);

        return new Promise(function (resolve, reject) {

            const obj = new conectAjax(self.#urlApiItems);
            obj.setParam(idItem);

            obj.getData()
                .then(function (response) {

                    const idTr = `${response.id}${Date.now()}`;

                    let strHTML = `<tr id="${idTr}" data-item_id="${response.id}">`;
                    strHTML += `<td><span>${response.name}</span></td>`;
                    strHTML += `<td class="text-center"><span class="quantity">0</span></td>`;
                    strHTML += `<td class="text-center"><span class="fixed_discount">0</span></td>`;
                    strHTML += `<td class="text-center"><span class="percentage_discount">0</span></td>`;
                    strHTML += `<td class="text-center"><b><span class="price">0</span></b></td>`;
                    strHTML += `<td class="text-center"><button class="btn btn-primary btn-sm edit me-2" data-id="${response.id}" title="Editar item ${response.name}"><i class="bi bi-pencil"></i></button>`;
                    strHTML += `<button class="btn btn-danger btn-sm delete" data-id="${response.id}" data-name="${response.name}" title="Excluir item ${response.name}"><i class="bi bi-trash"></i></button></td>`;
                    strHTML += '</tr>';

                    table.append(strHTML);
                    self.addQueryButtonEvents(idTr);

                    resolve(idTr);

                })
                .catch(function (error) {

                    reject(error);

                });

        });

    }

    countItems() {

        const count = $(`${this.#idPop} .table tbody`).children().length;
        $(this.#idPop).find('.totalRegisters').html(count)

    }

    saveButtonAction() {

        const self = this;
        let blnSave = true;
        const invalids = commonFunctions.getInvalidsDefaultValuesGenerateFilters();

        let data = commonFunctions.getInputsValues($(self.#idPop).find('form')[0], 1, false, false);
        data.quantity = commonFunctions.removeCommasFromCurrencyOrFraction(data.quantity);
        data.fixed_discount = commonFunctions.removeCommasFromCurrencyOrFraction(data.fixed_discount);
        data.percentage_discount = commonFunctions.removeCommasFromCurrencyOrFraction(data.percentage_discount);

        if (self.#action == enumAction.POST && invalids.includes(data.item_id)) {
            $(self.#idPop).find('select[name="item_id"]').notify('Selecione o produto.', 'info');
            blnSave = false;
        }

        if (invalids.includes(data.quantity)) {
            $(self.#idPop).find('input[name="quantity"]').notify('Informe a quantidade.', 'info');
            blnSave = false;
        }

        if (blnSave) {
            self.save(data);
        }

    }

    save(data) {

        const self = this;
        const obj = new conectAjax(self.#urlApi);

        if (obj.setAction(self.#action)) {

            const btn = $(self.#idPop).find('.btnSavePop');
            commonFunctions.simulateLoading(btn);

            obj.setData(data);

            if (self.#action == enumAction.PUT) {
                obj.setParam(`items/${self.#idItem}`);
            } else {
                obj.setParam(`items/`);
            }

            obj.saveData()
                .then(function (result) {
                    $.notify(`Dados enviados com sucesso!`, 'success');
                    self.getDataAll();

                    if (instanceManager.instanceVerification('registerBudgets')) {
                        const obj = instanceManager.setInstance(('registerBudgets'));
                        obj.getDataAll();
                    }

                    if (self.#action == enumAction.PUT) {
                        self.cancelPop();
                    } else {
                        self.clearPop();
                        $(self.#idPop).find('form').find('select[name="item_id"]').focus();
                    }

                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                })
                .finally(function () {
                    commonFunctions.simulateLoading(btn, false);
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

            obj.setParam(`items/${idDel}`);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Item deletado com sucesso!`, 'success');
                    self.cancelPop();
                    self.getDataAll();

                    if (instanceManager.instanceVerification('registerBudgets')) {
                        const obj = instanceManager.setInstance(('registerBudgets'));
                        obj.getDataAll();
                    }

                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

}

