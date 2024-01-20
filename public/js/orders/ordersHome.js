
import { conectAjax } from "../ajax/conectAjax.js";
import instanceManager from "../commons/instanceManager.js";

$(document).ready(function () {

    function init() {

        const obj = instanceManager.setInstance('ordersHome', new ordersHome());

        obj.getOrdersTotal();

    }

    if ($('#ordersHome, #home').length) {
        init();
    }

});

export class ordersHome {

    constructor() {
    }

    getOrdersTotal() {

        const obj = new conectAjax(urlApiOrders);

        obj.getData()
            .then(function (response) {

                $('#ordersTotal').html(response.data.length);

            })
            .catch(function (error) {

                console.log(error);
                $('#ordersTotal').html("Erro Consulta");

            });
    }

}
