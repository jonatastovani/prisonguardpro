import { conectAjax } from "../ajax/conectAjax.js";
import instanceManager from "../commons/instanceManager.js";
import { popDepartments } from "../popup/employees/popupDepartments.js";
import { popEmployees } from "../popup/employees/popupEmployees.js";
import { popRoles } from "../popup/employees/popupRoles.js";

$(document).ready(function () {

    function init() {

        const formPopDataHidden = '#formPopDataHidden';

        const obj = instanceManager.setInstance('employeesHome', new employeesHome());

        obj.getDepartmentsTotal();
        obj.getRolesTotal();
        obj.getEmployeesTotal();

        const popName = $(formPopDataHidden).find('input[name="popName"]').val();
        const popId = $(formPopDataHidden).find('input[name="popId"]').val();

        switch (popName) {

            case 'departments':
                if (popId != '') {

                    let obj = instanceManager.setInstance('popDepartments', new popDepartments(urlApiWorkDepartments));
                    obj.setId(popId);

                }

                openPopupDepartments.click();
                break;

            case 'roles':
                if (popId != '') {

                    let obj = instanceManager.setInstance('popRoles', new popRoles(urlApiWorkRoles));
                    obj.setId(popId);

                }

                openPopupRoles.click();
                break;

            case 'employees':
                if (popId != '') {

                    let obj = instanceManager.setInstance('popEmployees', new popEmployees(urlApiWorkEmployees, urlApiWorkDepartments, urlApiWorkRoles));
                    obj.setId(popId);

                }

                openPopupEmployees.click();
                break;

        }


    }

    const openPopupDepartments = $('#openPopupDepartments')
    openPopupDepartments.on("click", (event) => {

        event.preventDefault();

        let obj = instanceManager.setInstance('popDepartments', new popDepartments(urlApiWorkDepartments));
        obj.setElemFocusClose(openPopupDepartments);
        obj.openPop();

    });

    const openPopupRoles = $('#openPopupRoles')
    openPopupRoles.on("click", (event) => {

        event.preventDefault();

        let obj = instanceManager.setInstance('popRoles', new popRoles(urlApiWorkRoles));
        obj.setElemFocusClose(openPopupRoles);
        obj.openPop();

    });

    const openPopupEmployees = $('#openPopupEmployees')
    openPopupEmployees.on("click", (event) => {

        event.preventDefault();

        let obj = instanceManager.setInstance('popEmployees', new popEmployees(urlApiWorkEmployees, urlApiWorkDepartments, urlApiWorkRoles));
        obj.setElemFocusClose(openPopupEmployees);
        obj.openPop();

    });

    if ($('#employeesHome, #home').length) {
        init();
    }

});

export class employeesHome {

    constructor() {
    }

    getDepartmentsTotal() {

        const obj = new conectAjax(urlApiWorkDepartments);

        obj.getData()
            .then(function (response) {

                $('#departmentsTotal').html(response.data.length);

            })
            .catch(function (error) {

                console.log(error);
                $('#departmentsTotal').html("Erro Consulta");

            });
    }

    getRolesTotal() {

        const obj = new conectAjax(urlApiWorkRoles);

        obj.getData()
            .then(function (response) {

                $('#rolesTotal').html(response.data.length);

            })
            .catch(function (error) {

                console.log(error);
                $('#rolesTotal').html("Erro Consulta");

            });
    }

    getEmployeesTotal() {

        const obj = new conectAjax(urlApiWorkEmployees);

        obj.getData()
            .then(function (response) {

                $('#employeesTotal').html(response.data.length);

            })
            .catch(function (error) {

                console.log(error);
                $('#employeesTotal').html("Erro Consulta");

            });
    }

}
