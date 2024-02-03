import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { popNewBasicClient } from "../clients/popupNewBasicClient.js";
import { popSearchClients } from "../clients/popupSearchClients.js";
import { popOrders } from "../orders/popupOrders.js";
import { popSearchOrders } from "../orders/popupSearchOrders.js";

export class popEditBudgets {

    #urlApi;
    #urlApiClients;
    #urlApiOrders;
    #idPop;
    #idBudget;
    #returnPromisse
    #action;
    #elemFocusClose;
    #endTime;

    constructor(urlApi, urlClients, urlOrders) {

        this.#urlApi = urlApi;
        this.#urlApiClients = urlClients;
        this.#urlApiOrders = urlOrders;
        this.#idPop = "#pop-popEditBudgets";
        this.#idBudget = null;
        this.#returnPromisse = undefined;
        this.#action = enumAction.PATCH;
        this.#elemFocusClose = null;
        this.#endTime = false;

    }

    setId(id) {
        this.#idBudget = id;
    }

    getIdPop() {
        return this.#idPop;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    async openPop() {
        const self = this;

        self.addButtonsEvents();
        self.clearPop();

        const executeAfterSelects = async () => {
            $(self.#idPop).addClass("active");
            $(self.#idPop).find(".popup").addClass("active");
            self.get();
        };

        try {
            self.fillSearchOrders();
            await self.fillSelectClients(true);
            await executeAfterSelects();
        } catch (error) {
            $.notify('Houve um problema ao carregar a lista de clientes ou pedidos. Atualize a página e tente novamente.', 'error');
            self.#endTime = true;
        }

        return new Promise((resolve) => {
            const checkConfirmation = setInterval(() => {
                if (self.#returnPromisse !== undefined || self.#endTime) {
                    clearInterval(checkConfirmation);
                    if (self.#returnPromisse !== undefined) {
                        resolve(self.#returnPromisse);
                        self.closePop();
                    }
                    self.#returnPromisse = undefined;
                    self.#endTime = false;
                }
            }, 100);
        });
    }

    closePop() {

        const self = this;

        $(self.#idPop).removeClass("active");
        $(self.#idPop).find(".popup").removeClass("active");
        $(self.#idPop).find("*").off();
        $(self.#idPop).off('keydown');
        self.#idBudget = null;
        self.#endTime = true;

        self.clearPop();

        if (self.#elemFocusClose !== null && $(self.#elemFocusClose).length) {
            $(self.#elemFocusClose).focus();
            self.#elemFocusClose = null;
        }

    }

    clearPop() {

        const self = this;

        $(self.#idPop).find('form')[0].reset();
        $(self.#idPop).find('form').find('select').val('');
        $(self.#idPop).find('select[name="client_id"]').focus();

    }

    get() {

        const self = this;

        const obj = new conectAjax(self.#urlApi);
        obj.setParam(self.#idBudget);
        obj.getData()
            .then(function (response) {

                $(self.#idPop).find('.titlePop').html(`Alterar orçamento: ${response.id}`);
                $(self.#idPop).find('select[name="client_id"]').val(response.client_id).focus();
                $(self.#idPop).find('input[name="order_id"]').val(response.order_id);

            })
            .catch(function (error) {

                $(self.#idPop).find('form1 :input').prop('disabled', true);
                console.error(error);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

            });

    }

    addButtonsEvents() {
        const self = this;

        commonFunctions.eventDefaultPopups(self);

        const btnSearchClients = $(self.#idPop).find(".btnSearchClients");
        btnSearchClients.on("click", (event) => {
            event.preventDefault();

            const obj = instanceManager.setInstance('popSearchClients', new popSearchClients(this.#urlApiClients));
            obj.setElemFocusClose(btnSearchClients);

            obj.openPop().then(function (result) {

                if (result) {
                    $(self.#idPop).find('select[name="client_id"]').val(result);
                    $(self.#idPop).find('.btnSavePop').focus();
                }

            });

        });

        const btnNewBasicClient = $(self.#idPop).find(".btnNewBasicClient");
        btnNewBasicClient.on("click", (event) => {
            event.preventDefault();

            const obj = instanceManager.setInstance('popNewBasicClient', new popNewBasicClient(this.#urlApiClients));
            obj.setElemFocusClose(btnNewBasicClient);

            obj.openPop().then(function (result) {

                if (result) {
                    $(self.#idPop).find('select[name="client_id"]').val(result);
                    $(self.#idPop).find('.btnSavePop').focus();
                }

            });

        });

        const btnSearchOrders = $(self.#idPop).find(".btnSearchOrders");
        btnSearchOrders.on("click", (event) => {
            event.preventDefault();

            const obj = instanceManager.setInstance('popSearchOrders', new popSearchOrders(this.#urlApiOrders));
            obj.setElemFocusClose(btnSearchOrders);

            obj.openPop().then(function (result) {

                if (result) {
                    $(self.#idPop).find('input[name="order_id"]').val(result);
                    $(self.#idPop).find('.btnSavePop').focus();
                }

            });

        });

        const btnNewOrder = $(self.#idPop).find(".btnNewOrder");
        btnNewOrder.on("click", (event) => {
            event.preventDefault();

            const obj = instanceManager.setInstance('popOrders', new popOrders(this.#urlApiOrders));
            obj.setElemFocusClose(btnNewOrder);
            obj.setDefaultDescription(`Cliente: ${$(self.#idPop).find('select[name="client_id"] option:selected').text()} | Orçamento: ${self.#idBudget}`);
            obj.openPop().then(function (result) {

                if (result) {
                    $(self.#idPop).find('input[name="order_id"]').val(result);
                    $(self.#idPop).find('.btnSavePop').focus();
                }

            });

        });

    }

    async fillSelectClients(returnPromise = false) {
        const fillSelect = () => commonFunctions.fillSelect($(this.#idPop).find('select[name="client_id"]'), this.#urlApiClients);
        return returnPromise ? await fillSelect() : fillSelect();
    }

    fillSearchOrders() {

        const self = this;

        const obj = new conectAjax(`${self.#urlApiOrders}search/`);
        const elem = $(self.#idPop).find('#listOrdersEditBudgets');
        const filters = {
            sorting: {
                field: "created_at",
                method: "desc"
            }
        }
        obj.setData(filters);
        obj.setAction(enumAction.POST);
        obj.saveData()
            .then(function (response) {

                let strOptions = '';
                response.data.forEach(result => {

                    const status = result.status ? ` | ${result.status}` : '';
                    const description = result.description ? ` | ${result.description}` : '';
                    const id = result.id;
                    const display = `${moment(result.created_at).format('DD/MM/YYYY HH:mm')}${status}${description}`;
                    strOptions += `\n<option value="${id}">${display}</option>`;

                });

                elem.html(strOptions);

            })
            .catch(function (error) {

                $(self.#idPop).find('form1 :input').prop('disabled', true);
                console.error(error);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

            });

    }

    saveButtonAction() {

        const self = this;

        let blnSave = true;
        let data = commonFunctions.getInputsValues($(self.#idPop).find('form')[0], 1, false, false);

        const invalids = commonFunctions.getInvalidsDefaultValuesGenerateFilters();

        if (invalids.includes(data.client_id)) {
            $(self.#idPop).find('select[name="client_id"]').notify('Selecione um cliente', 'info');
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

            obj.setParam(self.#idBudget);
            obj.setData(data);

            obj.saveData()
                .then(function (result) {
                    const idBudget = result.id;

                    self.#returnPromisse = idBudget;
                    $.notify('Dados enviados com sucesso!', 'success');

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                })
                .finally(function () {
                    commonFunctions.simulateLoading(btn, false);
                });
        }

    }

}

