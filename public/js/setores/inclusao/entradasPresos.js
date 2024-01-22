import { commonFunctions } from "../../common/commonFunctions.js";
import { configurationApp } from "../../common/configurationsApp.js";
import { enumAction } from "../../common/enumAction.js";

$(document).ready(function () {

    const tableEntradasPresos = $('#table-entradaspresos').find('tbody');
    let timerSearchEntradasPresos = null;

    // const arrDateSearch = [
    //     {
    //         button: $('#rbEntradasPresos'),
    //         input: [$('#inicioEntradasPresos'), $('#fimEntradasPresos')],
    //         div_group: $('.group-EntradasPresos')
    //     }, {
    //         button: $('#rbUpdatedEntradasPresos'),
    //         input: [$('#updatedinicioEntradasPresos'), $('#updatedfimEntradasPresos')],
    //         div_group: $('.group-updatedEntradasPresos')
    //     }
    // ];

    // commonFunctions.eventRBHidden($('#rbEntradasPresos'), arrDateSearch);
    // commonFunctions.eventRBHidden($('#rbUpdatedEntradasPresos'), arrDateSearch);

    function init() {

        let dateNowSubtract = configurationApp.subtractDateDefault('YYYY-MM-DD');
        $('#inicioEntradasPresos').val(dateNowSubtract);
        $('#updatedinicioEntradasPresos').val(dateNowSubtract);
        preencherSelectStatus();
        gerarFiltros();
        commonFunctions.addEventToggleDiv($("#dataSearch"), $("#toggleDataSearchButton"))

    }

    $('.inputActionEntradasPresosSearch').on("input", function () {

        clearTimeout(timerSearchEntradasPresos);

        timerSearchEntradasPresos = setTimeout(function () {
            gerarFiltros();
        }, 1000);

    });

    function gerarFiltros() {
        let invalidos = commonFunctions.buscarValoresInvalidosGerarFiltros();

        let data = {
            // sorting: {
            //     field: '_at',
            //     method: $('input[name="methodEntradasPresos"]:checked').val()
            // },
            filtros: {}
        };

        let data_entrada = commonFunctions.gerarFiltroData(
            $('#inicioEntradasPresos').val(),
            $('#fimEntradasPresos').val()
        );

        if (Object.keys(data_entrada).length !== 0) {
            data.filtros.data_entrada = data_entrada;
        }

        let ordenacao = $('#ordenacaoEntradasPresos').val();
        ordenacao = String(ordenacao).trim();

        if (!invalidos.includes(ordenacao)) {
            data.filtros.ordenacao = ordenacao;
        }

        let status = $('#statusEntradasPresos').val();
        status = String(status).trim();

        if (!invalidos.includes(status)) {
            data.filtros.status = status;
        }

        let valor = $('#valorEntradasPresos').val();
        valor = String(valor).trim();

        if (!invalidos.includes(valor)) {
            data.filtros.texto = {
                valor: valor
            };

            let tratamento = $('#tratamentoEntradasPresos').val();
            tratamento = String(tratamento).trim();

            if (!invalidos.includes(tratamento)) {
                data.filtros.texto['tratamento'] = tratamento;
            }

            let metodo = $('#metodoEntradasPresos').val();
            metodo = String(metodo).trim();

            if (!invalidos.includes(metodo)) {
                data.filtros.texto['metodo'] = metodo;
            }
        }

        console.log(data);
        return;
        getDataAll(data);
    }

    function getDataAll(data) {

        const obj = new conectAjax(`${urlApiEntradasPresos}search/`);
        obj.setData(data);
        obj.setAction(enumAction.POST);
        obj.setParam('?size=100000');

        obj.saveData()
            .then(function (response) {

                let strHTML = '';

                response.data.forEach(result => {

                    const tel = commonFunctions.formatPhone(result.client.tel);
                    const _at = moment(result._at).format('DD/MM/YYYY HH:mm');
                    const price = commonFunctions.formatNumberToCurrency(result.price ? result.price : 0);

                    strHTML += `<tr>`;
                    strHTML += `<td><b><span>${result.id}</span></b></td>`;
                    strHTML += `<td><span>${result.client.name}</span></td>`;
                    strHTML += `<td class="text-center"><span>${tel}</span></td>`;
                    strHTML += `<td class="text-center"><span>${price}</span></td>`;
                    strHTML += `<td class="text-center"><span>${_at}</span></td>`;
                    strHTML += `<td class="text-center"><div class="col-12 d-flex justify-content-center">
                        <form action="/entradaspresos/${result.id}" method="get"><button class="btn btn-primary btn-sm edit me-2" type="submit" title="Editar este orçamento"><i class="bi bi-pencil"></i></button></form>`;
                    strHTML += `<button class="btn btn-danger btn-sm delete" data-id="${result.id}" title="Deletar este orçamento"><i class="bi bi-trash"></i></button>
                    </div></td>`;
                    strHTML += `</tr>`;

                });

                tableEntradasPresos.html(strHTML);

            })
            .catch(function (error) {

                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                tableEntradasPresos.html(`<td colspan=5>${description}</td>`);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

    function preencherSelectStatus() {

        const opcoes = {
            tipoRequest: enumAction.POST,
            envData: {
                tipo: 2
            },
        }

            commonFunctions.preencherSelect($('#statusEntradasPresos'), `${urlRefStatus}/busca/select`, opcoes)


    }

    // function fillSearchEntradasPresos() {

    //     const self = this;

    //     const obj = new conectAjax(urlApiEntradasPresos);
    //     const elem = $('#listEntradasPresos');

    //     obj.getData()
    //         .then(function (response) {

    //             let strOptions = '';

    //             response.data.forEach(result => {

    //                 const client = result.client.name;
    //                 const id = result.id;
    //                 const display = `${moment(result._at).format('DD/MM/YYYY HH:mm')} | ${client} `;
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

    //     let obj = instanceManager.setInstance('popNewEntradasPresos', new popNewEntradasPresos(urlApiEntradasPresos, urlApiClients));
    //     obj.setElemFocusClose(btnNewBudget);
    //     obj.openPop();

    // });

    // const btnSearchClientesEntradasPresos = $("#btnSearchClientesEntradasPresos");
    // btnSearchClientesEntradasPresos.on("click", (event) => {
    //     event.preventDefault();

    //     const obj = instanceManager.setInstance('popSearchClients', new popSearchClients(urlApiClients));
    //     obj.setElemFocusClose(btnSearchClientesEntradasPresos);

    //     obj.openPop().then(function (result) {

    //         if (result) {
    //             $('#statusEntradasPresos').val(result).trigger('input');
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

    //     const obj = new conectAjax(urlApiEntradasPresos);

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