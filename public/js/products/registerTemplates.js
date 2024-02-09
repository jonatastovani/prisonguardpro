import { conectAjax } from '../ajax/conectAjax.js';
import { commonFunctions } from "../commons/commonFunctions.js";
import { enumAction } from "../commons/enumAction.js";
import instanceManager from '../commons/instanceManager.js';
import { modalMessage } from '../commons/modalMessage.js';
import { popNewItemTemplate } from '../popup/products/popupNewItemTemplate.js';
import { popNewTemplate } from '../popup/products/popupNewTemplate.js';

$(document).ready(function () {

    const idTemplate = $('#id').val();

    const redirectPrevious = $('#redirectPrevious').val();

    function init() {

        const obj = instanceManager.setInstance('registerTemplates', new registerTemplates(urlApiProdTemplates, idTemplate));
        obj.getDataAll();

        btnAddItem.focus();

    };

    const btnAddItem = $('#btnAddItem')
    btnAddItem.on("click", (event) => {

        event.preventDefault();

        let obj = instanceManager.setInstance('popNewItemTemplate', new popNewItemTemplate(urlApiProdItems));
        obj.setUrlApi(`${urlApiProdTemplates}${idTemplate}/items/`)
        obj.setElemFocusClose(btnAddItem);
        obj.openPop().finally(function () {

            const objTemplate = instanceManager.setInstance('registerTemplates', new registerTemplates(urlApiProdTemplates, idTemplate));
            objTemplate.getDataAll();

        });

    });

    const editTemplate = $('#editTemplate')
    editTemplate.on("click", function (event) {
        event.preventDefault();

        const obj = instanceManager.setInstance('popNewTemplate', new popNewTemplate(urlApiProdTemplates));
        obj.setId(idTemplate);
        obj.setElemFocusClose(this);
        obj.openPop().finally(function (result) {

            const objTemplate = instanceManager.setInstance('registerTemplates', new registerTemplates(urlApiProdTemplates, idTemplate));
            objTemplate.getDataAll();

        });

    });

    $(document).on('click', '#cancel', function () {

        redirection();

    });

    function redirection() {

        window.location.href = redirectPrevious;

    }

    init();

});

export class registerTemplates {

    #idTemplate;
    #urlApi;

    constructor(urlApi, idTemplate) {
        this.#idTemplate = idTemplate;
        this.#urlApi = urlApi;
    }

    getDataAll() {
        const self = this;

        const obj = new conectAjax(self.#urlApi);
        obj.setParam(self.#idTemplate);

        obj.getData()
            .then(function (response) {

                $('#title').html(`Modelo: ${response.name}`);

                self.fillItems(response.item_refs);

            })
            .catch(function (error) {

                const message = `Não foi possível obter os dados.\nSe o problema persistir consulte o programador.\nErro: ${error}`;
                $.notify(message, 'error');
                console.error(message);

            });

    }

    fillItems(arrData) {

        const self = this;

        $('#containerItems').html('');

        arrData.forEach(item => {

            const obj = new conectAjax(urlApiProdItems);
            obj.setParam(item.default_item_id);

            obj.getData()
                .then(function (response) {

                    const idDiv = `${item.default_item_id}${Date.now()}`;
                    const itemType = commonFunctions.firstUppercaseLetter(item.item_type);
                    const nameItem = commonFunctions.firstUppercaseLetter(response.name);

                    let strItem = `
                    <div id="${idDiv}" class="p-2 col-md-6 bg-info bg-opacity-10 border border-info rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-start"><span>${itemType}</span></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button data-itemtype="${item.item_type}" title="Editar item ${nameItem}" class="btn btn-primary btn-sm edit me-2"><i class="bi bi-pencil"></i></button>
                                        <button data-itemtype="${item.item_type}" data-nameitem="${nameItem}" title="Excluir item ${nameItem}" class="btn btn-danger btn-sm delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <span title="Tipo definido para este item">Item padrão: <b>${nameItem}</b></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <span title="Desconto fixo aplicado para este tipo de item">Desconto fixo: <b>R$ ${commonFunctions.formatWithCurrencyCommasOrFraction(item.fixed_discount)}</b></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <span title="Porcentagem de desconto aplicado para este tipo de item">Desconto percentual: <b>${item.percentage_discount}%</b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $('#containerItems').append(strItem);
                    self.addQueryButtonEvents(idDiv);

                })
                .catch(function (error) {

                    const message = `Não foi possível obter os dados do item ${item.default_item_id}.\nSe o problema persistir consulte o programador.\nErro: ${error}`;
                    $.notify(message, 'error');
                    console.error(message);

                });

        });

    }

    addQueryButtonEvents(idDiv) {

        const self = this;

        $(`#${idDiv}`).find('.edit').on("click", function (event) {
            event.preventDefault();

            const itemtype = $(this).data('itemtype');

            const obj = instanceManager.setInstance('popNewItemTemplate', new popNewItemTemplate(urlApiProdItems));
            obj.setUrlApi(`${self.#urlApi}${self.#idTemplate}/items/`)
            obj.setItemType(itemtype);
            obj.setElemFocusClose(this);
            obj.openPop().finally(function (result) {

                const objTemplate = instanceManager.setInstance('registerTemplates', new registerTemplates(self.#urlApi, self.#idTemplate));
                objTemplate.getDataAll();

            });

        });

        $(`#${idDiv}`).find('.delete').on("click", function (event) {
            event.preventDefault();

            const itemtype = $(this).data('itemtype');
            const nameDel = $(this).data('nameitem');
            self.delButtonAction(itemtype, nameDel, this);

        });

    }

    delButtonAction(idDel, nameDel, button = null) {

        const self = this;

        const obj = instanceManager.setInstance('modalMessage', new modalMessage());
        obj.setMessage(`Confirma a exclusão do item <b>${nameDel}</b>?`);
        obj.setTitle('Confirmação de exclusão de item');
        obj.setElemFocusClose(button);

        obj.openModal().then(function (result) {

            if (result) {
                self.del(idDel);
            }

        });

    }

    del(idDel) {

        const self = this;
        const obj = new conectAjax(`${urlApiProdTemplates}${self.#idTemplate}/items/`);

        if (obj.setAction(enumAction.DELETE)) {

            obj.setParam(idDel);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Item deletado com sucesso!`, 'success');
                    self.getDataAll();

                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

}
