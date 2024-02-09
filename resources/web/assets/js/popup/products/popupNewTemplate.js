import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";

export class popNewTemplate {

    #urlApi;
    #idPop;
    #idTemplate;
    #returnPromisse
    #action;
    #elemFocusClose;
    #endTime;

    constructor(urlApi) {

        this.#urlApi = urlApi;
        this.#idPop = "#pop-popNewTemplate";
        this.#idTemplate = null;
        this.#returnPromisse = undefined;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
        this.#endTime = false;

    }

    setId(id) {
        this.#idTemplate = id;
    }

    getIdPop() {
        return this.#idPop;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    openPop() {

        const self = this;

        self.addButtonsEvents();
        self.clearPop();

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");

        if (self.#idTemplate) {
            self.#action = enumAction.PUT;
            self.get();
        } else {
            self.#action = enumAction.POST;
        }

        return new Promise(function (resolve) {

            const checkConfirmation = setInterval(function () {

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

        $(self.#idPop).find('.titlePop').html('Novo modelo');
        $(self.#idPop).removeClass("active");
        $(self.#idPop).find(".popup").removeClass("active");
        $(self.#idPop).find("*").off();
        $(self.#idPop).off('keydown');
        self.#idTemplate = null;
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
        $(self.#idPop).find('input[name="name"]').focus();

    }

    get() {

        const self = this;
        $(self.#idPop).find('.titlePop').html('Alterar modelo');

        const obj = new conectAjax(self.#urlApi);
        obj.setParam(self.#idTemplate);
        obj.getData()
            .then(function (response) {

                const name = response.name;
                $(self.#idPop).find('.titlePop').html(`Alterar modelo: ${name}`);
                $(self.#idPop).find('input[name="name"]').val(name).focus();

            })
            .catch(function (error) {

                $(self.#idPop).find('form1 :input').prop('disabled', true);
                console.error(error);
                $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

            });

    }

    addButtonsEvents() {
        const self = this;

        commonFunctions.eventDefaultPopups(self);

    }

    saveButtonAction() {

        let data = commonFunctions.getInputsValues($(this.#idPop).find('form')[0], 1, false, false);
        this.save(data);

    }

    save(data) {

        const self = this;
        const obj = new conectAjax(self.#urlApi);

        if (obj.setAction(this.#action)) {

            const btn = $(self.#idPop).find('.btnSavePop');
            commonFunctions.simulateLoading(btn);

            if (self.#idTemplate) {
                obj.setParam(self.#idTemplate);
            }
            obj.setData(data);

            obj.saveData()
                .then(function (result) {
                    const idTemplate = result.id;

                    if (self.#idTemplate) {
                        self.#returnPromisse = idTemplate;
                        $.notify('Dados enviados com sucesso!', 'success');
                    } else {

                        let btn = commonFunctions.redirectForm(`/products/templates/${idTemplate}`, [
                            { name: 'arrNotifyMessage', value: [{ message: 'Modelo gerado com sucesso!', type: 'success' }] }
                        ]);
                        btn.click();

                    }

                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                })
                .finally(function () {
                    commonFunctions.simulateLoading(btn, false);
                });
        }

    }

}

