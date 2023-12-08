// Confirmação do retorno para salvamento das informações
let confirmacaopopnovamud = 0;
let idmovpopnovamud = 0;
let idpresopopnovamud = 0;

$("#opennovamudanca").on("click",function(){
    //Fechar a visibilidade da população do raio antes de abrir o popup
    if($('.visibilidadepop').length>0){
        fecharPopulacaoRaio();
    }
    abrirPopNovaMudanca();
});

//idpreso = preencher caso queira que se abra o novo atendimento preenchendo a busca do preso. Só funcionará se não estiver alterando a solicitação de atendimento;
function abrirPopNovaMudanca(idpreso=0){
    atualizaListagemComum('busca_presos',{tipo: 2, tipobusca: 2, valor: 1, tiporetorno:2, idvisualizacao:idvisu, blnvisuchefia:true},$('#listapresospopnovamud'),$('#selectpresopopnovamud'));
    atualizaListagemComum('chefia_busca_gerenciar',{tipo: 2,idvisualizacao: 0, blnvisuchefia:true},0,$('#selectraiopopnovamud'),false,true,'change',false);
    atualizaListagemComum('buscas_comuns',{tipo:28,idtipo:2,naoselecionados:8},0,$('#situacaopopnovamud'),false,true,'change',false,false);
    $("#pop-novamudanca").addClass("active");
    $("#pop-novamudanca").find(".popup").addClass("active");

    if(idmovpopnovamud>0){
        buscaDadosMudanca();
    }else{
        if(idpreso>0){
            $('#searchpresopopnovamud').val(idpreso).trigger('change');
        }else{
            $('#searchpresopopnovamud').focus();
        }
    }
}
//Fechar pop-up Artigo
$("#pop-novamudanca").find(".close-btn").on("click",function(){
    fecharPopNovaMudanca();
})
function fecharPopNovaMudanca(){
    $("#pop-novamudanca").removeClass("active");
    $("#pop-novamudanca").find(".popup").removeClass("active");
    $('#table-pop-novamudanca').find('tbody').html('')
    limparCamposPopNovaMudanca();
}

function limparCamposPopNovaMudanca(){
    $('#selectpresopopnovamud').val(0).trigger('change');
    $('#searchpresopopnovamud').focus();
    $('#camposselectpresopopnovamud').removeAttr('hidden');
    $('#obspopnovamud').val('');
    confirmacaopopnovamud = 0;
    idmovpopnovamud=0;
    idpresopopnovamud = 0;
}

adicionaEventoSelectChange(0,$('#selectpresopopnovamud'),$('#searchpresopopnovamud'))

$('#selectpresopopnovamud').change(function(){
    var idpreso = $('#selectpresopopnovamud').val();
    
    if(idpreso!=0 && idpreso!=null){
        buscaDadosPresoPopNovaMudanca(idpreso);
    }else{
        $('#dadospresopopnovamud').attr('hidden','hidden');
    }
})

$('#searchpresopopnovamud').change(function(){
    var id = $('#searchpresopopnovamud').val();
    
    if(id!=$('#selectpresopopnovamud').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresopopnovamud'),$('#selectpresopopnovamud'),$('#selectraiopopnovamud'));
    }
})

function buscaDadosPresoPopNovaMudanca(idpreso){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 1, idpreso: idpreso},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            idpresopopnovamud = result[0].IDPRESO;

            $('#nomepopnovamud').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matriculapopnovamud').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matriculapopnovamud').html('Não Atribuída');
            }
            $('#raiocelapopnovamud').html(result[0].RAIOCELA);
            $('#dadospresopopnovamud').removeAttr('hidden');
        }
    });
}

adicionaEventoSelectChange(0,$('#selectraiopopnovamud'))

$('#selectraiopopnovamud').change(function(){
    var idraio = $('#selectraiopopnovamud').val();
    
    if(idraio!=null){
        preencheCelas(idraio,$('#selectcelapopnovamud'),true)
    }
})

function buscaDadosMudanca(blnmanterdestino=false){
    $.ajax({
        url: 'ajax/consultas/chefia_busca_gerenciar.php',
        method: 'POST',
        data: {tipo: 3, idmovimentacao: idmovpopnovamud},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#camposselectpresopopnovamud').attr('hidden','hidden');
            buscaDadosPresoPopNovaMudanca(result[0].IDPRESO)
            if(blnmanterdestino==false){
                let raio = result[0].RAIODESTINO;
                if(raio==null){
                    raio=0;
                }
                $('#selectraiopopnovamud').val(raio).trigger('change');
                let cela = result[0].CELADESTINO;
                if(cela==null && raio==0){
                    cela=0;
                }
                $('#selectcelapopnovamud').val(cela);
                $('#situacaopopnovamud').val(result[0].IDSITUACAO);
            }

            $('#obspopnovamud').val(result[0].OBSERVACOES).focus();
        }
    });
}

$('#salvarpopnovamud').click(function(){
    if(verificaSalvarPopNovaMudanca()==true){
        salvarPopNovaMudanca();
    }
})

function verificaSalvarPopNovaMudanca(){
    let mensagem = '';

    let elementoVerificar = $('#selectpresopopnovamud')
    if((elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN) && idmovpopnovamud==0){
        elementoVerificar.focus()
        mensagem = ("<li class = 'mensagem-aviso'> Selecione um Preso! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopNovaMudanca(){
    
    let idraio = $("#selectraiopopnovamud").val();
    let cela = $("#selectcelapopnovamud").val();
    let situacao = $("#situacaopopnovamud").val();
    let observacoes = $("#obspopnovamud").val();

    let dados = {
        tipo: 1,
        idmovimentacao: idmovpopnovamud,
        confirmacao: confirmacaopopnovamud,
        idpreso: idpresopopnovamud,
        idraio: idraio,
        cela: cela,
        idsituacao: situacao,
        observacoes: observacoes,
        blnvisuchefia: 1
    }

    // console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/chefia_gerenciar.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            if(result.MSGCONFIR){
                if(confirm(result.MSGCONFIR)==true){
                    confirmacaopopnovamud = result.CONFIR;
                    idmovpopnovamud = result.IDMOV;
                };
                if(confirmacaopopnovamud>1){
                    buscaDadosMudanca(true);
                }else{
                    confirmacaopopnovamud = 0;
                    idmovpopnovamud = 0;
                }
            }else{
                inserirMensagemTela(result.OK);
                if($('#camposselectpresopopnovamud').attr('hidden')=='hidden'){
                    fecharPopNovaMudanca();
                }
                limparCamposPopNovaMudanca();
            }
        }
    });
}

