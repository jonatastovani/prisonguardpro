import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { popNewBasicClient } from "../clients/popupNewBasicClient.js";
import { popSearchClients } from "../clients/popupSearchClients.js";

export class popNewBudgets {

    #urlApi;
    #urlApiClients;
    #idPop;
    #action;
    #elemFocusClose;

    constructor (urlApi, urlApiClients) {

        this.#urlApi = urlApi;
        this.#urlApiClients = urlApiClients;
        this.#idPop = "#pop-popNewBudgets";
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;

    }
    
    getIdPop() {
        return this.#idPop;
    }

    setElemFocusClose (elem) {
        this.#elemFocusClose = elem;
    }

    openPop (){

        const self = this;

        self.addButtonsEvents();
        self.clearPop();
        self.fillSelectClients();

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");

    }

    closePop () {

        const self = this;

        $(self.#idPop).find('.titlePop').html('Novo orçamento');   
        $(self.#idPop).removeClass("active");
        $(self.#idPop).find(".popup").removeClass("active");
        $(self.#idPop).find("*").off();
        $(self.#idPop).off('keydown');

        self.clearPop();

        if (self.#elemFocusClose!==null && $(self.#elemFocusClose).length) {
            $(self.#elemFocusClose).focus();
            self.#elemFocusClose = null;
        }

    }

    clearPop () {

        const self = this;

        $(self.#idPop).find('form')[0].reset();
        $(self.#idPop).find('form').find('select').val('');
        $(self.#idPop).find('select[name="client_id"]').focus();
        
    }

    addButtonsEvents () {
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

    }
    
    async fillSelectClients(returnPromise = false) {
        const fillSelect = () => commonFunctions.fillSelect($(this.#idPop).find('select[name="client_id"]'), this.#urlApiClients);
        return returnPromise ? await fillSelect() : fillSelect();
    }

    saveButtonAction () {

        const select = $(this.#idPop).find('select[name="client_id"]');
        const id = select.val();

        if (![undefined, null, 0, ""].includes(id)) {

            let data = commonFunctions.getInputsValues($(this.#idPop).find('form')[0], 1, false, false);
            this.save(data);

        } else {
            select.notify('Selecione um cliente', 'info');
        }

    }

    save(data) {

        const self = this;
        const obj = new conectAjax(self.#urlApi);

        if (obj.setAction(self.#action)) {

            const btn = $(self.#idPop).find('.btnSavePop');
            commonFunctions.simulateLoading(btn);

            obj.setData(data);    
            obj.saveData()
                .then(function (result) {
                    const idBudget = result.id;

                    let btn = commonFunctions.redirectForm(`budgets/${idBudget}`, [
                        {name: 'arrNotifyMessage', value: [{message: 'Orçamento iniciado com sucesso!', type: 'success'}]}
                    ]);
                    btn.click();

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`,'error');

                })
                .finally(function () {
                    commonFunctions.simulateLoading(btn, false);
                });
        }

    }

}

