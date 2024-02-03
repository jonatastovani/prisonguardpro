import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";

export class popSearchTemplates {

    #urlApi;
    #idPop;
    #returnPromisse;
    #elemFocusClose;
    #endTime;
    #timerSearch;

    constructor(urlApi) {

        this.#urlApi = urlApi;
        this.#idPop = "#pop-popSearchTemplates";
        this.#returnPromisse = undefined;
        this.#elemFocusClose = null;
        this.#endTime = false;
        this.#timerSearch = null;

    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    openPop() {
        const self = this;

        self.clearPop();
        self.generateFilters();

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");
        self.addButtonsEvents();

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

        $(self.#idPop).find(".close-btn").on("click", () => {
            self.closePop();
        });

        $(self.#idPop).find('.btnCancelPop').on('click', () => {
            self.clearPop();
        });

        $(self.#idPop).find('.inputActionSearch').on("input", function () {

            clearTimeout(self.#timerSearch);

            self.#timerSearch = setTimeout(function () {
                self.generateFilters();
            }, 1000);

        });

        $(self.#idPop).on('keydown', function (e) {
            if (e.key === 'Escape') {
                e.stopPropagation();
                self.closePop();
            }
        });

        self.adjustTableHeight();

    }

    addQueryButtonEvents() {
        const self = this;

        $(self.#idPop).find('.table').find('tr td button').on("click", function () {

            const id = $(this).data('id');
            self.#returnPromisse = id;

        });


        $(self.#idPop).find('.table').find('.select').on("click", function () {

            self.#returnPromisse = $(this).data('id');

        });

        $(self.#idPop).find('.table').find('tr').on('dblclick', function () {

            self.#returnPromisse = $(this).data('id');

        });

    }

    adjustTableHeight() {

        const self = this;
        const screenHeight = $(window).height();
        const maxHeight = screenHeight - 240;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    generateFilters() {
        const self = this;

        let data = {
            filters: {
                name: $(self.#idPop).find('input[name="name"]').val()
            },
            sorting: {
                field: 'name',
                method: $(self.#idPop).find('input[name="method"]:checked').val()
            }
        };

        this.getDataAll(data);

    }

    getDataAll(data) {

        const self = this;
        const table = $(`${self.#idPop} .table tbody`);

        const obj = new conectAjax(`${self.#urlApi}search/`);
        obj.setAction(enumAction.POST);
        obj.setParam('?size=100000');
        obj.setData(data);

        obj.getData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(result => {

                    const parameters = result.parameters !== null ? result.parameters.join(', ') : 'N/C';

                    strHTML += `<tr data-id="${result.id}" title="Parâmetros deste produto: ${parameters}">`;
                    strHTML += `<td class="text-center"><span><button class="btn btn-success btn-sm select me-2" data-id="${result.id}" title="Selecionar este modelo"><i class="bi bi-check2-square"></i></button></span></td>`;
                    strHTML += `<td><span>${result.name}</span></td>`;
                    strHTML += `<td class="text-center"><span>${result.item_refs.length}</span></td>`;
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

