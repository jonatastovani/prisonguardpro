import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { configurationApp } from "../../commons/configurationsApp.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { popEditBudgets } from "../budgets/popupEditBudgets.js";

export class popOrders {

    #urlApi;
    #idPop;
    #idOrder;
    #returnPromisse
    #action;
    #elemFocusClose;
    #endTime;
    #defaultDescription;

    constructor(urlApi) {

        this.#urlApi = urlApi;
        this.#idPop = "#pop-popOrders";
        this.#idOrder = null;
        this.#returnPromisse = undefined;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
        this.#endTime = false;
        this.#defaultDescription = null;

    }

    setId(id) {
        this.#idOrder = id;
    }

    getIdPop() {
        return this.#idPop;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    setDefaultDescription(description) {
        this.#defaultDescription = description;
    }

    async openPop() {
        const self = this;

        self.addButtonsEvents();
        self.clearPop();

        const executeShow = async () => {
            $(self.#idPop).addClass("active");
            $(self.#idPop).find(".popup").addClass("active");
        };

        if (self.#idOrder) {
            self.#action = enumAction.PUT;
            $(self.#idPop).find('select[name="status"]').html(configurationApp.fillOptionsOrderStatus({ insertFirstOption: false }));
            self.get();
        } else {
            $(self.#idPop).find('select[name="status"]').html(configurationApp.fillOptionsOrderStatus({ selectedIdOption: 'iniciado', insertFirstOption: false }))
            if (this.#defaultDescription) {
                $(self.#idPop).find('input[name="description"]').val(this.#defaultDescription);
            }
        }

        executeShow();

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
        self.#idOrder = null;
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
        $(self.#idPop).find('.titlePop').html(`Novo pedido`);
        $(self.#idPop).find('input[name="description"]').focus();
        self.#action = enumAction.POST;

    }

    get() {

        const self = this;

        const obj = new conectAjax(self.#urlApi);
        obj.setParam(self.#idOrder);
        obj.getData()
            .then(function (response) {

                $(self.#idPop).find('.titlePop').html(`Alterar pedido: ${response.id}`);
                $(self.#idPop).find('select[name="status"]').val(response.status).focus();
                $(self.#idPop).find('input[name="description"]').val(response.description);

            })
            .catch(function (error) {

                $(self.#idPop).find('form1 :input').prop('disabled', true);
                console.error(error);
                $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

            });

    }

    addButtonsEvents() {
        commonFunctions.eventDefaultPopups(this);
    }

    saveButtonAction() {

        const self = this;

        let data1 = commonFunctions.getInputsValues($(self.#idPop).find('.data1')[0], 1, false, false);
        let data2 = commonFunctions.getInputsValues($(self.#idPop).find('.data2')[0], 1, false, false);

        const executeMergeStatus = () => {
            const data = Object.assign({}, data1, data2);
            self.save(data)
                .then(idOrder => {

                    $.notify(`Dados enviados com sucesso!`, 'success');
                    self.#returnPromisse = idOrder;

                    if (instanceManager.instanceVerification('popEditBudgets')) {
                        const obj = instanceManager.setInstance(('popEditBudgets'), new popEditBudgets());
                        obj.fillSearchOrders();
                    }
                })
        }

        if (self.#idOrder) {

            executeMergeStatus();

        } else {

            self.save(data1)
                .then(idOrder => {

                    self.#idOrder = idOrder;
                    self.#action = enumAction.PATCH;
                    executeMergeStatus();

                })

        }

    }

    save(data) {
        return new Promise((resolve, reject) => {

            const self = this;
            const obj = new conectAjax(self.#urlApi);

            if (obj.setAction(self.#action)) {

                const btn = $(self.#idPop).find('.btnSavePop');
                commonFunctions.simulateLoading(btn);

                if (self.#idOrder) {
                    obj.setParam(self.#idOrder);
                }
                obj.setData(data);

                obj.saveData()
                    .then(result => {
                        resolve(result.id);
                    })
                    .catch(error => {
                        console.error(error);
                        $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');
                        reject(error);
                    })
                    .finally(function () {
                        commonFunctions.simulateLoading(btn, false);
                    });
            } else {
                const error = new Error('O verbo HTTP não foi definido corretamente.');
                console.error(error);
                reject(error);
            }
        });
    }


}

