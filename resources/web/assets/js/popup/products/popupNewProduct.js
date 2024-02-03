import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { popSearchTemplates } from "./popupSearchTemplates.js";

export class popNewProduct {

    #urlApi;
    #urlApiTemplates;
    #idPop;
    #idBudget;
    #idProduct
    #action;
    #elemFocusClose;
    #endTime;

    constructor(urlApi, urlApiTemplates) {

        this.#urlApi = urlApi;
        this.#urlApiTemplates = urlApiTemplates;
        this.#idPop = "#pop-popNewProduct";
        this.#idBudget = null;
        this.#idProduct = undefined;
        this.#action = enumAction.POST;
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

    openPop() {

        const self = this;

        self.addButtonsEvents();
        self.clearPop();

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");
        self.fillItemsFilter();

        return new Promise(function (resolve) {

            const checkConfirmation = setInterval(function () {

                if (self.#idProduct !== undefined || self.#endTime) {

                    clearInterval(checkConfirmation);
                    if (self.#idProduct !== undefined) {

                        resolve(self.#idProduct);
                        self.closePop();

                    }

                    self.#idProduct = undefined;
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
        this.#idBudget = null;
        this.#endTime = true;

        this.clearPop();

        if (this.#elemFocusClose !== null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop() {

        $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('input[name="name"]').focus();
        $(this.#idPop).find('.dynamic_parameters').html('');

    }

    addButtonsEvents() {
        const self = this;

        commonFunctions.eventDefaultPopups(self);

        $(self.#idPop).find('select[name="from_template"]').on('change', function () {
            self.fillParametersTemplate($(this).val())
        });

        const btnSearchTemplates = $(self.#idPop).find(".btnSearchTemplates");
        btnSearchTemplates.on("click", (event) => {
            event.preventDefault();

            const obj = instanceManager.setInstance('popSearchTemplates', new popSearchTemplates(this.#urlApiTemplates));
            obj.setElemFocusClose(btnSearchTemplates);

            obj.openPop().then(function (result) {

                if (result) {
                    $(self.#idPop).find('select[name="from_template"]').val(result).focus().trigger('change');
                }

            });

        });

    }

    fillParametersTemplate(idTemplate) {

        const self = this;
        const div_param = $(self.#idPop).find('.dynamic_parameters');

        div_param.html('');

        if (idTemplate && idTemplate != 0) {

            const obj = new conectAjax(this.#urlApiTemplates);
            obj.setParam(idTemplate);
            obj.getData().then()
                .then(function (response) {

                    if (response.parameters) {
                        response.parameters.forEach(item => {
                            
                            const idparameter = `${item}${Date.now()}`;
                            let strParameter =
                                `<div class="col-md-6">
                                <div class="input-group">
                                    <label class="input-group-text w-100" for="${idparameter}"><b class=me-2>${item}</b></span>
                                    <input type="text" name="${item}" id="${idparameter}" class="form-control" placeholder="${item}">
                                </div>
                            </div>`;

                            div_param.append(strParameter);
                            commonFunctions.applyCustomNumberMask($(`#${idparameter}`), { format: '#.##0,00', reverse: true });

                        });
                    } else {
                        div_param.html('<p><i>Não há parâmetros para este modelo</i></p>');
                    }

                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });

        }
    }

    async fillItemsFilter(word = "") {

        const self = this;
        const obj = new conectAjax(`${self.#urlApiTemplates}search/`);

        const filters = {
            filters: {
                name: word,
            }
        };
        console.log(filters);

        obj.setData(filters);
        obj.setParam('?size=100000');
        obj.setAction(enumAction.POST);
        await obj.saveData()
            .then(async function (response) {

                let strOptions = `<option value="0">Selecione o modelo</option>`;
                response.data.forEach(item => {
                    strOptions += `<option value="${item.id}">${item.name}</option>`;
                });

                $(self.#idPop).find('select[name="from_template"]').html(strOptions).trigger('click');

            })

            .catch(function (error) {

                const message = `Não foi possível obter os dados. Se o problema persistir consulte o programador.\nErro: ${error}`;
                $.notify(message, 'error');
                console.error(message);

            });

    }

    saveButtonAction() {

        let blnSave = true;

        let data = commonFunctions.getInputsValues($(this.#idPop).find('.data1')[0], 1, false, false);

        if (data.name == "") {
            $(this.#idPop).find('input[name="name"]').notify('O nome do produto é obrigatório.','info');
            blnSave = false;
        }

        if (blnSave) {

            if (data.from_template != 0 && data.from_template != "") {
                let data2 = commonFunctions.getInputsValues($(this.#idPop).find('.data2')[0], 1, false, false);
                
                Object.keys(data2).forEach(key => {
                    data2[key] = commonFunctions.removeCommasFromCurrencyOrFraction(data2[key]);
                });

                data['template_parameters'] = data2;

            } else {

                delete data.from_template;

            }

            data['budget_id'] = this.#idBudget;

            this.save(data);

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

                    self.#idProduct = result.id;

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

