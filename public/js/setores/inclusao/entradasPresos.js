import { conectAjax } from "../../ajax/conectAjax.js";
import { configuracoesApp } from "../../comuns/configuracoesApp.js";
import { enumAction } from "../../comuns/enumAction.js";
import { funcoesComuns } from "../../comuns/funcoesComuns.js";
import { funcoesPresos } from "../../comuns/funcoesPresos.js";

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

    // funcoesComuns.eventRBHidden($('#rbEntradasPresos'), arrDateSearch);
    // funcoesComuns.eventRBHidden($('#rbUpdatedEntradasPresos'), arrDateSearch);

    function init() {

        let dateNowSubtract = configuracoesApp.subtractDateDefault('YYYY-MM-DD');
        $('#inicioEntradasPresos').val(dateNowSubtract);
        $('#updatedinicioEntradasPresos').val(dateNowSubtract);
        preencherSelectStatus();
        gerarFiltros();
        funcoesComuns.addEventToggleDiv($("#dataSearch"), $("#toggleDataSearchButton"))

    }

    $('.inputActionEntradasPresosSearch').on("input", function () {

        clearTimeout(timerSearchEntradasPresos);

        timerSearchEntradasPresos = setTimeout(function () {
            gerarFiltros();
        }, 1000);

    });

    function gerarFiltros() {
        let invalidos = funcoesComuns.buscarValoresInvalidosGerarFiltros();

        let data = {
            // sorting: {
            //     field: '_at',
            //     method: $('input[name="methodEntradasPresos"]:checked').val()
            // },
            filtros: {}
        };

        let data_entrada = funcoesComuns.gerarFiltroData(
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

        buscarTodosDados(data);
    }

    function buscarTodosDados(data) {

        tableEntradasPresos.html('');

        const obj = new conectAjax(`${urlIncEntradaPreso}/busca`);
        obj.setData(data);
        obj.setAction(enumAction.POST);

        obj.envRequest()
            .then(function (response) {

                console.log(response)

                response.data.forEach(result => {

                    let strHTML = '';
                    const data_entrada = moment(result.entrada.data_entrada).format('DD/MM/YYYY HH:mm');
                    
                    let matricula = result.matricula;
                    let nome = result.nome;

                    if(result.preso) {
                        matricula = result.preso.matricula;
                    }
                    if(result.preso && result.preso.nome) {
                        nome = result.preso.nome;
                    }

                    matricula = matricula ? funcoesPresos.retornaMatriculaFormatada(matricula) : 'N/C'

                    strHTML += `<tr>`;
                    strHTML += `<td class="text-center"><b><span>${result.id}</span></b></td>`;
                    strHTML += `<td class="text-center"><div class="col-12 d-flex justify-content-center">
                        <a href="entradas/${result.entrada_id}" class="btn btn-primary btn-mini me-2" title="Editar Entrada de Preso ${result.entrada_id}"><i class="bi bi-pencil"></i></a></td>`;
                    strHTML += `<td class="text-center text-nowrap"><span>${matricula}</span></td>`;
                    strHTML += `<td><span>${nome}</span></td>`;
                    strHTML += `<td class="text-center text-nowrap"><span>${result.rg ? result.rg : 'N/C'}</span></td>`;
                    strHTML += `<td class="text-center"><span>${data_entrada}</span></td>`;
                    strHTML += `<td><span>${result.entrada.origem.nome}</span></td>`;
                    strHTML += `<td><span>${result.status.nome.nome}</span></td>`;
                    // strHTML += `<td class="text-center"><div class="col-12 d-flex justify-content-center">
                    //     <form action="/entradaspresos/${result.id}" method="get"><button class="btn btn-primary btn-sm edit me-2" type="submit" title="Editar este orçamento"><i class="bi bi-pencil"></i></button></form>`;
                    // strHTML += `<button class="btn btn-danger btn-sm delete" data-id="${result.id}" title="Deletar este orçamento"><i class="bi bi-trash"></i></button>
                    // </div></td>`;
                    strHTML += `</tr>`;

                    tableEntradasPresos.append(strHTML);

                });


            })
            .catch(function (error) {

                console.error(error);
                const message = funcoesComuns.firstUppercaseLetter(error.message);
                tableEntradasPresos.html(`<td colspan=8>${message}</td>`);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${message}`, 'error');

            });

    }

    function preencherSelectStatus() {

        const opcoes = {
            tipoRequest: enumAction.POST,
            envData: {
                tipo: 2
            },
        }

        funcoesComuns.preencherSelect($('#statusEntradasPresos'), `${urlRefStatus}/busca/select`, opcoes)


    }

    setTimeout(() => {
        window.Echo.channel('testing')
        .listen('.App\\Events\\testeWebsocket',(e)=>{
            tableEntradasPresos.html('');
            gerarFiltros();
        })
    }, 1000);

    init();

});