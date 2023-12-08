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

//    console.log(dados);

    $.ajax({
        url: 'ajax/consultas/chefia_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result);

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            
            result.forEach(dados => {

                let index = arrregistros.findIndex((registro)=>registro.idtabela==dados.TABELA && registro.idmov==dados.IDMOVIMENTACAO);

                
                if(index!=-1){
                    if(arrregistros[index].idmov != dados.IDMOVIMENTACAO){
                        arrregistros[index].idmov = dados.IDMOVIMENTACAO;
                    }
                    if(arrregistros[index].idmov != dados.IDMOVIMENTACAO){
                        arrregistros[index].idmov = dados.IDMOVIMENTACAO;
                    }
                    if(arrregistros[index].idtabela != dados.TABELA){
                        arrregistros[index].idtabela = dados.TABELA;
                    }
                    if(arrregistros[index].nome != dados.NOME){
                        arrregistros[index].nome = dados.NOME;
                    }
                    if(arrregistros[index].matricula != dados.MATRICULA){
                        arrregistros[index].matricula = dados.MATRICULA;
                    }
                    if(arrregistros[index].horario != dados.HORARIO){
                        arrregistros[index].horario = dados.HORARIO;
                    }
                    if(arrregistros[index].raioatual != dados.RAIOATUAL){
                        arrregistros[index].raioatual = dados.RAIOATUAL;
                    }
                    if(arrregistros[index].raiodestino != dados.RAIODESTINO){
                        arrregistros[index].raiodestino = dados.RAIODESTINO;
                    }
                    if(arrregistros[index].raiocelaatual != dados.RAIOCELAATUAL){
                        arrregistros[index].raiocelaatual = dados.RAIOCELAATUAL;
                    }
                    if(arrregistros[index].destino != dados.DESTINO){
                        arrregistros[index].destino = dados.DESTINO;
                    }
                    if(arrregistros[index].tipo != dados.TIPO){
                        arrregistros[index].tipo = dados.TIPO;
                    }
                    if(arrregistros[index].idsituacao != dados.IDSITUACAO){
                        arrregistros[index].idsituacao = dados.IDSITUACAO;
                    }
                    if(arrregistros[index].situacao != dados.SITUACAO){
                        arrregistros[index].situacao = dados.SITUACAO;
                    }
                    if(arrregistros[index].cor != dados.COR){
                        arrregistros[index].cor = dados.COR;
                    }
                    if(arrregistros[index].aprovado != dados.APROVADO){
                        arrregistros[index].aprovado = dados.APROVADO;
                    }
                    if(arrregistros[index].ordemreg != dados.ORDEMREG){
                        arrregistros[index].ordemreg = dados.ORDEMREG;
                    }
                    if(arrregistros[index].idmudanca != dados.IDMUDANCA){
                        arrregistros[index].idmudanca = dados.IDMUDANCA;
                    }
                    if(arrregistros[index].desig != dados.DESIG){
                        arrregistros[index].desig = dados.DESIG;
                    }
                }else{
                    arrregistros.push({
                        tr:0,
                        idtr:0,
                        idmov:dados.IDMOVIMENTACAO,
                        idtabela:dados.TABELA,
                        nome:dados.NOME,
                        matricula:dados.MATRICULA,
                        horario:dados.HORARIO,
                        raioatual:dados.RAIOATUAL,
                        raiodestino:dados.RAIODESTINO,
                        raiocelaatual:dados.RAIOCELAATUAL,
                        destino:dados.DESTINO,
                        tipo:dados.TIPO,
                        idsituacao:dados.IDSITUACAO,
                        situacao:dados.SITUACAO,
                        cor:dados.COR,
                        aprovado:dados.APROVADO,
                        ordemreg:dados.ORDEMREG,
                        idmudanca:dados.IDMUDANCA,
                        desig:dados.DESIG,
                    })
                }

            });
            
            // console.log(arrregistros);

            //Remove do array as movimentações que não estão no array da busca
            arrregistros.forEach(reg => {
                let idtabela = reg.idtabela;
                let idmov = reg.idmov;
                let index = result.findIndex((registro)=>registro.TABELA==idtabela && registro.IDMOVIMENTACAO==idmov);

                if(index<0){
                    // console.log(idtabela,idmov);
                    arrregistros = arrregistros.filter(registro=>!(registro.idtabela==idtabela && registro.idmov==idmov));
                }

            });
            // console.log(arrregistros);

            verificaRegistrosGerRaio();

            let registros = tabela.children();
            for(let i=0;i<registros.length;i++){
                let id = registros[i].id;
                if(id==undefined || id==null || id==''){
                    registros[i].remove();
                }else{
                    let indexexcluir = arrregistros.findIndex((registro)=>registro.idtr==id);

                    if(indexexcluir<0){
                        registros[i].remove();
                    }
                }
            }
        }
        consultando = false;
    });
}

//Verifica se o registro já existe e atualiza os dados. Se não existir então é inserido
function verificaRegistrosGerRaio(){

    arrregistros.forEach(dados => {

        let tr = dados.tr
        let idmov = dados.idmov;
        let idtabela = dados.idtabela;
        let nome = dados.nome;
        let matricula = dados.matricula;
        let horario = dados.horario;
        let raiocelaatual = dados.raiocelaatual;
        let destino = dados.destino;
        let tipo = dados.tipo;
        let situacao = dados.situacao;
        let cor = dados.cor;
        let idmudanca = parseInt(dados.idmudanca);
        let desig = parseInt(dados.desig);
    
        if(tr!=0){
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

        }else{

            let novoID = 'tr'+gerarID('.trraio');
            //<td><input type="checkbox" id="check'+novoID+'" class="ckbraio"></input></td>
        
            let linha = '<tr id="'+novoID+'" class="trraio '+cor+'"><td class="tdcheck"><input type="checkbox" id = "ckb'+novoID+'"></td><td class="centralizado tdbotoes nowrap" style="min-width: 50px;"></td><td class="centralizado min-width-100 tdmatricula">'+matricula+'</td><td class="min-width-350 max-width-450 tdnome">'+nome+'</td><td class="centralizado tdhorario">'+horario+'</td><td class="centralizado tdraioatual">'+raiocelaatual+'</td><td class="centralizado tddestino">'+destino+'</td><td class="min-width-250 max-width-350 tdtipo">'+tipo+'</td><td class="min-width-250 max-width-350 tdsituacao">'+situacao+'</td></tr>';
        
            tabela.append(linha);

            let index = arrregistros.findIndex((registro)=>registro.idtabela==idtabela && registro.idmov==idmov);

            let tr = $('#'+novoID);

            if(index!=-1){
                arrregistros[index].tr = tr
                arrregistros[index].idtr = novoID;
                
                let dadoscheck = {
                    tr: tr,
                    idtr: novoID,
                    idtabela: idtabela,
                    idmov: idmov,
                    desig: desig
                }
            
                adicionaEventoCheck(dadoscheck);
            }

        }

        verificaBotoesAcao(dados);
    });
}

//Altera a cor do registro caso estiver com status de Aprovado
function alteraCorFundoTr(dados){
    let tr = dados.tr;
    let corfundo = dados.cor;
    let aprovado = dados.aprovado;
    let classpadrao = "trraio"; //Valores padrão da classe do tr;
    if(tr.closest('.'+corfundo).length>0 && aprovado==true){
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
    let idtabela = dados.idtabela;
    let idmov = dados.idmov;
    let botoesexcluir = [];
    // console.log(dados)

    // Mudança de cela
    if(idtabela==1){
        botoesexcluir = [
            '.btnaltmudcel',
            '.btncanc',
            '.btnrealiz',
            '.btnneg',
            '.btnaprov',
            '.btndesig'
        ]

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

        if(desig>0){
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
        
        if(desig>0){
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
                iniciaConsultaTimer()
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    iniciaConsultaTimer()
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                iniciaConsultaTimer()
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
        // console.log(listacheck);
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
            let index = arrregistros.findIndex(registro=>registro.idtr==mov.idtr && registro.desig>0);
            if(index>-1){
                idsmudanca.push(arrregistros[index].idmudanca);
            }
        });

        if(idsmudanca.length>0){
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

let consultandosemcela = false;
setInterval(() => {

    if(!consultandosemcela){
        consultandosemcela = true;
        let result = consultaBanco('busca_presos',{tipo:8});
        if(result.length){
            inserirBotaoPresosSemCela($('#botoesferramentas'),2);
        }else{
            $('#botoesferramentas').find('.btnsemcela').remove();
        }
        consultandosemcela = false;
    }

    let btnsemcela = $('#botoesferramentas').find('.btnsemcela');
    if(btnsemcela.length){
        if(btnsemcela.hasClass('cor-amarelo')){
            btnsemcela.removeClass('cor-amarelo');
        }else{
            btnsemcela.addClass('cor-amarelo');
        }
    }
}, 1000);

let timer = 0;
let consultando = false;

function iniciaConsultaTimer(){
    clearInterval(timer);
    consultando = false;
    listacheck=[];
    arrregistros=[];
    tabela.html('');
    timer = setInterval(() => {
        if(consultando==false){
            atualizaListaGerChefia();
        }
    }, 1000);
};

setInterval(() => {
    arrregistros.forEach(registro => {
        alteraCorFundoTr(registro);
    });
}, 1000);

adicionaEventoPesquisaGerMov();
iniciaConsultaTimer();