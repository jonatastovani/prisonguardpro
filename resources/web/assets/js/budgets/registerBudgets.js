import { conectAjax } from '../ajax/conectAjax.js';
import { commonFunctions } from "../commons/commonFunctions.js";
import { enumAction } from "../commons/enumAction.js";
import instanceManager from '../commons/instanceManager.js';
import { modalMessage } from '../commons/modalMessage.js';
import { popEditBudgets } from '../popup/budgets/popupEditBudgets.js';
import { popOrders } from '../popup/orders/popupOrders.js';
import { popNewProduct } from "../popup/products/popupNewProduct.js";
import { popProducts } from "../popup/products/popupProducts.js";

$(document).ready(function () {

    const idBudget = $('#id').val();
    const redirectPrevious = $('#redirectPrevious').val();

    function init() {

        const obj = instanceManager.setInstance('registerBudgets', new registerBudgets(idBudget, redirectPrevious));
        obj.getDataAll();

        $('#btnNewProduct').focus();
        executeMask();

        commonFunctions.addEventToggleDiv($("#dataClient"), $("#toggleDataClientButton"), { minWidht: 577 })
        commonFunctions.hiddenInputValue($('#cost_priceBudget'), $("#show_cost_price"), { titleShow: 'Mostrar preço de custo', titleHidden: 'Ocultar preço de custo' });

    };

    const btnNewProduct = $('#btnNewProduct')
    btnNewProduct.on("click", (event) => {

        event.preventDefault();

        let obj = instanceManager.setInstance('popNewProduct', new popNewProduct(urlApiProducts, urlApiProdTemplates));
        obj.setId(idBudget);
        obj.setElemFocusClose(btnNewProduct);
        obj.openPop().then(function (result) {

            if (result) {

                const objBudget = instanceManager.setInstance('registerBudgets', new registerBudgets(idBudget));
                objBudget.getDataAll();

                const obj = instanceManager.setInstance('popProducts', new popProducts(urlApiProducts, urlApiProdItems));
                obj.setUrlApi(`${urlApiProducts}${result}/`)
                obj.openPop();

            }

        });

    });

    function handleButtonEditBudgetClick(event) {
        event.preventDefault();

        const obj = instanceManager.setInstance('popEditBudgets', new popEditBudgets(urlApiBudgets, urlApiClients, urlApiOrders));
        obj.setId(idBudget);
        obj.setElemFocusClose(this);
        obj.openPop().finally(function (result) {
            const objBudget = instanceManager.setInstance('registerBudgets', new registerBudgets(idBudget, redirectPrevious));
            objBudget.getDataAll();
        });
    }

    $(document).on("click", '#editBudget, #newOrder', handleButtonEditBudgetClick);

    $(document).on("click", '#editOrder', function (event) {
        event.preventDefault();

        const idOrder = $(this).data('id');

        const obj = instanceManager.setInstance('popOrders', new popOrders(urlApiOrders));
        obj.setId(idOrder);
        obj.setElemFocusClose(this);
        obj.openPop().finally(function (result) {
            const objBudget = instanceManager.setInstance('registerBudgets', new registerBudgets(idBudget, redirectPrevious));
            objBudget.getDataAll();
        });

    });

    function executeMask() {

        commonFunctions.applyCustomNumberMask($('#cost_priceBudget'), { format: '#.##0,00', reverse: true });
        commonFunctions.applyCustomNumberMask($('#priceBudget'), { format: '#.##0,00', reverse: true });

    }

    $(document).on('click', '#cancel', function () {

        redirection();

    });

    function redirection() {

        window.location.href = redirectPrevious;

    }

    $("#show_cost_price").click(function () {

        const value = (commonFunctions.getItemLocalStorage('hidden_data') == 'true') ? false : true;
        commonFunctions.setItemLocalStorage('hidden_data', value);
        commonFunctions.hiddenInputValue($('#cost_priceBudget'), this, { titleShow: 'Mostrar preço de custo', titleHidden: 'Ocultar preço de custo' });

    });

    init();

});

export class registerBudgets {

    #idBudget;
    #client_id;
    #action;
    #cost_price;
    #redirectPrevious;

    constructor(idBudget, redirectPrevious) {
        this.#idBudget = idBudget;
        this.#redirectPrevious = redirectPrevious;
        this.#action = enumAction.PATCH;
    }

    getDataAll() {
        const self = this;
        const obj = new conectAjax(urlApiBudgets);
        obj.setParam(self.#idBudget);

        obj.getData()
            .then(function (response) {

                self.#client_id = response.client_id;
                self.#cost_price = commonFunctions.formatNumberWithLimitDecimalPlaces(response.cost_price ? response.cost_price : 0);
                const cost_price = commonFunctions.formatWithCurrencyCommasOrFraction(self.#cost_price);
                const price = commonFunctions.formatWithCurrencyCommasOrFraction(response.price ? response.price : 0);

                const btnOpenClient = `<form action="/clients/${response.client_id}" method="post"><button class="btn btn-primary btn-mini edit ms-2" type="submit" title="Editar este cliente"><i class="bi bi-pencil"></i></button><input type="hidden" name="redirect-previous" value="/budgets/${response.id}"></form>`;

                $('#title').html(`Orçamento: ${response.id}`);
                $('#nameClient').html(response.client.name);
                $('#btnOpenClient').html(btnOpenClient);

                $('#order_id').html(response.order_id ? response.order_id : '');

                let strButton;
                if (response.order_id) {
                    strButton = `<button id="editOrder" class="btn btn-primary btn-mini" data-id="${response.order_id}" title="Editar pedido"><i class="bi bi-pencil"></i></button>`;
                } else {
                    strButton = `<button id="newOrder" class="btn btn-success btn-mini" title="Gerar pedido">Gerar pedido</button>`;
                }
                $('#edit_order').html(strButton);

                if (response.client.tel) {
                    $('#tel').html(commonFunctions.formatPhone(response.client.tel));
                }
                if (response.client.cpf) {
                    $('#typeDoc').html('CPF');
                    $('#doc').html(commonFunctions.formatCPF(response.client.cpf));
                }
                if (response.client.cnpj) {
                    $('#typeDoc').html('CNPJ');
                    $('#doc').html(commonFunctions.formatCNPJ(response.client.cnpj));
                }
                $('#created_at').html(moment(response.created_at).format('DD/MM/YYYY HH:mm'));
                $('#updated_at').html(moment(response.updated_at).format('DD/MM/YYYY HH:mm'));
                $('#cost_priceBudget').val(cost_price);
                $('#priceBudget').val(price);

                self.fillProducts(response.products);

                self.addQueryButtonEvents();

            })
            .catch(function (error) {

                $('#form1 :input').prop('disabled', true);
                $.notify(`Não foi possível obter os dados.\nSe o problema persistir consulte o programador.\nErro: ${error}`, 'error');

            });

    }

    fillProducts(arrData) {

        $('#containerProducts').html('');

        arrData.forEach(product => {

            const idDiv = `${product.id}${Date.now()}`;
            const qtdItems = product.item_refs.length;

            const sumPriceItems = (items) => {
                let total = 0;

                for (const item of items) {
                    if (item.price) {
                        total += item.price;
                    }
                }

                return total;
            };

            let strProduct = `
            <div id="${idDiv}" class="p-2 col-md-6 col-12 bg-info bg-opacity-10 border border-info rounded">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-start"><span class="nameProduct">${product.name}</span></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button data-idproduct="${product.id}" title="Editar produto ${product.name}" class="btn btn-primary edit me-2"><i class="bi bi-pencil"></i></button>
                                <button data-idproduct="${product.id}" data-nameproduct="${product.name}" title="Excluir produto ${product.name}" class="btn btn-danger delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                <span title="Quantidade de itens que compõe o produto ${product.name}">Qtd. Itens: <b>${qtdItems}</b></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <span title="Soma total dos itens do produto ${product.name}">Soma: <b>${commonFunctions.formatNumberToCurrency(sumPriceItems(product.item_refs))}</b></span>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

            $('#containerProducts').append(strProduct);

        });

    }

    addQueryButtonEvents() {

        const self = this;

        $('#containerProducts').find('.edit').on("click", function (event) {
            event.preventDefault();

            const idproduct = $(this).data('idproduct');

            const obj = instanceManager.setInstance('popProducts', new popProducts(urlApiProducts, urlApiProdItems));
            obj.setUrlApi(`${urlApiProducts}${idproduct}/`)
            obj.openPop();

        });

        $('#containerProducts').find('.delete').on("click", function (event) {
            event.preventDefault();

            const idDel = $(this).data('idproduct');
            const nameDel = $(this).data('nameproduct');
            self.delButtonAction(idDel, nameDel, this);

        });

    }

    delButtonAction(idDel, nameDel, button = null) {

        const self = this;

        const obj = instanceManager.setInstance('modalMessage', new modalMessage());
        obj.setMessage(`Confirma a exclusão do produto <b>${nameDel}</b>?`);
        obj.setTitle('Confirmação de exclusão de produto');
        obj.setElemFocusClose(button);

        obj.openModal().then(function (result) {

            if (result) {
                self.del(idDel);
            }

        });

    }

    del(idDel) {

        const obj = new conectAjax(urlApiProducts);
        const self = this;

        if (obj.setAction(enumAction.DELETE)) {

            obj.setParam(idDel);

            obj.deleteData()
                .then(function (result) {

                    $.notify(`Produto deletado com sucesso!`, 'success');
                    self.getDataAll();

                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`, 'error');

                });
        }

    }

}
