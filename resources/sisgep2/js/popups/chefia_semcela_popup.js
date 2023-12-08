// Confirmação do retorno para salvamento das informações
let confirmacaopopsemcela = 0;
const tabelapresossemcela = $('#table-presossemcela').find('tbody');
let arrpresospopsemcela = [];

$("#opensemcela").on("click",function(){
    abrirPopSemCela();
});

function abrirPopSemCela(){
    
    limparCamposPopSemCela();
    atualizaListaPopSemCela();
    atualizaListagemComum('buscas_comuns',{tipo:24},$('#listaraiospopsemcela'),tabelapresossemcela.find('.selectraio'));

    $("#pop-semcela").addClass("active");
    $("#pop-semcela").find(".popup").addClass("active");        
}
//Fechar pop-up Artigo
$("#pop-semcela").find(".close-btn").on("click",function(){
    fecharPopSemCela();
})
function fecharPopSemCela(){
    $('#listapresospopsemcela').html('');
    $('#selectpresopopsemcela').html('');
    $("#pop-semcela").removeClass("active");
    $("#pop-semcela").find(".popup").removeClass("active");
    limparCamposPopSemCela();
}

function limparCamposPopSemCela(){
    tabelapresossemcela.html('');
    arrpresospopsemcela=[];
}

function atualizaListaPopSemCela(){
    arrpresospopsemcela=[];
    let result = consultaBanco('busca_presos',{tipo:8});
    // console.log(result);
    if(result.length){
        result.forEach(linha => {
            let data = linha.DATAENTRADA;
            if(data=='0000-00-00 00:00:00'){
                data = '******';
            }else{
                data = retornaDadosDataHora(linha.DATAENTRADA,12);
            }
            let idpreso = linha.ID;
            let seguro = linha.SEGURO;

            let novoID = 'trsemcela' + gerarID('.trsemcela');
            tabelapresossemcela.append('<tr id="'+novoID+'" class="trsemcela cor-fundo-comum-tr"><td>'+linha.NOME+'</td><td><div id="divckb'+novoID+'" class="centralizado"><input type="checkbox" id="ckbseguro'+novoID+'"></div></td><td><select id="selectraio'+novoID+'" class="selectraio"></select></td><td><select id="selectcela'+novoID+'" class="selectcela"></select></td><td>'+linha.ORIGEM+'</td><td class="centralizado">'+data+'</td></tr>');

            $('#'+novoID).prop('checked',seguro==1?'true':'false');

            arrpresospopsemcela.push({
                idtr:novoID,
                idpreso: idpreso,
                idraio:0,
                cela:0,
                seguro:seguro
            })
        });
        adicionaEventosSelectPopSemCela();
    }
}

function adicionaEventosSelectPopSemCela(){

    arrpresospopsemcela.forEach(linha => {
        let tr = $('#'+linha.idtr);
        let selectraio = tr.find('.selectraio');
        let selectcela = tr.find('.selectcela');
        let ckb = tr.find('input:checkbox');
        let divckb = ckb.parent();
    
        ckb.change(()=>{
            let index = arrpresospopsemcela.findIndex((preso)=>preso.idtr==linha.idtr)
            if(index<0){
                inserirMensagemTela('<li class="mensagem-erro"> Não foi possível alterar a situação de Seguro, tente novamente mais tarde </li>');
            }else{
                let valor = ckb.prop('checked');
                if(valor==true){
                    valor=1;
                    tr.addClass('destaque-atencao').removeClass('cor-fundo-comum-tr')
                }else{
                    tr.removeClass('destaque-atencao').addClass('cor-fundo-comum-tr')
                    valor=0;
                }
                arrpresospopsemcela[index].seguro = valor;
            }
        })
    
        divckb.click(()=>{
            let index = arrpresospopsemcela.findIndex((preso)=>preso.idtr==linha.idtr)
            if(index<0){
                inserirMensagemTela('<li class="mensagem-erro"> Não foi possível alterar a situação de Seguro, tente novamente mais tarde </li>');
            }else{
                let valor = ckb.prop('checked');
                ckb.attr('checked',valor==false).trigger('change');
                // arrpresospopsemcela[index].seguro = valor;
            }
        })
    
        selectraio.change(()=>{
            let idraio = selectraio.val();
            let index = arrpresospopsemcela.findIndex((preso)=>preso.idtr==linha.idtr)
            if(index<0){
                inserirMensagemTela('<li class="mensagem-erro"> Não foi possível inserir este Raio, tente novamente mais tarde </li>');
            }else{
                arrpresospopsemcela[index].idraio = idraio;
                preencheCelas(idraio,selectcela);
                selectcela.trigger('change');
            }
        });
    
        selectcela.change(()=>{
            let cela = selectcela.val();
            let index = arrpresospopsemcela.findIndex((preso)=>preso.idtr==linha.idtr)
            if(index<0){
                inserirMensagemTela('<li class="mensagem-erro"> Não foi possível inserir esta Cela, tente novamente mais tarde </li>');
            }else{
                arrpresospopsemcela[index].cela = cela;
                let result = verificaRaioCelaSeguro(arrpresospopsemcela[index].idraio,arrpresospopsemcela[index].cela,[1,2]);
    
                if(result.length){
                    ckb.attr('checked',true).trigger('change');
                }else{
                    ckb.attr('checked',false).trigger('change');
                }
            }
        }); 
    });
}

$('#salvarpopsemcela').click(function(){
    if(verificaSalvarPopSemCela()==true){
        salvarPopSemCela();
    }
})

function verificaSalvarPopSemCela(){
    let mensagem = '';

    let blninserircela = false;
    for(let i=0;i<arrpresospopsemcela.length;i++){
        if(arrpresospopsemcela[i].idraio!=0){
            blninserircela = true;
        };
    }

    if(blninserircela==false){
        mensagem = ("<li class = 'mensagem-aviso'> Nenhum preso foi alterado! </li>")
        inserirMensagemTela(mensagem);
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }
}

function salvarPopSemCela(){
    
    let dados = {
        tipo: 11,
        arrpresos: arrpresospopsemcela,
    };

    // console.log(dados);
    // return;

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
            if(result.CONFIR){
                if(confirm(result.MSGCONFIR)==true){
                    confirmacaopopsemcela = result.CONFIR;
                };
                if(confirmacaopopsemcela>0){
                    salvarPopSemCela();
                }
            }else{
                inserirMensagemTela(result.OK);
                fecharPopSemCela();
            }
        }
    });
}
