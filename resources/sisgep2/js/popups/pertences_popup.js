//Abrir pop-up
var acaopertencespreso='';
var idpresopertences = 0;
var idpertencebuscar = 0;

function acoesIniciaisPertencesPreso(){
    buscaDadosPresoPopPertences();
    limparCamposPopPertencesPreso();
    atualizaSelectPopPertencesPreso();
    atualizaListagemComum('buscas_comuns',{tipo:37, idtipo:1},0,$('#selectgrauparentesco'));
}

$("#openpertencespreso").on("click",function(){
    abrirPertencesPreso()
});
function abrirPertencesPreso(){
    if(idpresopertences!=0){
        $("#pop-pertencespreso").addClass("active");
        $("#pop-pertencespreso").find(".popup").addClass("active");
        acoesIniciaisPertencesPreso()
        if(idpertencebuscar!=0){
            //setTimeout(() => {
                $('#selectpertences').val(idpertencebuscar).trigger('change');
            //}, 200);
        }
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhum ID Preso foi informado. </li>')
    }
}
//Fechar pop-up
$("#pop-pertencespreso").find(".close-btn").on("click",function(){
    $('#cancelarpertencespreso').click();
});

function fecharPertencesPreso(){
    $("#pop-pertencespreso").removeClass("active");
    $("#pop-pertencespreso").find(".popup").removeClass("active");
    $('#table-pop-pertencespreso').find('tbody').html('')
    $('#selectpertences').html('');
    idpertencebuscar = 0;
    idpresopertences = 0;
    limparCamposPopPertencesPreso();
    atualizaListaGerenciarPertences();
}
$('#cancelarpertencespreso').click(function(){
    fecharPertencesPreso();
})

function buscaDadosPresoPopPertences(){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 1, idpreso: idpresopertences},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#nomepresopertences').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matriculapresopertences').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matriculapresopertences').html('Não Atribuída');
            }
            $('#raiocelapresopertences').html(result[0].RAIOCELA);
        }
    });
}

function atualizaSelectPopPertencesPreso(){
    $.ajax({
        url: 'ajax/consultas/popup_busca_pertences.php',
        method: 'POST',
        data: {idpreso: idpresopertences, tipo: 1},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        var select = $('#selectpertences');
        select.html('');
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
            fecharPertencesPreso();
        }else{
            result.forEach(linha => {
                select.append('<option value='+linha.VALOR+'>'+linha.NOMEEXIBIR+'</option>');
            });
            select.trigger('change');
        }
    });
}

$('#selectpertences').change(()=>{
    var idpertence = $('#selectpertences').val();

    if(idpertence>0){
        //Busca informações do pertence
        $.ajax({
            url: 'ajax/consultas/popup_busca_pertences.php',
            method: 'POST',
            data: {idpertence: idpertence, tipo: 2},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)
    
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
                limparCamposPopPertencesPreso();
                fecharPertencesPreso();
            }else{
                $('#numeropertence').html('Pertence nº: '+result[0].ID);
                $('#dataentrada').val(result[0].DATAENTRADA);
                if(result[0].IDTIPO==1){
                    $('#dataentrada').attr('disabled','disabled');
                }else{
                    $('#dataentrada').removeAttr('disabled');
                }
                $('#tipopertence').html(result[0].TIPO).data('tipo', result[0].IDTIPO);
                $('#nomeretiradapertence').val(result[0].NOMERETIRADA);
                $('#dataretirada').val(result[0].DATARETIRADA);
                if(result[0].DATARETIRADA!=null){
                    $('#dataretirada').val(result[0].DATARETIRADA);
                }else{
                    $('#dataretirada').val(retornaDadosDataHora(new Date(),1));
                }
                if(result[0].IDGRAUPARENTESCO!=null){
                    $('#selectgrauparentesco').val(result[0].IDGRAUPARENTESCO);
                }else{
                    $('#selectgrauparentesco').val(0);
                }
                $('#obspertencespreso').val(result[0].OBSERVACOES);
                $('#ckbdescartado').prop('checked',result[0].DESCARTADO).trigger('change');
                if(result[0].DATADESCARTADO!=null){
                    $('#datadescartado').val(result[0].DATADESCARTADO);
                }else{
                    $('#datadescartado').val(retornaDadosDataHora(new Date(),1));
                }
            }
        });    
    }else{
        fecharPertencesPreso();
    }
})

$('#selectgrauparentesco').change(()=>{
    var idgrau = parseInt($('#selectgrauparentesco').val());
    if(idgrau==4 || idgrau==11 || idgrau==12){
        $('#nomeretiradapertence').val($('#nomepresopertences').html());
    }
})

function limparCamposPopPertencesPreso(){
    $('#dataentrada').val(retornaDadosDataHora(new Date(),1));
    $('#nomepresopertences').val('');
    $('#dataretirada').val(retornaDadosDataHora(new Date(),1));
    $('#selectgrauparentesco').val(0);
    $('#obspertencespreso').val('');
    $('#tipopertence').html('Tipo').data('tipo',0);
    $('#ckbdescartado').prop('checked','checked').trigger('click');
    $('#datadescartado').val(retornaDadosDataHora(new Date(),1));
}

$('#deletepertence').click(function(){
    var idpertence = $('#selectpertences').val();
    var nomepertence = $('#selectpertences').find('option:selected').text();

    if(idpertence!=0){
        var confirmacao = confirm('Confirma a exclusão do Pertence '+nomepertence+'? Obs: Esta ação não poderá ser desfeita.')

        if(confirmacao===true){
            $.ajax({
                url: 'ajax/inserir_alterar/inc_pertences.php',
                method: 'POST',
                data: {tipo: 3, tipopertence: tipopertencesedex, idpertence: idpertence},
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json',
                async: false
            }).done(function(result){
                //console.log(result)
        
                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM);
                }else{
                    inserirMensagemTela(result.OK);
                    atualizaSelectPopPertencesPreso();
                }
            });    
        }
    }
})

$('#opennovopertence').click(function(){
    idpresonovopertence = idpresopertences;
    //tipopertencesedex = $('#tipopertence').data('tipo');
    if(tipopertencesedex==1){
        tipopertencesedex = 2;
    }
    abrirNovoPertence();
})

$('#ckbdescartado').on('change',()=>{
    let blndescartado = $('#ckbdescartado').prop('checked');
    if(blndescartado==true){
        $('#dadosretiradapertence').attr('hidden','hidden');
        $('#datadescarte').removeAttr('hidden');
    }else{
        $('#dadosretiradapertence').removeAttr('hidden');
        $('#datadescarte').attr('hidden','hidden');
        $('#datadescartado').focus();
    }
})

$('#salvarpertencespreso').click(function(){
    if(verificaSalvarPopPertencesPreso()==true){
        salvarPopPertencesPreso();

        //Atualiza a lista de pertences do gerenciador de pertences
        atualizaListaGerenciarPertences();
    }
})

function verificaSalvarPopPertencesPreso(){
    var mensagem = '';

    var dataentrada = $('#dataentrada').val();
    if($('#ckbdescartado').prop('checked')==true){
        var datadescartado = $('#datadescartado').val();
        conteudoVerificar = datadescartado.trim();
        if(conteudoVerificar == '' || conteudoVerificar == null){
            mensagem = "<li class = 'mensagem-aviso'> Data de descarte ou doação inválida. Por favor, confira este campo. </li>"
            inserirMensagemTela(mensagem)
            $('#datadescartado').focus();
        }else{
            var diferenca = retornaDiferencaDeDataEHora(dataentrada, datadescartado, 1);
            if(diferenca<0){
                mensagem = "<li class = 'mensagem-aviso'> Data de descarte ou doação inferior a data de entrada. Por favor, confira este campo. </li>"
                inserirMensagemTela(mensagem)
                $('#datadescartado').focus();
            }
        }
    }else{
        var conteudoVerificar = dataentrada.trim();
        if(conteudoVerificar == '' || conteudoVerificar == null){
            mensagem = "<li class = 'mensagem-aviso'> Data de entrada inválida. Por favor, confira este campo. </li>"
            inserirMensagemTela(mensagem)
            $('#novodataentrada').focus();
        }else{
            conteudoVerificar = $('#nomeretiradapertence').val().trim();
            if(conteudoVerificar != '' && conteudoVerificar != null){
                var dataretirada = $('#dataretirada').val();
                conteudoVerificar = dataretirada.trim();
                if(conteudoVerificar == '' || conteudoVerificar == null){
                    mensagem = "<li class = 'mensagem-aviso'> Data de retirada inválida. Por favor, confira este campo. </li>"
                    inserirMensagemTela(mensagem)
                    $('#dataretirada').focus();
                }else{
                    var diferenca = retornaDiferencaDeDataEHora(dataentrada, dataretirada, 1);

                    if(diferenca<0){
                        mensagem = "<li class = 'mensagem-aviso'> Data de descarte ou doação inferior a data de entrada. Por favor, confira este campo. </li>"
                        inserirMensagemTela(mensagem)
                        if(mensagem==''){$('#dataretirada').focus();};
                    }

                    if($('#selectgrauparentesco').val()==0){
                        mensagem = "<li class = 'mensagem-aviso'> O Grau de Parentesco deve ser preenchido! </li>"
                        inserirMensagemTela(mensagem)
                        if(mensagem==''){$('#selectgrauparentesco').focus();};
                    }
                }
            }
        }
    
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopPertencesPreso(){
    var idpertence = $('#selectpertences').val();
    var ckbdescartado = $('#ckbdescartado').prop('checked');
    var dataentrada = $('#dataentrada').val();
    var tipopertencepreso = $('#tipopertence').data('tipo');
    var nomeretiradapertence = $('#nomeretiradapertence').val();
    var dataretirada = $('#dataretirada').val();
    var selectgrauparentesco = $('#selectgrauparentesco').val();
    var observacoes = $('#obspertencespreso').val();
    var datadescartado = $('#datadescartado').val();
    
    $.ajax({
        url: 'ajax/inserir_alterar/inc_pertences.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {
            descartado: ckbdescartado,
            idpreso: idpresopertences,
            entrada: dataentrada,
            idpertence: idpertence,
            tipopertence: tipopertencepreso,
            nomeretirada: nomeretiradapertence,
            retirada: dataretirada,
            grau: selectgrauparentesco,
            observacoes: observacoes,
            datadescartado: datadescartado,
            tipo: 2
        },
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            inserirMensagemTela(result.OK)
            limparCamposPopPertencesPreso();
            //setTimeout(() => {
                $('#selectpertences').trigger('change');
            //}, 200);
        }
    });
}