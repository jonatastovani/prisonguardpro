import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { modalMessage } from "../../commons/modalMessage.js";
import { productsHome } from "../../products/productsHome.js";

export class popItems {

    #urlApi;
    #idPop;
    #idItem;
    #action;
    #elemFocusClose;
    #returnPromisse;
    #endTime;
    #blnReturnPromisse;
    timerSearch;
    #arrDateSearch;

    constructor(urlApi) {
        this.#urlApi = urlApi;
        this.#idPop = "#pop-popItems";
        this.#idItem = null;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
        this.#returnPromisse = undefined;
        this.#endTime = false;
        this.#blnReturnPromisse = false;

        const pop = $(this.#idPop);

        this.#arrDateSearch = [
            {
                button: pop.find('#rbCreatedItems'),
                input: [pop.find('input[name="createdAfterItems"]'), pop.find('input[name="createdBeforeItems"]')],
                div_group: pop.find('.group-createdItems')
            }, {
                button: pop.find('#rbUpdatedItems'),
                input: [pop.find('input[name="updatedAfterItems"]'), pop.find('input[name="updatedBeforeItems"]')],
                div_group: pop.find('.group-updatedItems')
            }
        ];

        commonFunctions.eventRBHidden(pop.find('#rbCreatedItems'), this.#arrDateSearch);
        commonFunctions.eventRBHidden(pop.find('#rbUpdatedItems'), this.#arrDateSearch);

    }

    setId(id) {
        this.#idItem = id;
    }

    getIdPop() {
        return this.#idPop;
    }

    setAction(action) {
        this.#action = action;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    setReturnPromisse(bool) {
        this.#blnReturnPromisse = bool;
    }

    openPop() {

        const self = this;

        self.addButtonsEvents();
        self.generateFilters();

        if (self.#idItem != '' && self.#idItem != null) {

            self.get();

        } else {
            self.cancelPop();

        }

        commonFunctions.applyCurrencyMask($(`${self.#idPop}`).find('form').find('input[name="cost_price"]'));
        commonFunctions.applyCurrencyMask($(`${self.#idPop}`).find('form').find('input[name="price"]'));
        commonFunctions.applyCustomNumberMask($(`${self.#idPop}`).find('form').find('input[name="quantity"]'), { format: '9' });

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");

        return new Promise(function (resolve, reject) {

            const checkConfirmation = setInterval(function () {

                if (self.#returnPromisse !== undefined || self.#endTime) {

                    clearInterval(checkConfirmation);
                    if (self.#returnPromisse !== undefined) {

                        resolve(self.#returnPromisse);
                        self.closePop();

                    } else {

                        reject();

                    }

                    self.#returnPromisse = undefined;
                    self.#endTime = false;
                }

            }, 100);

        });

    }

    closePop() {


        $(this.#idPop).removeClass("active");
        $(this.#idPop).find(".popup").removeClass("active");
        $(this.#idPop).find("*").off();
        $(this.#idPop).off('keydown');
        this.#endTime = true;
        this.cancelPop();

        if (instanceManager.instanceVerification('productsHome')) {
            const obj = instanceManager.setInstance(('productsHome'), new productsHome());
            obj.getItemsTotal();
        }

        if (this.#elemFocusClose !== null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop() {

        $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('form').find('select').val('');
        this.#idItem = null;

    }

    cancelPop() {

        this.#action = enumAction.POST;
        if ($(this.#idPop).find(".hidden-fields").css("display") === "block") {
            this.registrationVisibility();
        }
        $(this.#idPop).find('.btnNewPop').focus();
        this.clearPop();
        this.adjustTableHeight();

    }

    addButtonsEvents() {
        const self = this;

        commonFunctions.eventDefaultPopups(self, { formRegister: true, inputsSearchs: $(self.#idPop).find('.inputActionItems') });

        $(self.#idPop).find(".btnNewPop").on("click", () => {
            $(self.#idPop).find('.titlePop').html('Novo item');
            self.registrationVisibility();
            $(self.#idPop).find('input[name="name"]').focus();
            self.#action = enumAction.POST;
        });

        self.adjustTableHeight();
        commonFunctions.addEventToggleDiv($(self.#idPop).find(".dataSearch"), $(self.#idPop).find(".toggleDataSearchButton"), { self: self })
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

        $(self.#idPop).find('.table').find('.select').on("click", function () {

            self.#returnPromisse = $(this).data('id');

        });

        $(self.#idPop).find('.table').find('tr').on('dblclick', function () {

            if (self.#blnReturnPromisse) {
                self.#returnPromisse = $(this).data('id');
            }

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
        const sizeDiscount = $(self.#idPop).find(".hidden-fields").css("display") === "block" ? 425 : 255;
        const maxHeight = screenHeight - sizeDiscount;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    generateFilters() {

        const self = this;
        const dataSearch = $(self.#idPop).find('.dataSearch');

        let data = {
            sorting: {
                field: 'name',
                method: dataSearch.find('input[name="methodItems"]:checked').val()
            },
            filters: {}
        };

        if (!dataSearch.find('input[name="createdAfterItems"]').attr('disabled')) {

            let created_at = commonFunctions.generateDateFilter(
                dataSearch.find('input[name="createdAfterItems"]').val(),
                dataSearch.find('input[name="createdBeforeItems"]').val()
            );
            if (Object.keys(created_at).length !== 0) {
                data.filters.created_at = created_at;
            }

        } else {

            let updated_at = commonFunctions.generateDateFilter(
                dataSearch.find('input[name="updatedAfterItems"]').val(),
                dataSearch.find('input[name="updatedBeforeItems"]').val()
            );
            if (Object.keys(updated_at).length !== 0) {
                data.filters.updated_at = updated_at;
            }

        }

        const name = dataSearch.find('input[name="name"]').val();
        if (name != '') {
            data.filters.name = name;
        }

        const type = dataSearch.find('input[name="type"]').val();
        if (type != '') {
            data.filters.type = type;
        }

        self.getDataAll(data);
    }

    getDataAll(data) {

        const self = this;
        const table = $(`${self.#idPop} .table tbody`);

        const obj = new conectAjax(`${this.#urlApi}search/`);
        obj.setAction(enumAction.POST);
        obj.setParam('?size=100000');
        obj.setData(data);

        obj.saveData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(result => {

                    const quantity = commonFunctions.formatWithCurrencyCommasOrFraction(result.quantity);
                    const price = commonFunctions.formatNumberToCurrency(result.price);
                    const cost_price = commonFunctions.formatNumberToCurrency(result.cost_price);
                    let btnReturn = '';

                    if (self.#blnReturnPromisse) {
                        btnReturn = `<button class="btn btn-success btn-mini select" data-id="${result.id}" title="Selecionar este registro"><i class="bi bi-check2-square"></i></button>`;
                    }
                    strHTML += `<tr data-id="${result.id}">`;
                    strHTML += `<td class="text-center">${btnReturn}</td>`;
                    strHTML += `<td><span>${result.name}</span></td>`;
                    strHTML += `<td class="text-center"><span>${result.type}</span></td>`;
                    strHTML += `<td class="text-center"><span>${quantity}</span></td>`;
                    strHTML += `<td class="text-center"><span>${result.unit}</span></td>`;
                    strHTML += `<td class="text-center"><span>${cost_price}</span></td>`;
                    strHTML += `<td class="text-center"><span>${price}</span></td>`;
                    strHTML += `<td><div class="d-flex flex-nowrap justify-content-center">
                        <button class="btn btn-primary btn-mini edit me-2" data-id="${result.id}" title="Editar este registro"><i class="bi bi-pencil"></i></button>`;
                    strHTML += `<button class="btn btn-danger btn-mini delete" data-id="${result.id}" data-name="${result.name}" title="Deletar este registro"><i class="bi bi-trash"></i></button>
                    </div></td>`;
                    strHTML += `</tr>`;

                });

                table.html(strHTML);
                $(self.#idPop).find('.totalRegisters').html(response.data.length)
                self.addQueryButtonEvents();

            })
            .catch(function (error) {

                $(self.#idPop).find('.totalRegisters').html('0');
                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                table.html(`<td colspan=5>${description}</td>`);
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

                const form = $(self.#idPop).find('form');

                form.find('input[name="name"]').val(response.name).focus();
                form.find('input[name="type"]').val(response.type);
                form.find('input[name="quantity"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(response.quantity));
                form.find('select[name="unit"]').val(response.unit);
                form.find('input[name="cost_price"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(response.cost_price, { decimalPlaces: 2 }));
                form.find('input[name="price"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(response.price, { decimalPlaces: 2 }));

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
        data.cost_price = commonFunctions.removeCommasFromCurrencyOrFraction(data.cost_price);

        this.save(data);

    }

    save(data) {

        const self = this;
        const obj = new conectAjax(self.#urlApi);

        if (obj.setAction(this.#action)) {

            const btn = $(self.#idPop).find('.btnSavePop');
            commonFunctions.simulateLoading(btn);

            obj.setData(data);

            if (this.#action == enumAction.PUT) {
                obj.setParam(this.#idItem);
            }

            obj.saveData()
                .then(function (result) {

                    $.notify(`Dados enviados com sucesso!`, 'success');
                    self.generateFilters();

                    if (instanceManager.instanceVerification('productsHome')) {
                        const obj = instanceManager.setInstance(('productsHome'), new productsHome());
                        obj.getItemsTotal();
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

            obj.setParam(idDel);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Item deletado com sucesso!`, 'success');
                    self.cancelPop();
                    self.generateFilters();

                    if (instanceManager.instanceVerification('productsHome')) {
                        const obj = instanceManager.setInstance(('productsHome'), new productsHome());
                        obj.getItemsTotal();
                    }

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

}

