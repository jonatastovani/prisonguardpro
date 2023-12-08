// Confirmação do retorno para salvamento das informações
let confirmacaopopreceb = 0;
let idmovpopreceb = 0;

$("#openrecebimentotrans").on("click",function(){
    abrirPopRecebimentoTrans();
});

function abrirPopRecebimentoTrans(){
    $("#pop-recebimentotrans").addClass("active");
    $("#pop-recebimentotrans").find(".popup").addClass("active");
    $("#pop-recebimentotrans").find("#valornovoartigo").focus();
    atualizaListagemPresosPopReceb();
    atualizaListagemComum('buscas_comuns',{tipo: 6, selecionados: '9,10,12,16'},$('#listatipospopreceb'),$('#selecttipopopreceb'));
    atualizaListagemComum('buscas_comuns',{tipo: 4},$('#listaunidadespopreceb'),$('#selectorigempopreceb'),$('#searchorigempopreceb'));
    $('#searchpresopopreceb').focus();
}
//Fechar pop-up Artigo
$("#pop-recebimentotrans").find(".close-btn").on("click",function(){
    fecharPopRecebimentoTrans();
})
function fecharPopRecebimentoTrans(){
    $("#pop-recebimentotrans").removeClass("active");
    $("#pop-recebimentotrans").find(".popup").removeClass("active");
    $('#table-pop-recebimentotrans').find('tbody').html('')
    limparCamposPopRecebimentoTrans();
    //Atualiza a listagem de todos os campos de artigos que houver na página.
    atualizaListaGerenciarTransf();
}

//Não usar a função atualizaListagemComum pois o retorno desta consulta é diferente
function atualizaListagemPresosPopReceb(){
    let valores = [];
    //Reserva os valores selecionados
    let valor = $('#selectpresopopreceb').val();
    if(valor==null){
        valor=0;
    }

    let option = "<option value=0>Selecione</option>";
    let retorno = option;

    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 7, tiporetorno:1},
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            retorno = result;
        }
    });
    $('#selectpresopopreceb').html(retorno)
    $('#selectpresopopreceb').val(valor).trigger('change');
    $('#listapresospopreceb').html(retorno);
};

function limparCamposPopRecebimentoTrans(){
    $('.tempsearchpopreceb').val('');
    $('.tempselectpopreceb').val(0);
    $('#datamovpopreceb').val('');
    $('#ckbseguropopreceb').prop('checked',false);
    confirmacaopopreceb = 0;
}

$('#cancelarpoprecebimentotrans').click(function(){
    limparCamposPopRecebimentoTrans();
})

adicionaEventoSelectChange(0,$('#selectpresopopreceb'),$('#searchpresopopreceb'))

$('#selectpresopopreceb').change(function(){
    var idpreso = $('#selectpresopopreceb').val();
    
    if(idpreso!=0 && idpreso!=null){
        console.log(idpreso)
        buscaDadosPresoPopRecebimentoTrans(idpreso);
    }
})

function buscaDadosPresoPopRecebimentoTrans(idpreso){
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
            if(result[0].SEGURO==1){
                $('#ckbseguropopreceb').prop('checked',true);
            }else{
                $('#ckbseguropopreceb').prop('checked',false);
            }
        }
    });
}

$('#searchpresopopreceb').change(function(){
    var id = $('#searchpresopopreceb').val();
    
    if(id!=$('#selectpresopopreceb').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresopopreceb'),$('#selectpresopopreceb'),$('#searchtipopopreceb'));
    }
})

$('#selecttipopopreceb').change(function (){
    var id = $('#selecttipopopreceb').val();
    
    atualizaListagemComum('buscas_comuns',{tipo: 8, idtipo: id},$('#listamotivospopreceb'),$('#selectmotivopopreceb'),true,true);

    if(id>0){
        $('#searchtipopopreceb').val(id);
    }else{
        $('#searchtipopopreceb').val('');
    }
});

$('#searchtipopopreceb').change(function(){
    var id = $('#searchtipopopreceb').val();
    
    if(id!=$('#selecttipopopreceb').val()){
        buscaSearchComum('buscas_comuns',{tipo:7, idtipo:id},$('#searchtipopopreceb'),$('#selecttipopopreceb'),$('#searchmotivopopreceb'));
    }
})

adicionaEventoSelectChange(0,$('#selectmotivopopreceb'),$('#searchmotivopopreceb'))
$('#searchmotivopopreceb').change(function(){
    var id = $('#searchmotivopopreceb').val();
    
    if(id!=$('#selectmotivopopreceb').val()){
        buscaSearchComum('buscas_comuns',{tipo:9, idmotivo:id},$('#searchmotivopopreceb'),$('#selectmotivopopreceb'),$('#searchorigempopreceb'));
    }
})

adicionaEventoSelectChange(0,$('#selectorigempopreceb'),$('#searchorigempopreceb'))
$('#searchorigempopreceb').change(function(){
    var id = $('#searchorigempopreceb').val();
    
    if(id!=$('#selectorigempopreceb').val()){
        buscaSearchComum('buscas_comuns',{tipo:5, iddestino:id},$('#searchorigempopreceb'),$('#selectorigempopreceb'),$('#datamovpopreceb'));
    }
})

$('#salvarpopreceb').click(function(){
    if(verificaSalvarPopRecebimentoTrans()==true){
        salvarPopRecebimentoTrans();
    }
})

function verificaSalvarPopRecebimentoTrans(){
    let mensagem = '';

    let elementoVerificar = $('#selectpresopopreceb')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        elementoVerificar.focus()
        mensagem = ("<li class = 'mensagem-aviso'> Selecione um Preso! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#selecttipopopreceb')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione um Tipo de Movimentação! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#selectmotivopopreceb')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione um Motivo da Movimentação! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#selectorigempopreceb')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione a Unidade Origem do Preso! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#datamovpopreceb')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O Campo Data da Transferência deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopRecebimentoTrans(){
    
    let idpreso = $("#selectpresopopreceb").val();
    let tipomov = $("#selecttipopopreceb").val();
    let motivomov = $("#selectmotivopopreceb").val();
    let origem = $("#selectorigempopreceb").val();
    let datamov = $("#datamovpopreceb").val();
    let ckbseguropopreceb = $("#ckbseguropopreceb").prop('checked');

    let dados = {
        tipo: 4,
        confirmacao: confirmacaopopreceb,
        idmovimentacao: idmovpopreceb,
        idpreso: idpreso,
        tipomov: tipomov,
        motivomov: motivomov,
        origem: origem,
        datamov: datamov,
        seguro: ckbseguropopreceb
    }

    //console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/cim_movimentacoes_transferencias.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            if(result.CONFIR){
                if(confirm(result.MSGCONFIR)==true){
                    confirmacaopopreceb = result.CONFIR;
                    idmovpopreceb = result.IDMOV;
                };
                if(confirmacaopopreceb>0){
                    salvarPopRecebimentoTrans();
                }
            }else{
                inserirMensagemTela(result.OK);
                $('.tempselectpopreceb').val(0).trigger('change');
                $('#ckbseguropopreceb').prop('checked',false);
                $('#searchpresopopreceb').focus();
                atualizaListagemPresosPopReceb();
            }
        }
    });
    confirmacaopopreceb = 0;
    idmovpopreceb = 0;
}

