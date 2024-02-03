import { commonFunctions } from "../commons/commonFunctions.js";

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

        const btn = this;
        
        commonFunctions.simulateLoading(btn);
        e.preventDefault();

        let data = commonFunctions.getInputsValues($('#form_login')[0], 1, false, false);

        $.ajax({
            url: `${window.location.origin}/setSession`,
            method: 'POST',
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (response,status,xhr) {

                if (response.status===200) {

                    $.notify(response.message, 'success');
                    commonFunctions.setItemLocalStorage('token_stylus', response.data.token);
                    window.location.reload();

                } else {

                    console.error(response);
                    console.error(xhr);
                    let description = "erro não definido.";
                    if(response.data) {
                        description = response.data.error.description;
                    }
                    $.notify(`${response.message}: ${description}`, 'error');
                }
                
            },
            error: function (xhr) {
                console.error('Erro inesperado', xhr);

                if (xhr.responseText) {
                    const json = JSON.parse(xhr.responseText); 
                    console.error(json)
                    $.notify(`${json.message}: ${json.data.error.description}`, 'error');
                }
            },
            complete: function () {
                commonFunctions.simulateLoading(btn, false);
            }
        });
    })
});