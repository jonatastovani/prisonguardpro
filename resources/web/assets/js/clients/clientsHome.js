
import { conectAjax } from "../ajax/conectAjax.js";
import instanceManager from "../commons/instanceManager.js";

$(document).ready(function () {

    function init() {

        const obj = instanceManager.setInstance('clientsHome', new clientsHome());

        obj.getClientsTotal();

    }

    if ($('#clientsHome, #home').length) {
        init();
    }

});

export class clientsHome {

    constructor() {
    }

    getClientsTotal() {

        const obj = new conectAjax(urlApiClients);

        obj.getData()
            .then(function (response) {

                $('#clientsTotal').html(response.data.length);

            })
            .catch(function (error) {

                console.log(error);
                $('#clientsTotal').html("Erro Consulta");

            });
    }

}
