import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";
import instanceManager from "../../commons/instanceManager.js";
import { popNewBudgets } from "../budgets/popupNewBudgets.js";

export class popNewBasicClient {
    
    #urlApi;
    #idPop;
    #idClient;
    #action;
    #elemFocusClose;
    #endTime;

    constructor (urlApi) {
        this.#urlApi = urlApi;
        this.#idPop = "#pop-popNewBasicClient";
        this.#idClient = undefined;
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
        this.#endTime = false;

        this.arrDocs = [
            {
                id: "rbCpfNewBasicClient",
                input: "cpfNewBasicClient"
            }, {
                id: "rbCnpjNewBasicClient",
                input: "cnpjNewBasicClient"
            }
        ];
    
        this.eventRBDocuments('rbCpfNewBasicClient');
        this.eventRBDocuments('rbCnpjNewBasicClient');

        commonFunctions.addEventCheckCPF({
            selector: '#cpfNewBasicClient',
            event: 'blur'
        });
        
        commonFunctions.addEventCheckCNPJ({
            selector: '#cnpjNewBasicClient',
            event: 'blur'
        });

        this.executeMask();

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

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");
        
        return new Promise(function (resolve) {

            const checkConfirmation = setInterval(function () {

                if (self.#idClient !== undefined || self.#endTime) {
                    
                    clearInterval(checkConfirmation);
                    if (self.#idClient !== undefined) {
                        resolve(self.#idClient);
                        self.closePop();
                    }
                }
                self.#idClient = undefined;
                self.#endTime = false;

            }, 100);

        });

    }

    closePop () {

        $(this.#idPop).removeClass("active");
        $(this.#idPop).find(".popup").removeClass("active");
        $(this.#idPop).find("*").off();
        $(this.#idPop).off('keydown');
        this.#endTime = true;

        this.clearPop();

        // if (instanceManager.instanceVerification('employeesHome')) {
        //     const obj = instanceManager.setInstance(('employeesHome'), new employeesHome());
        //     obj.getNewBasicClientTotal();
        // }

        if (this.#elemFocusClose!==null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop () {

        $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('form').find('select').val('');
        $(this.#idPop).find('input[name="name"]').focus();
                
    }

    eventRBDocuments(idRB) {
        const self = this;

        const rb = $(self.#idPop).find(`#${idRB}`);

        rb.on('change', function() {
        
            self.arrDocs.forEach(element => {
                
                const inp = $(self.#idPop).find(`#${element.input}`);
                if (element.id!=this.id) {
                    inp.attr('disabled',true);
                    inp.parent().attr('hidden',true);
                } else {
                    inp.removeAttr('disabled');
                    inp.parent().removeAttr('hidden');
                }
    
            });
            
        });

    }

    executeMask() {

        commonFunctions.cpfMask('#cpfNewBasicClient');
        commonFunctions.cnpjMask('#cnpjNewBasicClient');
        commonFunctions.phoneMask($('.clstelefone').val(),'.clstelefone');

    }

    addButtonsEvents () {
        const self = this;

        commonFunctions.eventDefaultPopups(self);
        
        $(self.#idPop).find('.clstelefone').on('blur', function() {
        
            commonFunctions.phoneMask($(this).val(),'#'+this.id);
    
        })

    }

    saveButtonAction () {

        let data = commonFunctions.getInputsValues($(this.#idPop).find('form')[0], 1, false, false);
        if (data.cpf) {
            data.cpf = commonFunctions.returnsOnlyNumber(data.cpf);
            if (data.cpf!=''){
                data.cpf = data.cpf;
            }
        }
        if (data.cnpj) {
            data.cnpj = commonFunctions.returnsOnlyNumber(data.cnpj);
            if (data.cnpj!=''){
                data.cnpj = data.cnpj;
            }
        }
        data.tel = parseInt(commonFunctions.returnsOnlyNumber(data.tel));

        if (this.saveVerifications(data)) {
            this.save(data);
        } 

    }

    saveVerifications(data) {

        let arrMessage = [];
        
        if (!$(this.#idPop).find('input[name="cpf"]').attr('disabled') && data.cpf) {
            if (!commonFunctions.validateCPF(data.cpf)) {
                if (arrMessage.length==0) {
                    $(this.#idPop).find('input[name="cpf"]').focus();
                }
                arrMessage.push('O CPF informado está incorreto.');
            }
        }

        if (!$(this.#idPop).find('input[name="cnpj"]').attr('disabled') && data.cnpj) {
            if (!commonFunctions.validateCNPJ(data.cnpj)) {
                if (arrMessage.length==0) {
                    $(this.#idPop).find('input[name="cnpj"]').focus();
                }
                arrMessage.push('O CNPJ informado está incorreto.');
            }
        }

        if (arrMessage.length) {
            
            arrMessage.forEach(mess => {
                $.notify(mess,'warning');
            });

            return false;

        }

        return true;

    }
    
    save(data) {

        const self = this;
        const obj = new conectAjax(self.#urlApi);

        if (obj.setAction(self.#action)) {

            const btn = $(self.#idPop).find('.btnSavePop');
            commonFunctions.simulateLoading(btn);

            obj.setData(data);
    
            if (self.#action == enumAction.PUT) {
                obj.setParam(self.#idClient);
            }
    
            obj.saveData()
                .then(function (result) {

                    const idReturn = result.id;

                    $.notify(`Cliente adicionado com sucesso!`,'success');

                    if (instanceManager.instanceVerification('popNewBudgets')) {
                        const obj = instanceManager.setInstance(('popNewBudgets'), new popNewBudgets(urlApiBudgets, self.#urlApi));
                        obj.fillSelectClients(true)
                        .then((result) => {

                            self.#idClient = idReturn;

                        })
                        .catch(error => {

                            $.notify('Houve um problema ao carregar a lista de clientes. Atualize a página e tente novamente.', 'error');
                            self.#endTime = true;

                        });
                    }
                    
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

