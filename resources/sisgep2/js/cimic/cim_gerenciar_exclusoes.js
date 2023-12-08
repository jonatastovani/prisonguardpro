const tabela = $('#table-exc-gerenciar').find('tbody');
//Array de ids de movimentações para envio
let idsmovimentacoes = [];
//Array de ids de movimentações para retorno
let idsmovretorno = [];
//Array de ids de movimentações para recebimento
let idsmovreceb = [];

$('#pendente').click(()=>{
    atualizaListaGerenciarAtend();
})
$('#encerrados').click(()=>{
    atualizaListaGerenciarAtend();
})
$('#todos').click(()=>{
    atualizaListaGerenciarAtend();
})
$('#pesquisar-gerenciar').click(()=>{
    atualizaListaGerenciarAtend();
})

function atualizaListaGerenciarAtend(){
    let datainicio = $('#datainicio').val();
    let datafinal = $('#datafinal').val();
    let ordem = 0;
    if($('#ordemmatricula').prop('checked')==true){
        ordem = $('#ordemmatricula').val();
    }
    else if($('#ordemnome').prop('checked')==true){
        ordem = $('#ordemnome').val();
    }
    else if($('#ordemdata').prop('checked')==true){
        ordem = $('#ordemdata').val();
    }
    let texto = 0;
    if($('#dividirtexto').prop('checked')==true){
        texto = $('#dividirtexto').val();
    }
    else if($('#todotexto').prop('checked')==true){
        texto = $('#todotexto').val();
    }
    let buscatexto = 0;
    if($('#buscaparte').prop('checked')==true){
        buscatexto = $('#buscaparte').val();
    }
    else if($('#buscaexata').prop('checked')==true){
        buscatexto = $('#buscaexata').val();
    }
    else if($('#buscainicio').prop('checked')==true){
        buscatexto = $('#buscainicio').val();
    }
    else if($('#buscafinal').prop('checked')==true){
        buscatexto = $('#buscafinal').val();
    }
    let textobusca = $('#textobusca').val().trim();

    let dados = {
        tipo: 1,
        datainicio: datainicio,
        datafinal: datafinal,
        ordem: ordem,
        buscatexto: buscatexto,
        texto: texto,
        textobusca: textobusca
    }

//    console.log(dados);

    tabela.html('');
    idsmovimentacoes = [];

    $.ajax({
        url: 'ajax/consultas/cim_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result);
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            tabela.html(result);
            adicionaEventosGerenciar()
        }
    });
}

function adicionaEventosGerenciar(){
    adicionaEventoAlterarAtend();
    adicionaEventoCheck();
}

function adicionaEventoAlterarAtend(){
    let exclusoes = tabela.find('tr')
    if(exclusoes.length>0){
        for(let i=0;i<exclusoes.length;i++){
            let excl = $('#'+exclusoes[i].id);
            let id = excl.find('input:checkbox').data('idmov');
            let idsituacao = excl.find('input:checkbox').data('idsituacao');

            if([13].includes(idsituacao)==false){
                inserirBotaoAlterarExclusoes(excl.find('.tdbotoes'),id);
            }
        }
    }
}

function adicionaEventoPesquisaGerMov(){
    let seletores = [];
    seletores.push(['#datainicio','enter'])
    seletores.push(['#datafinal','enter'])
    seletores.push(['#ordemmatricula','change'])
    seletores.push(['#ordemnome','change'])
    seletores.push(['#ordemdata','change'])
    seletores.push(['#textobusca','enter'])
    seletores.push(['#dividirtexto','change'])
    seletores.push(['#todotexto','change'])
    seletores.push(['#buscaparte','change'])
    seletores.push(['#buscaexata','change'])
    seletores.push(['#buscainicio','change'])
    seletores.push(['#buscafinal','change'])

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                atualizaListaGerenciarAtend(); 
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    atualizaListaGerenciarAtend();
                }
            })
        }
    });
}

function adicionaEventoCheck(){
    var checks = tabela.find('input:checkbox');

    for(var i=0;i<checks.length;i++){
        let check = $('#'+checks[i].id);
        let idmov = check.data('idmov');
        let tab = check.data('tabela');

        check.on('change', ()=>{
            if(check.prop('checked')==true){
                if(tab==1){
                    idsmovimentacoes.push(idmov); 
                }else if(tab==2){
                    idsmovretorno.push(idmov); 
                }else if(tab==3){
                    idsmovreceb.push(idmov); 
                }
            }else{
                if(tab==1){
                    idsmovimentacoes = idsmovimentacoes.filter((item)=>item!=idmov); 
                }else if(tab==2){
                    idsmovretorno = idsmovretorno.filter((item)=>item!=idmov); 
                }else if(tab==3){
                    idsmovreceb = idsmovreceb.filter((item)=>item!=idmov); 
                }
            }
        })
    }
}

function obtemChecados(){
    var check = tabela.find("input:checked");
    return check;
}

$('#checkall').click(()=>{
    var checks = tabela.find('input:checkbox');
    var bln = $('#checkall').prop('checked');

    for(var i=0;i<checks.length;i++){
        var check = $('#'+checks[i].id);
        check.prop('checked', bln).trigger('change');
    }
})

adicionaEventoPesquisaGerMov();

atualizaListaGerenciarAtend();
$('#datainicio').focus();
