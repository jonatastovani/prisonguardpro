const tabela = $('#table-mov-gerenciar').find('tbody');
//Array do nomes dos raios que pertencem a visualizacao
let raiosvisualizacao = [];
//Registros que restarem nesta listagem serão exclusos no final da verificação dos dados da tabela
let registrosexcluir = [];
let ordemregistros = 3;
//id de visualização do gerenciar raio
let idvisu = 0;
//Div de botões das contagens
const divbotoescontagens = $('#divbotoescontagens');

atualizaListagemComum('buscas_comuns',{tipo:26,verificapermissao:1},0,$('#selectvisu'),false,true,'change',false,false);

function atualizaListaGerRaio(){
    atualizaListagemVisualizacao();
    //id de visualizacao (variável do popnovamud)
    idvisu = $('#selectvisu').val();
    consultando = true;
    
    let dados = {
        tipo: 1,
        ordem: ordemregistros,
        idvisualizacao: idvisu,
    }
   //console.log(dados);

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
                let blnabertoencerrado = false;

                if((arrayencerrado.includes(idsituacao) && [2,3].includes(exibir)) || (arrayencerrado.includes(idsituacao)==false && [1,3].includes(exibir))){
                    blnabertoencerrado = true;
                }

                //Filtra os registros que serão exibidos na tela conforme a visualização informada
                
                // if((raiosvisualizacao.includes(raioatual) || raiosvisualizacao.includes(raiodestino) && raiodestino!=null) && blnabertoencerrado==true){
                //     let tr = verificaRegistrosGerRaio(dados);
                //     verificaBotoesAcao(tr,idsituacao);
                // }
                if((raiosvisualizacao.findIndex(x => x.NOME == raioatual) > -1 || raiosvisualizacao.findIndex(x => x.NOME == raiodestino) > -1 && raiodestino!=null) && blnabertoencerrado==true){
                    let tr = verificaRegistrosGerRaio(dados);
                    verificaBotoesAcao(tr,idsituacao);
                }

            });
            //Remove os registros que não existem mais
            for(let i=0;i<registrosexcluir.length;i++){
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

    let recebmud = false;
    if(raiosvisualizacao.findIndex(x => x.NOME == raiodestino)>-1 && raiodestino!=null){
        //True caso o destino é o raio que está com a visualização (casos de mudança de cela)
        recebmud = true;
    }
    
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
            if(recebmud!=tr.data('recebmud')){
                tr.data('recebmud',recebmud);
            }
            //alteraCorFundoTr(tr);
            //Remove da lista o id verificado
            registrosexcluir = registrosexcluir.filter((item)=>item!=registros[i].id); 
            return tr;
        }
    }
    let novoID = gerarID('.trraio');
    //<td><input type="checkbox" id="check'+novoID+'" class="ckbraio"></input></td>

    let linha = '<tr id="tr'+novoID+'" class="trraio '+cor+'" data-idmov="'+idmovimentacao+'" data-tabela="'+idtabela+'" data-cor="'+cor+'" data-aprov="'+aprovado+'" data-ordemreg="'+ordemreg+'" data-recebmud="'+recebmud+'"><td class="centralizado tdbotoes" style="min-width: 70px;"></td><td class="centralizado min-width-100 tdmatricula">'+matricula+'</td><td class="min-width-350 max-width-450 tdnome">'+nome+'</td><td class="centralizado tdhorario">'+horario+'</td><td class="centralizado tdraioatual">'+raiocelaatual+'</td><td class="centralizado tddestino">'+destino+'</td><td class="min-width-250 max-width-350 tdtipo">'+tipo+'</td><td class="min-width-250 max-width-350 tdsituacao">'+situacao+'</td></tr>';
    tabela.append(linha);
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

function verificaBotoesAcao(tr,idsituacao){
    let tdbotoes = tr.find('.tdbotoes');
    let idtabela = tr.data('tabela');
    let idmov = tr.data('idmov');
    let botoesexcluir = [];

    if(idtabela==1){
        let recebmud = tr.data('recebmud');
        botoesexcluir = [
            '.btnaltmudcel',
            '.btncanc',
            '.btnconfmud'
        ]
        if(recebmud==true){
            if(idsituacao==3 || idsituacao==4){
                //Se estiver Aguardando preenchimento de destino então se exibe somente o botão de alterar a mudança
                inserirBotaoAlterarMudanca(tdbotoes,idmov)
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btnaltmudcel');

            }
            
            if(idsituacao==5){
                //Se estiver aprovado então se exibe somente o botão de confirmar
                inserirBotaoRealizado(tdbotoes,idmov,2,0,6,'Confirma a alteração de cela?\r\rEsta ação não poderá ser desfeita.','Confirmar Mudança')
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btnconfmud');
            }
            
            if(idsituacao==3 || idsituacao==4 || idsituacao==5){
                //Se estiver Aguardando aprovação da chefia ou aprovado então se exibe somente o botão de cancelar solicitação
                inserirBotaoCancelar(tdbotoes,idmov,2,0,8,'Confirma a cancelamento da mudança de cela?','Cancelar Solicitação de Mudança')
                botoesexcluir = botoesexcluir.filter((item)=>item!='.btncanc');
            }
        }
    }
    //Transferências(2), Apresentações Externas(3) e Exclusões (7)
    else if(idtabela==2 || idtabela==3 || idtabela==7){
        botoesexcluir = [
            '.btnencam',
        ]
        let tab = 0;
        if(idtabela==2){
            tab=3;
        }else if(idtabela==3){
            tab=4;
        }else if(idtabela==7){
            tab=8;
        }

        if(idsituacao==12){

            //Se estiver Aprovado então se exibe somente o botão de saida para atendimento
            let arr = {
                tdbotoes:tdbotoes,
                idmov:idmov,
                tab:tab,
                blnvisuchefia:0,
                idsituacao:18,
                strpergunta:'Confirma o encaminhamento ao Desembarque Interno?',
                title:'Encaminhado ao Desen. Interno'
            }
            inserirBotaoEncaminhado(arr)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnencam');
        }
    }
    //Apresentações Internas(4), Atendimentos Enfermaria(5)
    else if(idtabela==4 || idtabela==5 || idtabela==6){
        botoesexcluir = [
            '.btnencam',
            '.btnrealiz'
        ]
        let tab = 0;
        if(idtabela==4){
            tab=5;
        }else if(idtabela==5){
            tab=6;
        }else if(idtabela==6){
            tab=7;
        }

        if(idsituacao==12){
            //Se estiver Aprovado então se exibe somente o botão de saida para atendimento
            let arr = {
                tdbotoes:tdbotoes,
                idmov:idmov,
                tab:tab
            }
            inserirBotaoEncaminhado(arr)
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnencam');
        }
        if(idsituacao==17){
            //Se estiver em atendimento se exibe o botão de retorno ao pavilhão (realizado)
            inserirBotaoRetornoPavilhao(tdbotoes,idmov,tab);
            botoesexcluir = botoesexcluir.filter((item)=>item!='.btnrealiz');
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
            let divpop = $('#popraio'+idpopraio);

            //Verifica se esse botão está na visualização selecionada
            let index = raiosvisualizacao.findIndex((linha => linha.IDRAIO == idraio));
            if(index!=-1){
                if(idraio==raio.IDRAIO){
                    if(btn.html()!=nomeexibir){
                        btn.html(nomeexibir);
                        //Atualiza contagem caso esteja visualizando a contagem do raio
                        if(divpop.find('.celas').length>0){
                            inserirPopulacaoRaio(divpop, idraio)
                        }
                    }
                    atualizado = true;
                }    
            }else{
                btn.remove();
                divpop.remove();
            }
        }

        if(atualizado==false){
            let novoID = gerarID('.btnpopraio');

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

    if(idvisu!=undefined && idvisu!=null && idvisu>0){
        let dados = {
            tipo: 2,
            idvisualizacao: idvisu,
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

function buscaDadosContagem(){
    let tiposbuscar = ''; //tipos de contagens existentes no banco
    let arrtipos = []; // tipos de contagens que foram encontrados na consulta
    let result = consultaBanco('buscas_comuns',{tipo:35});
    if(result.length>0){
        result.forEach(tipo => {
            if(tiposbuscar==''){
                tiposbuscar =tipo.VALOR;
            }else{
                tiposbuscar +=', '+ tipo.VALOR;
            }
        });
    }
    result = consultaBanco('chefia_busca_gerenciar',{tipo:13, strtiposcontagem:tiposbuscar})
    // console.log(result);

    if(result.length>0){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{

            if(divbotoescontagens.attr('hidden')=='hidden'){
                divbotoescontagens.removeAttr('hidden');
            }

            result.forEach(cont => {
                raiosvisualizacao.forEach(raio => {
                    if(raio.IDRAIO==cont.IDRAIO && cont.QTD>0){
                        let idcontagem = cont.IDCONTAGEM;
                        let idtipocontagem = cont.IDTIPO;
                        if(arrtipos.includes(idtipocontagem)==false){
                            arrtipos.push({IDTIPO: idtipocontagem});
                        }
                        let qtd = cont.QTD;
                        if(qtd>1){
                            qtd = ' ('+qtd+' presos)';
                        }else{
                            qtd = ' ('+qtd+' preso)';
                        }
                        let nomeraio = cont.NOMERAIO;
                        let idraio = cont.IDRAIO;
                        let idfuncionario = 0;
                        if(cont.IDUSUARIO!=null){
                            idfuncionario = cont.IDUSUARIO;
                        }
                        let nomefuncionario = cont.NOMEUSUARIO;
                        let autenticado = cont.AUTENTICADO;
                        let nomecontagem = cont.NOMECONTAGEM;
    
                        let divcontagem = $('#contagem'+idraio);
                        while(divcontagem.length<1){
                            if(divcontagem.length==0){
                                divbotoescontagens.append('<div id="contagem'+idraio+'" class="grupo contagemraio" data-idraio="'+idraio+'"><h4 class="titulo-grupo">'+nomeraio+'</h4></div>')
                            }
                            divcontagem = $('#contagem'+idraio);
                        }
    
                        // let divtipocontagem = divcontagem.find('.tipo'+idtipocontagem).find('div');
                        let divtipocontagem = [];
                        while(divtipocontagem.length<1){
                            let arrdivtipocontagem = divcontagem.find('.tipocontagem');
                            if(arrdivtipocontagem.length>0){
                                for(let i=0;i<arrdivtipocontagem.length;i++){
                                    if($('#'+arrdivtipocontagem[i].id).data('idtipocontagem')==idtipocontagem){
                                        divtipocontagem = $('#'+arrdivtipocontagem[i].id).find('div');
                                    }
                                }
                            }

                            if(divtipocontagem.length==0){
                                let novoID = 'tipocontagem'+gerarID('.tipocontagem');
                                divcontagem.append('<fieldset id="'+novoID+'" class="grupo tipocontagem" data-idtipocontagem="'+idtipocontagem+'"><legend>'+nomecontagem+qtd+'</legend><div></div></fieldset>')
                            }
                        }
    
                        if(nomefuncionario!=null){
                            if(divtipocontagem.find('span.nomefuncionario').find('span').html()!=nomefuncionario){
                                divtipocontagem.html('<span class="nomefuncionario">Contado por: <b><span>'+nomefuncionario+'</span></b></span>');
                            }
                        }else{
                            if(divtipocontagem.find('span.nomefuncionario').length>0){
                                divtipocontagem.find('span.nomefuncionario').remove();
                            }
                        }
                        
                        if(autenticado==1){
                            divtipocontagem.find('button').remove();
                        }else{
                            inserirBotaoAutenticacao(divtipocontagem,idcontagem,idraio,idtipocontagem,'Autenticar contagem','Autenticar com usuário a contagem do '+nomeraio);
                        }
                    }
                });                    
            });
        }
    }else{
        divbotoescontagens.attr('hidden','hidden');
        divbotoescontagens.find('.contagens').remove();;    
    }

    let arr = divbotoescontagens.children();
    for(let i=0;i<arr.length;i++){
        //Remove qualquer contagem que não esteja na lista dos raios em visualização
        for(let i=0;i<arr.length;i++){
            let id = arr[i].id;
            if(id==''){
                arr[i].remove();
            }else{
                //Exclui as contagens que não existirem mais
                let divcontagem = $('#'+id);
                if(raiosvisualizacao.findIndex((raio)=>raio.IDRAIO==divcontagem.data('idraio'))==-1){
                    divcontagem.remove();
                }else{
                    //Exclui os tipos de contagem que não existir mais
                    let arrtipo = divcontagem.find('.tipocontagem');
                    for(let i=0;i<arrtipo.length;i++){
                        let id = arrtipo[i].id;

                        if(id==''){
                            arrtipo[i].remove();
                        }else{

                            let divtipo = $('#'+id);
                            if(arrtipos.findIndex((tipo)=>tipo.IDTIPO==divtipo.data('idtipocontagem'))==-1){
                                divtipo.remove();
                            }
                        }
                    }
                }
            }
        }
        // if(raiosvisualizacao.findIndex((raio)=>raio.IDRAIO==$('#'+arr[i].id).data('idraio'))==-1){
        //     $('#'+arr[i].id).remove();
        // }
    };

}

function inserirBotaoAutenticacao(tdbotoes,id,idraio,idtipocontagem,strbotao,title='Autenticar com usuário'){

    let blnexiste = false;
    let arr = tdbotoes.find('.btnautentic');
    for(let i=0;i<arr.length;i++){
        if($('#'+arr[i].id).data('idraio')==idraio){
            if($('#'+arr[i].id).data('idtipocontagem')==idtipocontagem){
                blnexiste = true;
            }
        }
    };

    if(blnexiste==false){
        let novoID = gerarID('.btnautentic');

        tdbotoes.append('<button id="btnautentic'+novoID+'" class="btnautentic margin-espaco-esq" data-idraio="'+idraio+'" data-idtipocontagem="'+idtipocontagem+'" title="'+title+'"><img src="imagens/autenticacao.png" class="imgBtnAcao"> '+strbotao+'</button>');

        $('#btnautentic'+novoID).click(()=>{
            abrirPopPopAutenticacao();
            let timer = setInterval(() => {
                if(idusuariopopautent>0){
                    let dados = {
                        tipo:6,
                        idcontagem: id,
                        idfuncionario: idusuariopopautent,
                        idtipocontagem: idtipocontagem
                    }
                    salvarFuncionarioContagem(dados);
                    encerrartimerpopautent=true;
                }
                if(encerrartimerpopautent==true){
                    clearInterval(timer);
                    fecharPopPopAutenticacao();
                }
            }, 300);
        })
    }
}

adicionaEventoPesquisaGerMov();
atualizaListagemVisualizacao();
let timer = true;
let consultando = false;

setInterval(() => {
    if(timer==false){
        //Limpa todos registros e efetua a busca para inserir os registro na ordenação escolhida
        if(consultando==false){
            tabela.html('');
            timer = true
        }
    }
    if(consultando==false){
        atualizaListaGerRaio();
    }
}, 1000);

setInterval(() => {
    let trs = tabela.find('tr');
    for(let i=0;i<trs.length;i++){
        alteraCorFundoTr($('#'+trs[i].id));
    }
}, 1000);

timerbusca = setInterval(() => {
    buscaDadosContagem();
}, 500);

