import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { configurationApp } from "../../commons/configurationsApp.js";
import { enumAction } from "../../commons/enumAction.js";

export class popSearchOrders {

    #urlApi;
    #idPop;
    #returnPromisse;
    #elemFocusClose;
    #endTime;
    #arrDateSearch;
    #timerSearch;

    constructor(urlApi) {

        this.#urlApi = urlApi;
        this.#idPop = "#pop-popSearchOrders";
        this.#returnPromisse = undefined;
        this.#elemFocusClose = null;
        this.#endTime = false;
        this.#timerSearch = null;

        const pop = $(this.#idPop);

        this.#arrDateSearch = [
            {
                button: pop.find('#rbCreatedSearchOrders'),
                input: [pop.find('input[name="createdAfterSearchOrders"]'), pop.find('input[name="createdBeforeSearchOrders"]')],
                div_group: pop.find('.group-createdSearchOrders')
            }, {
                button: pop.find('#rbUpdatedSearchOrders'),
                input: [pop.find('input[name="updatedAfterSearchOrders"]'), pop.find('input[name="updatedBeforeSearchOrders"]')],
                div_group: pop.find('.group-updatedSearchOrders')
            }
        ];

        commonFunctions.eventRBHidden(pop.find('#rbCreatedSearchOrders'), this.#arrDateSearch);
        commonFunctions.eventRBHidden(pop.find('#rbUpdatedSearchOrders'), this.#arrDateSearch);

    }

    getIdPop() {
        return this.#idPop;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    openPop() {
        const self = this;

        self.clearPop();

        let dateNowSubtract = configurationApp.subtractDateDefault('YYYY-MM-DD');
        $(self.#idPop).find('input[name="createdAfterSearchOrders"]').val(dateNowSubtract);
        $(self.#idPop).find('input[name="updatedAfterSearchOrders"]').val(dateNowSubtract);

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");
        self.generateFilters();
        self.addButtonsEvents();
        $(self.#idPop).find('select[name="status"]').html(configurationApp.fillOptionsOrderStatus());

        return new Promise(function (resolve) {

            const checkConfirmation = setInterval(function () {

                if (self.#returnPromisse !== undefined || self.#endTime) {

                    clearInterval(checkConfirmation);
                    if (self.#returnPromisse !== undefined) {
                        resolve(self.#returnPromisse);
                        self.closePop();
                    }

                }

                self.#returnPromisse = undefined;
                self.#endTime = false;

            }, 100);

        });

    }

    closePop() {

        $(this.#idPop).removeClass("active");
        $(this.#idPop).find(".popup").removeClass("active");
        $(this.#idPop).find("*").off();
        $(this.#idPop).off('keydown');
        this.#endTime = true;

        this.clearPop();

        if (this.#elemFocusClose !== null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop() {

        $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('form').find('select').val('');
        $(`${this.#idPop} .table tbody`).html('');
        $(this.#idPop).find('select[name="status"]').focus();

    }

    addButtonsEvents() {
        const self = this;

        commonFunctions.eventDefaultPopups(self, { inputsSearchs: $(self.#idPop).find('.inputActionSearchOrders') })

        self.adjustTableHeight();
        commonFunctions.addEventToggleDiv($(self.#idPop).find(".dataSearch"), $(self.#idPop).find(".toggleDataSearchButton"), { self: self })

    }

    addQueryButtonEvents() {
        const self = this;

        $(self.#idPop).find('.table').find('tr td button').on("click", function () {

            const id = $(this).data('id');
            self.#returnPromisse = id;

        });

        $(self.#idPop).find('.table').find('tr').on('dblclick', function () {

            self.#returnPromisse = $(this).data('id');

        });

    }

    adjustTableHeight() {

        const self = this;
        const screenHeight = $(window).height();
        const maxHeight = screenHeight - 210;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    generateFilters() {

        const self = this;

        let data = {
            sorting: {
                field: 'created_at',
                method: $(self.#idPop).find('input[name="method"]:checked').val()
            },
            filters: {}
        };

        if (!$(self.#idPop).find('input[name="createdAfterSearchOrders"]').attr('disabled')) {

            let created_at = commonFunctions.generateDateFilter(
                $(self.#idPop).find('input[name="createdAfterSearchOrders"]').val(),
                $(self.#idPop).find('input[name="createdBeforeSearchOrders"]').val()
            );
            if (Object.keys(created_at).length !== 0) {
                data.filters.created_at = created_at;
            }

        } else {

            let updated_at = commonFunctions.generateDateFilter(
                $(self.#idPop).find('input[name="updatedAfterSearchOrders"]').val(),
                $(self.#idPop).find('input[name="updatedBeforeSearchOrders"]').val()
            );
            if (Object.keys(updated_at).length !== 0) {
                data.filters.updated_at = updated_at;
            }

            data.sorting.field = 'updated_at';

        }

        const status = $(self.#idPop).find('select[name="status"]').val();
        if (status != '') {
            data.filters.status = status;
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

        obj.getData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(result => {

                    let strClientName = '<b class="text-center">N/C</b>'
                    let strBudgetId = '<b class="text-center">N/C</b>'
                    let strBudgetPrice = '<b class="text-center">N/C</b>';
                    let strClientTel = '<b class="text-center">N/C</b>'

                    if (result.budget) {
                        const budget = result.budget;
                        const client = budget.client;

                        strClientName = client.name;
                        strBudgetId = budget.id;
                        strBudgetPrice = commonFunctions.formatNumberToCurrency(budget.price ? budget.price : 0);
                        strClientTel = commonFunctions.formatPhone(client.tel);
                    }

                    strHTML += `<tr data-id="${result.id}">`;
                    strHTML += `<td class="text-center"><span><button class="btn btn-success btn-sm select me-2" data-id="${result.id}" title="Selecionar este pedido"><i class="bi bi-check2-square"></i></button></span></td>`;
                    strHTML += `<td class="text-center"><b>${result.id}</b></td>`;
                    strHTML += `<td>${result.status}</td>`;
                    strHTML += `<td>${strClientName}</td>`;
                    strHTML += `<td class="text-center">${strBudgetId}</td>`;
                    strHTML += `<td class="text-center">${strBudgetPrice}</td>`;
                    strHTML += `<td class="text-center">${strClientTel}</td>`;
                    strHTML += `</tr>`;

                });

                table.html(strHTML);
                $(self.#idPop).find('.totalRegisters').html(response.data.length);
                self.addQueryButtonEvents();

            })
            .catch(function (error) {

                $(self.#idPop).find('.totalRegisters').html('0');
                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                table.html(`<td colspan=10>${description}</td>`);
                $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

}

