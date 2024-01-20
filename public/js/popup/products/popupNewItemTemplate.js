import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";

export class popNewItemTemplate {

    #urlApi;
    #urlApiItems;
    #idPop;
    #itemType;
    #returnPromisse
    #action;
    #elemFocusClose;
    #endTime;

    constructor(urlApiItems) {

        this.#urlApiItems = urlApiItems;
        this.#idPop = "#pop-popNewItemTemplate";
        this.#itemType = null;
        this.#returnPromisse = undefined;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
        this.#endTime = false;

    }

    getIdPop() {
        return this.#idPop;
    }

    setUrlApi(urlApi) {
        this.#urlApi = urlApi;
    }

    setItemType(itemType) {
        this.#itemType = itemType;
    }

    setAction(action) {
        this.#action = action;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    async openPop() {

        const self = this;

        self.addButtonsEvents();
        self.clearPop();

        if (!self.#itemType) {

            self.setAction(enumAction.POST);
            $(self.#idPop).find('#sel_type_div').css('display', 'block');
            self.fillItemTypesFilter();

        } else {

            self.setAction(enumAction.PUT);
            await self.fillItemsFilter();
            self.getDataItemTemplate();

        }

        commonFunctions.applyCustomNumberMask($(this.#idPop).find('input[name="fixed_discount"]'), { format: '#.##0,00', reverse: true });
        commonFunctions.applyCustomNumberMask($(this.#idPop).find('input[name="percentage_discount"]'), { format: '99,99' });

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");

        return new Promise(function (resolve, reject) {

            const checkConfirmation = setInterval(function () {

                if (self.#returnPromisse !== undefined || self.#endTime) {

                    clearInterval(checkConfirmation);
                    if (self.#returnPromisse !== undefined) {

                        resolve(self.#returnPromisse);

                    } else {

                        reject();

                    }

                    self.closePop();
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
        this.#itemType = null;

        this.clearPop();

        if (this.#elemFocusClose !== null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop() {

        const self = this;

        $(self.#idPop).find('form')[0].reset();
        $(self.#idPop).find('form').find('select').html('<option value="">Selecione o tipo</option>');
        $(self.#idPop).find('#sel_type_div').css('display', 'none');
        $(self.#idPop).find('.nameType').html('Inserir item');
        $(self.#idPop).find('input[name="expression"]').focus();
        $(self.#idPop).find('.expression_demonstration').html('');

    }

    addButtonsEvents() {
        const self = this;

        commonFunctions.eventDefaultPopups(self);

        $(self.#idPop).find('select[name="sel_type"').on("change", function () {
            const sel_type = $(this).val().trim();

            if (sel_type.length && sel_type != 0) {

                self.#itemType = sel_type;
                self.fillItemsFilter();

            } else {

                self.#itemType = "";
                let strOptions = `<option value="">Selecione o tipo</option>`;
                $(self.#idPop).find('select[name="default_item_id"]').html(strOptions);

            }
        });

        $(self.#idPop).find('input[name="expression"]').on('input', function () {

            let formula = $(this).val();

            let regexOperators = /([+\-*/])/g;
            let result = formula.replace(regexOperators, '<span class="operators-regex">$1</span>');

            let regex = /parameters(\.[\wÀ-ÿ]*)?/g;
            result = result.replace(regex, function (match) {
                if (match.includes(".")) {
                    var parameter = match.substring(match.indexOf(".") + 1);
                    return '<span class="parameters-regex">parameters</span><span class="punctuation-regex">.</span><span class="name-parameters-regex">' + parameter + '</span>';
                } else {
                    return '<span class="parameters-regex">parameters</span>';
                }
            });

            $(self.#idPop).find(".expression_demonstration").html(result);
        });
    }

    getDataItemTemplate() {
        const self = this;

        const obj = new conectAjax(self.#urlApi);

        obj.getData()
            .then(function (response) {

                response.data.forEach(item => {

                    if (item.item_type == self.#itemType) {

                        $(self.#idPop).find('.nameType').html(commonFunctions.firstUppercaseLetter(item.item_type));
                        $(self.#idPop).find('select[name="default_item_id"]').val(item.default_item_id);
                        $(self.#idPop).find('input[name="fixed_discount"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(item.fixed_discount));
                        $(self.#idPop).find('input[name="percentage_discount"]').val(commonFunctions.formatWithCurrencyCommasOrFraction(item.percentage_discount));
                        $(self.#idPop).find('input[name="expression"]').val(item.expression).trigger('input');

                    }

                });
            })
            .catch(function (error) {

                const message = `Não foi possível obter os dados. Se o problema persistir consulte o programador.\nErro: ${error}`;
                $.notify(message, 'error');
                console.error(message);

            });

    }

    fillItemTypesFilter() {
        const self = this;

        const obj = new conectAjax(`${self.#urlApiItems}types/`);

        obj.getData()
            .then(async function (response) {

                const objTypesTemplate = new conectAjax(`${self.#urlApi}`);

                await objTypesTemplate.getData()
                    .then(function (result) {

                        result.data.forEach(item => {
                            response = response.filter((itemExists) => itemExists !== item.item_type)
                        });

                    })

                let strOptions = `<option value="">Selecione o tipo</option>`;
                response.forEach(element => {
                    strOptions += `<option value="${element}">${element}</option>`;
                });

                $(self.#idPop).find('select[name="sel_type"]').html(strOptions);

            })

            .catch(function (error) {

                const message = `Não foi possível obter os dados. Se o problema persistir consulte o programador.\nErro: ${error}`;
                $.notify(message, 'error');
                console.error(message);

            });

    }

    async fillItemsFilter() {

        const self = this;
        const obj = new conectAjax(`${self.#urlApiItems}search/`);

        const filters = {
            filters: {
                type: self.#itemType,
            }
        };

        obj.setData(filters);
        obj.setParam('?size=100000');
        obj.setAction(enumAction.POST);
        await obj.saveData()
            .then(async function (response) {

                let strOptions = `<option value="">Selecione o item</option>`;
                response.data.forEach(item => {
                    strOptions += `<option value="${item.id}">${item.name}</option>`;
                });

                $(self.#idPop).find('select[name="default_item_id"]').html(strOptions);

            })

            .catch(function (error) {

                const message = `Não foi possível obter os dados. Se o problema persistir consulte o programador.\nErro: ${error}`;
                $.notify(message, 'error');
                console.error(message);

            });

    }

    saveButtonAction() {

        const self = this;
        let blnSave = true;
        const invalids = commonFunctions.getInvalidsDefaultValuesGenerateFilters();

        let data = commonFunctions.getInputsValues($(self.#idPop).find('form')[0], 1, false, false);
        data.item_type = self.#itemType;
        data.fixed_discount = commonFunctions.removeCommasFromCurrencyOrFraction(data.fixed_discount);
        data.percentage_discount = commonFunctions.removeCommasFromCurrencyOrFraction(data.percentage_discount);

        if (invalids.includes(data.item_type)) {
            $(self.#idPop).find('select[name="sel_type"]').notify('Selecione um tipo', 'info');
            blnSave = false;
        }
        if (invalids.includes(data.default_item_id)) {
            $(self.#idPop).find('select[name="default_item_id"]').notify('Selecione um item padrão', 'info');
            blnSave = false;
        }

        if (blnSave) {
            self.save(data);
        }

    }

    save(data) {

        const self = this;
        const obj = new conectAjax(self.#urlApi);

        if (obj.setAction(this.#action)) {

            const btn = $(self.#idPop).find('.btnSavePop');
            commonFunctions.simulateLoading(btn);

            obj.setParam(this.#action === enumAction.PUT ? this.#itemType : null);

            obj.setData(data);

            obj.saveData()
                .then(function (result) {

                    $.notify('Item do modelo salvo com sucesso!', 'success');
                    self.#endTime = true;

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

}

