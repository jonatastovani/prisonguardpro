const tabela = $('#table-atend').find('tbody');
let idvisu = 0;

atualizaListagemComum('buscas_comuns',{tipo: 27, idgrupo:1},0,$('#selecttipoatend'),false,false,false,false,true,'Todos Atendimentos');

function atualizaListaGerAtendGerais(){

    tabela.html('');
    let datainicio = $('#datainicio').val();
    let datafinal = $('#datafinal').val();
    let idtipoatend = $('#selecttipoatend').val();
    
    let dados = {
        tipo: 10,
        datainicio: datainicio,
        datafinal: datafinal,
        idtipoatend: idtipoatend
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
        // console.log(result);

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(dados => {
                let idmovimentacao = dados.IDMOVIMENTACAO;
                let dataatend = 'Não agendado';
                if(dados.DATAATEND!=null){
                    dataatend = retornaDadosDataHora(dados.DATAATEND,12);
                }
                let requisitante = dados.REQUISITANTE;
                let tipoatend = dados.TIPOATEND;
                let quantidade = dados.QUANTIDADE;

                let novoID = gerarID('.tratend');

                let linha = '<tr id="tr'+novoID+'" class="tratend cor-fundo-comum-tr"><td class="centralizado tdbotoes" style="min-width: 50px;"></td><td style="min-width: 140px;" class="centralizado">'+dataatend+'</td><td class="min-width-250 max-width-450">'+requisitante+'</td><td class="min-width-350 max-width-450">'+tipoatend+'</td><td class="centralizado">'+quantidade+'</td></tr>';
                tabela.append(linha);

                inserirBotaoAlterarAtendGerais($('#tr'+novoID).find('.tdbotoes'),dados.IDMOVIMENTACAO,2)
            });
        }
    });
}

function verificaBotoesAcao(tr){
    let tdbotoes = tr.find('.tdbotoes');
    let idtabela = tr.data('tabela');
    let idmov = tr.data('idmov');
    let botoesexcluir = [];

    //Atendimentos Gerais(6)
    //Botão para alterar o atendimento
    botoesexcluir = botoesexcluir.filter((item)=>item!='.btnaltatend');
}

function adicionaEventoPesquisaGerAtendGerais(){
    let seletores = [];
    seletores.push(['#pesquisar','click']);
    seletores.push(['#selecttipoatend','change'])

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                atualizaListaGerAtendGerais();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    atualizaListaGerAtendGerais();
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                atualizaListaGerAtendGerais();
            })
        }
    });
}

function inserirBotaoPopulacaoRaio(){
    let divbtns = $('#btnspop');
    let btns = divbtns.find('.btnpopraio');
    // divbtns.html('');
    let divspop = $('#divspop');
    // divspop.html('');

    raiosvisualizacao.forEach(raio => {
        let atualizado = false;
        let qtd = raio.TOTAL;
        let nomeexibir = raio.NOMECOMPLETO+' ('+qtd+')';

        for(i=0;i<btns.length;i++){
            let btn = $('#'+btns[i].id);
            let idraio = btn.data('idraio');
            let idpopraio = retornaSomenteNumeros(btn.attr('id'));

            if(idraio==raio.IDRAIO){
                if(btn.html()!=nomeexibir){
                    btn.html(nomeexibir);
                    //Atualiza contagem caso esteja visualizando a contagem do raio
                    let divpop = $('#popraio'+idpopraio);
                    if(divpop.find('.celas').length>0){
                        inserirPopulacaoRaio(divpop, idraio)
                    }
                }
                atualizado = true;
            }
        }

        if(atualizado==false){
            let novoID = gerarID('.btnpopraio');
            if(raio.TOTAL==1){
                qtd += ' preso';
            }else if(raio.TOTAL>1){
                qtd += ' presos';
            }else{
                qtd = 'vazio';
            }
            divbtns.append('<button id="btnpopraio'+novoID+'" class="btnpopraio" data-idraio="'+raio.IDRAIO+'">'+nomeexibir+'</button>');
            divspop.append('<div id="popraio'+novoID+'" class="popraio flex"></div>');
            eventoVisibilidadePopulacaoRaio(novoID, raio.IDRAIO);
        }
    });
}

function eventoVisibilidadePopulacaoRaio(idpopraio, idraio){

    let botao = $('#btnpopraio'+idpopraio);
    let divpop = $('#popraio'+idpopraio);

    botao.click(()=>{
        if(divpop.hasClass('visibilidadepop')==true){
            fecharPopulacaoRaio();
        }else{
            fecharPopulacaoRaio();
            divpop.addClass('visibilidadepop');
            inserirPopulacaoRaio(divpop, idraio);
        }
    })
}

function fecharPopulacaoRaio(){
    let divpop = $('.visibilidadepop');
    if(divpop.length>0){
        let celas = divpop.find('.celas');
        let milisec = 100;
        for(let i=celas.length-1;i>-1;i--){
            let cela = $('#'+celas[i].id);
            setTimeout(() => {
                cela.removeClass('active');
            }, milisec);
            //Caso queira que vá se excluindo as div das celas
            setTimeout(() => {
                cela.remove();
            }, milisec+500);
            milisec += 50;
        }
        setTimeout(() => {
            divpop.removeClass('visibilidadepop');
        }, milisec);
    }
}

function inserirPopulacaoRaio(divpop, idraio){
    
    divpop.html('');

    let dados = {
        tipo: 7,
        idraio: idraio
    };

    $.ajax({
        url: 'ajax/consultas/chefia_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async:false
    }).done(function(result){
        //console.log(result);
    
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            divpop.removeClass('visibilidadepop');
        }else{
            let qtdcelas = result[0].QTDCELAS;
            let raio = result[0].RAIO;
            let milisec = 100;

           for(let i=0; i<qtdcelas;i++){
                let qtd = 0;
                let numcela = i+1;
                
                result.forEach(dados => {
                    if(dados.CELA==numcela){
                        qtd = dados.QTD;
                    }
                });

                let novoID = gerarID('.book');
                
                divpop.append('<div id="cela'+novoID+'" class="centralizado celas">'+raio+'/'+numcela+' <hr> '+qtd+' <br> <button id="book'+novoID+'" class="book" data-idraio="'+idraio+'" data-cela="'+numcela+'">Book</button></div>');

                setTimeout(() => {
                    $('#cela'+novoID).addClass('active');
                }, milisec);
                milisec +=75;

                abrirBook($('#book'+novoID),idraio,numcela,divpop)
           }
        }
    });
}

function abrirBook(botao,idraio,cela,divpop){
    botao.click(()=>{
        divpopocultarpopbook = divpop;
        idraiopopbook = idraio;
        celapopbook = cela;
        abrirPopBook();
    })
}

function atualizaListagemVisualizacao(){
    idvisu = $('#selectvisu').val();
    raiosvisualizacao = [];
    //idraiosvisualizacao = [];

    if(idvisu!=undefined && idvisu!=null){
        let dados = {
            tipo: 2,
            idvisualizacao: idvisu,
            blnvisuchefia: true
        }
       //console.log(dados);
    
        $.ajax({
            url: 'ajax/consultas/chefia_busca_gerenciar.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async:false
        }).done(function(result){
            //console.log(result);
    
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                result.forEach(linha => {
                    raiosvisualizacao.push({IDRAIO: linha.VALOR, NOME: linha.NOMEEXIBIR, NOMECOMPLETO: linha.NOMECOMPLETO, TOTAL: linha.TOTAL})
                });  
            }
        });    
    }
    inserirBotaoPopulacaoRaio();
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

$('#ordemmatricula').click(()=>{
    ordemregistros = $('#ordemmatricula').val();
})

$('#ordemnome').click(()=>{
    ordemregistros = $('#ordemnome').val();
})

$('#ordemhorario').click(()=>{
    ordemregistros = $('#ordemhorario').val();
})

adicionaEventoPesquisaGerAtendGerais();
atualizaListaGerAtendGerais();
