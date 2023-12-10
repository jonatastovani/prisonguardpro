import { conectAjax } from "../ajax/conectAjax.js";
// import { commonFunctions } from "../common/commonFunctions.js";
import { enumAction } from "../common/enumAction.js";

$(document).ready(function() {

    $('#send').click(function (e) {
        // e.preventDefault();
        
        // const dataToSend = commonFunctions.getInputsValues($('#form1')[0], 1);
        // console.log(dataToSend)
        
        const obj = new conectAjax(`${urlApiVersion}/userPermissao/2`);

        if (obj.setAction(enumAction.GET)) {
                
            obj.getData()
                .then(function (response) {
                    console.log(response);
                    console.log(response[0].created_at)
                    $('#data').val(response[0].created_at)
                    // $.notify(response.message,'success');

                    // window.location.href = response.data.redirect;

                })
                .catch(function (error) {

                    console.err(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`,'error');

                });
        }
        
    })

    $('#imp').click(function () {
        
        $('#dataimpressa').val($("#data").val())
    })
});