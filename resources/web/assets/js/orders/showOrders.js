import { conectAjax } from "../ajax/conectAjax.js";
import { commonFunctions } from "../commons/commonFunctions.js";
import { configurationApp } from "../commons/configurationsApp.js";
import { enumAction } from "../commons/enumAction.js";
import instanceManager from "../commons/instanceManager.js";
import { modalMessage } from "../commons/modalMessage.js";
import { popOrders } from "../popup/orders/popupOrders.js";

$(document).ready(function () {

    const tableOrders = $('#table-orders').find('tbody');
    let timerSearchOrders = null;

    function init() {

        const arrDateSearch = [
            {
                button: $('#rbCreatedOrders'),
                input: [$('#createdAfterOrders'), $('#createdBeforeOrders')],
                div_group: $('.group-createdOrders')
            }, {
                button: $('#rbUpdatedOrders'),
                input: [$('#updatedAfterOrders'), $('#updatedBeforeOrders')],
                div_group: $('.group-updatedOrders')
            }
        ];

        commonFunctions.eventRBHidden($('#rbCreatedOrders'), arrDateSearch);
        commonFunctions.eventRBHidden($('#rbUpdatedOrders'), arrDateSearch);

        let dateNowSubtract = configurationApp.subtractDateDefault('YYYY-MM-DD');
        $('#createdAfterOrders').val(dateNowSubtract);
        $('#updatedAfterOrders').val(dateNowSubtract);
        $('#statusOrders').html(configurationApp.fillOptionsOrderStatus());
        generateFilters();
        commonFunctions.addEventToggleDiv($("#dataSearch"), $("#toggleDataSearchButton"))

    }

    $('.inputActionOrders').on("input", function () {

        clearTimeout(timerSearchOrders);

        timerSearchOrders = setTimeout(function () {
            generateFilters();
        }, 1000);

    });

    function generateFilters() {

        let data = {
            sorting: {
                field: 'created_at',
                method: $('input[name="method"]:checked').val()
            },
            filters: {}
        };

        if (!$('#createdAfterOrders').attr('disabled')) {

            let created_at = commonFunctions.generateDateFilter(
                $('#createdAfterOrders').val(),
                $('#createdBeforeOrders').val()
            );
            if (Object.keys(created_at).length !== 0) {
                data.filters.created_at = created_at;
            }

        } else {

            let updated_at = commonFunctions.generateDateFilter(
                $('#updatedAfterOrders').val(),
                $('#updatedBeforeOrders').val()
            );
            if (Object.keys(updated_at).length !== 0) {
                data.filters.updated_at = updated_at;
            }

            data.sorting.field = 'updated_at';

        }

        const status = $('#statusOrders').val();
        if (status != '') {
            data.filters.status = status;
        }
        getDataAll(data);

    }

    function getDataAll(data) {

        const obj = new conectAjax(`${urlApiOrders}search/`);
        obj.setData(data);
        obj.setAction(enumAction.POST);
        obj.setParam('?size=100000');

        obj.saveData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(result => {

                    let strClientName = '<b class="text-center">N/C</b>'
                    let strBudgetId = '<b class="text-center">N/C</b>'
                    let strBudgetPrice = '<b class="text-center">N/C</b>';
                    let strClientTel = '<b class="text-center">N/C</b>'
                    let strBtnEdit = '';

                    if (result.budget) {
                        const budget = result.budget;
                        const client = budget.client;

                        strClientName = client.name;
                        strBudgetId = budget.id;
                        strBudgetPrice = commonFunctions.formatNumberToCurrency(budget.price ? budget.price : 0);
                        strClientTel = commonFunctions.formatPhone(client.tel);

                        strBtnEdit = `<form action="budgets/${budget.id}" method="post"><button class="btn btn-primary btn-sm ms-2 btn-mini" type="submit" title="Editar este orçamento"><i class="bi bi-pencil"></i></button><input type="hidden" name="redirect-previous" value="/orders"><input type="hidden" name="id" value="${budget.id}"></form>`;
                    }

                    strHTML += `<tr data-id="${result.id}">`;
                    strHTML += `<td class="text-center"><b>${result.id}</b></td>`;
                    strHTML += `<td>${result.status}</td>`;
                    strHTML += `<td>${strClientName}</td>`;
                    strHTML += `<td class="text-center"><div class="d-flex flex-row">${strBudgetId}${strBtnEdit}</div></td>`;
                    strHTML += `<td class="text-center">${strBudgetPrice}</td>`;
                    strHTML += `<td class="text-center">${strClientTel}</td>`;
                    strHTML += `<td class="text-center"><div class="col-12 d-flex justify-content-center">
                    <button class="btn btn-primary edit me-2 btn-sm" data-id="${result.id}" title="Editar este pedido"><i class="bi bi-pencil"></i></button>`;
                    strHTML += `<button class="btn btn-danger btn-sm delete" data-id="${result.id}" title="Deletar este pedido"><i class="bi bi-trash"></i></button>
                    </div></td>`;
                    strHTML += `</tr>`;
                });

                tableOrders.html(strHTML);

            })
            .catch(function (error) {

                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                tableOrders.html(`<td colspan=8>${description}</td>`);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

    $(document).on('click', '.edit', function (event) {
        event.preventDefault();

        const idOrder = $(this).data('id');

        const obj = instanceManager.setInstance('popOrders', new popOrders(urlApiOrders));
        obj.setId(idOrder);
        obj.setElemFocusClose(this);
        obj.openPop().finally(function (result) {
            generateFilters()
        });

    });

    $(document).on('click', '.delete', function (event) {
        event.preventDefault();

        var id = $(this).data('id');

        const obj = instanceManager.setInstance('modalMessage', new modalMessage());
        obj.setMessage(`Confirma a exclusão deste pedido?`);
        obj.setTitle('Confirmação de exclusão de Pedido');
        obj.setElemFocusClose(this);

        obj.openModal().then(function (result) {

            if (result) {
                delOrder(id);
            }

        });

    });

    function delOrder(idClient) {

        const obj = new conectAjax(urlApiOrders);

        if (obj.setAction(enumAction.DELETE)) {

            obj.setParam(idClient);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Pedido deletado com sucesso!`, 'success');
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