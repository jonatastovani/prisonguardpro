
import { conectAjax } from "../ajax/conectAjax.js";
import instanceManager from "../commons/instanceManager.js";
import { popNewBudgets } from "../popup/budgets/popupNewBudgets.js";

$(document).ready(function () {

    function init() {

        const obj = instanceManager.setInstance('budgetsHome', new budgetsHome());

        obj.getBudgetsTotal();

    }

    const btnNewBudget = $('#btnNewBudget')
    btnNewBudget.on("click", (event) => {

        event.preventDefault();

        let obj = instanceManager.setInstance('popNewBudgets', new popNewBudgets(urlApiBudgets, urlApiClients));
        obj.setElemFocusClose(btnNewBudget);
        obj.openPop();

    });

    if ($('#budgetsHome, #home').length) {
        init();
    }

});

export class budgetsHome {

    constructor() {
    }

    getBudgetsTotal() {

        const obj = new conectAjax(urlApiBudgets);

        obj.getData()
            .then(function (response) {

                $('#budgetsTotal').html(response.data.length);

            })
            .catch(function (error) {

                console.log(error);
                $('#budgetsTotal').html("Erro Consulta");

            });
    }

}
