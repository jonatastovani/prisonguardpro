import { conectAjax } from "../ajax/conectAjax.js";
import { enumAction } from "../common/enumAction.js";
import { funcoesComuns } from "../common/funcoesComuns.js";

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
        
        const dataToSend = funcoesComuns.obterValoresDosInputs($('#form1')[0], 1);
  
        const obj = new conectAjax(urlLogin);

        if (obj.setAction(enumAction.POST)) {
            obj.setData(dataToSend);
                
            obj.envRequest()
                .then(function (response) {
                    console.log(response);

                    if (response.status === 200) {
                        $.notify('Acesso autorizado!','success');
                        window.location.href = response.data.redirect;
                    } else {
                        $.notify(response.message,'error');
                    }
                    
                })
                .catch(function (error) {

                    console.error(error);
                    $.notify(`Não foi possível enviar os dados.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${error.message}`,'error');

                });
        }
        
    })

});