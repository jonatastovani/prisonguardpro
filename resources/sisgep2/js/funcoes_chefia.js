function inserirBotaoAlterarMudanca(tdbotoes,idmov,title='Alterar Dados da Mudança'){
    if(tdbotoes.find('.btnaltmudcel').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaltmudcel" title="'+title+'"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnaltmudcel').click(()=>{
            idmovpopnovamud = idmov;
            abrirPopNovaMudanca();
        })
    }
}

function inserirBotaoAlterarAtendGerais(tdbotoes,idmov,tipoid=1,title='Alterar Dados do Atendimento'){
    if(tdbotoes.find('.btnaltatend').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaltatend" title="'+title+'"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnaltatend').click(()=>{
            if(tipoid==1){
                idbancopopnovogerais = idmov;
            }else{
                idmovpopnovogerais = idmov;
            }
            abrirPopNovoGerais(true);
        })
    }
}

function inserirBotaoAprovado(tdbotoes,idmov,tab,blnvisuchefia=0,idsituacao=12,strpergunta='',title='Aprovar'){
    if(tdbotoes.find('.btnaprov').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaprov" title="'+title+'"><img src="imagens/aprovado.png" class="imgBtnAcao"></button>')
        alterarSituacaoComum(idmov,idsituacao,tab,tdbotoes.find('.btnaprov'),strpergunta,blnvisuchefia);
    }
}

function inserirBotaoRealizado(tdbotoes,idmov,tab,blnvisuchefia=0,idsituacao=13,strpergunta='Confirma como realizado?\r\rEsta ação não poderá ser desfeita.',title='Realizado movimentação'){
    if(tdbotoes.find('.btnrealiz').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnrealiz" title="'+title+'"><img src="imagens/confirmar.png" class="imgBtnAcao"></button>')
        alterarSituacaoComum(idmov,idsituacao,tab,tdbotoes.find('.btnrealiz'),strpergunta,blnvisuchefia);
    }
}

function inserirBotaoCancelar(tdbotoes,idmov,tab,blnvisuchefia=0,idsituacao=16,strpergunta='Confirma o cancelamento da autorização?',title='Cancelar autorização'){
    if(tdbotoes.find('.btncanc').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btncanc" title="'+title+'"><img src="imagens/cancelar.png" class="imgBtnAcao"></button>');
        alterarSituacaoComum(idmov,idsituacao,tab,tdbotoes.find('.btncanc'),strpergunta,blnvisuchefia);
    }
}

function inserirBotaoNegarMudanca(tdbotoes,idmov,tab,blnvisuchefia=0,strpergunta='Confirma a exclusão da Mudança de Cela?',title='Negar mudança de cela'){
    if(tdbotoes.find('.btnneg').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnneg" title="'+title+'"><img src="imagens/negado.png" class="imgBtnAcao"></button>')
        alterarSituacaoComum(idmov,7,tab,tdbotoes.find('.btnneg'),strpergunta,blnvisuchefia);
    }
}

function inserirBotaoEncaminhado(arr){
    let tdbotoes = arr.tdbotoes;
    let idmov = arr.idmov;
    let tab = arr.tab;
    let blnvisuchefia = 0;
    if(arr.blnvisuchefia!=undefined){
        blnvisuchefia = arr.blnvisuchefia;
    }
    let idsituacao = 17;
    if(arr.idsituacao!=undefined){
        idsituacao = arr.idsituacao;
    }
    let strpergunta = 'Confirma que o preso foi encaminhado ao atendimento?';
    if(arr.strpergunta!=undefined){
        strpergunta = arr.strpergunta;
    }
    let title = 'Encaminhado ao Atendimento';
    if(arr.title!=undefined){
        title = arr.title;
    }
    let botao = 'btnencam';
    if(arr.botao!=undefined){
        // console.log(arr.botao);
        botao = arr.botao;
    }

    let icone = 'seta-esq-vermelha';
    if(tab==4){
        icone = 'caminhao-preso';
    }else if(tab==5){
        icone = 'teleaudiencia';
    }else if(tab==6){
        icone = 'cruz-vermelha';
    }else if(tab==7){
        icone = 'relogio';
    }
    if(tdbotoes.find('.'+botao).length==0){
        tdbotoes.append('<button class="btnAcaoRegistro '+botao+'" title="'+title+'"><img src="imagens/'+icone+'.png" class="imgBtnAcao"></button>');
        alterarSituacaoComum(idmov,idsituacao,tab,tdbotoes.find('.'+botao),strpergunta,blnvisuchefia);
    }

}

function inserirBotaoRetornoPavilhao(tdbotoes,idmov,tab,blnvisuchefia=0,strpergunta='Confirma que o preso retornou ao pavilhão?',title='Retorno ao pavilhão'){
    if(tdbotoes.find('.btnrealiz').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnrealiz" title="'+title+'"><img src="imagens/seta-dir-verde.png" class="imgBtnAcao"></button>');
        alterarSituacaoComum(idmov,13,tab,tdbotoes.find('.btnrealiz'),strpergunta,blnvisuchefia);
    }
}

function inserirBotaoImprimirDesignacao(arr){
    let tdbotoes = arr.tdbotoes;
    let idmudanca = arr.idmudanca;

    let title = 'Imprimir Termo de Designação';
    if(arr.title!=undefined){
        title = arr.title;
    }
    let botao = 'btndesig';
    if(arr.botao!=undefined){
        botao = arr.botao;
    }

    let icone = 'limpeza';
    
    if(tdbotoes.find('.'+botao).length==0){
        tdbotoes.append('<button class="btnAcaoRegistro '+botao+'" title="'+title+'"><img src="imagens/'+icone+'.png" class="imgBtnAcao"></button>');
        
        tdbotoes.find('.'+botao).click(()=>{

            let informacoes = [
                {get:'documento',valor:['imp_chef_designacao']},
                {get:'idsmudanca',valor:[idmudanca]},
                {get:'opcaocabecalho',valor:[6]}
            ];
        
            // console.log(informacoes)
        
            imprimirDocumentos(informacoes); 
        })
    }
}

function inserirBotaoAlterarHorario(tdbotoes,idmov,tab,title='Alterar horário'){
    if(tdbotoes.find('.btnalttime').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnalttime" title="'+title+'"><img src="imagens/relogio3.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnalttime').click(()=>{
            idmovpoptime = idmov;
            tabpoptime = tab;
            abrirPopPopHorario();
        })
    }
}

function inserirBotaoAlterarCelasSelecionadas(tdbotoes,id,tipoarr,title='Alterar Celas'){
    if(tdbotoes.find('.btnaltcel').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaltcel" title="'+title+'"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnaltcel').click(()=>{
            let index = arrfuncionariosbatepisograde.findIndex((func)=>func.divfunc==id);
            idarr = id;
            idtipoarr = tipoarr;
            abrirPopRaiosLocais(arrfuncionariosbatepisograde[index].celas);
        })
    }
}

function salvarFuncionarioContagem(dados){
    // console.log(dados);
    
    let confirmacao = true;
    if(dados.idfuncionario>0){
        let dadosfunc = {
            tipo: 4,
            idfuncionario: dados.idfuncionario
        }
        let resultfunc = consultaBanco('busca_funcionarios',dadosfunc);
        // console.log(resultfunc);

        let resultboletim = consultaBanco('chefia_busca_gerenciar',{tipo: 15});


        //Se for a contagem fim de plantão e o funcionário não ser do turno seguinte, exibe a mensagem
        if(resultfunc[0].IDTURNO != resultboletim[0].IDTURNOSEGUINTE && dados.idtipocontagem==1){
            confirmacao = confirm('O turno esperado para realizar a contagem é o turno '+resultboletim[0].NOMETURNOSEGUINTE+'.\r\rDeseja continuar mesmo assim?')
        }
    }else{
        confirmacao = true;
    }

    if(confirmacao==true){
        // console.log(dados);
        $.ajax({
            url: 'ajax/inserir_alterar/chefia_gerenciar.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            //async: false
        }).done(function(result){
            //console.log(result);

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
            }else{
                inserirMensagemTela(result.OK);
            }
        });
    }
}

function inserirBotaoPresosSemCela(tdbotoes,tipobotao=1,title='Ver presos sem cela definida'){
    if(tdbotoes.find('.btnsemcela').length==0){
        if(tipobotao==1){
            tdbotoes.append('<button class="btnAcaoRegistro btnsemcela" title="'+title+'"><img src="imagens/cela.png" class="imgBtnAcao"></button>');
        }else{
            tdbotoes.append('<button class="btnsemcela" title="'+title+'">Sem Cela</button>');
        }
        tdbotoes.find('.btnsemcela').click(()=>{
            abrirPopSemCela();
        })
    }
}

//Atribui o evento para impressão do Boletim informativo
//extra = variáveis extra para a url (ex: variaveis sem codificar)
function eventoBotaoImprimirBoletim(arrvariaveis,extra=''){

    let seletor = arrvariaveis.seletor;
    let boletimvigente = 0;
    if(arrvariaveis.boletimvigente!=undefined){
        boletimvigente = arrvariaveis.boletimvigente;
    }
    let idboletim = 0;
    if(arrvariaveis.idboletim!=undefined){
        idboletim = arrvariaveis.idboletim;
    }

    $(seletor).on('click', ()=>{
        let informacoes = [
            {get:'documento',valor:['imp_chef_boletim']},
            {get:'boletimvigente',valor:[boletimvigente]},
        ];
        if(idboletim>0){
            informacoes.push({get:'idboletim',valor:[idboletim]});
        }
        
        imprimirDocumentos(informacoes,extra);
    })
}
