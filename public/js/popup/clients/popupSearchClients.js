import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { configurationApp } from "../../commons/configurationsApp.js";
import { enumAction } from "../../commons/enumAction.js";

export class popSearchClients {

    #urlApi;
    #idPop;
    #returnPromisse;
    #elemFocusClose;
    #endTime;
    #arrDocs;
    #arrDateSearch;
    timerSearch;

    constructor(urlApi) {

        this.#urlApi = urlApi;
        this.#idPop = "#pop-popSearchClients";
        this.#returnPromisse = undefined;
        this.#elemFocusClose = null;
        this.#endTime = false;
        this.timerSearch = null;

        const pop = $(this.#idPop);

        this.#arrDocs = [
            {
                button: pop.find('#rbCpfSearchClients'),
                input: [pop.find('.group-cpfSearchClients input[name="cpf"]')],
                div_group: pop.find('.group-cpfSearchClients')
            }, {
                button: pop.find('#rbCnpjSearchClients'),
                input: [pop.find('.group-cnpjSearchClients input[name="cnpj"]')],
                div_group: pop.find('.group-cnpjSearchClients')
            }
        ];

        this.#arrDateSearch = [
            {
                button: pop.find('#rbCreatedSearchClients'),
                input: [pop.find('input[name="createdAfterSearchClients"]'), pop.find('input[name="createdBeforeSearchClients"]')],
                div_group: pop.find('.group-createdSearchClients')
            }, {
                button: pop.find('#rbUpdatedSearchClients'),
                input: [pop.find('input[name="updatedAfterSearchClients"]'), pop.find('input[name="updatedBeforeSearchClients"]')],
                div_group: pop.find('.group-updatedSearchClients')
            }
        ];

        commonFunctions.eventRBHidden(pop.find('#rbCpfSearchClients'), this.#arrDocs);
        commonFunctions.eventRBHidden(pop.find('#rbCnpjSearchClients'), this.#arrDocs);
        commonFunctions.eventRBHidden(pop.find('#rbCreatedSearchClients'), this.#arrDateSearch);
        commonFunctions.eventRBHidden(pop.find('#rbUpdatedSearchClients'), this.#arrDateSearch);

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
        $(self.#idPop).find('input[name="createdAfterSearchClients"]').val(dateNowSubtract);
        $(self.#idPop).find('input[name="updatedAfterSearchClients"]').val(dateNowSubtract);

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");
        self.generateFilters();
        self.addButtonsEvents();

        commonFunctions.cpfMask('#cpfSearchClients');
        commonFunctions.cnpjMask('#cnpjSearchClients');

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
        $(this.#idPop).find('input[name="name"]').focus();

    }

    addButtonsEvents() {
        const self = this;

        commonFunctions.eventDefaultPopups(self, { inputsSearchs: $(self.#idPop).find('.inputActionSearch') })

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
        const maxHeight = screenHeight - 200;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    generateFilters() {

        const self = this;
        let invalids = commonFunctions.getInvalidsDefaultValuesGenerateFilters();

        let data = {
            sorting: {
                field: 'name',
                method: $(self.#idPop).find('input[name="method"]:checked').val()
            },
            filters: {}
        };

        if (!$(self.#idPop).find('input[name="createdAfterSearchClients"]').attr('disabled')) {

            let created_at = commonFunctions.generateDateFilter(
                $(self.#idPop).find('input[name="createdAfterSearchClients"]').val(),
                $(self.#idPop).find('input[name="createdBeforeSearchClients"]').val()
            );
            if (Object.keys(created_at).length !== 0) {
                data.filters.created_at = created_at;
            }

            // data.sorting.field = 'created_at';

        } else {

            let updated_at = commonFunctions.generateDateFilter(
                $(self.#idPop).find('input[name="updatedAfterSearchClients"]').val(),
                $(self.#idPop).find('input[name="updatedBeforeSearchClients"]').val()
            );
            if (Object.keys(updated_at).length !== 0) {
                data.filters.updated_at = updated_at;
            }

            // data.sorting.field = 'updated_at';

        }

        let name = $(self.#idPop).find('input[name="name"]').val();
        name = String(name).trim();

        if (!invalids.includes(name)) {
            data.filters.name = name;
        }

        const field = $(self.#idPop).find('input[name="document"]:checked').val();
        let valueField = $(self.#idPop).find(`#${field}SearchClients`).val();
        if (valueField.trim().length) {

            valueField = commonFunctions.returnsOnlyNumber(valueField);
            if (valueField != '') {
                data.filters[field] = valueField;
            }

        }

        self.getDataAll(data);
    }

    getDataAll(data) {

        const table = $(`${this.#idPop} .table tbody`);
        const self = this;

        const obj = new conectAjax(`${this.#urlApi}search/`);
        obj.setAction(enumAction.POST);
        obj.setParam('?size=100000');
        obj.setData(data);

        obj.getData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(result => {

                    const tel = commonFunctions.formatPhone(result.tel);
                    const cpf = commonFunctions.formatCPF(result.cpf);
                    const cnpj = commonFunctions.formatCNPJ(result.cnpj);
                    const city = result.city != null ? result.city : '';

                    strHTML += `<tr data-id="${result.id}">`;
                    strHTML += `<td class="text-center"><span><button class="btn btn-success btn-sm select me-2" data-id="${result.id}" title="Selecionar este cliente"><i class="bi bi-check2-square"></i></button></span></td>`;
                    strHTML += `<td>${result.name}</td>`;
                    strHTML += `<td class="text-center">${tel}</td>`;
                    strHTML += `<td class="text-center">${cpf}</td>`;
                    strHTML += `<td class="text-center">${cnpj}</td>`;
                    strHTML += `<td>${city}</td>`;
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
                table.html(`<td colspan=6>${description}</td>`);
                $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

}

