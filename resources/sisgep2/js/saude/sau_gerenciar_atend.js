const tabela = $('#table-atend-gerenciar').find('tbody');
//Array dos selecionados (para impressão e coisas do tipo)
let listacheck = [];
let arratendimentos = [];
let tipodata = 2;
let ordem = 1;
let texto = 1;
let buscatexto = 1;

function adicionaEventoOpcoesConsulta(){
    let seletores = [];
    seletores.push(['#tipodataatend',1])
    seletores.push(['#tipodatasolicitacao',1])
    seletores.push(['#ordemmatricula',2])
    seletores.push(['#ordemnome',2])
    seletores.push(['#ordemdata',2])
    seletores.push(['#ordemsolicitacao',2])
    seletores.push(['#dividirtexto',3])
    seletores.push(['#todotexto',3])
    seletores.push(['#buscaparte',4])
    seletores.push(['#buscaexata',4])
    seletores.push(['#buscainicio',4])
    seletores.push(['#buscafinal',4])

    seletores.forEach(linha => {
        let valor = 0;
        $(linha[0]).on('click', (e)=>{
            valor = $('#'+e.target.id).val();
            valor = parseInt(valor);

            if(linha[1]==1){
                tipodata = valor;
            }else if(linha[1]==2){
                ordem = valor;
            }else if(linha[1]==3){
                texto = valor;
            }else if(linha[1]==4){
                buscatexto = valor;
            }
        })
    });
    
}

function atualizaListaGerenciarAtend(){

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
        tipo: 1,
        datainicio: datainicio,
        datafinal: datafinal,
        tipodata: tipodata,
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
            clearInterval(timer);
            tabela.html('');
        }else{
            //Atualizo os valores do array
            result.forEach(linha => {
                let index = arratendimentos.findIndex((atend)=>atend.idatend==linha.IDATEND);
                let idtr = 0;
                if(index!=-1){
                    if($('#'+arratendimentos[index].idtr).length>0){
                        idtr = arratendimentos[index].idtr;
                    }
                    arratendimentos = arratendimentos.filter((atend)=>atend.idatend!=linha.IDATEND);
                }

                arratendimentos.push({
                    idatend:linha.IDATEND,
                    idtr:idtr,
                    idpreso:linha.IDPRESO,
                    nome: linha.NOME,
                    matricula: linha.MATRICULA,
                    descpedido: linha.DESCPEDIDO,
                    dataatend: linha.DATAATEND,
                    datasolic: linha.DATASOLICITACAO,
                    idtipo: linha.IDTIPOATEND,
                    tipo: linha.TIPO,
                    idsituacao: linha.IDSITUACAO,
                    situacao: linha.SITUACAO,
                    seguro: linha.SEGURO,
                    cor: linha.COR,
                    idraio:linha.IDRAIO,
                    raio: linha.RAIO,
                    cela: linha.CELA
                });
            });

            //Remove do array os aendimentos que não estão no array da busca
            arratendimentos.forEach(atend => {
                let idatend = atend.idatend;
                let index = result.findIndex((linha)=>linha.IDATEND==idatend);

                if(index<0){
                    arratendimentos = arratendimentos.filter((atend)=>atend.idatend!=idatend);
                }
            });

            //Atualiza a tela
            verificaRegistrosGerAtend();

            let registros = tabela.children();
            // console.log(registros);
            // console.log(arratendimentos);

            for(let i=0;i<registros.length;i++){
                let id = registros[i].id;
                if(id==undefined || id==null || id==''){
                    registros[i].remove();
                }else{
                    if(arratendimentos.findIndex((atend)=>atend.idtr==id)<0){
                        registros[i].remove();
                    }
                }
            }
        }
    });
}

//Verifica se o registro já existe e atualiza os dados. Se não existir então é inserido
function verificaRegistrosGerAtend(){
    // console.log(arratendimentos);

    arratendimentos.forEach(atend => {
        let matricula = 'N/C';
        if(atend.matricula!=null){
            matricula = midMatricula(atend.matricula,3);
        }
        let dataatend = '';
        if(atend.dataatend!=null){
            dataatend = retornaDadosDataHora(atend.dataatend,12);
        }
        let datasolic = 'Agendamento direto';
        if(atend.datasolic!=null){
            datasolic = retornaDadosDataHora(atend.datasolic,12);
        }
        let descpedido = 'Agendado pela enfermaria';
        if(atend.descpedido!=null){
            descpedido = atend.descpedido;
        }
        let raiocelaatual = atend.raio+'/'+atend.cela;

        if(atend.idtr!=0){
            let tr = $('#'+atend.idtr);
            // let idmov = tr.data('idmov');
            
            if(matricula!=tr.find('.tdmatricula').html()){
                tr.find('.tdmatricula').html(matricula);
            }
            if(atend.nome!=tr.find('.tdnome').html()){
                tr.find('.tdnome').html(atend.nome);
            }
            if(descpedido!=tr.find('.tddescpedido').html()){
                tr.find('.tddescpedido').html(descpedido);
            }
            if(dataatend!=tr.find('.tddataatend').html()){
                tr.find('.tddataatend').html(dataatend);
            }
            if(datasolic!=tr.find('.tddatasolic').html()){
                tr.find('.tddatasolic').html(datasolic);
            }
            if(raiocelaatual!=tr.find('.tdraioatual').html()){
                tr.find('.tdraioatual').html(raiocelaatual);
            }
            if(atend.tipo!=tr.find('.tdtipo').html()){
                tr.find('.tdtipo').html(atend.tipo);
            }
            if(atend.situacao!=tr.find('.tdsituacao').html()){
                tr.find('.tdsituacao').html(atend.situacao);
            }
            if(atend.idatend!=tr.data('idatend')){
                tr.data('idatend',atend.idatend);
            }
            if(atend.cor!=tr.data('cor')){
                tr.removeClass(tr.data('cor')).addClass(atend.cor);
                tr.data('cor',atend.cor);
            }
        }
        else{
            let novoID = 'tratend'+gerarID('.tratend');

            let linha = '<tr id="'+novoID+'" class="tratend '+atend.cor+'" data-idatend="'+atend.idatend+'" data-tabela="5" data-cor="'+atend.cor+'"><td class="tdcheck"><input type="checkbox" id = "ckb'+novoID+'"></td><td class="centralizado tdbotoes" style="min-width: 70px;"></td><td class="centralizado min-width-100 tdmatricula">'+matricula+'</td><td class="min-width-350 max-width-450 tdnome">'+atend.nome+'</td><td class="tddescpedido min-width-200 max-width-450">'+descpedido+'</td><td class="centralizado tdraioatual">'+raiocelaatual+'</td><td class="centralizado tddataatend">'+dataatend+'</td><td class="min-width-250 max-width-350 tdtipo">'+atend.tipo+'</td><td class="centralizado tddatasolic">'+datasolic+'</td><td class="min-width-250 max-width-350 tdsituacao">'+atend.situacao+'</td></tr>';
            tabela.append(linha);
            atend.idtr = novoID;

            let dadoscheck = {
                tr: $('#'+novoID),
                idtr: novoID,
                idtabela: 5,
                idmov: atend.idatend
            }
        
            adicionaEventoCheck(dadoscheck);

        }

        let blnagendado = false;
        if(atend.dataatend!=null){
            blnagendado=true;
        }
        adicionaEventoBotoesAtend(atend.idtr,blnagendado)
    });
}

function adicionaEventoBotoesAtend(idtr,blnagendado){
    let tr = tabela.find('#'+idtr);
    let arrbotoesexcluir = [
        'btnaltatend',
        'btnabriratend'
    ]
    let id = tr.data('idatend');

    inserirBotaoAlterarAtendEnf(tr.find('.tdbotoes'),id);
    arrbotoesexcluir = arrbotoesexcluir.filter((atend)=>atend!='btnaltatend');
    if(blnagendado){
        inserirBotaoAbrirAtendEnf(tr.find('.tdbotoes'),id);
        arrbotoesexcluir = arrbotoesexcluir.filter((atend)=>atend!='btnabriratend');
    }

    arrbotoesexcluir.forEach(botao => {
        tr.find('.'+botao).remove();
    });
}

function adicionaEventoPesquisaGerMov(){
    let seletores = [];
    seletores.push(['#pesquisar-gerenciar','click'])
    seletores.push(['#datainicio','enter'])
    seletores.push(['#datafinal','enter'])
    seletores.push(['#ordemmatricula','change'])
    seletores.push(['#ordemnome','change'])
    seletores.push(['#ordemdata','change'])
    seletores.push(['#ordemsolicitacao','change'])
    seletores.push(['#textobusca','enter'])
    seletores.push(['#dividirtexto','change'])
    seletores.push(['#todotexto','change'])
    seletores.push(['#buscaparte','change'])
    seletores.push(['#buscaexata','change'])
    seletores.push(['#buscainicio','change'])
    seletores.push(['#buscafinal','change'])
    seletores.push(['#tipodataatend','change'])
    seletores.push(['#tipodatasolicitacao','change'])

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

function adicionaEventoCheck(dados){
    let tr = dados.tr;
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

$('#imp_req').click(()=>{
    console.log(listacheck);
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

adicionaEventoPesquisaGerMov();
adicionaEventoOpcoesConsulta();

$('#datainicio').focus();

let timer;
let blnLimparConsulta = false;

function iniciaConsultaTimer(){
    clearInterval(timer);
    timer = setInterval(() => {
        atualizaListaGerenciarAtend();
    }, 500);
};

iniciaConsultaTimer();