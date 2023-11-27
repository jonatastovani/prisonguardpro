import { conectAjax } from "../ajax/conectAjax.js";
import instanceManager from "../common/instanceManager.js";
import { popItems } from "../popup/products/popupItems.js";

$(document).ready(function(){

    function init () {

        const formPopDataHidden = '#formPopDataHidden';

        const obj = instanceManager.setInstance('productsHome', new productsHome());
        obj.getItemsTotal();

        const popName = $(formPopDataHidden).find('input[name="popName"]').val();
        const popId = $(formPopDataHidden).find('input[name="popId"]').val();
        
        switch (popName) {

            case 'items':
                if (popId!='') {

                    let obj = instanceManager.setInstance('popItems', new popItems(urlApiProdItems));
                    obj.setId(popId);

                }

                openPopupItems.click();
            break;

        }


    }

    const openPopupItems = $('#openPopupItems')
    openPopupItems.on("click", (event) => {

        event.preventDefault();

        let obj = instanceManager.setInstance('popItems', new popItems(urlApiProdItems));
        obj.setElemFocusClose(openPopupItems);
        obj.openPop();

    });
  
    init ();

});

export class productsHome {

    getItemsTotal () {

        const obj = new conectAjax (urlApiProdItems);

        obj.getData()
            .then(function (response) {

                $('#itemsTotal').html(response.data.length);
                
            })
            .catch(function (error) {

                console.log(error);
                $('#itemsTotal').html("Erro Consulta");
                
            });
    }

}
