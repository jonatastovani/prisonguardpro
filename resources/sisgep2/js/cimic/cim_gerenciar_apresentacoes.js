const tabela = $('#table-mov-gerenciar').find('tbody');
//Array de ids de apresentações externas
let idsapresext = [];
//Array de ids de apresentações internas
let idsapresint = [];

$('#pendente').click(()=>{
    atualizaListaGerenciarApres();
})
$('#encerrados').click(()=>{
    atualizaListaGerenciarApres();
})
$('#todos').click(()=>{
    atualizaListaGerenciarApres();
})
$('#pesquisar-gerenciar').click(()=>{
    atualizaListaGerenciarApres();
})

function atualizaListaGerenciarApres(){
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
    else if($('#ordemlocal').prop('checked')==true){
        ordem = $('#ordemlocal').val();
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
    idsapresext = [];
    idsapresint = [];
    $.ajax({
        url: 'ajax/consultas/cim_busca_gerenciar_apresentacoes.php',
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
    adicionaEventoCheck();
}

function adicionaEventoPesquisaGerApres(){
    let seletores = [];
    seletores.push(['#datainicio','enter'])
    seletores.push(['#datafinal','enter'])
    seletores.push(['#ordemmatricula','change'])
    seletores.push(['#ordemnome','change'])
    seletores.push(['#ordemdata','change'])
    seletores.push(['#ordemlocal','change'])
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
                atualizaListaGerenciarApres(); 
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    atualizaListaGerenciarApres();
                }
            })
        }
    });
}

function adicionaEventoCheck(){
    var checks = tabela.find('input:checkbox');

    for(var i=0;i<checks.length;i++){
        let check = $('#'+checks[i].id);
        let idapres = check.data('idapres');
        let tab = check.data('tabela');

        check.on('change', ()=>{
            if(check.prop('checked')==true){
                if(tab==1){
                    idsapresext.push(idapres); 
                }else if(tab==2){
                    idsapresint.push(idapres); 
                }
            }else{
                if(tab==1){
                    idsapresext = idsapresext.filter((item)=>item!=idapres); 
                }else if(tab==2){
                    idsapresint = idsapresint.filter((item)=>item!=idapres); 
                }
            }
        })
    }
}

$('#checkall').click(()=>{
    var checks = tabela.find('input:checkbox');
    var bln = $('#checkall').prop('checked');

    for(var i=0;i<checks.length;i++){
        var check = $('#'+checks[i].id);
        check.prop('checked', bln).trigger('change');
    }
})

$('#imp_oficio').click(function(){
    if(idsapresext.length>0 || idsapresint.length>0){
        
        for(let i=0;i<2;i++){
            let array = [];
            let query = 0;

            if(i==0){
                array = idsapresext;
                query = 2;
            }else{
                array = idsapresint;
                query = 3;
            }
            array.forEach(idapres => {
                let informacoes = [
                    {get:'documento',valor:['oficio_apresentacao']},
                    {get:'apresentacoes',valor:[idapres]},
                    {get:'opcaocabecalho',valor:[2]},
                    {get:'query',valor:[query]}
                ];
                imprimirDocumentos(informacoes);
            });
        }
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhuma apresentação foi selecionada</li>')
    }

})

$('#imp_escolta').click(function(){
    
    if(idsapresext.length>0){
        let dados = {
            tipo:2,
            idsapresext: idsapresext
        };

        $.ajax({
            url: 'ajax/consultas/cim_busca_gerenciar_apresentacoes.php',
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
                        {get:'query',valor:[2]}
                    ];
                    imprimirDocumentos(informacoes);
                });
            }
        });
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhuma apresentação foi selecionada</li>')
    }

})

$('#imp_ordem').click(function(){
    
    if(idsapresext.length>0){
        let dados = {
            tipo:2,
            idsapresext: idsapresext
        };

        $.ajax({
            url: 'ajax/consultas/cim_busca_gerenciar_apresentacoes.php',
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
                        {get:'query',valor:[2]}
                    ];
                    imprimirDocumentos(informacoes);
                });
            }
        });
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhuma apresentação foi selecionada</li>')
    }

})

adicionaEventoPesquisaGerApres();

atualizaListaGerenciarApres();
$('#datainicio').focus();