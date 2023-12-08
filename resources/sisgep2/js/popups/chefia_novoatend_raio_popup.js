// Confirmação do retorno para salvamento das informações
let confirmacaopopnovoatend = 0;
let idmovpopnovoatend = 0;
const tabelahistatend = $('#table-hist-atend').find('tbody');

$("#opennovoatend").on("click",function(){
    //Fechar a visibilidade da população do raio antes de abrir o popup
    if($('.visibilidadepop').length>0){
        fecharPopulacaoRaio();
    }
    abrirPopNovoAtend();
});

//idpreso = preencher caso queira que se abra o novo atendimento preenchendo a busca do preso. Só funcionará se não estiver alterando a solicitação de atendimento;
function abrirPopNovoAtend(idpreso=0){
    atualizaListagemComum('busca_presos',{tipo: 2, tipobusca: 2, valor: 1, tiporetorno:2, idvisualizacao:idvisu},$('#listapresospopnovoatend'),$('#selectpresopopnovoatend'));
    atualizaListagemComum('buscas_comuns',{tipo: 27, idgrupo:2},0,$('#selecttipopopnovoatend'));
    $("#pop-novoatend").addClass("active");
    $("#pop-novoatend").find(".popup").addClass("active");

    if(idmovpopnovoatend>0){
        buscaDadosAtendimento();
    }else{
        $('#searchpresopopnovoatend').focus();
        if(idpreso>0){
            $('#searchpresopopnovoatend').val(idpreso).trigger('change');
        }
    }
}
//Fechar pop-up Artigo
$("#pop-novoatend").find(".close-btn").on("click",function(){
    fecharPopNovoAtend();
})
function fecharPopNovoAtend(){
    $("#pop-novoatend").removeClass("active");
    $("#pop-novoatend").find(".popup").removeClass("active");
    $('#table-pop-novoatend').find('tbody').html('')
    limparCamposPopNovoAtend();
}

function limparCamposPopNovoAtend(){
    $('#selectpresopopnovoatend').val(0).trigger('change');
    $('#searchpresopopnovoatend').focus();
    $('#camposselectpresopopnovoatend').removeAttr('hidden');
    $('#obspopnovoatend').val('');
    confirmacaopopnovoatend = 0;
    idmovpopnovoatend=0;
}

adicionaEventoSelectChange(0,$('#selectpresopopnovoatend'),$('#searchpresopopnovoatend'))

$('#selectpresopopnovoatend').change(function(){
    var idpreso = $('#selectpresopopnovoatend').val();
    
    if(idpreso!=0 && idpreso!=null){
        buscaDadosPresoPopNovoAtend(idpreso);
    }else{
        $('#dadospresopopnovoatend').attr('hidden','hidden');
    }
})

$('#searchpresopopnovoatend').change(function(){
    var id = $('#searchpresopopnovoatend').val();
    
    if(id!=$('#selectpresopopnovoatend').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresopopnovoatend'),$('#selectpresopopnovoatend'),$('#selecttipopopnovoatend'));
    }
})

function buscaDadosPresoPopNovoAtend(idpreso){
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
            $('#nomepopnovoatend').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matriculapopnovoatend').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matriculapopnovoatend').html('Não Atribuída');
            }
            $('#raiocelapopnovoatend').html(result[0].RAIOCELA);
            $('#dadospresopopnovoatend').removeAttr('hidden');
        }
    });
}

adicionaEventoSelectChange(0,$('#selecttipopopnovoatend'))

function atualizaListaHistoricoAtend(){
    let idpreso = $('#selectpresopopnovoatend').val();
    let idatend = $('#selecttipopopnovoatend').val();

    if(idpreso!=null && idpreso!=undefined && idpreso>0 && idatend!=null && idatend!=undefined && idatend>0){

        let dados = {
            tipo: 5,
            idpreso: idpreso,
            idtipoatend: idatend
        }
        //console.log(dados);

        $.ajax({
            url: 'ajax/consultas/chefia_busca_gerenciar.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result);

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
                limpaCampoHistAtend();
            }else{
                tabelahistatend.html('');
                
                result.forEach(dados => {
                    let datasolic = 'Agendado direto';
                    if(dados.DATASOLICITACAO!=null){
                        datasolic = retornaDadosDataHora(dados.DATASOLICITACAO,12);
                    }
                    let descpedido = 'Agendado pela enfermaria';
                    if(dados.DESCPEDIDO!=null){
                        descpedido = dados.DESCPEDIDO;
                    }
                    let dataatend = 'Não agendado';
                    let cor = dados.COR;

                    if(dados.DATAATEND!=null){
                        dataatend = retornaDadosDataHora(dados.DATAATEND,12);;
                    }
                    let descatend = '';
                    if(dados.DESCATEND!=null){
                        descatend = dados.DESCATEND;
                    }
                    let tipoatd = dados.TIPOATEND;
                    let situacao = dados.SITUACAO;

                    tabelahistatend.append('<tr class="'+cor+'"><td class="centralizado">'+datasolic+'</td><td>'+descpedido+'</td><td class="centralizado">'+dataatend+'</td><td>'+descatend+'</td><td>'+tipoatd+'</td><td class="min-width-250 max-width-350 tdsituacao">'+situacao+'</td></tr>')
                });
                if(tabelahistatend.find('tr').length>0){
                    $('#historicoatend').removeAttr('hidden');
                }else{
                    limpaCampoHistAtend();
                }
            }
        });
        
    }else{
        limpaCampoHistAtend();
    }
}

function limpaCampoHistAtend(){
    $('#historicoatend').attr('hidden','hidden');
    $('#tablehistoricoatend').attr('hidden','hidden');
    tabelahistatend.html('');
}

function adicionaEventoHistNovoAtend(){
    let seletores = [];
    seletores.push(['#selecttipopopnovoatend','change'])
    seletores.push(['#selectpresopopnovoatend','change'])

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                atualizaListaHistoricoAtend();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    atualizaListaHistoricoAtend();
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                atualizaListaHistoricoAtend();
            })
        }
    });
}

$('#btnhistoricoatend').click(()=>{
    if($('#tablehistoricoatend').attr('hidden')=='hidden'){
        $('#tablehistoricoatend').removeAttr('hidden');
    }else{
        $('#tablehistoricoatend').attr('hidden','hidden');
    }
})

function buscaDadosAtendimento(){

    $.ajax({
        url: 'ajax/consultas/chefia_busca_gerenciar.php',
        method: 'POST',
        data: {tipo: 4, idsolicitacao: idmovpopnovoatend},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#camposselectpresopopnovoatend').attr('hidden','hidden');
            buscaDadosPresoPopNovoAtend(result[0].IDPRESO)
            $('#selecttipopopnovoatend').val(result[0].IDTIPOATEND).trigger('change');
            $('#obspopnovoatend').val(result[0].DESCPEDIDO).focus();
        }
    });
}

$('#salvarpopnovoatend').click(function(){
    if(verificaSalvarPopNovoAtend()==true){
        salvarPopNovoAtend();
    }
})

function verificaSalvarPopNovoAtend(){
    let mensagem = '';

    let elementoVerificar = $('#selectpresopopnovoatend')
    if((elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN) && idmovpopnovoatend==0){
        elementoVerificar.focus()
        mensagem = ("<li class = 'mensagem-aviso'> Selecione um Preso! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#selecttipopopnovoatend')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o Tipo de Atendimento! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#obspopnovoatend')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Descreva uma breve observação! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopNovoAtend(){
    
    let idpreso = $("#selectpresopopnovoatend").val();
    let idtipoatend = $("#selecttipopopnovoatend").val();
    let descpedido = $("#obspopnovoatend").val();

    let dados = {
        tipo: 2,
        idmovimentacao: idmovpopnovoatend,
        confirmacao: confirmacaopopnovoatend,
        idpreso: idpreso,
        idtipoatend: idtipoatend,
        descpedido: descpedido
    }

    //console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/chefia_gerenciar.php',
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
                    confirmacaopopnovoatend = result.CONFIR;
                    idmovpopnovoatend = result.IDMOV;
                };
                if(confirmacaopopnovoatend>0){
                    salvarPopNovoAtend();
                }
            }else{
                inserirMensagemTela(result.OK);
                if($('#camposselectpresopopnovoatend').attr('hidden')=='hidden'){
                    fecharPopNovoAtend();
                }
                //Se estiver aberto a consulta dos atendimentos do dia então se atualiza a lista
                if($("#pop-atend").find(".active").length>0){
                    atualizaListaAtend();
                }
                limparCamposPopNovoAtend();
            }
        }
    });
    confirmacaopopnovoatend = 0;
    idmovpopnovoatend = 0;
}

adicionaEventoHistNovoAtend();