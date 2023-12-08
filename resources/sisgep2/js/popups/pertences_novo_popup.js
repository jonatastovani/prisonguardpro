var idpresonovopertence = 0;

function acoesIniciaisNovoPertence(){
    limparCamposPopNovoPertence();
    buscaDadosPresoPopNovoPertences();
}

function abrirNovoPertence(){
    if(idpresonovopertence==0){
        buscaPresosPopNovoPertences();
        $('#divselPresoNovoPert').removeAttr('hidden')
    }
    else{
        $('#divselPresoNovoPert').attr('hidden','hidden')
        acoesIniciaisNovoPertence()
    }
    buscaTipoNovoPertences();
    $("#pop-novopertence").addClass("active");
    $("#pop-novopertence").find(".popup").addClass("active");
}
//Fechar pop-up
$("#pop-novopertence").find(".close-btn").on("click",function(){
    $('#cancelarnovopertence').click();
});

$('#cancelarnovopertence').click(function(){
    $("#pop-novopertence").removeClass("active");
    $("#pop-novopertence").find(".popup").removeClass("active");
    idpresonovopertence = 0;
    limparCamposPopNovoPertence();
    $('#searchpresosnovopertence').val('');
    $('#listapresosnovopertence').html('');
    $('#selectpresosnovopertence').html('');
})

function buscaPresosPopNovoPertences(){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 2, tipobusca: 1, valor: 1},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#listapresosnovopertence').html(result);
            $('#selectpresosnovopertence').html(result);
        }
    });
}

$('#searchpresosnovopertence').change(function(){
    var idpreso = $('#searchpresosnovopertence').val();
    
    if(idpreso!=0){
        $.ajax({
            url: 'ajax/consultas/busca_presos.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            data: {tipo:3, valor:1, idpreso: idpreso},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
                //Limpa os campos pois o valor digitado não existe
                $('#selectpresosnovopertence').val(0);
                $('#searchpresosnovopertence').val('');
            }else{
                $('#selectpresosnovopertence').val(idpreso);
                $('#novodataentrada').focus();
            }
        });
    }else{
        $('#searchpresosnovopertence').val('');
        $('#selectpresosnovopertence').val(0);
    }
})

//Executa função na saída do foco do campo select da MATRICULA
$('#selectpresosnovopertence').change(function(){
    var id = $('#selectpresosnovopertence').val();
    if(id!=0){
        $('#searchpresosnovopertence').val(id);
        idpresonovopertence = id;
        buscaDadosPresoPopNovoPertences();
    }else{
        $('#searchpresosnovopertence').val('');
        limparCamposPopNovoPertence();
    }
})

function buscaDadosPresoPopNovoPertences(){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 1, idpreso: idpresonovopertence},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#nomepresonovopertence').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matriculapresonovopertence').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matriculapresonovopertence').html('Não Atribuída');
            }
            $('#raiocelapresonovopertence').html(result[0].RAIOCELA);
        }
    });
}

function buscaTipoNovoPertences(){
    $.ajax({
        url: 'ajax/consultas/popup_busca_pertences.php',
        method: 'POST',
        data: {tipo: 4, tipopertence: tipopertencesedex},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#tiponovopertence').html(result[0].NOMEEXIBIR);
        }
    });
}

function limparCamposPopNovoPertence(){
    $('#novodataentrada').val(retornaDadosDataHora(new Date(),1));
    $('#obsnovopertence').val('');
    $('#nomepresonovopertence').html('Selecione o Preso');
    $('#matriculapresonovopertence').html('Selecione o Preso');
    $('#raiocelapresonovopertence').html('Selecione o Preso');
}

$('#salvarnovopertence').click(function(){
    if(verificaSalvarPopNovoPertence()==true){
        salvarPopNovoPertence();
        //Atualiza a lista de pertences do gerenciador de pertences
        atualizaListaGerenciarPertences();
    }
})

function verificaSalvarPopNovoPertence(){
    var mensagem = '';

    if(idpresonovopertence<1){
        mensagem = "<li class = 'mensagem-aviso'> Selecione um preso para continuar. </li>"
        inserirMensagemTela(mensagem)
        $('#selectpresosnovopertence').focus();
    }
    var conteudoVerificar = $('#novodataentrada').val().trim();
    if(conteudoVerificar == '' || conteudoVerificar == null){
        mensagem = "<li class = 'mensagem-aviso'> Data de entrada inválida. Por favor, confira este campo. </li>"
        inserirMensagemTela(mensagem)
        $('#novodataentrada').focus();
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopNovoPertence(){
    var novodataentrada = $('#novodataentrada').val();
    //var tipopertence = $('#selecttipopertence').val();
    var observacoes = $('#obsnovopertence').val();
    
    $.ajax({
        url: 'ajax/inserir_alterar/inc_pertences.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {
            idpreso: idpresonovopertence,
            entrada: novodataentrada,
            tipopertence: tipopertencesedex,
            observacoes: observacoes,
            tipo: 1
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
            if($("#pop-pertencespreso").find('.active').length){
                atualizaSelectPopPertencesPreso();
            }
            $('#cancelarnovopertence').click();
        }
    });
}
