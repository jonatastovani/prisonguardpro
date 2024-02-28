import { modalCadastroDocumento } from "../modals/referencias/modalCadastroDocumento.js";

$(document).ready(function () {

    // preSetMask.apply('#rg', 1);
    $('#rg').mask('#.##0-A', {
        reverse: true,
        translation: {
            'A': {
                pattern: /[0-9Xx]/,
            }
        }
    });

    $(`#btnModal`).on('click', function () {
        const obj = new modalCadastroDocumento();
        obj.setFocusElementWhenClosingModal = this;
        obj.modalOpen().then(function (result) {
            if (result && result.refresh) {
                // inserirArtigos(result.arrData);
            }
        });
    }).click();


});