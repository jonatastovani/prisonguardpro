import { modalCadastroDocumento } from "../modals/referencias/modalCadastroDocumento.js";

$(document).ready(function () {


    // preSetMask.apply('#rg', 1);
    $('#rg').mask('00.000.00A-X', {
        reverse: false,
        translation: {
            'X': {
                pattern: /[0-9xX]/,
            }
        },
        onKeyPress: function(value, e, field, options) {
            console.log(value)
            console.log(e)
            console.log(field)
            console.log(options)
            const key = e.key;
            console.log(e.key)
            // const lastChar = value[value.length - 1];
            // if (key !== 'X' && lastChar !== 'X') {
            //     e.preventDefault();
            // }
        }
    });

    // $('#rg').mask('#.##0', {
    //     reverse: true,
    //     translation: {
    //         '0': {
    //             pattern: /[0-9]/
    //         }
    //     },
    //     onKeyPress: function(value, event) {
    //         console.log('event.key',event.key)
    //         console.log('event.key',event.key)
    //       if (event.key === '-' && value.indexOf('-') === -1) {
    //         $(this).mask('#.##0-0');
    //       }
    //     //   else if (value.indexOf('-') !== -1 && event.key !== '-') {
    //     //     $(this).mask('0.000.000-00');
    //     //   }
    //     }
    // });

    $(`#btnModal`).on('click', function () {
        const obj = new modalCadastroDocumento();
        obj.setFocusElementWhenClosingModal = this;
        obj.modalOpen().then(function (result) {
            if (result && result.refresh) {
                // inserirArtigos(result.arrData);
            }
        });
    })
    .click();


});