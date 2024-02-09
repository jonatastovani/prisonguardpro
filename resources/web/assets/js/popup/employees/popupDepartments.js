import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { modalMessage } from "../../commons/modalMessage.js";
import { employeesHome } from "../../employees/employeesHome.js";
import { popEmployees } from "./popupEmployees.js";

export class popDepartments {

    #urlApi;
    #idPop;
    #idDepartment;
    #action;
    #elemFocusClose;
    #returnPromisse;
    #endTime;
    #blnReturnPromisse;
    timerSearch;
    #arrDateSearch;

    constructor(urlApi) {
        this.#urlApi = urlApi;
        this.#idPop = "#pop-popDepartments";
        this.#idDepartment = null;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
        this.#endTime = false;
        this.#blnReturnPromisse = false;

        const pop = $(this.#idPop);

        this.#arrDateSearch = [
            {
                button: pop.find('#rbCreatedDepartments'),
                input: [pop.find('input[name="createdAfterDepartments"]'), pop.find('input[name="createdBeforeDepartments"]')],
                div_group: pop.find('.group-createdDepartments')
            }, {
                button: pop.find('#rbUpdatedDepartments'),
                input: [pop.find('input[name="updatedAfterDepartments"]'), pop.find('input[name="updatedBeforeDepartments"]')],
                div_group: pop.find('.group-updatedDepartments')
            }
        ];

        commonFunctions.eventRBHidden(pop.find('#rbCreatedDepartments'), this.#arrDateSearch);
        commonFunctions.eventRBHidden(pop.find('#rbUpdatedDepartments'), this.#arrDateSearch);

    }

    setId(id) {
        this.#idDepartment = id;
    }

    getIdPop() {
        return this.#idPop;
    }

    setAction(action) {
        this.#action = action;
    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    openPop() {

        const self = this;

        self.addButtonsEvents();
        self.generateFilters();

        if (self.#idDepartment != '' && self.#idDepartment != null) {

            self.get();

        } else {
            self.cancelPop();

        }

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");

        return new Promise(function (resolve, reject) {

            const checkConfirmation = setInterval(function () {

                if (self.#returnPromisse !== undefined || self.#endTime) {

                    clearInterval(checkConfirmation);
                    if (self.#returnPromisse !== undefined) {

                        resolve(self.#returnPromisse);
                        self.closePop();

                    } else {

                        reject();

                    }

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
        this.cancelPop();

        if (instanceManager.instanceVerification('employeesHome')) {
            const obj = instanceManager.setInstance(('employeesHome'), new employeesHome());
            obj.getDepartmentsTotal();
        }
        if (instanceManager.instanceVerification('popEmployees')) {
            const obj = instanceManager.setInstance(('popEmployees'), new popEmployees(urlApiWorkEmployees, urlApiWorkDepartments, urlApiWorkRoles));
            obj.fillSelectDepartments();
        }

        if (this.#elemFocusClose !== null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop() {

        $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('form').find('select').val('');
        this.#idDepartment = null;

    }

    cancelPop() {

        this.#action = enumAction.POST;
        if ($(this.#idPop).find(".hidden-fields").css("display") === "block") {
            this.registrationVisibility();
        }
        $(this.#idPop).find('.btnNewPop').focus();
        this.clearPop();

    }

    addButtonsEvents() {
        const self = this;

        commonFunctions.eventDefaultPopups(self, { formRegister: true, inputsSearchs: $(self.#idPop).find('.inputActionDepartments') });

        $(self.#idPop).find(".btnNewPop").on("click", () => {
            $(self.#idPop).find('.titlePop').html('Novo departamento');
            self.registrationVisibility();
            $(self.#idPop).find('input[name="name"]').focus();
            self.#action = enumAction.POST;
        });

        self.adjustTableHeight();
        commonFunctions.addEventToggleDiv($(self.#idPop).find(".dataSearch"), $(self.#idPop).find(".toggleDataSearchButton"), { self: self })
    }

    addQueryButtonEvents() {
        const self = this;

        $(self.#idPop).find('.table').find('.edit').on("click", function () {
            self.#idDepartment = $(this).data('id');

            self.get();

        });

        $(self.#idPop).find('.table').find('.delete').on("click", function () {

            const idDel = $(this).data('id');
            const nameDel = $(this).data('name');
            self.delButtonAction(idDel, nameDel, this);

        });

        $(self.#idPop).find('.table').find('.select').on("click", function () {

            self.#returnPromisse = $(this).data('id');

        });

        $(self.#idPop).find('.table').find('tr').on('dblclick', function () {

            if (self.#blnReturnPromisse) {
                self.#returnPromisse = $(this).data('id');
            }

        });

    }

    registrationVisibility() {

        $(this.#idPop).find('.btnNewPop').parent().parent().slideToggle();
        $(this.#idPop).find(".hidden-fields").slideToggle();
        setTimeout(() => {
            this.adjustTableHeight();
        }, 500);

    }

    adjustTableHeight() {

        const self = this;
        const screenHeight = $(window).height();
        const sizeDiscount = $(this.#idPop).find(".hidden-fields").css("display") === "block" ? 385 : 285;
        const maxHeight = screenHeight - sizeDiscount;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    generateFilters() {

        const self = this;
        const dataSearch = $(self.#idPop).find('.dataSearch');

        let data = {
            sorting: {
                field: 'name',
                method: dataSearch.find('input[name="methodDepartments"]:checked').val()
            },
            filters: {}
        };

        if (!dataSearch.find('input[name="createdAfterDepartments"]').attr('disabled')) {

            let created_at = commonFunctions.generateDateFilter(
                dataSearch.find('input[name="createdAfterDepartments"]').val(),
                dataSearch.find('input[name="createdBeforeDepartments"]').val()
            );
            if (Object.keys(created_at).length !== 0) {
                data.filters.created_at = created_at;
            }

        } else {

            let updated_at = commonFunctions.generateDateFilter(
                dataSearch.find('input[name="updatedAfterDepartments"]').val(),
                dataSearch.find('input[name="updatedBeforeDepartments"]').val()
            );
            if (Object.keys(updated_at).length !== 0) {
                data.filters.updated_at = updated_at;
            }

        }

        const name = dataSearch.find('input[name="name"]').val();
        if (name != '') {
            data.filters.name = name;
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

        obj.saveData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(result => {
                    let btnReturn = '';

                    if (self.#blnReturnPromisse) {
                        btnReturn = `<button class="btn btn-success btn-mini select" data-id="${result.id}" title="Selecionar este registro"><i class="bi bi-check2-square"></i></button>`;
                    }

                    strHTML += `<tr data-id="${result.id}">`;
                    strHTML += `<td class="text-center">${btnReturn}</td>`;
                    strHTML += `<td><span>${result.name}</span></td>`;
                    strHTML += `<td><div class="d-flex flex-nowrap justify-content-center"><button class="btn btn-primary btn-mini edit me-2" data-id="${result.id}" title="Editar este registro"><i class="bi bi-pencil"></i></button>`;
                    strHTML += `<button class="btn btn-danger btn-mini delete" data-id="${result.id}" data-name="${result.name}" title="Deletar este registro"><i class="bi bi-trash"></i></button></div></td>`;
                    strHTML += `</tr>`;

                });

                table.html(strHTML);
                $(self.#idPop).find('.totalRegisters').html(response.data.length)
                self.addQueryButtonEvents();

            })
            .catch(function (error) {

                $(self.#idPop).find('.totalRegisters').html('0');
                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                table.html(`<td colspan=2>${description}</td>`);
                $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

    get() {

        const self = this;
        $(self.#idPop).find('.titlePop').html('Alterar departamento');
        $(self.#idPop).find('input[name="name"]').focus();
        self.#action = enumAction.PUT;

        const obj = new conectAjax(self.#urlApi);
        obj.setParam(self.#idDepartment);

        obj.getData()
            .then(function (response) {

                if ($(self.#idPop).find(".hidden-fields").css("display") === "none") {
                    self.registrationVisibility();
                }
                const form = $(self.#idPop).find('form');

                form.find('input[name="name"]').val(response.name).focus();

            })
            .catch(function (error) {

                $(self.#idPop).find('form1 :input').prop('disabled', true);
                console.log(error);
                $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

            });

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

            obj.setData(data);

            if (this.#action == enumAction.PUT) {
                obj.setParam(this.#idDepartment);
            }

            obj.saveData()
                .then(function (result) {

                    $.notify(`Dados enviados com sucesso!`, 'success');
                    self.generateFilters();

                    if (instanceManager.instanceVerification('employeesHome')) {
                        const obj = instanceManager.setInstance(('employeesHome'), new employeesHome());
                        obj.getDepartmentsTotal();
                    }
                    if (instanceManager.instanceVerification('popEmployees')) {
                        const obj = instanceManager.setInstance(('popEmployees'), new popEmployees(urlApiWorkEmployees, urlApiWorkDepartments, urlApiWorkRoles));
                        obj.fillSelectDepartments();
                    }

                    if (self.#action == enumAction.PUT) {
                        self.cancelPop();
                    } else {
                        self.clearPop();
                        $(self.#idPop).find('form').find('input[name="name"]').focus();
                    }

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                })
                .finally(function () {
                    commonFunctions.simulateLoading(btn, false);
                });
        }

    }

    delButtonAction(idDel, nameDel, button = null) {

        const self = this;

        const obj = instanceManager.setInstance('modalMessage', new modalMessage());
        obj.setMessage(`Confirma a exclusão do departamento <b>${nameDel}</b>?`);
        obj.setTitle('Confirmação de exclusão de Departamento');
        obj.setElemFocusClose(button);

        obj.openModal().then(function (result) {

            if (result) {
                self.del(idDel);
            }

        });

    }

    del(idDel) {

        const obj = new conectAjax(this.#urlApi);
        const self = this;

        if (obj.setAction(enumAction.DELETE)) {

            obj.setParam(idDel);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Departamento deletado com sucesso!`, 'success');
                    self.cancelPop();
                    self.generateFilters();

                    if (instanceManager.instanceVerification('employeesHome')) {
                        const obj = instanceManager.setInstance('employeesHome', new employeesHome());
                        obj.getDepartmentsTotal();
                    }
                    if (instanceManager.instanceVerification('popEmployees')) {
                        const obj = instanceManager.setInstance('popEmployees', new popEmployees(urlApiWorkEmployees, urlApiWorkDepartments, urlApiWorkRoles));
                        obj.fillSelectDepartments();
                    }

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

}

