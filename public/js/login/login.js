import { commonFunctions } from "../common/commonFunctions.js";
// import { enumAction } from "../common/enumAction.js";

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
        
        // const obj = new conectAjax(urlApiClients);

        // if (obj.setAction(actionRegCli)) {
        //     obj.setData(data);
            
        //     let notifyMessage = 'Cliente adicionado com sucesso!';

        //     if (actionRegCli == enumAction.PUT) {
        //         obj.setParam(idClient);
        //         notifyMessage = 'Cliente alterado com sucesso!';
        //     }
    
        //     obj.saveData()
        //         .then(function (result) {

        //         let form = document.createElement('form');
        //         form.id = 'notifyShowPost';
        //         form.hidden = 'hidden';
        //         form.method = 'post';
        //         form.action = redirectPrevious;
        //         let input = document.createElement('input');
        //         input.type = 'hidden';
        //         input.name = 'notifyMessage';
        //         input.value = notifyMessage;
        //         form.appendChild(input);
        //         input = document.createElement('input');
        //         input.type = 'hidden';
        //         input.name = 'notifyType';
        //         input.value = 'success';
        //         form.appendChild(input);
        //         var submitButton = document.createElement('input');
        //         submitButton.type = 'submit';
        //         form.appendChild(submitButton);
        //         document.body.appendChild(form);
        //         submitButton.click();

        //             $.notify(`Dados enviados com sucesso!`,'success');

        //             // redirection();

        //         })
        //         .catch(function (error) {

        //             console.log(error);
        //             $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`,'error');

        //         });
        // }
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