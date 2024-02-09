import { conectAjax } from "../ajax/conectAjax.js";
import { commonFunctions } from "../commons/commonFunctions.js";
import { enumAction } from "../commons/enumAction.js";
import instanceManager from "../commons/instanceManager.js";
import { modalMessage } from "../commons/modalMessage.js";
import { popNewTemplate } from "../popup/products/popupNewTemplate.js";

$(document).ready(function () {

    const tableTemplates = $('#table-templates').find('tbody');

    function init() {

        getDataTemplatesAll();
        // btnNewTemplate.click();

    }

    function getDataTemplatesAll() {

        const obj = new conectAjax(urlApiProdTemplates);

        obj.getData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(result => {

                    const parameters = result.parameters !== null ? result.parameters.join(', ') : 'N/C';

                    strHTML += `<tr title="Parâmetros deste produto: ${parameters}">`;
                    strHTML += `<td><span>${result.name}</span></td>`;
                    strHTML += `<td class="text-center"><span>${result.item_refs.length}</span></td>`;
                    strHTML += `<td class="text-center"><div class="col-12 d-flex justify-content-center">
                        <form action="/products/templates/${result.id}" method="get"><button class="btn btn-primary btn-sm edit me-2" type="submit" title="Editar este modelo"><i class="bi bi-pencil"></i></button></form>`;
                    strHTML += `<button class="btn btn-danger btn-sm delete" data-id="${result.id}" data-name="${result.name}" title="Deletar este modelo"><i class="bi bi-trash"></i></button>
                    </div></td>`;
                    strHTML += `</tr>`;

                });

                tableTemplates.html(strHTML);

            })
            .catch(function (error) {

                console.error(error);
                const description = commonFunctions.firstUppercaseLetter(error.description);
                tableTemplates.html(`<td colspan=4>${description}</td>`);
                $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${description}`, 'error');

            });

    }

    const btnNewTemplate = $('#btnNewTemplate')
    btnNewTemplate.on("click", (event) => {

        event.preventDefault();

        let obj = instanceManager.setInstance('popNewTemplate', new popNewTemplate(urlApiProdTemplates));
        obj.setElemFocusClose(btnNewTemplate)
        obj.openPop()


    });

    $(document).on('click', '.delete', function (event) {
        event.preventDefault();

        const id = $(this).data('id');
        const name = $(this).data('name');

        const obj = instanceManager.setInstance('modalMessage', new modalMessage());
        obj.setMessage(`Confirma a exclusão do modelo <b>${name}</b>?`);
        obj.setTitle('Confirmação de exclusão de Modelo');
        obj.setElemFocusClose(this);

        obj.openModal().then(function (result) {

            if (result) {
                delTemplate(id, name);
            }

        });

    });

    function delTemplate(idTemplate) {

        const obj = new conectAjax(urlApiProdTemplates);

        if (obj.setAction(enumAction.DELETE)) {

            obj.setParam(idTemplate);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Modelo deletado com sucesso!`, 'success');
                    getDataTemplatesAll();

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

    init();

});