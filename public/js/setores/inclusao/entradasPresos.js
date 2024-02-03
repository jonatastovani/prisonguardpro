import { conectAjax } from "../../ajax/conectAjax.js";
import { configuracoesApp } from "../../common/configuracoesApp.js";
import { enumAction } from "../../common/enumAction.js";
import { funcoesComuns } from "../../common/funcoesComuns.js";
import { funcoesPresos } from "../../common/funcoesPresos.js";

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
                    
                    let strTitle = ``;
                    let strStyle = ``;
                    if (!result.convivio_tipo.convivio_padrao_bln) {
                        strTitle = `Convívio: ${result.convivio_tipo.nome}`;
                        strStyle = `style="color: ${result.convivio_tipo.cor.cor_texto}; background-color: ${result.convivio_tipo.cor.cor_fundo};"`;
                    }
                    strHTML += `<tr title="${strTitle}">`;
                    strHTML += `<td class="text-center" ${strStyle}><b><span>${result.id}</span></b></td>`;
                    strHTML += `<td class="text-center" ${strStyle}><div class="col-12 d-flex justify-content-center">
                        <a href="entradas/${result.entrada_id}" class="btn btn-outline-primary btn-mini-2 me-2" title="Editar Entrada de Preso ${result.entrada_id}"><i class="bi bi-pencil"></i></a>
                        <a href="qualificativa/${result.id}" class="btn btn-outline-primary btn-mini-2 me-2" title="Preencher Qualificativa para o Preso ${result.nome}"><i class="bi bi-clipboard2-data-fill"></i></a>
                        </td>`;
                    strHTML += `<td class="text-center text-nowrap" ${strStyle}><span>${matricula}</span></td>`;
                    strHTML += `<td ${strStyle}><span>${nome}</span></td>`;
                    strHTML += `<td class="text-center text-nowrap" ${strStyle}><span>${result.rg ? result.rg : 'N/C'}</span></td>`;
                    strHTML += `<td class="text-center" ${strStyle}><span>${data_entrada}</span></td>`;
                    strHTML += `<td ${strStyle}><span>${result.entrada.origem.nome}</span></td>`;
                    strHTML += `<td ${strStyle}><span>${result.status.nome.nome}</span></td>`;
                    strHTML += `</tr>`;

                    tableEntradasPresos.append(strHTML);

                });


            })
            .catch(function (error) {

                console.error(error);
                tableEntradasPresos.html(`<td colspan=8>${error.message}</td>`);
                $.notify(`Não foi possível obter os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`, 'error');

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
        window.Echo.channel('EntradasPresos')
        .listen('.EntradasPresos',(e)=>{
            tableEntradasPresos.html('');
            gerarFiltros();
        })
    }, 1000);

    init();

});