import { zipCode } from '../ajax/ZipCodeAjax.js';
import { conectAjax } from '../ajax/conectAjax.js';
import { commonFunctions } from "../commons/commonFunctions.js";
import { enumAction } from "../commons/enumAction.js";

$(document).ready(function () {

    const idClient = $('#id').val();
    const redirectPrevious = $('#redirectPrevious').val();
    let actionRegCli;

    const arrDocs = [
        {
            id: "rbcpf",
            input: "cpf"
        }, {
            id: "rbcnpj",
            input: "cnpj"
        }
    ];


    function init() {

        let title;

        if (idClient != '') {
            actionRegCli = enumAction.PUT;
            title = 'Atualizar Cliente';
            getClient();
        } else {
            actionRegCli = enumAction.POST;
            title = 'Novo Cliente';
        }

        $('#title').html(title);

        commonFunctions.addEventCheckCPF({
            selector: '#cpf',
            event: 'blur'
        });

        commonFunctions.addEventCheckCNPJ({
            selector: '#cnpj',
            event: 'blur'
        });

        eventRBDocuments('rbcpf');
        eventRBDocuments('rbcnpj');

        executeMask();
        $('#name').focus();

    };

    function getClient() {
        const obj = new conectAjax(urlApiClients);
        obj.setParam(idClient);

        obj.getData()
            .then(function (response) {

                $('#name').val(response.name);
                if (response.cpf) {
                    $('#rbcpf').prop('checked', true).trigger('change');
                    $('#cpf').val(response.cpf).trigger('input');
                }
                if (response.cnpj) {
                    $('#rbcnpj').prop('checked', true).trigger('change');
                    $('#cnpj').val(response.cnpj).trigger('input');
                }
                $('#zipcode').val(response.zipcode);
                $('#street').val(response.street);
                $('#street_number').val(response.street_number);
                $('#neighbourhood').val(response.neighbourhood);
                $('#complement').val(response.complement);
                $('#reference').val(response.reference);
                $('#city').val(response.city);
                $('#state').val(response.state);
                $('#email').val(response.email);
                $('#tel').val(response.tel);

                executeMask();

            })
            .catch(function (error) {

                $('#form1 :input').prop('disabled', true);
                $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o programador.\nErro: ${error}`, 'error');

            });

    }

    $('#save').on('click', function (event) {
        event.preventDefault();

        let data = commonFunctions.getInputsValues($('#form1')[0], 1);
        if (data.cpf) {
            data.cpf = commonFunctions.returnsOnlyNumber(data.cpf);
            if (data.cpf != '') {
                data.cpf = data.cpf;
            }
        }
        if (data.cnpj) {
            data.cnpj = commonFunctions.returnsOnlyNumber(data.cnpj);
            if (data.cnpj != '') {
                data.cnpj = data.cnpj;
            }
        }
        data.street_number = parseInt(commonFunctions.returnsOnlyNumber(data.street_number));
        data.tel = parseInt(commonFunctions.returnsOnlyNumber(data.tel));

        if (saveVerifications(data)) {
            save(data);
        }

    });

    function save(data) {

        const obj = new conectAjax(urlApiClients);

        if (obj.setAction(actionRegCli)) {

            const btn = $('#save');
            commonFunctions.simulateLoading(btn);

            obj.setData(data);

            let notifyMessage = 'Cliente adicionado com sucesso!';

            if (actionRegCli == enumAction.PUT) {
                obj.setParam(idClient);
                notifyMessage = 'Cliente alterado com sucesso!';
            }

            obj.saveData()
                .then(function (result) {

                    let btn = commonFunctions.redirectForm(redirectPrevious, [
                        { name: 'arrNotifyMessage', value: [{ message: notifyMessage, type: 'success' }] }
                    ]);
                    btn.click();

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

    function saveVerifications(data) {

        let arrMessage = [];

        if (data.name.length == 0) {
            arrMessage.push('O nome do cliente deve ser preenchido.');
            $('#name').focus();
        }

        if (!$('#cpf').attr('disabled') && data.cpf) {
            if (!commonFunctions.validateCPF(data.cpf)) {
                if (arrMessage.length == 0) {
                    $('#cpf').focus();
                }
                arrMessage.push('O CPF informado está incorreto.');
            }
        }

        if (!$('#cnpj').attr('disabled') && data.cnpj) {
            if (!commonFunctions.validateCNPJ(data.cnpj)) {
                if (arrMessage.length == 0) {
                    $('#cnpj').focus();
                }
                arrMessage.push('O CNPJ informado está incorreto.');
            }
        }

        if (arrMessage.length) {

            arrMessage.forEach(mess => {
                $.notify(mess, 'warning');
            });

            return false;

        }

        return true;

    }

    function eventRBDocuments(idRB) {

        const rb = $(`#${idRB}`);

        rb.on('change', function () {

            arrDocs.forEach(element => {

                const inp = $(`#${element.input}`);
                if (element.id != this.id) {
                    inp.attr('disabled', true);
                    inp.parent().attr('hidden', true);
                } else {
                    inp.removeAttr('disabled');
                    inp.parent().removeAttr('hidden');
                }

            });

        });

    }

    $('#zipcode').on('input', function () {

        if (this.value.length == 10) {

            const obj = new zipCode();
            obj.setZipcode(this.value);
            obj.setIdElemStreet('#street');
            obj.setIdElemNeighbourhood('#neighbourhood');
            obj.setIdElemcity('#city');
            obj.setIdElemState('#state');
            obj.setIdElemFocus('#street_number');

            obj.execute();

        }

    });

    function executeMask() {

        commonFunctions.cepMask('#zipcode');
        commonFunctions.cpfMask('#cpf');
        commonFunctions.cnpjMask('#cnpj');
        commonFunctions.phoneMask($('.clstelefone').val(), '.clstelefone');

    }

    $('.clstelefone').on('blur', function () {

        commonFunctions.phoneMask($(this).val(), '#' + this.id);

    })

    $(document).on('click', '#cancel', function () {

        redirection();

    });

    function redirection() {

        window.location.href = redirectPrevious;

    }

    init();

});