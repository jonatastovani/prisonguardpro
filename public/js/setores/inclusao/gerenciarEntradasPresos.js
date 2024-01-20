import { commonFunctions } from "../../common/commonFunctions.js";
import { configurationApp } from "../../common/configurationsApp.js";

$(document).ready(function () {

    const tableGerEntradasPresos = $('#table-gerentradaspresos').find('tbody');
    let timerSearchGerEntradasPresos = null;

    // const arrDateSearch = [
    //     {
    //         button: $('#rbCreatedGerEntradasPresos'),
    //         input: [$('#createdAfterGerEntradasPresos'), $('#createdBeforeGerEntradasPresos')],
    //         div_group: $('.group-createdGerEntradasPresos')
    //     }, {
    //         button: $('#rbUpdatedGerEntradasPresos'),
    //         input: [$('#updatedAfterGerEntradasPresos'), $('#updatedBeforeGerEntradasPresos')],
    //         div_group: $('.group-updatedGerEntradasPresos')
    //     }
    // ];

    // commonFunctions.eventRBHidden($('#rbCreatedGerEntradasPresos'), arrDateSearch);
    // commonFunctions.eventRBHidden($('#rbUpdatedGerEntradasPresos'), arrDateSearch);

    function init() {

        let dateNowSubtract = configurationApp.subtractDateDefault('YYYY-MM-DD');
        $('#createdAfterGerEntradasPresos').val(dateNowSubtract);
        $('#updatedAfterGerEntradasPresos').val(dateNowSubtract);
        // fillSelectClients();
        // generateFilters();
        commonFunctions.addEventToggleDiv($("#dataSearch"), $("#toggleDataSearchButton"))

    }

    // $('.inputActionGerEntradasPresosSearch').on("input", function () {

    //     clearTimeout(timerSearchGerEntradasPresos);

    //     timerSearchGerEntradasPresos = setTimeout(function () {
    //         generateFilters();
    //     }, 1000);

    // });

    // function generateFilters() {
    //     let invalids = commonFunctions.getInvalidsDefaultValuesGenerateFilters();

    //     let data = {
    //         sorting: {
    //             field: 'created_at',
    //             method: $('input[name="methodGerEntradasPresos"]:checked').val()
    //         },
    //         filters: {}
    //     };

    //     if (!$('#createdAfterGerEntradasPresos').attr('disabled')) {

    //         let created_at = commonFunctions.generateDateFilter(
    //             $('#createdAfterGerEntradasPresos').val(),
    //             $('#createdBeforeGerEntradasPresos').val()
    //         );
    //         if (Object.keys(created_at).length !== 0) {
    //             data.filters.created_at = created_at;
    //         }

    //     } else {

    //         let updated_at = commonFunctions.generateDateFilter(
    //             $('#updatedAfterGerEntradasPresos').val(),
    //             $('#updatedBeforeGerEntradasPresos').val()
    //         );
    //         if (Object.keys(updated_at).length !== 0) {
    //             data.filters.updated_at = updated_at;
    //         }

    //         // data.sorting.field = 'updated_at';

    //     }

    //     let client_id = $('#client_idGerEntradasPresos').val();
    //     client_id = String(client_id).trim();

    //     if (!invalids.includes(client_id)) {
    //         data.filters.client_id = client_id;
    //     }

    //     let budget_id = $('#budget_id').val();
    //     budget_id = String(budget_id).trim();

    //     if (!invalids.includes(budget_id)) {
    //         data.filters.id = budget_id;
    //     }

    //     getDataAll(data);
    //     fillSearchGerEntradasPresos();
    // }

    function getDataAll(data) {

        const obj = new conectAjax(`${urlApiGerEntradasPresos}search/`);
        obj.setData(data);
        obj.setAction(enumAction.POST);
        obj.setParam('?size=100000');

        obj.saveData()
            .then(function (response) {

                let strHTML = '';

                response.data.forEach(result => {

                    const tel = commonFunctions.formatPhone(result.client.tel);
                    const created_at = moment(result.created_at).format('DD/MM/YYYY HH:mm');
                    const price = commonFunctions.formatNumberToCurrency(result.price ? result.price : 0);

                    strHTML += `<tr>`;
                    strHTML += `<td><b><span>${result.id}</span></b></td>`;
                    strHTML += `<td><span>${result.client.name}</span></td>`;
                    strHTML += `<td class="text-center"><span>${tel}</span></td>`;
                    strHTML += `<td class="text-center"><span>${price}</span></td>`;
                    strHTML += `<td class="text-center"><span>${created_at}</span></td>`;
                    strHTML += `<td class="text-center"><div class="col-12 d-flex justify-content-center">
                        <form action="/gerentradaspresos/${result.id}" method="get"><button class="btn btn-primary btn-sm edit me-2" type="submit" title="Editar este orçamento"><i class="bi bi-pencil"></i></button></form>`;
                    strHTML += `<button class="btn btn-danger btn-sm delete" data-id="${result.id}" title="Deletar este orçamento"><i class="bi bi-trash"></i></button>
                    </div></td>`;
                    strHTML += `</tr>`;

                });

                tableGerEntradasPresos.html(strHTML);

            })
            .catch(function (error) {

                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                tableGerEntradasPresos.html(`<td colspan=5>${description}</td>`);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

    // function fillSelectClients(returnPromisse = false) {

    //     if (returnPromisse) {

    //         return new Promise((resolve, reject) => {
    //             commonFunctions.fillSelect($('#client_idGerEntradasPresos'), urlApiClients)
    //                 .then((result) => {
    //                     resolve(result);
    //                 })
    //                 .catch(error => {
    //                     reject(error);
    //                 });
    //         });

    //     } else {

    //         commonFunctions.fillSelect($('#client_idGerEntradasPresos'), urlApiClients)

    //     }

    // }

    // function fillSearchGerEntradasPresos() {

    //     const self = this;

    //     const obj = new conectAjax(urlApiGerEntradasPresos);
    //     const elem = $('#listGerEntradasPresos');

    //     obj.getData()
    //         .then(function (response) {

    //             let strOptions = '';

    //             response.data.forEach(result => {

    //                 const client = result.client.name;
    //                 const id = result.id;
    //                 const display = `${moment(result.created_at).format('DD/MM/YYYY HH:mm')} | ${client} `;
    //                 strOptions += `\n<option value="${id}">${display}</option>`;

    //             });

    //             elem.html(strOptions);

    //         })
    //         .catch(function (error) {

    //             console.error(error);
    //             $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

    //         });

    // }

    // const btnNewBudget = $('#btnNewBudget')
    // btnNewBudget.on("click", (event) => {

    //     event.preventDefault();

    //     let obj = instanceManager.setInstance('popNewGerEntradasPresos', new popNewGerEntradasPresos(urlApiGerEntradasPresos, urlApiClients));
    //     obj.setElemFocusClose(btnNewBudget);
    //     obj.openPop();

    // });

    // const btnSearchClientesGerEntradasPresos = $("#btnSearchClientesGerEntradasPresos");
    // btnSearchClientesGerEntradasPresos.on("click", (event) => {
    //     event.preventDefault();

    //     const obj = instanceManager.setInstance('popSearchClients', new popSearchClients(urlApiClients));
    //     obj.setElemFocusClose(btnSearchClientesGerEntradasPresos);

    //     obj.openPop().then(function (result) {

    //         if (result) {
    //             $('#client_idGerEntradasPresos').val(result).trigger('input');
    //         }

    //     });

    // });

    // $(document).on('click', '.delete', function (event) {
    //     event.preventDefault();

    //     var id = $(this).data('id');

    //     const obj = instanceManager.setInstance('modalMessage', new modalMessage());
    //     obj.setMessage(`Confirma a exclusão deste orçamento?`);
    //     obj.setTitle('Confirmação de exclusão de Orçamento');
    //     obj.setElemFocusClose(this);

    //     obj.openModal().then(function (result) {

    //         if (result) {
    //             delBudget(id);
    //         }

    //     });

    // });

    // function delBudget(idClient) {

    //     const obj = new conectAjax(urlApiGerEntradasPresos);

    //     if (obj.setAction(enumAction.DELETE)) {

    //         obj.setParam(idClient);

    //         obj.deleteData()
    //             .then(function (result) {

    //                 $.notify(`Orçamento deletado com sucesso!`, 'success');
    //                 generateFilters();

    //             })
    //             .catch(function (error) {

    //                 console.log(error);
    //                 $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

    //             });
    //     }

    // }

    init();

});