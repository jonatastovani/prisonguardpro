const tabela = $('#table-mov-gerenciar').find('tbody');
//Array do nomes dos raios que pertencem a visualizacao
let raiosvisualizacao = [];
//Array dos selecionados (para impressão e coisas do tipo)
let listacheck = [];
//Registros que restarem nesta listagem serão exclusos no final da verificação dos dados da tabela
let registrosexcluir = [];
let ordemregistros = 3;
//id de visualização do gerenciar raio
let idvisu = 0;
let arrregistros = [];

atualizaListagemComum('buscas_comuns',{tipo:26},0,$('#selectvisu'),false,true,'change',false,true,"Todas visualizações");

function atualizaListaGerChefia(){
    atualizaListagemVisualizacao();

    //id de visualizacao (variável do popnovamud)
    idvisu = $('#selectvisu').val();
    consultando = true;
    
    let exibir = 0;
    let emaberto = $('#emaberto');
    if(emaberto.prop('checked')==true && emaberto != undefined){
        exibir = 1;
    }
    let encerrado = $('#encerrado');
    if(encerrado.prop('checked')==true && encerrado != undefined){
        exibir += 2;
    }
    if(exibir==0){
        exibir = 1;
    }
    
    let dados = {
        tipo: 1,
        ordem: ordemregistros,
        idvisualizacao: idvisu,
        blnvisuchefia: 1,
        exibir: exibir
    }
    
   console.log(dados);

    $.ajax({
        url: 'ajax/consultas/chefia_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        console.log(result);

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            let trs = tabela.find('tr');
            registrosexcluir = [];
            // Adiciona todos os tr que estão na tabela na lista de excluir registros
            for(let i=0;i<trs.length;i++){
                registrosexcluir.push(trs[i].id);
            };
            let arrayencerrado = [6,7,8,9,13,15,19]; //ids das situações de encerrado;

            result.forEach(dados => {
                let raioatual = dados.RAIOATUAL;
                let raiodestino = dados.RAIODESTINO;
                let idsituacao = dados.IDSITUACAO;
                // Situação do registro (Se já está encerrado ou ainda tem coisas para fazer)
                let blnabertoencerrado = false;

                if((arrayencerrado.includes(idsituacao) && [2,3].includes(exibir)) || (arrayencerrado.includes(idsituacao)==false && [1,3].includes(exibir))){
                    blnabertoencerrado = true;
                }

                //Filtra os registros que serão exibidos na tela conforme a visualização informada
                if((idvisu==0 && blnabertoencerrado==true) || ((raiosvisualizacao.findIndex(x => x.NOME == raioatual) > -1 || raiosvisualizacao.findIndex(x => x.NOME == raiodestino) > -1 && raiodestino!=null) && blnabertoencerrado==true)){
                    let tr = verificaRegistrosGerRaio(dados);
                    let dadosbotoes = {
                        tr:tr,
                        idsituacao:idsituacao,
                        desig: parseInt(dados.DESIG),
                        idmov: dados.IDMOVIMENTACAO,
                        idmudanca: dados.IDMUDANCA
                    }
                    verificaBotoesAcao(dadosbotoes);
                }

            });
            //Remove os registros que não existem mais
            for(let i=0;i<registrosexcluir.length;i++){
                listacheck = listacheck.filter((item)=>item.idtr!=registrosexcluir[i]);
                $('#'+registrosexcluir[i]).remove();
            }
        }
        consultando = false;
    });
}


//Verifica se o registro já existe e atualiza os dados. Se não existir então é inserido
function verificaRegistrosGerRaio(dados){
    let idmovimentacao = dados.IDMOVIMENTACAO;
    let idtabela = dados.TABELA;
    let nome = dados.NOME;
    let matricula = dados.MATRICULA;
    let horario = dados.HORARIO;
    let raioatual = dados.RAIOATUAL;
    let raiodestino = dados.RAIODESTINO;
    let raiocelaatual = dados.RAIOCELAATUAL;
    let destino = dados.DESTINO;
    let tipo = dados.TIPO;
    let situacao = dados.SITUACAO;
    let cor = dados.COR;
    let aprovado = dados.APROVADO;
    let ordemreg = dados.ORDEMREG;
    let idmudanca = parseInt(dados.IDMUDANCA);
    let desig = parseInt(dados.DESIG);

    let recebmud = true;
    // if(raiosvisualizacao.findIndex(x => x.NOME == raiodestino)>-1 && raiodestino!=null){
    //     //True caso o destino é o raio que está com a visualização (casos de mudança de cela)
    //     recebmud = true;
    // }
    
    //Verificar o raio origem e o destino para ver se exibe aqui ou não
    let registros = tabela.find('tr');
    for(let i=0;i<registros.length;i++){
        let tr = $('#'+registros[i].id);
        let idmov = tr.data('idmov');
        let idtab = tr.data('tabela');
        if(idmov==idmovimentacao && idtab==idtabela){
            //Se existir então se verifica se os dados precisam ser atualizados;
            if(matricula!=tr.find('.tdmatricula').html()){
                tr.find('.tdmatricula').html(matricula);
            }
            if(nome!=tr.find('.tdnome').html()){
                tr.find('.tdnome').html(nome);
            }
            if(horario!=tr.find('.tdhorario').html()){
                tr.find('.tdhorario').html(horario);
            }
            if(raiocelaatual!=tr.find('.tdraioatual').html()){
                tr.find('.tdraioatual').html(raiocelaatual);
            }
            if(destino!=tr.find('.tddestino').html()){
                tr.find('.tddestino').html(destino);
            }
            if(tipo!=tr.find('.tdtipo').html()){
                tr.find('.tdtipo').html(tipo);
            }
            if(situacao!=tr.find('.tdsituacao').html()){
                tr.find('.tdsituacao').html(situacao);
            }
            if(cor!=tr.data('cor')){
                tr.data('cor',cor);
            }
            if(aprovado!=tr.data('aprov')){
                tr.data('aprov',aprovado);
            }
            if(idmudanca!=tr.data('idmudanca')){
                tr.data('idmudanca',idmudanca);
            }
            if(desig!=tr.data('desig')){
                tr.data('desig',desig);
            }
            //alteraCorFundoTr(tr);
            //Remove da lista o id verificado
            registrosexcluir = registrosexcluir.filter((item)=>item!=registros[i].id); 
            return tr;
        }
    }

    let novoID = gerarID('.trraio');
    //<td><input type="checkbox" id="check'+novoID+'" class="ckbraio"></input></td>

    let linha = '<tr id="tr'+novoID+'" class="trraio '+cor+'" data-idmov="'+idmovimentacao+'" data-tabela="'+idtabela+'" data-cor="'+cor+'" data-aprov="'+aprovado+'" data-ordemreg="'+ordemreg+'" data-recebmud="'+recebmud+'" data-idmudanca="'+idmudanca+'" data-desig="'+desig+'"><td class="tdcheck"><input type="checkbox" id = "ckb'+novoID+'"></td><td class="centralizado tdbotoes nowrap" style="min-width: 50px;"></td><td class="centralizado min-width-100 tdmatricula">'+matricula+'</td><td class="min-width-350 max-width-450 tdnome">'+nome+'</td><td class="centralizado tdhorario">'+horario+'</td><td class="centralizado tdraioatual">'+raiocelaatual+'</td><td class="centralizado tddestino">'+destino+'</td><td class="min-width-250 max-width-350 tdtipo">'+tipo+'</td><td class="min-width-250 max-width-350 tdsituacao">'+situacao+'</td></tr>';
    
    tabela.append(linha);
    arrregistros.push({
        tr: $('#tr'+novoID),
        idtr: 'tr'+novoID,
        cor: cor,
        idmov: idmovimentacao,
        idtabela: idtabela,
        aprov: aprovado,
        ordemreg: ordemreg,
        recebmud: recebmud,
        idmudanca: idmudanca,
        desig: desig,
        nomepreso: nome,
        matricula: matricula,
    })

    let dadoscheck = {
        idtr: 'tr'+novoID,
        idtabela: idtabela,
        idmov: idmovimentacao,
        idmudanca: idmudanca,
        desig: desig
    }

    adicionaEventoCheck(dadoscheck);

    return $('#tr'+novoID);
}

//Altera a cor do registro caso estiver com status de Aprovado
function alteraCorFundoTr(tr){
    let corfundo = tr.data('cor');
    let aprov = tr.data('aprov');
    let classpadrao = "trraio"; //Valores padrão da classe do tr;
    if(tr.closest('.'+corfundo).length>0 && aprov==true){
        tr.removeClass(corfundo);
        tr.addClass('cor-aprovado');
    }else{
        tr.attr('class',classpadrao+' '+corfundo);
        //tr.addClass(corfundo);
    }
}

function verificaBotoesAcao(dados){
    let tr = dados.tr;
    let idsituacao = dados.idsituacao;
    let desig = dados.desig;
    let tdbotoes = tr.find('.tdbotoes');
    let idtabela = tr.data('tabela');
    let idmov = tr.data('idmov');
    let botoesexcluir = [];
    // console.log(dados)

    // Mudança de cela
    if(idtabela==1){
        let recebmud = tr.data('recebmud');
        botoesexcluir = [
            '.btnaltmudcel',
            '.btncanc',
            '.btnrealiz',
            '.btnneg',
            '.btnaprov',
            '.btndesig'
        ]

        if(recebmud==true){
            if(idsituacao!=6){
                //Exibe sempre botão de alterar a mudança
                inserirBotaoAlterarMudanca(tdbotoes,idmov)
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btnaltmudcel');
                
                if(idsituacao==4){
                    //Botão para aprovar a mudança
                    inserirBotaoAprovado(tdbotoes,idmov,2,1,5)
                    botoesexcluir = botoesexcluir.filter((item)=>item!='.btnaprov');
                }

                if(idsituacao==3 || idsituacao==4){
                    //Botão para negar e excluir a mudança de cela solicitada
                    inserirBotaoNegarMudanca(tdbotoes,idmov,2,1)
                    botoesexcluir = botoesexcluir.filter((item)=>item!='.btnneg');
                }
                
                if(idsituacao==5){
                    //Se estiver aprovado então se exibe somente o botão de confirmar
                    inserirBotaoRealizado(tdbotoes,idmov,2,1,6,'Confirma a alteração de cela?\r\rEsta ação não poderá ser desfeita.','Confirmar Mudança')
                    botoesexcluir = botoesexcluir.filter((item)=>item!='.btnrealiz');
                
                    //Se estiver aprovado então se exibe somente o botão de cancelar autorização de mudança
                    inserirBotaoCancelar(tdbotoes,idmov,2,1,9)
                    botoesexcluir = botoesexcluir.filter((item)=>item!='.btncanc');
                }
            }

            if(desig==1){
                let arr = {
                    tdbotoes: tdbotoes,
                    idmov: idmov,
                    idtabela: idtabela,
                    idmudanca: dados.idmudanca
                }
                inserirBotaoImprimirDesignacao(arr);
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btndesig');
            }
        }
    }
    //Transferências(2), Apresentações Externas(3) e Exclusões (7)
    else if(idtabela==2 || idtabela==3 || idtabela==7){
        
        botoesexcluir = [
            '.btnencam',
            '.btnencamfora',
            '.btnrealiz',
            '.btncanc',
            '.btnaprov',
            '.btnalttime',
            '.btndesig'
        ]
        let tab = 0;
        if(idtabela==2){
            tab=3;
        }else if(idtabela==3){
            tab=4;
        }else if(idtabela==7){
            tab=8;
        }

        if(idsituacao==11 || idsituacao==16){
            //Botão para aprovar a movimentação ou atendimento
            inserirBotaoAprovado(tdbotoes,idmov,tab,1)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnaprov');
        }

        if(idsituacao==12){
            //Se estiver Aprovado então se exibe somente o botão de saida para atendimento ou movimentação e o botão de cancelar autorização
            let arr = {
                tdbotoes:tdbotoes,
                idmov:idmov,
                tab:tab,
                blnvisuchefia:1,
                idsituacao:18,
                strpergunta:'Confirma o encaminhamento ao Desembarque Interno?',
                title:'Encaminhado ao Desen. Interno'
            }
            inserirBotaoEncaminhado(arr)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnencam');

            inserirBotaoCancelar(tdbotoes,idmov,tab,1)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btncanc');
        
        }

        if(idsituacao==18){
            //Se estiver no Desembarque Interno então se exibe botão de cancelar autorização para o preso retornar ao raio e o de encaminhado ao atendimento (caso for apresentação externa)
            if(idtabela==2 || idtabela==7){
                inserirBotaoRealizado(tdbotoes,idmov,tab,1)
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btnrealiz');
            
            }else if(idtabela==3){
                //Se estiver Aprovado então se exibe somente o botão de saida para atendimento
                let arr = {
                    tdbotoes:tdbotoes,
                    idmov:idmov,
                    tab:tab,
                    blnvisuchefia:1,
                    idsituacao:17,
                    strpergunta:'Confirma o encaminhamento ao Atendimento Externo?',
                    title:'Encaminhado ao Atendimento Externo',
                    botao:'btnencamfora'
                }
                inserirBotaoEncaminhado(arr)
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btnencamfora');
            }

            inserirBotaoCancelar(tdbotoes,idmov,tab,1)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btncanc');
        }

        if([11,16].includes(idsituacao)){
            //Se estiver no Desembarque Interno então se exibe botão de cancelar autorização para o preso retornar ao raio e o de encaminhado ao atendimento (caso for apresentação externa)
            if(idtabela==7){
                inserirBotaoAlterarHorario(tdbotoes,idmov,tab);
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btnalttime');
            }
        }
        
        if(desig==1){
            let arr = {
                tdbotoes: tdbotoes,
                idmov: idmov,
                idtabela: idtabela,
                idmudanca: dados.idmudanca
            }
            inserirBotaoImprimirDesignacao(arr);
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btndesig');
        }

    }
    //Apresentações Internas(4), Atendimentos Enfermaria(5), Atendimentos Gerais(6)
    else if(idtabela==4 || idtabela==5 || idtabela==6){
        botoesexcluir = [
            '.btnaltatend',
            '.btnencam',
            '.btnrealiz',
            '.btncanc',
            '.btnaprov',
            '.btnalttime'
        ]
        let tab = 0;
        if(idtabela==4){
            tab=5;
        }else if(idtabela==5){
            tab=6;
        }else if(idtabela==6){
            tab=7;
        }

        if(idtabela==6 && idsituacao!=13){
            //Botão para alterar o atendimento
            inserirBotaoAlterarAtendGerais(tdbotoes,idmov)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnaltatend');
        }
        if([11,16].includes(idsituacao)){
            //Botão para aprovar a movimentação ou atendimento
            inserirBotaoAprovado(tdbotoes,idmov,tab,1)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnaprov');
        }
        if(idsituacao==12){
            //Se estiver Aprovado então se exibe somente o botão de saida para atendimento ou movimentação e o botão de cancelar autorização
            let arr = {
                tdbotoes:tdbotoes,
                idmov:idmov,
                tab:tab,
                blnvisuchefia:1
            }
            inserirBotaoEncaminhado(arr)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnencam');

            inserirBotaoCancelar(tdbotoes,idmov,tab,1)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btncanc');
        }
        if(idsituacao==17){
            //Se estiver em atendimento se exibe o botão de retorno ao pavilhão (realizado)
            inserirBotaoRetornoPavilhao(tdbotoes,idmov,tab,1);
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnrealiz');
        }
        if([11,16].includes(idsituacao) && [5,6].includes(idtabela)){
            //Se estiver no Desembarque Interno então se exibe botão de cancelar autorização para o preso retornar ao raio e o de encaminhado ao atendimento (caso for apresentação externa)
            if(idtabela==7){
                inserirBotaoAlterarHorario(tdbotoes,idmov,tab);
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btnalttime');
            }
        }

    }

    botoesexcluir.forEach(seletor => {
        tdbotoes.find(seletor).remove();
    });
}

function adicionaEventoPesquisaGerMov(){
    let seletores = [];
    seletores.push(['#ordemmatricula','click']);
    seletores.push(['#ordemnome','click']);
    seletores.push(['#ordemhorario','click']);
    seletores.push(['#selectvisu','change'])
    seletores.push(['#organizar-gerenciar','click'])

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                timer=false;
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    timer=false;
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                timer=false;
            })
        }
    });
}

$('#btnabrirbtnspop').click(()=>{
    let btnspop = $('#btnspop');
    if(btnspop.attr('hidden')=='hidden'){
        btnspop.removeAttr('hidden');
    }else{
        btnspop.attr('hidden','hidden');
        fecharPopulacaoRaio();
    }
})

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

    if(idvisu!=undefined && idvisu!=null){
        let dados = {
            tipo: 2,
            idvisualizacao: idvisu,
            blnvisuchefia: 1
        }
    //    console.log(dados);
    
        $.ajax({
            url: 'ajax/consultas/chefia_busca_gerenciar.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async:false
        }).done(function(result){
            // console.log(result);
    
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

function adicionaEventoCheck(dados){
    let tr = $('#'+dados.idtr);
    let ckb = tr.find('input:checkbox');

    ckb.on('change', ()=>{
        if(ckb.prop('checked')==true){
            listacheck.push(dados);
        }else{
            listacheck = listacheck.filter((item)=>item.idtr!=dados.idtr);
        }
        console.log(listacheck);
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

$('#ordemmatricula').click(()=>{
    ordemregistros = $('#ordemmatricula').val();
})

$('#ordemnome').click(()=>{
    ordemregistros = $('#ordemnome').val();
})

$('#ordemhorario').click(()=>{
    ordemregistros = $('#ordemhorario').val();
})

$('#imp_req').click(()=>{
    if(listacheck.length>0){
        let idsmov = [];
        let idstabela = [];

        listacheck.sort(function (x,y){
            return x.idtabela - y.idtabela;
        })

        listacheck.forEach(mov => {
            idsmov.push(mov.idmov);
            idstabela.push(mov.idtabela);
        });

        //Duas vezes para imprimir a requisição e a listagem
        for(let i=1;i<3;i++){
            let informacoes = [
                {get:'documento',valor:['imp_chef_requisicao']},
                {get:'idsmov',valor:idsmov},
                {get:'idstabela',valor:idstabela},
                {get:'tiporeq',valor:[i]},
                {get:'opcaocabecalho',valor:[5]}
            ];
        
            // console.log(informacoes)
        
            imprimirDocumentos(informacoes);            
        }
        
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhuma movimentação foi selecionada </li>');
    }
})

$('#imp_desig').click(()=>{
    if(listacheck.length>0){
        let idsmudanca = [];

        listacheck.forEach(mov => {
            // if(mov.desig==1){
                idsmudanca.push(mov.idmudanca);
            // }
        });

        if(idsmudanca.length>0){
            //Duas vezes para imprimir a requisição e a listagem
            let informacoes = [
                {get:'documento',valor:['imp_chef_designacao']},
                {get:'idsmudanca',valor:idsmudanca},
                {get:'opcaocabecalho',valor:[6]}
            ];
        
            // console.log(informacoes)
        
            imprimirDocumentos(informacoes);            
        
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Nenhuma das movimentação consiste em Designação de Trabalho para Remissão </li>');
        }
        
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhuma movimentação foi selecionada </li>');
    }
})

// let contadorsemcela = 0
setInterval(() => {
    // contadorsemcela++;
    // if((contadorsemcela/5)==parseInt((contadorsemcela/5))){
        let result = consultaBanco('busca_presos',{tipo:8});
        // console.log(result)
        if(result.length){
            inserirBotaoPresosSemCela($('#botoesferramentas'),2);
        }else{
            $('#botoesferramentas').find('.btnsemcela').remove();
        }
    // }

    let btnsemcela = $('#botoesferramentas').find('.btnsemcela');
    if(btnsemcela.length){
        if(btnsemcela.hasClass('cor-amarelo')){
            btnsemcela.removeClass('cor-amarelo');
        }else{
            btnsemcela.addClass('cor-amarelo');
        }
    }
}, 1000);

let timer = true;
let consultando = false;
setInterval(() => {
    if(timer==false){
        //Limpa todos registros e efetua a busca para inserir os registro na ordenação escolhida
        if(consultando==false){
            listacheck=[];
            tabela.html('');
            timer = true
        }
    }
    if(consultando==false){
        atualizaListaGerChefia();
    }
}, 1000);

setInterval(() => {
    let trs = tabela.find('tr');
    for(let i=0;i<trs.length;i++){
        alteraCorFundoTr($('#'+trs[i].id));
    }
}, 1000);
adicionaEventoPesquisaGerMov();