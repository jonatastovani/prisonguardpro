import { conectAjax } from "../ajax/conectAjax.js";

export class commonFunctions {

    /**
     * Removes all non-numeric characters from a string.
     * @param {string} num - The string to process.
     * @returns {string} - The string with only numeric characters.
     */
    static returnsOnlyNumber(num) {
        return String(num).replace(/\D/g, '');
    }

    /**
     * Validates a Brazilian CPF (Individual Taxpayer Registry) number.
     * @param {string} numCPF - The CPF number to validate.
     * @returns {boolean} - True if the CPF is valid, false otherwise.
     */
    static validateCPF(numCPF) {

        var num = this.returnsOnlyNumber(numCPF);

        if (num.length !== 11 || /^(\d)\1*$/.test(num)) {
            return false;
        }

        var sum = 0;
        for (var i = 0; i < 9; i++) {
            sum += parseInt(num.charAt(i)) * (10 - i);
        }
        var rest = sum % 11;
        var dig1 = rest < 2 ? 0 : 11 - rest;

        if (parseInt(num.charAt(9)) !== dig1) {
            return false;
        }

        sum = 0;
        for (i = 0; i < 10; i++) {
            sum += parseInt(num.charAt(i)) * (11 - i);
        }
        rest = sum % 11;
        var dig2 = rest < 2 ? 0 : 11 - rest;

        if (parseInt(num.charAt(10)) !== dig2) {
            return false;
        }

        return true;

    }

    /**
     * Adds an event handler to check and validate a CPF (Individual Taxpayer Registry) input field.
     * @param {Object} arrData - An object containing event and selector data.
     */
    static addEventCheckCPF(arrData) {
        const event = arrData.event;
        const selector = arrData.selector;

        $(selector).on(event, function () {

            const num = commonFunctions.returnsOnlyNumber(this.value);

            if (num.length == 11) {
                if (!commonFunctions.validateCPF($(this).val())) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            } else if (num.length < 11 && num.length > 0) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }

        });

    }

    /**
     * Formats a CPF (Individual Taxpayer Registry) number as a string.
     * @param {string} numCPF - The CPF number to format.
     * @returns {string} - The formatted CPF number.
     */
    static formatCPF(numCPF) {
        let num = this.returnsOnlyNumber(numCPF);

        if (num.length == 11) {
            return num.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        } else if (num == '') {
            return '';
        } else {
            return num;
        }
    }

    /**
     * Applies a Brazilian ZIP code (CEP) mask to an input field.
     * @param {string} selector - The jQuery selector of the input field where the mask should be applied.
     */
    static cpfMask(selector) {

        $(selector).mask('000.000.000-00');

    }

    /**
     * Validates a Brazilian CNPJ (National Register of Legal Entities) number.
     * @param {string} numCNPJ - The CNPJ number to validate.
     * @returns {boolean} - True if the CNPJ is valid, false otherwise.
     */
    static validateCNPJ(numCNPJ) {

        var numCNPJ = this.returnsOnlyNumber(numCNPJ);

        if (numCNPJ.length !== 14) {
            return false;
        }

        if (/^(\d)\1*$/.test(numCNPJ)) {
            return false;
        }

        function calcDigVerificador(pos) {
            var Soma = 0;
            var Multiplicador = pos === 12 ? 5 : 6;
            for (var i = 0; i < pos; i++) {
                Soma += parseInt(numCNPJ.charAt(i)) * Multiplicador;
                Multiplicador--;
                if (Multiplicador < 2)
                    Multiplicador = 9;
            }
            var Resto = Soma % 11;
            var DigitosVerificadores = Resto < 2 ? 0 : 11 - Resto;
            return parseInt(numCNPJ.charAt(pos)) === DigitosVerificadores;
        }

        return calcDigVerificador(12) && calcDigVerificador(13);

    }

    /**
     * Adds an event handler to check and validate a CNPJ (National Register of Legal Entities) input field.
     * @param {Object} arrData - An object containing event and selector data.
     */
    static addEventCheckCNPJ(arrData) {
        const event = arrData.event;
        const selector = arrData.selector;

        $(selector).on(event, function () {

            const num = commonFunctions.returnsOnlyNumber(this.value);

            if (num.length == 14) {
                if (!commonFunctions.validateCNPJ($(this).val())) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            } else if (num.length < 14 && num.length > 0) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }

        });

    }

    /**
     * Formats a CNPJ (National Register of Legal Entities) number as a string.
     * @param {string} numCNPJ - The CNPJ number to format.
     * @returns {string} - The formatted CNPJ number.
     */
    static formatCNPJ(numCNPJ) {
        let num = this.returnsOnlyNumber(numCNPJ);

        if (num.length == 14) {
            return num.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
        } else if (num == '') {
            return '';
        } else {
            return num;
        }
    }

    /**
     * Applies a Brazilian CNPJ (National Register of Legal Entities) mask to an input field.
     * @param {string} selector - The jQuery selector of the input field where the mask should be applied.
     */
    static cnpjMask(selector) {

        $(selector).mask('00.000.000/0000-00');

    }

    /**
     * Applies a Brazilian telephone mask to a number and adjusts the number format in the target element.
     * @param {string} num - The phone number to mask.
     * @param {string} selector - The jQuery selector of the element where the mask should be applied.
     */
    static phoneMask(num, selector) {

        const number = this.returnsOnlyNumber(num);

        if (number.length < 11) {
            $(selector).mask('(00) 0000-00009');
        } else {
            $(selector).mask('(00) 0 0000-0009');
        }

        if (this.returnsOnlyNumber($(selector).val()) != number) {
            $(selector).val(this.formatPhone(number));
        }

    }

    /**
     * Formats a phone number by removing non-numeric characters and applying the correct mask.
     * @param {string} num - The phone number to format.
     * @returns {string} - The phone number formatted with the mask.
     */
    static formatPhone(num) {

        const number = this.returnsOnlyNumber(num);

        if (number.length < 11) {
            return number.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else {
            return number.replace(/(\d{2})(\d)(\d{4})(\d{4})/, '($1) $2 $3-$4');
        }

    }

    /**
     * Applies a Brazilian zip code mask to an input field.
     * @param {string} selector - The jQuery selector of the input field where the mask should be applied.
     */
    static cepMask(selector) {

        $(selector).mask('00.000-000');

    }

    /**
     * Gets the values ​​of a form's elements and returns an object or formatted string.
     *
     * @param {string} container - Container to be processed.
     * @param {number} returnType - The desired return type: 1 for object, 2 for string.
     * @param {boolean} blnDisabled - Indicates whether disabled elements should be included (true) or excluded (false).
     * @param {boolean} keyId - Defines whether the "name" attribute (keyId=false) or the "id" attribute (keyId=true) should be used as a key in the return object.
     *
     * @returns {object|string} - An object with the values ​​of the form elements (returnType 1)
     *                            or a string formatted with the element values ​​(returnType 2).
     */
    static getInputsValues(container, returnType, blnDisabled = false, keyId = true) {

        const formData = {};
        let strReturn = '';

        // const elemInput = form.elements;
        const elemInput = $(container).find('input, select, textarea');

        for (let i = 0; i < elemInput.length; i++) {
            const element = elemInput[i];
            const id = element.id;
            const name = element.name;
            const val = element.value;
            const disabled = element.disabled;

            if (blnDisabled || !blnDisabled && !disabled) {
                if ((id && keyId)) {
                    formData[id] = val.trim();
                    strReturn += `${id}:${val.trim()}\n`;
                } else if ((name && !keyId)) {
                    formData[name] = val.trim();
                    strReturn += `${name}:${val.trim()}\n`;
                }
            }
        }

        switch (returnType) {
            case 1:
                return formData;

            case 2:
                return strReturn;
        }

    }

    /**
     * Capitalizes the first letter of a text.
     * @param {string} text - The text to be processed.
     * @returns {string} - The text with the first letter capitalized.
     */
    static firstUppercaseLetter(text) {

        if (text.length === 0) {
            return text;
        }

        return text.charAt(0).toUpperCase() + text.slice(1);

    }

    /**
     * Applies a custom mask to a numeric input element.
     *
     * @param {jQuery} elem - The input element to which the mask will be applied.
     * @param {Object} metadata - Metadata that customizes the mask.
     * @param {string} metadata.format - The desired format mask (default: '0,99' for numbers with two decimal places).
     * @param {Object} metadata.before - Settings for digits before the decimal point.
     * @param {number} metadata.before.quantity - The number of digits before the decimal point.
     * @param {Object} metadata.after - Settings for digits after the decimal point.
     * @param {number} metadata.after.quantity - The number of digits after the decimal point.
     * @param {boolean} metadata.reverse - Defines whether the mask should be applied in reverse mode (from right to left).
     */
    static applyCustomNumberMask(elem, metadata = {}) {
        let format = '0,99';

        if (metadata.format) {
            format = metadata.format;
        }

        if ((metadata.before && metadata.before.quantity) || (metadata.after && metadata.after.quantity)) {

            if (metadata.before && metadata.before.quantity) {
                const beforeDigits = '0'.repeat(metadata.before.quantity);
                format = `${beforeDigits}`;
            } else {
                format = '0'
            }

            if (metadata.after && metadata.after.quantity) {
                const afterDigits = '9'.repeat(metadata.after.quantity);
                if (afterDigits) {
                    format += `,${afterDigits}`;
                }
            }

        }

        elem.mask(format, { reverse: metadata.reverse });

    }

    /**
     * Applies a currency mask to a text input field.
     * @param {string} elem - The element of the input field where the mask should be applied.
     */
    static applyCurrencyMask(elem) {

        elem.maskMoney({ thousands: '.', decimal: ',' });
    }

    /**
     * Formats a number as a string in Brazilian monetary format (with commas and currency symbol).
     * @param {number} number - The number to format.
     * @returns {string} - A string in Brazilian monetary format.
     */
    static formatNumberToCurrency(number) {

        return number.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

    }

    /**
     * Removes commas, periods, and other non-numeric characters from a string in monetary or fractional format, keeping only the digits.
     * @param {string} currency - The string in currency format to be unformatted.
     * @returns {number} - The deformatted number.
     */
    static removeCommasFromCurrencyOrFraction(currency) {

        const formattedCurrency = currency.replace(/[^0-9,-]+/g, '');
        const formattedCurrencyWithDecimalPoint = formattedCurrency.replace(',', '.');

        return Number(formattedCurrencyWithDecimalPoint);

    }

    /**
     * Formats a number as a currency with commas and fractions.
     *
     * @param {number} number - The number to format.
     * @param {Object} metadata - An object containing formatting options.
     * @param {number} metadata.decimalPlaces - The number of decimal places to display.
     * @returns {string} The formatted currency string.
     */
    static formatWithCurrencyCommasOrFraction(number, metadata = {}) {
        const decimalPlaces = metadata.decimalPlaces || 2;

        // const formattedCurrency = parseFloat(number).toFixed(decimalPlaces).replace('.', ',');
        // console.log(formattedCurrency)

        // return formattedCurrency;

        const formattedNumber = number.toLocaleString('pt-BR', {
            minimumFractionDigits: decimalPlaces,
            maximumFractionDigits: decimalPlaces,
        });

        return formattedNumber;

    }

    /**
     * Formats a number with commas and limits the number of decimal places.
     *
     * @param {number} number - The number to format.
     * @param {Object} options - Formatting options.
     * @param {number} options.decimalPlaces - The number of decimal places to display. Default is 2.
     * @returns {string} The formatted string.
     */
    static formatNumberWithLimitDecimalPlaces(number, options = {}) {
        const decimalPlaces = options.decimalPlaces || 2;

        const formattedNumber = Number(number.toFixed(decimalPlaces));

        return formattedNumber;
    }

    /**
     * Fills a select element with options retrieved from an API.
     *
     * @param {jQuery} elem - The jQuery-wrapped select element to be filled.
     * @param {string} urlApi - The URL of the API to fetch data from.
     * @param {Object} options - Additional options to customize the filling process.
     * @param {boolean} options.insertFirstOption - Whether to insert the first option (default: true).
     * @param {string} options.firstOptionName - The name of the first option (default: 'Selecione').
     * @param {string} options.firstOptionValue - The value of the first option (default: '').
     * @param {string} options.selectedIdOption - The ID of the option to be marked as selected (default: the current value of the select element).
     * @param {string} options.displayColumnName - The name of the column to be displayed in the options (default: 'name').
     * @returns {Promise} - A Promise that resolves when the select element is filled or rejects on error.
     */
    static async fillSelect(elem, urlApi, options = {}) {
        const {
            insertFirstOption = true,
            firstOptionName = 'Selecione',
            firstOptionValue = '',
            selectedIdOption = elem.val(),
            displayColumnName = 'name'
        } = options;

        const obj = new conectAjax(urlApi);

        try {
            const response = await obj.getData();

            let strOptions = '';

            if (insertFirstOption) {
                strOptions += `<option value="${firstOptionValue}">${firstOptionName}</option>`;
            }

            response.data.forEach(result => {
                const id = result.id;
                const columnValue = result[displayColumnName];
                const strSelected = (id == selectedIdOption ? ' selected' : '');
                strOptions += `\n<option value="${id}"${strSelected}>${columnValue}</option>`;
            });

            elem.html(strOptions);
            return Promise.resolve('A lista foi carregada com sucesso!');
        } catch (error) {
            const errorMessage = 'Erro ao preencher';
            console.error(error);
            elem.html(`<option>${errorMessage}</option>`);
            return Promise.reject(error);
        }
    }

    /**
     * Generates a form and redirects the user to the specified URL.
     *
     * @param {string} redirect - The URL to redirect to upon form submission.
     * @param {Object[]} arrInputs - An array containing input data for the form.
     * @param {Object} options - Additional options to configure form attributes and submission button.
     * @param {Object} options.formAttr - Attributes for the form.
     * @param {string} options.formAttr.method - The method attribute for the form (default: 'POST').
     * @param {boolean} options.formAttr.hidden - Specifies if the form should be hidden (default: true).
     * @param {string} options.formAttr.id - The ID attribute for the form.
     * @param {string} options.formAttr.class - The CSS class attribute for the form.
     * @param {Object} options.submit - Attributes for the submit button.
     * @param {string} options.submit.name - The name attribute for the submit button (default: 'submit').
     * @param {boolean} options.submit.hidden - Specifies if the submit button should be hidden (default: false).
     * @param {string} options.submit.value - The value attribute for the submit button (default: 'Enviar').
     * @param {string} options.submit.id - The ID attribute for the submit button.
     * @param {string} options.submit.class - The CSS class attribute for the submit button.
     * @param {string} options.returnElem - Determines the element to return ('form' for the entire form, 'submit' for the submit button).
     * @returns {HTMLFormElement|HTMLInputElement} - The form or submit button element based on the specified return option.
     */
    static redirectForm(redirect, arrInputs, options = {}) {
        const { formAttr = { method: 'POST', hidden: true, id: '', class: '' },
            submit = { name: 'submit', hidden: false, value: 'Enviar', id: '', class: '' },
            returnElem = 'submit' } = options;

        let form = document.createElement('form');
        form.id = formAttr.id;
        form.hidden = formAttr.hidden;
        form.method = formAttr.method;
        form.action = redirect;

        arrInputs.forEach(input => {

            let newInput = document.createElement('input');
            newInput.type = input.type ? input.type : 'hidden';
            newInput.name = input.name;
            newInput.value = JSON.stringify(input.value);
            form.appendChild(newInput);

        });

        let submitButton = document.createElement('input');
        submitButton.type = 'submit';
        submitButton.id = submit.id;
        submitButton.className = submit.class;
        submitButton.name = submit.name;
        submitButton.hidden = submit.hidden;
        submitButton.value = submit.value;
        form.appendChild(submitButton);
        document.body.appendChild(form);

        let returnElement = submitButton;

        switch (returnElem) {
            case 'form':
                returnElement = form;
                break;

            default:
                returnElement = submitButton
        }

        return returnElement;
    }

    static setItemLocalStorage(name, value) {
        localStorage.setItem(name, value);
    }

    static getItemLocalStorage(name) {
        return localStorage.getItem(name);
    }

    /**
     * Gets an array of default values considered as invalid for generating filters.
     *
     * @returns {Array} - An array of default values.
     */
    static getInvalidsDefaultValuesGenerateFilters() {
        return ['undefined', undefined, 'null', null, '0', 0, ''];
    }

    /**
     * Formats a date to ISO format.
     *
     * @param {Date} date - The date to be formatted (default is the current date if not provided).
     * @returns {string} - The formatted date string in ISO format.
     */
    static formatToISODate(date) {
        if (!date) {
            date = new Date();
        }

        // const isoString = date.toISOString();
        // const offsetMinutes = date.getTimezoneOffset();
        // const offsetHours = Math.abs(offsetMinutes) / 60;
        // const offsetSign = offsetMinutes > 0 ? '-' : '+';

        // return `${isoString.slice(0, -1)}${offsetSign}${this.padNumberWithZero(offsetHours)}:${this.padNumberWithZero(offsetMinutes % 60)}`;

        const year = date.getUTCFullYear();
        const month = this.padNumberWithZero(date.getUTCMonth() + 1);
        const day = this.padNumberWithZero(date.getUTCDate());
        const hours = this.padNumberWithZero(date.getUTCHours());
        const minutes = this.padNumberWithZero(date.getUTCMinutes());
        const seconds = this.padNumberWithZero(date.getUTCSeconds());
        const milliseconds = this.padNumberWithZero(date.getUTCMilliseconds(), 5);

        return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}.${milliseconds}Z`;

    }

    /**
     * Pads a number with zeros to the specified length.
     *
     * @param {number} number - The number to be padded.
     * @param {number} length - The desired length of the padded number (default is 2).
     * @returns {string} - The padded number as a string.
     */
    static padNumberWithZero(number, length = 2) {
        return number.toString().padStart(length, '0');
    }

    /**
     * Generates a date filter object based on after and before values.
     *
     * @param {string} afterValue - The start date for the filter (in YYYY-MM-DD format).
     * @param {string} beforeValue - The end date for the filter (in YYYY-MM-DD format).
     * @returns {Object} - The date filter object.
     */
    static generateDateFilter(afterValue, beforeValue) {
        let filter = {};

        if (afterValue) {
            filter.after = this.formatToISODate(new Date(`${afterValue}T00:00:00`));
        }

        if (beforeValue) {
            filter.before = this.formatToISODate(new Date(`${beforeValue}T23:59:59`));
        }

        return filter;
    }

    /**
     * Handles the change event for radio buttons, enabling or disabling input elements based on selection.
     *
     * @param {string} button - The selector for the radio button.
     * @param {Array} arr - An array of objects representing input elements and their associated properties.
     */
    static eventRBHidden(button, arr) {

        const rb = $(button);

        rb.on('change', function () {

            arr.forEach(element => {

                const group = $(element.div_group);

                element.input.forEach(inp => {

                    const input = $(inp);

                    if ($(element.button).attr('id') != this.id) {
                        input.attr('disabled', true);
                        group.attr('hidden', true);
                    } else {
                        input.removeAttr('disabled');
                        group.removeAttr('hidden');
                    }
                });

            });

        });

    }

    /**
     * Sets up default event handlers for popups, such as close, cancel, and save actions.
     *
     * @param {Object} self - The reference to the current object.
     * @param {Object} options - Additional options to configure the event handlers.
     * @param {boolean} options.formRegister - Whether to include additional event handlers for registration forms (default: false).
     */
    static eventDefaultPopups(self, options = {}) {
        const { formRegister = false,
            inputsSearchs = null
        } = options;

        const idPop = self.getIdPop();

        $(idPop).find(".close-btn").on("click", () => {
            self.closePop();
        });

        $(idPop).find('.btnCancelPop').on('click', () => {

            if (formRegister == true) {

                if (typeof self.cancelPop === 'function') {
                    self.cancelPop();
                } else {
                    self.closePop();
                }

            } else {
                self.closePop();
            }

        });

        $(idPop).find('.btnSavePop').on('click', (event) => {
            event.preventDefault();
            self.saveButtonAction();
        });

        $(idPop).on('keydown', function (e) {
            if (e.key === 'Escape') {
                e.stopPropagation();
                self.closePop();
            }
        });

        if (formRegister == true) {
            this.eventDefaultPopupsRegisters(self);
        }

        if (inputsSearchs != null) {

            inputsSearchs.on("input", function () {

                clearTimeout(self.timerSearch);

                self.timerSearch = setTimeout(function () {
                    self.generateFilters();
                }, 1000);

            });

        }

    }

    /**
     * Sets up additional event handlers for popups related to registration forms.
     *
     * @param {Object} self - The reference to the current object.
     */
    static eventDefaultPopupsRegisters(self) {

        const idPop = self.getIdPop();

        $(idPop).find('form').on('keydown', function (e) {
            if (e.key === 'Escape') {
                self.cancelPop();
                e.stopPropagation();
            }
        });

    }

    static addEventToggleDiv(dataSearchDiv, toggleButton, options = {}) {
        const { self = null, minWidht = 991 } = options;

        function toggleDataSearch() {
            const screenWidth = $(window).width();

            if (screenWidth <= minWidht) {
                dataSearchDiv.hide();
                toggleButton.show();
            } else {
                dataSearchDiv.show();
                toggleButton.hide();
            }

            if (self != null) {
                if (typeof self.adjustTableHeight === 'function') {
                    self.adjustTableHeight();
                }
            }
        }

        toggleButton.click(function (event) {
            event.preventDefault();
            dataSearchDiv.toggle();
        });

        $(window).on('resize.toggleDataSearch', function () {
            toggleDataSearch();
        });

        $(':input').on('focus', function () {
            $(window).off('resize.toggleDataSearch');
        });

        $(':input').on('blur', function () {
            $(window).on('resize.toggleDataSearch', function () {
                toggleDataSearch();
            });
        });

        toggleDataSearch();
    }

    static hiddenInputValue(elem, btn, options = {}) {
        const {
            titleShow = 'Exibir', titleHidden = 'Ocultar'
        } = options;

        const ico = $(btn).find("i");

        const getHidden = () => {
            return this.getItemLocalStorage('hidden_data');
        }

        if (getHidden() == null) {
            this.setItemLocalStorage('hidden_data', true);
        };

        if (getHidden() == 'true') {
            elem.attr("type", "password");
            ico.removeClass("bi bi-eye-slash-fill").addClass("bi bi-eye-fill");
            $(btn).attr('title', titleShow);
        } else {
            $(elem).attr("type", "text");
            ico.removeClass("bi bi-eye-fill").addClass("bi bi-eye-slash-fill");
            $(btn).attr('title', titleHidden);
        }

    }

    static simulateLoading(elem, status = true) {

        if (status) {
            $(elem).addClass('disabled').attr('disabled', true);
            $(elem).find('.spinner-border').removeClass('d-none');
        } else {
            $(elem).removeClass('disabled').removeAttr('disabled');
            $(elem).find('.spinner-border').addClass('d-none');
        }

    }

}