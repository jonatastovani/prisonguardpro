// Confirmação do retorno para salvamento das informações
let confirmacaopopatendenf = 0;
let idmovpopatendenf = 0;
let idbancopopatendenf = 0;
const tabelaatendgerais = $('#table-atendgerais').find('tbody');
let idspresosadicionados = [];
let listasituacaopopatendenf = $('#situacaopopatendenf').html();

$("#openatendenf").on("click",function(){
    abrirPopAtendEnfermaria();
});

//idpreso = preencher caso queira que se abra o novo atendimento preenchendo a busca do preso. Só funcionará se não estiver alterando a solicitação de atendimento;
function abrirPopAtendEnfermaria(blnalterar=false,idpreso=0){
    let blnencontrado = false;

    atualizaListagemComum('busca_presos',{tipo: 2, tipobusca: 2, valor: 1, tiporetorno:2, idvisualizacao:0, blnvisuchefia:true},$('#listapresospopatendenf'),$('#selectpresopopatendenf'));
    atualizaListagemComum('buscas_comuns',{tipo: 27, idgrupo:2},0,$('#selecttipopopatendenf'));
    atualizaListagemComum('buscas_comuns',{tipo:28,idtipo:6},$('#listasituacaopopatendenf'),0,false,false,'',false,false);
    listasituacaopopatendenf = $('#listasituacaopopatendenf').html();
    limparCamposPopAtendEnfermaria(blnalterar==false);

    if(idmovpopatendenf>0 || idbancopopatendenf>0){
        blnencontrado = buscaDadosAtendimentoAtendEnfermaria();
    }else{
        $('#selecttipopopatendenf').focus();
        if(idpreso>0){
            let tr = buscaDadosPresoPopAtendEnfermaria(idpreso);
            tr.find('.horarioatend').focus();
        }
        blnencontrado = true;
    }
    if(blnencontrado){
        $("#pop-atendenf").addClass("active");
        $("#pop-atendenf").find(".popup").addClass("active");
    }else{
        fecharPopAtendEnfermaria();
    }
}
//Fechar pop-up Artigo
$("#pop-atendenf").find(".close-btn").on("click",function(){
    fecharPopAtendEnfermaria();
})
function fecharPopAtendEnfermaria(){
    $("#pop-atendenf").removeClass("active");
    $("#pop-atendenf").find(".popup").removeClass("active");
    $('#table-pop-atendenf').find('tbody').html('')
    limparCamposPopAtendEnfermaria();
}

function limparCamposPopAtendEnfermaria(limparID=true){
    $('#selectpresopopatendenf').val(0).trigger('change');
    $('#searchpresopopatendenf').focus();
    $('#selecttipopopatendenf').val(0);
    $('#datapopatendenf').val(retornaDadosDataHora(new Date(),1));
    $('#horapopatendenf').val(retornaDadosDataHora(new Date(),6));
    $('#requisitantepopatendenf').val('');
    tabelaatendgerais.html('');

    //Atualiza lista de sugestões
    atualizaListagemComum('buscas_comuns',{tipo:29,idtipo:2},$('#listarequisitantepopatendenf'),0,false,false,'',false,false);

    if(limparID==true){
        idmovpopatendenf=0;
        idbancopopatendenf=0;
    }
    confirmacaopopatendenf = 0;
    idspresosadicionados=[];
}

adicionaEventoSelectChange(0,$('#selectpresopopatendenf'),$('#searchpresopopatendenf'))

$('#btninserirpopatendenf').click(function(){
    var idpreso = $('#selectpresopopatendenf').val();
    
    if(idpreso!=0 && idpreso!=null && idpreso!=undefined){
        buscaDadosPresoPopAtendEnfermaria(idpreso);
    }else{
        inserirMensagemTela('<li class="mensagem-aviso">Selecione um preso</li>');
    }
})

$('#searchpresopopatendenf').change(function(){
    var id = $('#searchpresopopatendenf').val();
    
    if(id!=$('#selectpresopopatendenf').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresopopatendenf'),$('#selectpresopopatendenf'),$('#btninserirpopatendenf'));
    }
})

function buscaDadosPresoPopAtendEnfermaria(idpreso){
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
            let matricula = '<td class="centralizado nowrap">Não Atribuída</td>';
            if(result[0].MATRICULA!=null){
                matricula = '<td class="centralizado nowrap">'+midMatricula(result[0].MATRICULA,3)+'</td>';
            }
            let nome = '<td class="nowrap">'+result[0].NOME+'</td>';
            let horario = '<td><input type="time" class="horarioatend"></td>';
            let local = '<td class="centralizado">'+result[0].RAIOCELA+'</td>';
            let cor = 'cor-fundo-comum-tr';
            if(result[0].SEGURO==1){
                cor = 'destaque-atencao';
            }
            let situacao = '<td><select class="selectsituacao"></select></td>';

            tabelaatendgerais.append('<tr id="atendgeraispreso'+novoID+'" class="'+cor+' atendgeraispreso">'+acao+matricula+nome+horario+local+situacao+'</tr>')
            
            idspresosadicionados.push({idbanco: 0, nomepreso: result[0].NOME, idpreso: idpreso, idtr: 'atendgeraispreso'+novoID, idsituacao: 10, horario:0});
            
            tr = $('#atendgeraispreso'+novoID);
            adicionaEventoAtendEnfermaria(tr);
            $('#searchpresopopatendenf').val('').trigger('change').focus();
            return tr;
        }
    });
    return tr;
}

function adicionaEventoAtendEnfermaria(tr){
    let tdbotoes = tr.find('.tdbotoes');
    let situacao = tr.find('.selectsituacao');
    let horario = tr.find('.horarioatend');
    let idtr = tr.attr('id');

    tdbotoes.find('.btndel').click(()=>{
        idspresosadicionados = idspresosadicionados.filter((item)=>item.idtr!=idtr)
        tr.remove();
    })

    //Adiciona a lista de situação no select
    situacao.html(listasituacaopopatendenf);
    
    situacao.change(()=>{
        if(situacao.val()>0){
            let index = idspresosadicionados.findIndex((linha => linha.idtr == idtr));
            idspresosadicionados[index].idsituacao = situacao.val();
        }
    })
    horario.change(()=>{
        // console.log(horario.val());
        let index = idspresosadicionados.findIndex((linha => linha.idtr == idtr));
        if(horario.val()==null || horario.val()==undefined || horario.val()==''){
            inserirMensagemTela('<li class="mensagem-aviso"> Insira um horário válido </li>');
        }else{
            idspresosadicionados[index].horario = horario.val();
        }
    })
    horario.focusout(()=>{
        // console.log(horario.val());
        let index = idspresosadicionados.findIndex((linha => linha.idtr == idtr));
        if(horario.val()==null || horario.val()==undefined || horario.val()==''){
            if(idspresosadicionados[index].horario==0){
                inserirMensagemTela('<li class="mensagem-aviso"> O horário inserido é inválido. Não será possível salvar sem antes o atendimento possuir um horário válido. </li>');
            }else{
                inserirMensagemTela('<li class="mensagem-aviso"> O horário inserido é inválido, por este motivo foi retornado automaticamente o horário anteriormente inserido. </li>');
                horario.val(idspresosadicionados[index].horario);
            }
        }
    })
}

function buscaDadosAtendimentoAtendEnfermaria(){
    let retorno = false;

    let dados = [];
    if(idbancopopatendenf>0){
        dados = {
            tipo:2,
            idbanco:idbancopopatendenf
        }
    }else{
        dados = {
            tipo:2,
            idmovimentacao:idmovpopatendenf
        }
    }
    $.ajax({
        url: 'ajax/consultas/saude_busca_gerenciar.php',
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

            if(result[0].IDMOVIMENTACAO!=0 && result[0].IDMOVIMENTACAO>0){
                idmovpopatendenf = result[0].IDMOVIMENTACAO;
            }else{
                idmovpopatendenf = 0;
            }
            $('#selecttipopopatendenf').val(result[0].IDTIPOATEND);
            if(result[0].DATAATEND!=null){
                $('#datapopatendenf').val(retornaDadosDataHora(result[0].DATAATEND,1));
                $('#horapopatendenf').val(retornaDadosDataHora(result[0].DATAATEND,6));
            }else{
                $('#datapopatendenf').val('');
                $('#horapopatendenf').val('');
            }
            $('#requisitantepopatendenf').val(result[0].REQUISITANTE);

            result.forEach(linha => {

                let preso = buscaDadosPresoPopAtendEnfermaria(linha.IDPRESO)
                if(preso!=false){
                    let selectsituacao = preso.find('.selectsituacao');
                    let horaatend = preso.find('.horarioatend');
                    selectsituacao.val(linha.IDSITUACAO).trigger('change');
                    let index = idspresosadicionados.findIndex((linha => linha.idtr == preso.attr('id')));
                    idspresosadicionados[index].idbanco = linha.IDTEND;

                    if(linha.HORAATEND!=null){
                        preso.find('.horarioatend').val(linha.HORAATEND).trigger('change');
                    }
                    // Se a situação for 13 ou 17 então terá opções definidas a seguir
                    if(linha.IDSITUACAO==13){
                        selectsituacao.attr('disabled','disabled');
                        horaatend.attr('disabled','disabled');
                        preso.find('.tdbotoes').html('');
                    }else if(linha.IDSITUACAO==17){
                        selectsituacao.html('<option value="17" selected>Em atendimento</option><option value="13">Realizado</option>');
                        preso.find('.tdbotoes').html('');
                    }
                }
            });
            retorno = true;
        }
    });
    return retorno;
}

$('#salvarpopatendenf').click(function(){
    if(verificaSalvarPopAtendEnfermaria()==true){
        salvarPopAtendEnfermaria();
    }
})

function verificaSalvarPopAtendEnfermaria(){
    let mensagem = '';

    if(idspresosadicionados.length==0){
        $('#searchpresopopatendenf').focus();
        mensagem = ("<li class = 'mensagem-aviso'> Adicione pelo menos um preso! </li>")
        inserirMensagemTela(mensagem);
    }else{
        idspresosadicionados.forEach(preso => {
            if(preso.horario==0){
                mensagem = ("<li class = 'mensagem-aviso'> Horário do preso "+preso.nomepreso+", inválido. </li>")
                inserirMensagemTela(mensagem);        
            }
        });
    }
    let elementoVerificar = $('#selecttipopopatendenf');
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus();
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o Tipo de Atendimento! </li>")
        inserirMensagemTela(mensagem);
    }
    elementoVerificar = $('#requisitantepopatendenf');
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus();
        }
        mensagem = ("<li class = 'mensagem-aviso'> Digite o nome do requisitante! </li>")
        inserirMensagemTela(mensagem);
    }
    elementoVerificar = $('#datapopatendenf');
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus();
        }
        mensagem = ("<li class = 'mensagem-aviso'> Data inválida! </li>")
        inserirMensagemTela(mensagem);
    }else{
        let diferenca = retornaDiferencaDeDataEHora(retornaDadosDataHora(new Date(),1),elementoVerificar.val(),1);
        if(diferenca<0){
            if(mensagem==''){
                elementoVerificar.focus();
            }
            mensagem = ("<li class = 'mensagem-aviso'> Data não pode ser retroativa! </li>")
            inserirMensagemTela(mensagem);
        }
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }
}

function salvarPopAtendEnfermaria(){
    
    let idtipoatend = $("#selecttipopopatendenf").val();
    let requisitante = $("#requisitantepopatendenf").val();
    let dataatend = $("#datapopatendenf").val();

    let dados = {
        tipo: 1,
        idtipoatend: idtipoatend,
        dataatend: dataatend,
        requisitante: requisitante,
        idmovimentacao: idmovpopatendenf,
        presos: idspresosadicionados,
        confirmacao: confirmacaopopatendenf
    }

        // console.log(dados)
    $.ajax({
        url: 'ajax/inserir_alterar/saude_gerenciar.php',
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
            if(result.CONFIR){
                if(confirm(result.MSGCONFIR)==true){
                    confirmacaopopatendenf = result.CONFIR;
                    idmovpopatendenf = result.IDMOV;
                };
                if(confirmacaopopatendenf>0){
                    salvarPopAtendEnfermaria();
                }
            }else{
                inserirMensagemTela(result.OK);
                fecharPopAtendEnfermaria();
                //Se estiver aberto a consulta dos atendimentos do dia então se atualiza a lista
                if($("#table-atend-gerenciar").length>0){
                    atualizaListaGerenciarAtend();
                }
                limparCamposPopAtendEnfermaria();
            }
        }
    });
}