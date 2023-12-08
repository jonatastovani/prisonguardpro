const idbancoatend = buscaIDAtend();
const containermedicamentos = $('#containermedicamentos');

let arrmedicamentos = [];

function buscaIDAtend(){
    let idbuscar = $('#idbancoatend').val();
    return buscaIDDecodificado(6,idbuscar);
}

function buscaDadosAtend(){
    // console.log(idbancoatend)

    $.ajax({
        url: 'ajax/consultas/saude_busca_gerenciar.php',
        method: 'POST',
        data: {tipo: 3, idatend: idbancoatend},
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            buscaDadosPresoAtend(result[0].IDPRESO);

            let datasolic = 'Não solicitado'
            if(result[0].DATASOLICITACAO!=null){
                datasolic = retornaDadosDataHora(result[0].DATASOLICITACAO,12);
            }
            $('#datasolic').html(datasolic);
            $('#dataatend').html(retornaDadosDataHora(result[0].DATAATEND,12));
            $('#requisitante').html(result[0].REQUISITANTE);
            $('#descpedido').val(result[0].DESCPEDIDO);
            $('#descatend').val(result[0].DESCATEND);
            $('#observacoes').val(result[0].OBSERVACOES);
            
            buscaMedicEntreguesAtend();
        }
    });
}

function buscaDadosPresoAtend(idpreso){
    let result = consultaBanco('busca_presos',{tipo: 1, idpreso: idpreso});
    // console.log(result)
    if(result.length>0 || result!=[]){
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#nome').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matricula').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matricula').html('Não Atribuída');
            }
            $('#raiocela').html(result[0].RAIOCELA);
            if(result[0].MAE!=null){
                $('#mae').html(result[0].MAE);
            }else{
                $('#mae').html('Não informado');
            }
            if(result[0].PAI!=null){
                $('#pai').html(result[0].PAI);
            }else{
                $('#pai').html('Não informado');
            }
        }
    }

}

function buscaMedicEntreguesAtend(){

    $.ajax({
        url: 'ajax/consultas/saude_busca_gerenciar.php',
        method: 'POST',
        data: {tipo: 4, idatend: idbancoatend},
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                inserirMedicamento(linha.IDMEDICAMENTO);
                let index = arrmedicamentos.findIndex((medic)=>medic.idmedic==linha.IDMEDICAMENTO);
                arrmedicamentos[index].idbanco = linha.ID;
                $('#'+arrmedicamentos[index].iddiv).find('.qtdentregue').val(linha.QTD).trigger('change');
                $('#'+arrmedicamentos[index].iddiv).find('.recommedic').val(linha.OBSERVACOES).trigger('change');
                // console.log(arrmedicamentos);
            });
        }
    });
}

adicionaEventoSelectChange(0,$('#selectmedic'),$('#searchmedic'))

$('#searchmedic').change(()=>{
    var id = $('#searchmedic').val();
    
    if(id!=$('#selectmedic').val()){
        buscaSearchComum('buscas_comuns',{tipo:41, idmedic:id},$('#searchmedic'),$('#selectmedic'),$('#inserirmedic'));
    }
})

$('#inserirmedic').click(()=>{
    var idmedic = $('#selectmedic').val();

    if(idmedic>0 &&idmedic!=null && idmedic!=undefined && idmedic!=NaN){
        let index = arrmedicamentos.findIndex((medic)=>medic.idmedic==idmedic);
        if(index<0){
            inserirMedicamento(idmedic);
            $('#searchmedic').val('').trigger('change').focus();
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Este medicamento já foi incluso, altere somente a quantidade. </li>');
            $('#'+arrmedicamentos[index].iddiv).find('.qtdentregue').focus();
        }
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Selecione um medicamento </li>');
        $('#searchmedic').val('').trigger('change').focus();
    }
})

function inserirMedicamento(idmedic){
    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo: 41, idmedic: idmedic},
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            let novoID = 'medicamentosentregues'+gerarID('.medicamentosentregues');

            let registro = '<div id="'+novoID+'" class="medicamentosentregues grupo-block largura-total relative"><span>ID Medic: <b><span class="idmedic">'+result[0].ID+'</span></b></span><span class="margin-espaco-esq">Nome: <b><span class="nomemedic">'+result[0].NOME+'</span></b></span><br><span>Quant.: <input type="text" style="width: 90px;" class="qtdentregue num-inteiro" value="'+result[0].QTD+'" title="Quantidade entregue para o preso"></span><br><div class="flex"><span>Recomendações:</span><input type="text" class="recommedic margin-espaco-esq largura-restante"></div><button class="fechar-absolute">&times;</button></div>';

            arrmedicamentos.push({
                idbanco: 0,
                idmedic: result[0].ID,
                nomemedic:result[0].NOME,
                iddiv: novoID,
                qtdentregue:result[0].QTD,
                recommedic:''
            });

            containermedicamentos.append(registro);
            adicionaEventosMedicamentos($('#'+novoID),result[0].ID)
        }
    });
}

function adicionaEventosMedicamentos(div,idmedic){
    let qtdentregue = div.find('.qtdentregue');
    let recommedic = div.find('.recommedic');

    qtdentregue.change(()=>{
        let qtd = qtdentregue.val();
        if(qtd=='' || qtd.trim()==''){
            qtd=0;
        }
        let index = arrmedicamentos.findIndex((medic)=>medic.idmedic==idmedic);
        arrmedicamentos[index].qtdentregue = qtd;
        qtdentregue.val(qtd);
    });

    recommedic.change(()=>{
        let index = arrmedicamentos.findIndex((medic)=>medic.idmedic==idmedic);
        arrmedicamentos[index].recommedic = recommedic.val();
    });

}

function adicionarEventoMensagensProntas(){
    let botoes = $('#mensagens-prontas').find('button');
    for(let i=0;i<botoes.length;i++){
        let id = botoes[i].id;
        if(id!=''){
            $('#'+id).click(()=>{
                let texto = $('#'+id).attr('title');
                if($('#descatend').val().trim()!=''){
                    texto = "\r"+texto;
                }
                $('#descatend').val( $('#descatend').val()+texto);
            })
        }else{
            botoes[i].remove();
        }
    }
}

$('#salvaratendimento').click(function(){
    if(verificaSalvarAtendimento()==true){
        salvarAtendimento();
    }
})

function verificaSalvarAtendimento(){
    var mensagem = '';
    
    var elementoVerificar = $('#descatend')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Descrição do Atendimento deve ser preenchido! </li>")
        inserirMensagemTela(mensagem);
    }

    if(arrmedicamentos.length>0){
        arrmedicamentos.forEach(medic => {
            if(medic.qtdentregue<1){
                mensagem = ("<li class = 'mensagem-aviso'> A quantidade entregue do <b>"+medic.nomemedic+"</b> não pode ser 0! </li>")
                inserirMensagemTela(mensagem);
                $('#'+medic.iddiv).find('.qtdentregue').focus();
            }
        });
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarAtendimento(){
    
    let descatend = $('#descatend').val();
    let observacoes = $('#observacoes').val();

    let dados = {
        tipo:4,
        idatend:idbancoatend,
        descatend:descatend,
        observacoes:observacoes,
        arrmedicamentos:arrmedicamentos
    };
    // console.log(dados);

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
            alert("Dados enviados com sucesso!");
            window.close();
        }
    });
}

atualizaListagemComum('buscas_comuns',{tipo:40},$('#searchmedicamentos'),$('.selectmedicamentos'));
buscaDadosAtend();
adicionarEventoMensagensProntas();