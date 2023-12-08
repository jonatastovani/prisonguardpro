// Confirmação do retorno para salvamento das informações
let confirmacaopopnovogerais = 0;
let idmovpopnovogerais = 0;
let idbancopopnovogerais = 0;
const tabelaatendgerais = $('#table-atendgerais').find('tbody');
let idspresosadicionados = [];
let listasituacaopopnovogerais = $('#situacaopopnovogerais').html();

$("#opennovogerais").on("click",function(){
    //Fechar a visibilidade da população do raio antes de abrir o popup
    if($('.visibilidadepop').length>0){
        fecharPopulacaoRaio();
    }
    abrirPopNovoGerais();
});

//idpreso = preencher caso queira que se abra o novo atendimento preenchendo a busca do preso. Só funcionará se não estiver alterando a solicitação de atendimento;
function abrirPopNovoGerais(blnalterar=false,idpreso=0){
    atualizaListagemComum('busca_presos',{tipo: 2, tipobusca: 2, valor: 1, tiporetorno:2, idvisualizacao:idvisu, blnvisuchefia:true},$('#listapresospopnovogerais'),$('#selectpresopopnovogerais'));
    atualizaListagemComum('buscas_comuns',{tipo: 27, idgrupo:1},0,$('#selecttipopopnovogerais'));
    atualizaListagemComum('buscas_comuns',{tipo:28,idtipo:7},$('#listasituacaopopnovogerais'),0,false,false,'',false,false);
    listasituacaopopnovogerais = $('#listasituacaopopnovogerais').html();
    $("#pop-novogerais").addClass("active");
    $("#pop-novogerais").find(".popup").addClass("active");
    limparCamposPopNovoGerais(blnalterar==false);
    
    if(idmovpopnovogerais>0 || idbancopopnovogerais>0){
        buscaDadosAtendimentoNovoGerais();
    }else{
        $('#selecttipopopnovogerais').focus();
        if(idpreso>0){
            buscaDadosPresoPopNovoGerais(idpreso);
        }
    }
}
//Fechar pop-up Artigo
$("#pop-novogerais").find(".close-btn").on("click",function(){
    fecharPopNovoGerais();
})
function fecharPopNovoGerais(){
    $("#pop-novogerais").removeClass("active");
    $("#pop-novogerais").find(".popup").removeClass("active");
    $('#table-pop-novogerais').find('tbody').html('')
    limparCamposPopNovoGerais();
}

function limparCamposPopNovoGerais(limparID=true){
    $('#selectpresopopnovogerais').val(0).trigger('change');
    $('#searchpresopopnovogerais').focus();
    $('#selecttipopopnovogerais').val(0);
    $('#datapopnovogerais').val(retornaDadosDataHora(new Date(),1));
    $('#horapopnovogerais').val(retornaDadosDataHora(new Date(),6));
    $('#requisitantepopnovogerais').val('');
    tabelaatendgerais.html('');

    //Atualiza lista de sugestões
    atualizaListagemComum('buscas_comuns',{tipo:29,idtipo:1},$('#listarequisitantepopnovogerais'),0,false,false,'',false,false);

    if(limparID==true){
        idmovpopnovogerais=0;
        idbancopopnovogerais=0;
    }
    confirmacaopopnovogerais = 0;
    idspresosadicionados=[];
}

adicionaEventoSelectChange(0,$('#selectpresopopnovogerais'),$('#searchpresopopnovogerais'))

$('#btninserirpopnovogerais').click(function(){
    var idpreso = $('#selectpresopopnovogerais').val();
    
    if(idpreso!=0 && idpreso!=null && idpreso!=undefined){
        buscaDadosPresoPopNovoGerais(idpreso);
    }else{
        inserirMensagemTela('<li class="mensagem-aviso">Selecione um preso</li>');
    }
})

$('#searchpresopopnovogerais').change(function(){
    var id = $('#searchpresopopnovogerais').val();
    
    if(id!=$('#selectpresopopnovogerais').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresopopnovogerais'),$('#selectpresopopnovogerais'),$('#btninserirpopnovogerais'));
    }
})

function buscaDadosPresoPopNovoGerais(idpreso){
    let tr = false;
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
            let novoID = gerarID('.atendgeraispreso');

            let acao = '<td class="tdbotoes"><button class="btnAcaoRegistro btndel" title="Excluir Preso"><img src="imagens/lixeira.png" class="imgBtnAcao"></button></td>';
            let matricula = '<td class="centralizado">Não Atribuída</td>';
            if(result[0].MATRICULA!=null){
                matricula = '<td class="centralizado">'+midMatricula(result[0].MATRICULA,3)+'</td>';
            }
            let nome = '<td>'+result[0].NOME+'</td>';
            let local = '<td class="centralizado">'+result[0].RAIOCELA+'</td>';
            let cor = 'cor-fundo-comum-tr';
            if(result[0].SEGURO==1){
                cor = 'destaque-atencao';
            }
            let situacao = '<td><select class="selectsituacao"></select></td>';

            tabelaatendgerais.append('<tr id="atendgeraispreso'+novoID+'" class="'+cor+' atendgeraispreso">'+acao+matricula+nome+local+situacao+'</tr>')
            
            idspresosadicionados.push({idbanco: 0, idpreso: idpreso, idtr: 'atendgeraispreso'+novoID, idsituacao: 10});
            
            tr = $('#atendgeraispreso'+novoID);
            adicionaEventoNovoGerais(tr);
            $('#searchpresopopnovogerais').val('').trigger('change').focus();
            return tr;
        }
    });
    return tr;
}

function adicionaEventoNovoGerais(tr){
    let tdbotoes = tr.find('.tdbotoes');
    let situacao = tr.find('.selectsituacao');
    let idtr = tr.attr('id');

    tdbotoes.find('.btndel').click(()=>{
        idspresosadicionados = idspresosadicionados.filter((item)=>item.idtr!=idtr)
        tr.remove();
    })

    //Adiciona a lista de situação no select
    situacao.html(listasituacaopopnovogerais);
    
    situacao.change(()=>{
        if(situacao.val()>0){
            let index = idspresosadicionados.findIndex((linha => linha.idtr == idtr));
            idspresosadicionados[index].idsituacao = situacao.val();
        }
    })
}

function buscaDadosAtendimentoNovoGerais(){
    let dados = [];
    if(idbancopopnovogerais>0){
        dados = {
            tipo:9,
            idbanco:idbancopopnovogerais
        }
    }else{
        dados = {
            tipo:9,
            idmovimentacao:idmovpopnovogerais
        }
    }
    $.ajax({
        url: 'ajax/consultas/chefia_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            //Atualiza o IDMOVIMENTACAO pois o ID informado para consulta é o ID do atendimento
            idmovpopnovogerais = result[0].IDMOVIMENTACAO;
            $('#selecttipopopnovogerais').val(result[0].IDTIPOATEND);
            if(result[0].DATAATEND!=null){
                $('#datapopnovogerais').val(retornaDadosDataHora(result[0].DATAATEND,1));
                $('#horapopnovogerais').val(retornaDadosDataHora(result[0].DATAATEND,6));
            }else{
                $('#datapopnovogerais').val('');
                $('#horapopnovogerais').val('');
            }
            $('#requisitantepopnovogerais').val(result[0].REQUISITANTE);
            result.forEach(linha => {
                let preso = buscaDadosPresoPopNovoGerais(linha.IDPRESO)
                if(preso!=false){
                    let selectsituacao = preso.find('.selectsituacao');
                    selectsituacao.val(linha.IDSITUACAO).trigger('change');
                    let index = idspresosadicionados.findIndex((linha => linha.idtr == preso.attr('id')));
                    idspresosadicionados[index].idbanco = linha.IDTEND;

                    // Se a situação for 13 ou 17 então terá opções definidas a seguir
                    if(linha.IDSITUACAO==13){
                        selectsituacao.attr('disabled','disabled');
                        preso.find('.tdbotoes').html('');
                    }else if(linha.IDSITUACAO==17){
                        selectsituacao.html('<option value="17" selected>Em atendimento</option><option value="13">Realizado</option>');
                        preso.find('.tdbotoes').html('');
                    }
                }
            });
        }
    });
}

$('#salvarpopnovogerais').click(function(){
    if(verificaSalvarPopNovoGerais()==true){
        salvarPopNovoGerais();
    }
})

function verificaSalvarPopNovoGerais(){
    let mensagem = '';

    if(idspresosadicionados.length==0){
        $('#searchpresopopnovogerais').focus();
        mensagem = ("<li class = 'mensagem-aviso'> Selecione pelo menos um preso! </li>")
        inserirMensagemTela(mensagem);
    }
    let elementoVerificar = $('#selecttipopopnovogerais');
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus();
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o Tipo de Atendimento! </li>")
        inserirMensagemTela(mensagem);
    }
    elementoVerificar = $('#requisitantepopnovogerais');
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus();
        }
        mensagem = ("<li class = 'mensagem-aviso'> Digite o nome do requisitante! </li>")
        inserirMensagemTela(mensagem);
    }
    elementoVerificar = $('#datapopnovogerais');
    if(elementoVerificar.val()!=0 && elementoVerificar.val()!=null && elementoVerificar.val()!=NaN){

        let diferenca = retornaDiferencaDeDataEHora(retornaDadosDataHora(new Date(),1),elementoVerificar.val(),1);
        if(diferenca<0){
            if(mensagem==''){
                elementoVerificar.focus();
            }
            mensagem = ("<li class = 'mensagem-aviso'> Data não pode ser retroativa! </li>")
            inserirMensagemTela(mensagem);
            
        }else{
            elementoVerificar = $('#horapopnovogerais');
            if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
                if(mensagem==''){
                    elementoVerificar.focus();
                }
                mensagem = ("<li class = 'mensagem-aviso'> Hora inválida! </li>")
                inserirMensagemTela(mensagem);
            }
    
        }
    }else{
        if(mensagem==''){
            elementoVerificar.focus();
        }
        mensagem = ("<li class = 'mensagem-aviso'> Data inválida! </li>")
        inserirMensagemTela(mensagem);
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopNovoGerais(){
    
    let idtipoatend = $("#selecttipopopnovogerais").val();
    let requisitante = $("#requisitantepopnovogerais").val();
    let dataatend = $("#datapopnovogerais").val();
    if(dataatend!=null && dataatend!=NaN && dataatend!=0){
        dataatend += ' '+$("#horapopnovogerais").val();
    }else{
        dataatend = '';
    }

    let dados = {
        tipo: 3,
        idtipoatend: idtipoatend,
        dataatend: dataatend,
        requisitante: requisitante,
        idmovimentacao: idmovpopnovogerais,
        presos: idspresosadicionados,
        confirmacao: confirmacaopopnovogerais
    }

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
                    confirmacaopopnovogerais = result.CONFIR;
                    idmovpopnovogerais = result.IDMOV;
                };
                if(confirmacaopopnovogerais>0){
                    salvarPopNovoGerais();
                }
            }else{
                inserirMensagemTela(result.OK);
                if(idmovpopnovogerais!=0){
                    fecharPopNovoGerais();
                }
                //Se estiver aberto a consulta dos atendimentos do dia então se atualiza a lista
                if($("#pop-atend").find(".active").length>0){
                    atualizaListaAtend();
                }
                limparCamposPopNovoGerais();
            }
        }
    });
}