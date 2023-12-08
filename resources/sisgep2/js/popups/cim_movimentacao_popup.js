//Abrir pop-up
let idpresomovimentacao = 0;
let idmovimentacao = 0;
let movimentacaotranstipo = 0;
let tiporecebimento = 0;

function acoesIniciaisMovimentacaoTrans(){
    limparCamposPopMovimentacaoTrans();
    atualizarListaTiposMovimentacao();
    atualizarListaUnidades();
    if(movimentacaotranstipo==2){
        tiporecebimento=7;
    }else if(movimentacaotranstipo==3){
        tiporecebimento=8;
    }
    buscaDadosMovimentacao();
}

$("#openmovimentacaotrans").on("click",function(){
    abrirMovimentacaoTrans()
});

function abrirMovimentacaoTrans(){
    $("#pop-movimentacaotrans").addClass("active");
    $("#pop-movimentacaotrans").find(".popup").addClass("active");
    acoesIniciaisMovimentacaoTrans()
}

//Fechar pop-up
$("#pop-movimentacaotrans").find(".close-btn").on("click",function(){
    $('#cancelarmovimentacaotrans').click();
});

function fecharMovimentacaoTrans(){
    $("#pop-movimentacaotrans").removeClass("active");
    $("#pop-movimentacaotrans").find(".popup").removeClass("active");
    $('#table-pop-movimentacaotrans').find('tbody').html('')
    idmovimentacao = 0;
    idpresomovimentacao = 0;
    tiporecebimento = 0;
    limparCamposPopMovimentacaoTrans();
}
$('#cancelarmovimentacaotrans').click(function(){
    fecharMovimentacaoTrans();
})

function buscaDadosPresoPopMovimentacaoTrans(){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 1, idpreso: idpresomovimentacao},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#nomepresoprevia').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matriculaprevia').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matriculaprevia').html('Não Atribuída');
            }
            if(result[0].SEGURO==1){
                $('#ckbpresoseguro').prop('checked',true);
            }else{
                $('#ckbpresoseguro').prop('checked',false);
            }
        }
    });
}

function buscaDadosMovimentacao(){

    $.ajax({
        url: 'ajax/consultas/cim_busca_dados_transferencias.php',
        method: 'POST',
        data: {idmovimentacao: idmovimentacao, tipo: tiporecebimento},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
            fecharMovimentacaoTrans();
        }else{
            idpresomovimentacao = result[0].IDPRESO;
            buscaDadosPresoPopMovimentacaoTrans();
            $('#unidadeprevia').html(result[0].UNIDADE);
            $('#datamov').val(result[0].DATARECEB);
            $('#selectorigem').val(result[0].IDPROCEDENCIA).trigger('change');
            $('#selecttipo').val(result[0].IDTIPOMOV).trigger('change');
            $('#selectmotivo').val(result[0].IDMOTIVOMOV).trigger('change');
            if(movimentacaotranstipo==2){
                bloquearCampos(false)
            }else{
                bloquearCampos(true);
                let containerPai = $('#movimentacaotrans');
                containerPai.find('.final-pagina').append('<button class="btn-excluir">Excluir</button>');
                eventoExcluirOrdemSaida(containerPai.find('.btn-excluir'));
            }
        }
    });
}

function bloquearCampos(bln){
    if(bln===true){
        $('#searchorigem').removeAttr('disabled');
        $('#selectorigem').removeAttr('disabled');
        $('#searchtipo').removeAttr('disabled');
        $('#selecttipo').removeAttr('disabled');
        $('#searchmotivo').removeAttr('disabled');
        $('#selectmotivo').removeAttr('disabled');
    }else{
        $('#searchorigem').attr('disabled','disabled');
        $('#selectorigem').attr('disabled','disabled');
        $('#searchtipo').attr('disabled','disabled');
        $('#selecttipo').attr('disabled','disabled');
        $('#searchmotivo').attr('disabled','disabled');
        $('#selectmotivo').attr('disabled','disabled');
    }
}

function eventoExcluirOrdemSaida(botao){
    botao.click(()=>{
        if(confirm('Confirma a exclusão deste agendamento de Recebimento?\r\r***ATENÇÃO***\rEsta ação não poderá ser desfeita!')==true){
            excluirRecebimento();
        }
    })
}

function excluirRecebimento(){
    $.ajax({
        url: 'ajax/inserir_alterar/cim_movimentacoes_transferencias.php',
        method: 'POST',
        data: {tipo:6, idmovimentacao: idmovimentacao},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            inserirMensagemTela(result.OK)
            limparCamposPopMovimentacaoTrans();
            atualizaListaGerenciarTransf();
            fecharMovimentacaoTrans();
        }
    })
}

//Busca todas os Destinos existentes
function atualizarListaUnidades(){
    var option = '<option value="0">Selecione</option>';
    $('#listaunidades').empty().html(option);

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo:4},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                var option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                $('#listaunidades').append(option);            
            });
        }
    });
    $('.unidades').html($('#listaunidades').html());    
}

function atualizarListaTiposMovimentacao(){
    var option = '<option value="0">Selecione</option>';
    $('#listatipos').html(option);

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo:6, selecionados: '10,12,16'},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                var option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                $('#listatipos').append(option);            
            });
        }
    });
    $('.tipos').html($('#listatipos').html());    
}

//Adiciona motivos na listagem
function atualizarListaMotivos(idtipo){

    var option = '<option value="0">Selecione</option>';
    $('#listamotivos').html(option);

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo:8, idtipo: idtipo},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                var option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                $('#listamotivos').append(option);            
            });
        }
    });
    $('#selectmotivo').html($('#listamotivos').html()).trigger('change');
    if(idtipo==0){
        $('#searchmotivo').val('');
    }    
}

function adicionaEventoSearch(search, select, tipo, elementofoco=''){

    search.change(function(){
        let id = search.val();
        let dados = [];

        if(tipo==5){
            dados = {
                tipo: tipo,
                iddestino: id
            }
        }else if(tipo==7){
            dados = {
                tipo: tipo,
                idtipo: id
            }
        }else if(tipo==9 || tipo==11){
            dados = {
                tipo: tipo,
                idmotivo: id
            }
        }

        if(id!=select.val()){
            if(id>0){
                $.ajax({
                    url: 'ajax/consultas/buscas_comuns.php',
                    method: 'POST',
                    //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
                    data: dados,
                    //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                    dataType: 'json',
                    async: false
                    }).done(function(result){
                    //console.log(result)

                    if(result.MENSAGEM){
                        inserirMensagemTela(result.MENSAGEM)
                        //Limpa os campos pois o valor digitado não existe
                        select.val(0);
                        search.val('');
                    }else{
                        select.val(result[0].VALOR).trigger('change');
                        if(select.val()==null){
                            select.val(0);
                            search.val('');
                        }else{
                            elementofoco.focus();
                        }                    
                    }
                });
            }else{
                select.val(0);
                search.val('');
            }
        }
    })
}

function adicionaEventoSelect(search, select, tipo, matric=''){

    select.change(function(){
        var id = select.val();
        if(id!=0){
            search.val(id);
        }else{
            search.val('');
        }
        if(tipo==7){
            atualizarListaMotivos(id)
        }
    })
}

function limparCamposPopMovimentacaoTrans(){
    $('#nomepresoprevia').html('');
    $('#matriculaprevia').html('');
    $('#unidadeprevia').html('');
    $('#datamov').val('');
    $('#tipotranferencia').html('Tipo').data('tipo',0);
    $('#selectorigem').val(0).trigger('change');
    $('#selecttipo').val(0).trigger('change');
    $('#selectmotivo').val(0).trigger('change');
    $('#ckbpresoseguro').prop('checked',false);
    $('#movimentacaotrans').find('.btn-excluir').remove();
}

$('#salvarmovimentacaotrans').click(function(){
    if(verificaSalvarPopMovimentacaoTrans()==true){
        salvarPopMovimentacaoTrans();
    }
})

function verificaSalvarPopMovimentacaoTrans(){
    var mensagem = '';

    if($('#datamov').val() == '' || $('#datamov').val().length>10){
        $('#datamov').focus()
        mensagem = "<li class = 'mensagem-aviso'>Data de Transferência inválida. </li>"
        inserirMensagemTela(mensagem)
    }

    if(movimentacaotranstipo==3){
        if($('#selectorigem').val()==0 || $('#selectorigem').val()==null || $('#selectorigem').val()==NaN){
            if(mensagem==''){
                $('#selectorigem').focus()
            }
            mensagem = "<li class = 'mensagem-aviso'>Origem não selecionada. </li>"
            inserirMensagemTela(mensagem)
        }

        if($('#selecttipo').val()==0 || $('#selecttipo').val()==null || $('#selecttipo').val()==NaN){
            if(mensagem==''){
                $('#selecttipo').focus()
            }
            mensagem = "<li class = 'mensagem-aviso'>Tipo de Movimentação não selecionada. </li>"
            inserirMensagemTela(mensagem)
        }

        if($('#selectmotivo').val()==0 || $('#selectmotivo').val()==null || $('#selectmotivo').val()==NaN){
            if(mensagem==''){
                $('#selectmotivo').focus()
            }
            mensagem = "<li class = 'mensagem-aviso'>Motivo da Movimentação não selecionada. </li>"
            inserirMensagemTela(mensagem)
        }

    }
    
    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopMovimentacaoTrans(){
    var datamov = $('#datamov').val();
    var origem = $('#selectorigem').val();
    var tipomov = $('#selecttipo').val();
    var motivomov = $('#selectmotivo').val();
    var ckbpresoseguro = $('#ckbpresoseguro').prop('checked');
    
    $.ajax({
        url: 'ajax/inserir_alterar/cim_movimentacoes_transferencias.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {
            tipo: movimentacaotranstipo,
            idpreso: idpresomovimentacao,
            datamov: datamov,
            origem: origem,
            tipomov: tipomov,
            motivomov: motivomov,
            seguro: ckbpresoseguro,
            idmovimentacao: idmovimentacao
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
            limparCamposPopMovimentacaoTrans();
            atualizaListaGerenciarTransf();
            fecharMovimentacaoTrans();
        }
    });
}

adicionaEventoSearch($('#searchorigem'),$('#selectorigem'),5,$('#searchtipo'));
adicionaEventoSelect($('#searchorigem'),$('#selectorigem'),5);
adicionaEventoSearch($('#searchtipo'),$('#selecttipo'),7,$('#searchmotivo'));
adicionaEventoSelect($('#searchtipo'),$('#selecttipo'),7);
adicionaEventoSearch($('#searchmotivo'),$('#selectmotivo'),9,$('#ckbpresoseguro'));
adicionaEventoSelect($('#searchmotivo'),$('#selectmotivo'),9);
