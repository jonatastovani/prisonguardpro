import { conectAjax } from "../ajax/conectAjax.js";
import { commonFunctions } from "../commons/commonFunctions.js";
import { configurationApp } from "../commons/configurationsApp.js";
import { enumAction } from "../commons/enumAction.js";
import instanceManager from "../commons/instanceManager.js";
import { modalMessage } from "../commons/modalMessage.js";

$(document).ready(function () {

    const tableClients = $('#table-clients').find('tbody');
    let timerSearchClients = null;

    function init() {

        let dateNowSubtract = configurationApp.subtractDateDefault('YYYY-MM-DD');
        $('#createdAfterSearchClients').val(dateNowSubtract);
        $('#updatedAfterSearchClients').val(dateNowSubtract);

        const arrDocs = [
            {
                button: $('#rbCpfSearchClients'),
                input: [$('input[name="cpf"]')],
                div_group: $('.group-cpfSearchClients')
            }, {
                button: $('#rbCnpjSearchClients'),
                input: [$('input[name="cnpj"]')],
                div_group: $('.group-cnpjSearchClients')
            }
        ];

        const arrDateSearch = [
            {
                button: $('#rbCreatedSearchClients'),
                input: [$('input[name="createdAfterSearchClients"]'), $('input[name="createdBeforeSearchClients"]')],
                div_group: $('.group-createdSearchClients')
            }, {
                button: $('#rbUpdatedSearchClients'),
                input: [$('input[name="updatedAfterSearchClients"]'), $('input[name="updatedBeforeSearchClients"]')],
                div_group: $('.group-updatedSearchClients')
            }
        ];

        commonFunctions.eventRBHidden($('#rbCpfSearchClients'), arrDocs);
        commonFunctions.eventRBHidden($('#rbCnpjSearchClients'), arrDocs);
        commonFunctions.eventRBHidden($('#rbCreatedSearchClients'), arrDateSearch);
        commonFunctions.eventRBHidden($('#rbUpdatedSearchClients'), arrDateSearch);

        commonFunctions.cpfMask('#cpfSearchClients');
        commonFunctions.cnpjMask('#cnpjSearchClients');

        generateFilters();
        commonFunctions.addEventToggleDiv($("#dataSearch"), $("#toggleDataSearchButton"))

    }

    function generateFilters() {

        let invalids = commonFunctions.getInvalidsDefaultValuesGenerateFilters();

        let data = {
            sorting: {
                field: 'name',
                method: $('input[name="method"]:checked').val()
            },
            filters: {}
        };

        if (!$('input[name="createdAfterSearchClients"]').attr('disabled')) {

            let created_at = commonFunctions.generateDateFilter(
                $('input[name="createdAfterSearchClients"]').val(),
                $('input[name="createdBeforeSearchClients"]').val()
            );
            if (Object.keys(created_at).length !== 0) {
                data.filters.created_at = created_at;
            }

            // data.sorting.field = 'created_at';

        } else {

            let updated_at = commonFunctions.generateDateFilter(
                $('input[name="updatedAfterSearchClients"]').val(),
                $('input[name="updatedBeforeSearchClients"]').val()
            );
            if (Object.keys(updated_at).length !== 0) {
                data.filters.updated_at = updated_at;
            }

            // data.sorting.field = 'updated_at';

        }

        let name = $('input[name="name"]').val();
        name = String(name).trim();

        if (!invalids.includes(name)) {
            data.filters.name = name;
        }

        const field = $('input[name="document"]:checked').val();
        let valueField = $(`#${field}SearchClients`).val();
        if (valueField.trim().length) {

            valueField = commonFunctions.returnsOnlyNumber(valueField);
            if (valueField != '') {
                data.filters[field] = valueField;
            }

        }

        getDataAll(data);
    }

    function getDataAll(data) {

        const obj = new conectAjax(`${urlApiClients}search/`);
        obj.setData(data);
        obj.setAction(enumAction.POST);
        obj.setParam('?size=100000');

        obj.saveData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(client => {
                    const tel = commonFunctions.formatPhone(client.tel);
                    const cpf = commonFunctions.formatCPF(client.cpf);
                    const cnpj = commonFunctions.formatCNPJ(client.cnpj);
                    const city = client.city != null ? client.city : '';

                    strHTML += `<tr>`;
                    strHTML += `<td><span>${client.name}</span></td>`;
                    strHTML += `<td class="text-center"><span>${tel}</span></td>`;
                    strHTML += `<td class="text-center"><span>${cpf}</span></td>`;
                    strHTML += `<td class="text-center"><span>${cnpj}</span></td>`;
                    strHTML += `<td class="text-center"><span>${city}</span></td>`;
                    strHTML += `<td class="text-center"><div class="col-12 d-flex justify-content-center">
                        <form action="clients/${client.id}" method="get"><button class="btn btn-primary btn-sm edit me-2" type="submit" title="Editar este cliente"><i class="bi bi-pencil"></i></button></form>`;
                    strHTML += `<button class="btn btn-danger btn-sm delete" data-id="${client.id}" data-name="${client.name}" title="Deletar este cliente"><i class="bi bi-trash"></i></button>
                    </div></td>`;
                    strHTML += `</tr>`;

                });

                tableClients.html(strHTML);

            })
            .catch(function (error) {

                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                tableClients.html(`<td colspan=7>${description}</td>`);
                $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

    $('.inputActionSearch').on("input", function () {

        clearTimeout(timerSearchClients);

        timerSearchClients = setTimeout(function () {
            generateFilters();
        }, 1000);

    });

    $(document).on('click', '.delete', function (event) {
        event.preventDefault();

        const id = $(this).data('id');
        const name = $(this).data('name');

        const obj = instanceManager.setInstance('modalMessage', new modalMessage());
        obj.setMessage(`Confirma a exclusão do cadastro do cliente <b>${name}</b>?`);
        obj.setTitle('Confirmação de exclusão de Cliente');
        obj.setElemFocusClose(this);

        obj.openModal().then(function (result) {

            if (result) {
                delClient(id);
            }

        });

    });

    function delClient(idClient) {

        const obj = new conectAjax(urlApiClients);

        if (obj.setAction(enumAction.DELETE)) {

            obj.setParam(idClient);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Cliente deletado com sucesso!`, 'success');
                    generateFilters();

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

    init();

});