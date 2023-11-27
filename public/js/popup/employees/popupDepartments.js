import { conectAjax } from "../../ajax/conectAjax.js";
import instanceManager from "../../common/instanceManager.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import { modalMessage } from "../../commons/modalMessage.js";
import { employeesHome } from "../../employees/employeesHome.js";
import { popEmployees } from "./popupEmployees.js";

export class popDepartments {
    
    #urlApi;
    #idPop;
    #idDepartment;
    #action;
    #elemFocusClose;

    constructor (urlApi) {
        this.#urlApi = urlApi;
        this.#idPop = "#pop-popDepartments";
        this.#idDepartment = null;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
    }
    
    setId (id) {
        this.#idDepartment = id;
    }

    setAction (action) {
        this.#action = action;
    }

    setElemFocusClose (elem) {
        this.#elemFocusClose = elem;
    }

    openPop (){

        this.addButtonsEvents();
        this.getDataAll ();

        if (this.#idDepartment!='' && this.#idDepartment!=null){

            this.get();

        } else {
            this.cancelPop();

        }

        $(this.#idPop).addClass("active");
        $(this.#idPop).find(".popup").addClass("active");

        
    }

    closePop () {

        $(this.#idPop).removeClass("active");
        $(this.#idPop).find(".popup").removeClass("active");
        $(this.#idPop).find("*").off();
        $(this.#idPop).off('keydown');

        this.cancelPop();

        if (instanceManager.instanceVerification('employeesHome')) {
            const obj = instanceManager.setInstance(('employeesHome'), new employeesHome());
            obj.getDepartmentsTotal();
        }
        if (instanceManager.instanceVerification('popEmployees')) {
            const obj = instanceManager.setInstance(('popEmployees'), new popEmployees(urlApiWorkEmployees,urlApiWorkDepartments,urlApiWorkRoles));
            obj.fillSelectDepartments();
        }

        if (this.#elemFocusClose!==null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop () {

        $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('form').find('select').val('');
        this.#idDepartment = '';
        
    }

    cancelPop () {

        this.#action = enumAction.POST;
        if ($(this.#idPop).find(".hidden-fields").css("display") === "block") {
            this.registrationVisibility();
        }
        $(this.#idPop).find('.btnNewPop').focus();
        this.clearPop();

    }

    addButtonsEvents () {
        const self = this;

        $(self.#idPop).find(".close-btn").on("click", () => {
            self.closePop();
        });

        $(self.#idPop).find('.btnCancelPop').on('click', () => {
            self.cancelPop();
        });

        $(self.#idPop).find(".btnNewPop").on("click", () => {
            $(self.#idPop).find('.titlePop').html('Novo departamento');
            self.registrationVisibility();
            $(self.#idPop).find('input[name="name"]').focus();
            self.#action = enumAction.POST;
        });

        $(self.#idPop).find('.btnSavePop').on('click', (event) => {
            event.preventDefault();
            self.saveButtonAction();
        });

        $(self.#idPop).find('form').on('keydown', function (e) {
            if (e.key === 'Escape') {
                self.cancelPop();
                e.stopPropagation();
            }
        });
        
        $(self.#idPop).on('keydown', function (e) {
            if (e.key === 'Escape') {
                self.closePop();
                e.stopPropagation();
            }
        });
        
        self.adjustTableHeight();
        
    }
    
    addQueryButtonEvents (){
        const self = this;

        $(self.#idPop).find('.table').find('.edit').on("click", function () {
            self.#idDepartment = $(this).data('id');

            self.get();

        });

        $(self.#idPop).find('.table').find('.delete').on("click", function () {

            const idDel = $(this).data('id');
            const nameDel = $(this).data('name');
            self.delButtonAction (idDel, nameDel, this);
            
        });

    }

    registrationVisibility () {

        $(this.#idPop).find('.btnNewPop').slideToggle();
        $(this.#idPop).find(".hidden-fields").slideToggle();

    }

    adjustTableHeight() {

        const self = this;
        const screenHeight = $(window).height();
        const maxHeight = screenHeight - 290;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    getDataAll () {

        const obj = new conectAjax (this.#urlApi);
        const table = $(`${this.#idPop} .table tbody`);
        const self = this;

        obj.getData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(result => {

                    strHTML += '<tr><td>'+result.name+'</td>';
                    strHTML += '<td  class="text-right"><input class="btn btn-primary edit" type="button" value="Editar" data-id="'+result.id+'" title="Editar este registro"></td>';
                    strHTML += '<td><button class="btn btn-danger delete" type=button data-id="'+result.id+'" data-name="'+result.name+'" title="Deletar este registro">Deletar</button></td></tr>';

                });

                table.html(strHTML);
                $(self.#idPop).find('.totalRegisters').html(response.data.length)
                self.addQueryButtonEvents();

            })
            .catch(function (error) {

                $(self.#idPop).find('.totalRegisters').html('0');
                table.html('<td colspan=6>'+error+'</td>');
                
            });

    }

    get() {
        
        const self = this;
        $(self.#idPop).find('.titlePop').html('Alterar departamento');   
        $(self.#idPop).find('input[name="name"]').focus();
        self.#action = enumAction.PUT;

        const obj = new conectAjax (self.#urlApi);
        obj.setParam(self.#idDepartment);

        obj.getData()
            .then(function (response) {

                if ($(self.#idPop).find(".hidden-fields").css("display") === "none") {
                    self.registrationVisibility();
                }
        
                $(self.#idPop).find('input[name="name"]').val(response.name).focus();
                
            })
            .catch(function (error) {

                $(self.#idPop).find('form1 :input').prop('disabled', true);
                console.log(error);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`,'error');

            });

    }

    saveButtonAction () {

        let data = commonFunctions.getInputsValues($(this.#idPop).find('form')[0], 1, false, false);
        
        if (this.saveVerifications(data)) {
            this.save(data);
        } 

    }

    save(data) {

        const obj = new conectAjax (this.#urlApi);
        const self = this;

        if (obj.setAction(this.#action)) {
            obj.setData(data);
    
            if (this.#action == enumAction.PUT) {
                obj.setParam(this.#idDepartment);
            }
    
            obj.saveData()
                .then(function (result) {

                    $.notify(`Dados enviados com sucesso!`,'success');
                    self.getDataAll();

                    if (instanceManager.instanceVerification('employeesHome')) {
                        const obj = instanceManager.setInstance(('employeesHome'), new employeesHome());
                        obj.getDepartmentsTotal();
                    }
                    if (instanceManager.instanceVerification('popEmployees')) {
                        const obj = instanceManager.setInstance(('popEmployees'), new popEmployees(urlApiWorkEmployees,urlApiWorkDepartments,urlApiWorkRoles));
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
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`,'error');

            });
        }

    }

    saveVerifications(data) {

        // let arrMessage = [];

        // if (data.name.length==0) {
        //     arrMessage.push('O nome ou descrição do departamento deve ser preenchido.');
        //     $(this.#idPop).find('input[name="name"]').focus();
        // }

        // if (arrMessage.length) {
            
        //     let strMessage = '';
        //     arrMessage.forEach(mess => {
        //         if (strMessage!='') {
        //             strMessage += '\n';
        //         }
        //         strMessage += mess;
        //     });

        //     $.notify(strMessage,'warning');
        //     return false;

        // }

        return true;

    }
    
    delButtonAction (idDel, nameDel, button = null) {
        
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

        const obj = new conectAjax (this.#urlApi);
        const self = this;

        if (obj.setAction(enumAction.DELETE)) {
    
            obj.setParam(idDel);
    
            obj.deleteData()
                .then(function (result) {

                    $.notify(`Departamento deletado com sucesso!`,'success');
                    self.cancelPop();
                    self.getDataAll();

                    if (instanceManager.instanceVerification('employeesHome')) {
                        const obj = instanceManager.setInstance(('employeesHome'), new employeesHome());
                        obj.getDepartmentsTotal();
                    }
                    if (instanceManager.instanceVerification('popEmployees')) {
                        const obj = instanceManager.setInstance(('popEmployees'), new popEmployees(urlApiWorkEmployees,urlApiWorkDepartments,urlApiWorkRoles));
                        obj.fillSelectDepartments();
                    }

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`,'error');

            });
        }

    }

}

