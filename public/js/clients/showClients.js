import { conectAjax } from "../ajax/conectAjax.js";
import { commonFunctions } from "../commons/commonFunctions.js";
import { enumAction } from "../commons/enumAction.js";
import instanceManager from "../commons/instanceManager.js";
import { modalMessage } from "../commons/modalMessage.js";

$(document).ready(function(){

    const tableClients = $('#table-clients').find('tbody');

    function init () {

        getDataClientsAll();

    }

    function getDataClientsAll() {

        const obj = new conectAjax(urlApiClients);

        obj.getData()
            .then(function (response) {

                let strHTML = '';
                response.data.forEach(client => {
                    const tel = commonFunctions.formatPhone(client.tel);
                    const cpf = commonFunctions.formatCPF(client.cpf);
                    const cnpj = commonFunctions.formatCNPJ(client.cnpj);
                    const city = client.city!=null?client.city:'';

                    strHTML += '<tr><td>'+client.name+'</td>';
                    strHTML += '<td class="text-center">'+tel+'</td>';
                    strHTML += '<td class="text-center">'+cpf+'</td>';
                    strHTML += '<td class="text-center">'+cnpj+'</td>';
                    strHTML += '<td>'+city+'</td>';
                    strHTML += '<td  class="text-right"><form action="managClients/'+client.id+'" method="post"><input class="btn btn-primary" type="submit" value="Editar" title="Editar este cliente"><input type="hidden" name="id" value="' + client.id + '" title="Editar este cliente"></form></td>';
                    strHTML += '<td><button class="btn btn-danger delete" type=button data-id="'+client.id+'" title="Deletar este cliente">Deletar</button></td></tr>';
                });

                tableClients.html(strHTML);

            })
            .catch(function (error) {

                tableClients.html('<td colspan=7>'+error+'</td>');
                
            });

    }

    $(document).on('click', '.delete', function(){
        
        var id = $(this).data('id');
                
        const obj = instanceManager.setInstance('modalMessage', new modalMessage());
        obj.setMessage(`Confirma a exclusão do cadastro deste cliente?`);
        obj.setTitle('Confirmação de exclusão de Cliente');
        obj.setElemFocusClose(this);

        obj.openModal().then(function (result) {

            if (result) {
                delClient(id);
            }

        });
            
    });		

    function delClient(idClient) {

        const obj = new conectAjax(urlApiClients);
    
        if (obj.setAction(enumAction.DELETE)) {
    
            obj.setParam(idClient);
    
            obj.deleteData()
                .then(function (result) {

                    $.notify(`Cliente deletado com sucesso!`,'success');
                    getDataClientsAll();

                })
                .catch(function (error) {

                    console.log(error);
                    $.notify(`Não foi possível enviar os dados. Se o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}`,'error');
    
                });
        }

    }

    init();

});