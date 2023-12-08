const tabela = $('#table-mov-gerenciar').find('tbody');
//Array de ids de movimentações para envio
let idsmovenvio = [];
//Array de ids de movimentações para retorno
let idsmovretorno = [];
//Array de ids de movimentações para recebimento
let idsmovreceb = [];

$('#pendente').click(()=>{
    atualizaListaGerenciarTransf();
})
$('#encerrados').click(()=>{
    atualizaListaGerenciarTransf();
})
$('#todos').click(()=>{
    atualizaListaGerenciarTransf();
})
$('#pesquisar-gerenciar').click(()=>{
    atualizaListaGerenciarTransf();
})

function atualizaListaGerenciarTransf(){
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

   //console.log(dados);

    tabela.html('');
    idsmovenvio = [];
    idsmovretorno = [];
    idsmovreceb = [];
    $.ajax({
        url: 'ajax/consultas/cim_busca_gerenciar_transferencias.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result);
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            tabela.html(result);
            adicionaEventosGerenciar()
        }
    });
}

function adicionaEventosGerenciar(){
    adicionaEventoAlterarRetorno();
    adicionaEventoAlterarRecebimento()
    adicionaEventoCheck();
}

function adicionaEventoAlterarRetorno(){
    var altmovretorno = tabela.find('.alterarretorno')
    if(altmovretorno.length>0){
        for(var i=0;i<altmovretorno.length;i++){
            var id = $('#'+altmovretorno[i].id).data('id');
            abrirAlteracaoMovimentacao($('#'+altmovretorno[i].id),id,2);
        }
    }
}

function adicionaEventoAlterarRecebimento(){
    var altmovrecebimento = tabela.find('.alterarrecebimento')
    if(altmovrecebimento.length>0){
        for(var i=0;i<altmovrecebimento.length;i++){
            var id = $('#'+altmovrecebimento[i].id).data('id');
            abrirAlteracaoMovimentacao($('#'+altmovrecebimento[i].id),id,3);
        }
    }
}

function abrirAlteracaoMovimentacao(botao, id, tipo){
    botao.on('click', ()=>{
        movimentacaotranstipo = tipo;
        idmovimentacao = id;
        abrirMovimentacaoTrans();
    })
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
                atualizaListaGerenciarTransf(); 
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    atualizaListaGerenciarTransf();
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
                    idsmovenvio.push(idmov); 
                }else if(tab==2){
                    idsmovretorno.push(idmov); 
                }else if(tab==3){
                    idsmovreceb.push(idmov); 
                }
            }else{
                if(tab==1){
                    idsmovenvio = idsmovenvio.filter((item)=>item!=idmov); 
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

$('#imp_envio').click(function(){
    
    if(idsmovenvio.length>0){
        let dados = {
            tipo:2,
            idsmovenvio: idsmovenvio
        };

        $.ajax({
            url: 'ajax/consultas/cim_busca_gerenciar_transferencias.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result);
            
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                result.forEach(linha => {
                    let informacoes = [
                        {get:'documento',valor:['planilha_envio_transf']},
                        {get:'idordem',valor:[linha.ID]},
                        {get:'opcaocabecalho',valor:[10]}
                    ];
                    // console.log(informacoes);
                    imprimirDocumentos(informacoes);
                });
            }
        });
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhuma movimentação de envio foi selecionada</li>')
    }

})

$('#imp_receb').click(function(){
    
    if(idsmovreceb.length>0 || idsmovretorno.length>0){
        let dados = {
            tipo:3,
            idsmovreceb: idsmovreceb,
            idsmovretorno: idsmovretorno
        };

        $.ajax({
            url: 'ajax/consultas/cim_busca_gerenciar_transferencias.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result);
            
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                result.forEach(linha => {
                    let informacoes = [
                        {get:'documento',valor:['planilha_recebimento_transf']},
                        {get:'datareceb',valor:[linha.DATARECEB]},
                        {get:'opcaocabecalho',valor:[10]}
                    ];
                    imprimirDocumentos(informacoes);
                });
            }
        });
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhuma movimentação de recebimento ou retorno foi selecionada</li>')
    }

})

$('#imp_escolta').click(function(){
    
    if(idsmovenvio.length>0){
        let dados = {
            tipo:2,
            idsmovenvio: idsmovenvio
        };

        $.ajax({
            url: 'ajax/consultas/cim_busca_gerenciar_transferencias.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result);
            
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                result.forEach(linha => {
                    let informacoes = [
                        {get:'documento',valor:['oficio_escolta']},
                        {get:'ordens',valor:[linha.ID]},
                        {get:'query',valor:[1]}
                    ];
                    imprimirDocumentos(informacoes);
                });
            }
        });
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhuma movimentação de envio foi selecionada</li>')
    }

})

$('#imp_ordem').click(function(){
    
    if(idsmovenvio.length>0){
        let dados = {
            tipo:2,
            idsmovenvio: idsmovenvio
        };

        $.ajax({
            url: 'ajax/consultas/cim_busca_gerenciar_transferencias.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result);
            
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                result.forEach(linha => {
                    let informacoes = [
                        {get:'documento',valor:['ordem_saida_presos']},
                        {get:'ordens',valor:[linha.ID]},
                        {get:'query',valor:[1]}
                    ];
                    imprimirDocumentos(informacoes);
                });
            }
        });
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhuma movimentação de envio foi selecionada</li>')
    }

})

adicionaEventoPesquisaGerMov();

atualizaListaGerenciarTransf();
$('#datainicio').focus();