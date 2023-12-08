const tabela = $('#table-presos-gerenciar').find('tbody');
//Array de ids de movimentações para envio
let idsmovimentacoes = [];
let arrentradas = [];
let situacao = 0;
let ordem = 1;
let texto = 1;
let buscatexto = 1;
let datainicio = retornaDadosDataHora(new Date(),1);
let datafinal = retornaDadosDataHora(new Date(),1);

function adicionaEventoOpcoesConsulta(){
    let seletores = [];
    seletores.push(['#pendente',1])
    seletores.push(['#encerrados',1])
    seletores.push(['#todos',1])
    seletores.push(['#ordemmatricula',2])
    seletores.push(['#ordemnome',2])
    seletores.push(['#ordemdata',2])
    seletores.push(['#dividirtexto',3])
    seletores.push(['#todotexto',3])
    seletores.push(['#buscaparte',4])
    seletores.push(['#buscaexata',4])
    seletores.push(['#buscainicio',4])
    seletores.push(['#buscafinal',4])
    seletores.push(['#datainicio',5])
    seletores.push(['#datafinal',6])

    seletores.forEach(linha => {
        let valor = 0;
        if([1,2,3,4].includes(linha[1])){
            $(linha[0]).on('click', (e)=>{
                valor = $('#'+e.target.id).val();
                valor = parseInt(valor);

                if(linha[1]==1){
                    situacao = valor;
                }else if(linha[1]==2){
                    ordem = valor;
                }else if(linha[1]==3){
                    texto = valor;
                }else if(linha[1]==4){
                    buscatexto = valor;
                }
            })
        }
        else if([5,6].includes(linha[1])){
            $(linha[0]).on('change', (e)=>{
                valor = $('#'+e.target.id).val();
                // console.log($('#'+e.target.id));
                // console.log(valor);

                let elementoVerificar = $('#'+e.target.id)
                if(elementoVerificar.val()!=0 && elementoVerificar.val()!=null && elementoVerificar.val()!=NaN){
                    if(linha[1]==5){
                        datainicio = valor;
                    }else if(linha[6]==4){
                        datafinal = valor;
                    }
                }
            })
        }
    });
    
}

function atualizaListaGerenciarEntrada(){
    
    let textobusca = $('#textobusca').val().trim();

    let dados = {
        tipo: 5,
        datainicio: datainicio,
        datafinal: datafinal,
        situacao: situacao,
        ordem: ordem,
        buscatexto: buscatexto,
        texto: texto,
        textobusca: textobusca
    }

    // console.log(dados);

    if(blnLimparConsulta==true){
        tabela.html('');
        blnLimparConsulta = false;
    }
    idsmovimentacoes = [];

    $.ajax({
        url: 'ajax/consultas/inc_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result);

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            clearInterval(timer);
            tabela.html('');
        }else{
            //Atualizo os valores do array
            result.forEach(linha => {
                let index = arrentradas.findIndex((preso)=>preso.idpreso==linha.ID);
                let idtr = 0;
                if(index!=-1){
                    if($('#'+arrentradas[index].idtr).length>0){
                        idtr = arrentradas[index].idtr;
                    }
                    arrentradas = arrentradas.filter((preso)=>preso.idpreso!=linha.ID);
                }

                arrentradas.push({
                    idtr:idtr,
                    idpreso:linha.ID,
                    idpresocad:linha.IDPRESO,
                    nome: linha.NOME,
                    matricula: linha.MATRICULA,
                    identrada: linha.IDENTRADA,
                    rg: linha.RG,
                    origem: linha.ORIGEM,
                    idsituacao: linha.IDSITUACAO,
                    situacao: linha.SITUACAO,
                    dataentrada: linha.DATAENTRADA,
                    lancadocimic: linha.LANCADOCIMIC,
                    idkit: linha.IDKITENTREGUE,
                    seguro: linha.SEGURO,
                    cor: linha.COR
                });
            });

            //Remove do array os aendimentos que não estão no array da busca
            arrentradas.forEach(atend => {
                let idpreso = atend.idpreso;
                let index = result.findIndex((linha)=>linha.ID==idpreso);

                if(index<0){
                    arrentradas = arrentradas.filter((atend)=>atend.idpreso!=idpreso);
                }
            });

            // console.log(arrentradas);
            //Atualiza a tela
            verificaRegistrosGerEntr();

            let registros = tabela.children();
            // console.log(registros);

            for(let i=0;i<registros.length;i++){
                let id = registros[i].id;
                if(id==undefined || id==null || id==''){
                    registros[i].remove();
                }else{
                    if(arrentradas.findIndex((atend)=>atend.idtr==id)<0){
                        registros[i].remove();
                    }
                }
            }
        }
    });
}

//Verifica se o registro já existe e atualiza os dados. Se não existir então é inserido
function verificaRegistrosGerEntr(){
    // console.log(arratendimentos);

    arrentradas.forEach(preso => {
        let matricula = 'N/C';
        if(preso.matricula!=null){
            matricula = midMatricula(preso.matricula,3);
        }
        let dataentrada = '';
        if(preso.dataentrada!=null){
            dataentrada = retornaDadosDataHora(preso.dataentrada,12);
        }
        let rg = 'N/C';
        if(preso.rg!=null){
            rg = preso.rg;
        }
        // let raiocelaatual = atend.raio+'/'+atend.cela;

        if(preso.idtr!=0){
            let tr = $('#'+preso.idtr);
            // let idmov = tr.data('idmov');
            
            if(matricula!=tr.find('.tdmatricula').html()){
                tr.find('.tdmatricula').html(matricula);
            }
            if(preso.nome!=tr.find('.tdnome').html()){
                tr.find('.tdnome').html(preso.nome);
            }
            if(rg!=tr.find('.tdrg').html()){
                tr.find('.tdrg').html(rg);
            }
            if(dataentrada!=tr.find('.tddataentr').html()){
                tr.find('.tddataentr').html(dataentrada);
            }
            // if(raiocelaatual!=tr.find('.tdraioatual').html()){
            //     tr.find('.tdraioatual').html(raiocelaatual);
            // }
            if(preso.origem!=tr.find('.tdorigem').html()){
                tr.find('.tdorigem').html(preso.origem);
            }
            if(preso.situacao!=tr.find('.tdsituacao').html()){
                tr.find('.tdsituacao').html(preso.situacao);
            }
            if(preso.idpreso!=tr.data('idpreso')){
                tr.data('idpreso',preso.idpreso);
            }
            if(preso.identrada!=tr.data('identrada')){
                tr.data('identrada',preso.identrada);
            }
            if(preso.cor!=tr.data('cor')){
                tr.removeClass(tr.data('cor')).addClass(preso.cor);
                tr.data('cor',preso.cor);
            }
        }
        else{
            let novoID = gerarID('.trentr');
            //<td><input type="checkbox" id="check'+novoID+'" class="ckbraio"></input></td>

            let linha = '<tr id="trentr'+novoID+'" class="trentr '+preso.cor+'" data-idpreso="'+preso.idpreso+'" data-identrada="'+preso.identrada+'" data-cor="'+preso.cor+'"><td><input type="checkbox" id="ckb'+novoID+'"></td><td class="centralizado tdbotoes" style="min-width: 70px;"></td><td class="centralizado min-width-100 tdmatricula">'+matricula+'</td><td class="min-width-350 max-width-450 tdnome">'+preso.nome+'</td><td class="tdrg centralizado min-width-100 nowrap">'+rg+'</td><td class="centralizado tddataentr">'+dataentrada+'</td><td class="min-width-250 max-width-350 tdorigem">'+preso.origem+'</td><td class="min-width-250 max-width-350 tdsituacao">'+preso.situacao+'</td></tr>';
            tabela.append(linha);
            preso.idtr = 'trentr'+novoID;
        }

        let blnAtual = false;
        if(preso.idpreso == preso.idpresocad){
            blnAtual = true;
        }

        adicionaEventoBotoesGerEntr(preso.idtr,preso.idsituacao,blnAtual,preso.matricula,preso.idkit);
    });
}

function adicionaEventoBotoesGerEntr(idtr,idsit,blnAtual,matricula,idkit){
    let tr = tabela.find('#'+idtr);
    let arrbotoesexcluir = [
        'btnok',
        'btnpend',
        'btnqual',
        'btnaltkit',
        'novokit',
        'btnvisi',
    ]
    let id = tr.data('idpreso');

    if(idsit==1){
        inserirBotaoOK(tr.find('.tdbotoes'),id,1,2);
        arrbotoesexcluir = arrbotoesexcluir.filter((preso)=>preso!='btnok');
    }else if(idsit==2){
        inserirBotaoPendente(tr.find('.tdbotoes'),id,1,1);
        arrbotoesexcluir = arrbotoesexcluir.filter((preso)=>preso!='btnpend');
    }

    if(blnAtual==1){
        inserirBotaoAlterarQualificativa(tr.find('.tdbotoes'),matricula);
        arrbotoesexcluir = arrbotoesexcluir.filter((preso)=>preso!='btnqual');
        inserirBotaoVisitantesPreso(tr.find('.tdbotoes'),id);
        arrbotoesexcluir = arrbotoesexcluir.filter((preso)=>preso!='btnvisi');
    }

    if(idkit!=null){
        // console.log('Inserir botao alterar kit')
        inserirBotaoAlterarKitPreso(tr.find('.tdbotoes'),id);
        arrbotoesexcluir = arrbotoesexcluir.filter((preso)=>preso!='btnaltkit');
    }else{
        inserirBotaoInseirKitPadraoPreso(tr.find('.tdbotoes'),id);
        arrbotoesexcluir = arrbotoesexcluir.filter((preso)=>preso!='novokit');
    }

    arrbotoesexcluir.forEach(botao => {
        tr.find('.'+botao).remove();
    });
}

function adicionaEventoPesquisaGerEntr(){
    let seletores = [];
    seletores.push(['#pesquisar-gerenciar','click'])
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
    seletores.push(['#pendente','change'])
    seletores.push(['#encerrados','change'])
    seletores.push(['#todos','change'])

    seletores.forEach(linha => {
        if(['click','change'].includes(linha[1])){
            $(linha[0]).on(linha[1], (e)=>{
                iniciaConsultaTimer();
                blnLimparConsulta=true;
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    iniciaConsultaTimer();
                    blnLimparConsulta=true;
                }
            })
        }
    });
}

function obtemChecados(){
    let check = tabela.find("input:checked");
    return check;
}

$('#checkall').click(()=>{
    let checks = tabela.find('input:checkbox');
    let bln = $('#checkall').prop('checked');

    for(let i=0;i<checks.length;i++){
        let check = $('#'+checks[i].id);
        check.prop('checked', bln);
    }
})

$('#impr-recibopresos').click(function(){
    //Seleciona todos os presos
    let check = obtemChecados();

    if(check.length>0){
        let identrada = [];
        for(let i = 0;i<check.length;i++){
            identrada.push(codifica($('#'+check[i].id).parent().parent().data('identrada')));
        }

        if(identrada.length>0){
            let tipoDocumento = 'entrada_presos';
            window.open('impressoes/impressao.php?documento='+codifica(tipoDocumento)+'&identrada='+identrada+'&opcaocabecalho='+codifica(2), '_blank')
        }
        else{
            inserirMensagemTela('<li class="mensagem-aviso">Nenhuma entrada foi selecionada</li>');
        }
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhuma entrada foi selecionada</li>');
    }

})

$('#impr-digitaispreso').click(function(){
    //Seleciona todos os presos
    let check = obtemChecados();

    if(check.length>0){
        let idpreso = [];
        for(let i = 0;i<check.length;i++){
            idpreso.push(codifica($('#'+check[i].id).parent().parent().data('idpreso')));
        }

        if(idpreso.length>0){
            let tipoDocumento = 'digitais_presos';
            window.open('impressoes/impressao.php?documento='+codifica(tipoDocumento)+'&idpreso='+idpreso+'&opcaocabecalho='+codifica(1)+'&tipo='+codifica('idpreso'), '_blank')
        }
        else{
            inserirMensagemTela('<li class="mensagem-aviso">Nenhum preso foi selecionado</li>')
        }
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhum preso foi selecionado</li>')
    }

})

$('#impr-termodeclaracao').click(function(){
    //Seleciona todos os presos
    let check = obtemChecados();

    if(check.length>0){
        let idpreso = [];
        for(let i = 0;i<check.length;i++){
            idpreso.push(codifica($('#'+check[i].id).parent().parent().data('idpreso')));
        }

        if(idpreso.length>0){
            let tipoDocumento = 'termo_declaracao';
            window.open('impressoes/impressao.php?documento='+codifica(tipoDocumento)+'&idpreso='+idpreso+'&opcaocabecalho='+codifica(2), '_blank')
        }
        else{
            inserirMensagemTela('<li class="mensagem-aviso">Nenhum preso foi selecionado</li>')
        }
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhum preso foi selecionado</li>')
    }

})

$('#impr-carteirinha').click(function(){
    //Seleciona todos os presos
    let check = obtemChecados();

    if(check.length>0){
        let idpreso = [];
        for(let i = 0;i<check.length;i++){
            idpreso.push(codifica($('#'+check[i].id).parent().parent().data('idpreso')));
        }

        if(idpreso.length>0){
            let tipoDocumento = 'carteirinha';
            window.open('impressoes/impressao.php?documento='+codifica(tipoDocumento)+'&idpreso='+idpreso+'&opcaocabecalho='+codifica(5), '_blank')
        }
        else{
            inserirMensagemTela('<li class="mensagem-aviso">Nenhum preso foi selecionado</li>')
        }
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhum preso foi selecionado</li>')
    }

})

adicionaEventoPesquisaGerEntr();
adicionaEventoOpcoesConsulta();

$('#datainicio').focus();

let timer;
let blnLimparConsulta = false;

function iniciaConsultaTimer(){
    clearInterval(timer);
    timer = setInterval(() => {
        atualizaListaGerenciarEntrada();
    }, 500);
};

iniciaConsultaTimer();