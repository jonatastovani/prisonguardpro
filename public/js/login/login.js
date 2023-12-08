import { conectAjax } from "../ajax/conectAjax.js";
import { commonFunctions } from "../common/commonFunctions.js";
import { enumAction } from "../common/enumAction.js";

$(document).ready(function() {

    $("#show-password").click(function() {

        var passwordField = $("#password");
        var passwordType = passwordField.attr("type");
        
        if (passwordType === "password") {
            passwordField.attr("type", "text");
            $("#show-password i").removeClass("bi bi-eye-fill");
            $("#show-password i").addClass("bi bi-eye-slash-fill");
        } else {
            passwordField.attr("type", "password");
            $("#show-password i").removeClass("bi bi-eye-slash-fill");
            $("#show-password i").addClass("bi bi-eye-fill");
        }

    });

    $('#send').click(function (e) {
        e.preventDefault();
        
        const dataToSend = commonFunctions.getInputsValues($('#form1')[0], 1);
        console.log(dataToSend)
        
        const obj = new conectAjax(urlLogin);

        if (obj.setAction(enumAction.POST)) {
            obj.setData(dataToSend);
                
            obj.saveData()
                .then(function (response) {
                    console.log(response);

                    $.notify(response.message,'success');

                    window.location.href = response.data.redirect;

                })
                .catch(function (error) {

                    console.err(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`,'error');

                });
        }
        
        // $.ajax({
        //     url: `${window.location.origin}/api/auth`,
        //     method: 'POST',
        //     contentType: "application/json",
        //     data: JSON.stringify(dataToSend),
        //     success: function (response) {

        //         if (response.status===200) {
        //             location.reload();
        //         } else {
        //             console.error('Erro ao configurar a SESSION: ' + response.message);
        //             $.notify('Erro ao configurar a SESSION: ' + response.message, 'error');
        //         }
                
        //     },
        //     error: function (xhr) {
        //         console.error('Erro inesperado', xhr);
        //     }
        // });
    })

});