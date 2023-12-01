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

    // $('#send').click(function () {

    //     const dataToSend = {
    //         userId: '123'
    //     };

    //     $.ajax({
    //         url: `${window.location.origin}/setSession`,
    //         method: 'POST',
    //         contentType: "application/json",
    //         data: JSON.stringify(dataToSend),
    //         success: function (response) {

    //             if (response.status===200) {
    //                 location.reload();
    //             } else {
    //                 console.error('Erro ao configurar a SESSION: ' + response.message);
    //                 $.notify('Erro ao configurar a SESSION: ' + response.message, 'error');
    //             }
                
    //         },
    //         error: function (xhr) {
    //             console.error('Erro inesperado', xhr);
    //         }
    //     });
    // })
});