var blnrolvisu = 1;
const tabela = $('#table-assistidos-gerenciar').find('tbody');
//Array de ids de movimentações para envio
let arrselecionados = [];
let arrconsulta = [];
let situacao = 0;
let ordem = 3;
let opcaobusca = 1;
let buscatexto = 1;
let periodo = 0;

function adicionaEventoOpcoesGerenciarAssistidos(){
    let seletores = [];
    seletores.push(['#pendente',1])
    seletores.push(['#entregue',1])
    seletores.push(['#todos',1])
    seletores.push(['#ordemmatricula',2])
    seletores.push(['#ordemnome',2])
    seletores.push(['#ordemraio',2])
    seletores.push(['#dividirtexto',3])
    seletores.push(['#todotexto',3])
    seletores.push(['#buscaparte',4])
    seletores.push(['#buscaexata',4])
    seletores.push(['#buscainicio',4])
    seletores.push(['#buscafinal',4])
    seletores.push(['#periodomanha',5])
    seletores.push(['#periodotarde',5])
    seletores.push(['#periodonoite',5])
    seletores.push(['#periodotodos',5])

    seletores.forEach(linha => {
        let valor = 0;
        $(linha[0]).on('click', (e)=>{
            valor = $('#'+e.target.id).val();
            valor = parseInt(valor);

            if(linha[1]==1){
                situacao = valor;
            }else if(linha[1]==2){
                ordem = valor;
            }else if(linha[1]==3){
                opcaobusca = valor;
            }else if(linha[1]==4){
                buscatexto = valor;
            }else if(linha[1]==5){
                periodo = valor;
            }
        })
    });
}

function atualizaListaGerenciarAssistidos(){

    elementoVerificar = $('#datainicio')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        elementoVerificar.focus()
        mensagem = ("<li class = 'mensagem-aviso'> Data início inválida! </li>")
        inserirMensagemTela(mensagem)
        clearInterval(timer);
        tabela.html('');
        return;
    }

    elementoVerificar = $('#datafinal')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Data final inválida! </li>")
        inserirMensagemTela(mensagem)
        clearInterval(timer);
        tabela.html('');
        return;
    }

    let datainicio = $('#datainicio').val();
    let datafinal = $('#datafinal').val();
    let textobusca = $('#textobusca').val().trim();

    let dados = {
        tipo: 5,
        datainicio: datainicio,
        datafinal: datafinal,
        situacao: situacao,
        ordem: ordem,
        buscatexto: buscatexto,
        opcaobusca: opcaobusca,
        textobusca: textobusca,
        periodo: periodo
    }

    // console.log(dados);
    tabela.html('');
    arrconsulta = [];
    arrselecionados = [];
    $('#checkall').prop('checked',false);

    $.ajax({
        url: 'ajax/consultas/saude_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result);
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            // clearInterval(timer);
            tabela.html('');
        }else{

            let novoID = '';
            let idperiodo = 0;
            let idpreso = 0;
            let idperiodolinha = 0;
            let idpresolinha = 0;
            let data = 0;
            let datalinha = 0;
            let titlemedic = '';

            result.forEach(linha => {
                idperiodo = linha.IDPERIODO;
                idpreso = linha.IDPRESO;
                if(linha.DATAENTREGUE!=null){
                    data = retornaDadosDataHora(linha.DATAENTREGUE,2);
                }else{
                    data = null;
                }

                if(idperiodo!=idperiodolinha || idpreso!=idpresolinha || data!=datalinha){

                    if(novoID!=''){
                        $('#'+novoID).attr('title',titlemedic);
                    }

                    idperiodolinha = linha.IDPERIODO;
                    idpresolinha = linha.IDPRESO;
                    
                    novoID = 'trgerass'+gerarID('.trgerass');
                    
                    let matricula = midMatricula(linha.MATRICULA,3);
                    let dataentregue = '*******';
                    if(linha.DATAENTREGUE!=null){
                        dataentregue = retornaDadosDataHora(linha.DATAENTREGUE,2);
                        datalinha = dataentregue;
                    }else{
                        datalinha = null;
                    }
                    let raiocelaatual = linha.RAIO+'/'+linha.CELA;
                    let qtdmed = linha.QTDMED;
                    qtdmed += linha.QTDMED>1?' medicamentos':' medicamento';

                    let tr = '<tr id="'+novoID+'" class="trgerass '+linha.COR+' nowrap" data-idpreso="'+linha.IDPRESO+'" data-cor="'+linha.COR+'"><td><input type="checkbox" id="ckb'+novoID+'" class="ckb"></td><td class="centralizado tdbotoes nowrap" style="min-width: 70px;"></td><td class="centralizado min-width-100 tdmatricula">'+matricula+'</td><td class="tdnome min-width-200 max-width-450">'+linha.NOME+'</td><td class="centralizado tdraioatual">'+raiocelaatual+'</td><td class="tdperiodo">'+linha.PERIODO+'</td><td class="centralizado nowrap qtdmed">'+qtdmed+'</td><td class="centralizado nowrap dataentregue">'+dataentregue+'</td></tr>';

                    tabela.append(tr);

                    arrconsulta.push({
                        idtr:novoID,
                        idpreso:linha.IDPRESO,
                        idperiodo:linha.IDPERIODO,
                        periodoentrega:linha.PERIODO,
                        idraio:linha.IDRAIO,
                        raio: linha.RAIO,
                        cela: linha.CELA,
                        cor: linha.COR,
                        qtd: linha.QTDMED
                    });

                    //Limpa o titlemedic para começar a nova lista de medicamentos
                    titlemedic='';

                    if(linha.DATAENTREGUE==null){
                        let dados = {
                            tipo:5,
                            arrentregar: [{
                                idpreso:linha.IDPRESO,
                                idperiodo:linha.IDPERIODO
                            }],
                            idtipo: 2
                        }
                        inserirBotaoEntregarAssistido($('#'+novoID).find('.tdbotoes'),dados);
                    }

                    let dadosvisu = {
                        idpreso:linha.IDPRESO,
                        idperiodo:linha.IDPERIODO,
                        data:linha.DATAENTREGUE
                    }
                    inserirBotaoVisualizarAssistido($('#'+novoID).find('.tdbotoes'),dadosvisu);
                    inserirBotaoEditarAssistido($('#'+novoID).find('.tdbotoes'),dadosvisu);
                    adicionaEventoCheckGerenciarAssistidos(novoID,linha.ORDEM);
                }

                titlemedic += titlemedic==''?'':'\r';
                titlemedic += linha.NOMEMEDICAMENTO+' - '+linha.QTDENTREGA+' '+linha.UNIDADEFORN;
            });

            if(novoID!=''){
                $('#'+novoID).attr('title',titlemedic);
            }

        }
    });
}

function adicionaEventoPesquisaGerenciarAssistidos(){
    let seletores = [];
    seletores.push(['#pesquisar-gerenciar','click'])
    seletores.push(['#datainicio','enter'])
    seletores.push(['#datafinal','enter'])
    seletores.push(['#ordemmatricula','change'])
    seletores.push(['#ordemnome','change'])
    seletores.push(['#ordemraio','change'])
    seletores.push(['#textobusca','enter'])
    seletores.push(['#dividirtexto','change'])
    seletores.push(['#todotexto','change'])
    seletores.push(['#buscaparte','change'])
    seletores.push(['#buscaexata','change'])
    seletores.push(['#buscainicio','change'])
    seletores.push(['#buscafinal','change'])
    seletores.push(['#pendente','change'])
    seletores.push(['#entregue','change'])
    seletores.push(['#todos','change'])
    seletores.push(['#periodomanha','change'])
    seletores.push(['#periodotarde','change'])
    seletores.push(['#periodonoite','change'])
    seletores.push(['#periodotodos','change'])

    seletores.forEach(linha => {
        if(['click','change'].includes(linha[1])){
            $(linha[0]).on(linha[1], (e)=>{
                blnLimparConsulta=true;
                iniciaConsultaTimer();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    blnLimparConsulta=true;
                    iniciaConsultaTimer();
                }
            })
        }
    });
}

function adicionaEventoCheckGerenciarAssistidos(idtr,idtipo){
    let check = $('#'+idtr).find('.ckb');

    check.change(()=>{
        if(check.prop('checked')==true){
            arrselecionados.push({
                idtr: idtr,
                idtipo: idtipo
            }); 
        }else{
            arrselecionados = arrselecionados.filter((item)=>item.idtr!=idtr); 
        }
    })
}

$('#checkall').click(()=>{
    var checks = tabela.find('input:checkbox');
    var bln = $('#checkall').prop('checked');

    for(var i=0;i<checks.length;i++){
        var check = $('#'+checks[i].id);
        check.prop('checked', bln).trigger('change');
    }
})

$('#entsel').click(()=>{
    if(arrselecionados.length>0){
        let arr = [];
        arrselecionados.forEach(sel => {
            if(sel.idtipo==1){
                let index = arrconsulta.findIndex((tr)=>tr.idtr==sel.idtr);
                arr.push({
                    idpreso: arrconsulta[index].idpreso,
                    idperiodo: arrconsulta[index].idperiodo
                });
            }
        });
        if(arr.length>0){
            let dados = {
                tipo:5,
                arrentregar: arr,
                idtipo: 2
            }
            acaoBotaoEntregarAssistido(dados);
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Os itens selecionados não são do tipo para entrega. </li>');
        }
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhum item foi selecionado! </li>');
    }
})

$('#impsel').click(function(){

    if(arrselecionados.length>0){
        let arridpreso = [];
        let arridperiodo = [];
        arrselecionados.forEach(sel => {
            if(sel.idtipo==1){
                let index = arrconsulta.findIndex((tr)=>tr.idtr==sel.idtr);
                arridpreso.push(arrconsulta[index].idpreso);
                arridperiodo.push(arrconsulta[index].idperiodo);
            }
        });

        if(arridpreso.length){
            let informacoes = [
                {get:'documento',valor:['imp_sau_medic_ass_entregar']},
                {get:'idspreso',valor:arridpreso},
                {get:'idsperiodo',valor:arridperiodo},
                {get:'opcaocabecalho',valor:[10]}
            ];

            // console.log(informacoes)

            imprimirDocumentos(informacoes);
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Nenhum dos itens selecionados não são do tipo para entregar. </li>');
        }
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhum item selecionado! </li>')
    }

})

$('#impsel').click(function(){
    let datainicio = $('#datainicio').val();
    let datafinal = $('#datafinal').val();

    if(datainicio!='' && datafinal!=''){
        if(arrselecionados.length>0){
            let arridpreso = [];
            let arridperiodo = [];
            arrselecionados.forEach(sel => {
                if(sel.idtipo==2){
                    let index = arrconsulta.findIndex((tr)=>tr.idtr==sel.idtr);
                    arridpreso.push(arrconsulta[index].idpreso);
                    arridperiodo.push(arrconsulta[index].idperiodo);
                }
            });

            if(arridpreso.length){
                let informacoes = [
                    {get:'documento',valor:['imp_sau_medic_ass_entregues']},
                    // {get:'datainicio',valor:[datainicio]},
                    // {get:'datafinal',valor:[datafinal]},
                    {get:'idspreso',valor:arridpreso},
                    {get:'idsperiodo',valor:arridperiodo},
                    {get:'opcaocabecalho',valor:[10]}
                ];

                // console.log(informacoes)

                imprimirDocumentos(informacoes,'&datainicio='+datainicio+'&datafinal='+datafinal);
            }else{
                inserirMensagemTela('<li class="mensagem-aviso"> Nenhum dos itens selecionados não são do tipo entregue. </li>');
            }
    
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Nenhum item selecionado! </li>')
        }
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Data Início ou final inválida! </li>')
    }
})

$('#implistaassistidos').click(function(){

    let informacoes = [
        {get:'documento',valor:['imp_sau_medic_ass']}
    ];

    // console.log(informacoes)

    imprimirDocumentos(informacoes);

})

adicionaEventoOpcoesGerenciarAssistidos();
adicionaEventoPesquisaGerenciarAssistidos();

$('#textobusca').focus();

let timer;
let blnLimparConsulta = false;

function iniciaConsultaTimer(){
    // clearInterval(timer);
    // timer = setInterval(() => {
        atualizaListaGerenciarAssistidos();
    // }, 500);
};

iniciaConsultaTimer();

