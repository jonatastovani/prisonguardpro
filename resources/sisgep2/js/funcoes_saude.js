function inserirBotaoAlterarAtendEnf(tdbotoes,idmov,tipoid=1,title='Alterar Dados do Atendimento'){
    if(tdbotoes.find('.btnaltatend').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaltatend" title="'+title+'"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnaltatend').click(()=>{
            if(tipoid==1){
                idbancopopatendenf = idmov;
            }else{
                idmovpopatendenf = idmov;
            }
            abrirPopAtendEnfermaria(true);
        })
    }
}

function inserirBotaoAbrirAtendEnf(tdbotoes,idmov,title='Abrir Atendimento'){
    if(tdbotoes.find('.btnabriratend').length==0){
        tdbotoes.append('<form action="principal.php?menuop=sau_atend" method="post" class="inline" target="_blank"><input type="hidden" name="idbancoatend" value="'+codifica(idmov)+'"><button type="submit" class="btnAcaoRegistro btnabriratend" title="'+title+'"><img src="imagens/arquivo.png" class="imgBtnAcao"></button></form>');
    }
}

function inserirBotaoEntregarAssistido(tdbotoes,dados,title='Entregar todos medicametos assistido deste período'){
    if(tdbotoes.find('.btnentass').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnentass" title="'+title+'"><img src="imagens/confirmar.png" class="imgBtnAcao"></button>');
        
        tdbotoes.find('.btnentass').click(()=>{
            acaoBotaoEntregarAssistido(dados);  
        })
    }
}

function acaoBotaoEntregarAssistido(dados){
    // console.log(dados);
    $.ajax({
        url: 'ajax/inserir_alterar/saude_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async:false
    }).done(function(result){
        // console.log(result);
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);

        }else{
            inserirMensagemTela(result.OK);
        }

        if($('#table-assistidos-gerenciar').length>0){
            atualizaListaGerenciarAssistidos();
        }
        if($('#table-pop-visumedicass').length>0){
            buscaDadosPopVisuMedicAss();
        }
    });        
}

function inserirBotaoVisualizarAssistido(tdbotoes,dados,title='Visualizar medicametos assistido deste preso neste período'){
    if(tdbotoes.find('.btnvisuass').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnvisuass" title="'+title+'"><img src="imagens/olho.png" class="imgBtnAcao"></button>');
        
        tdbotoes.find('.btnvisuass').click(()=>{
            idpresopopvismed = dados.idpreso;
            idperiodopopvismed = dados.idperiodo;
            datapopvismed = dados.data;
            abrirPopVisuMedicAss();
        })
    }
}

function inserirBotaoEditarAssistido(tdbotoes,dados,title='Alterar medicametos assistido'){
    if(tdbotoes.find('.btnaltuass').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaltuass" title="'+title+'"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
        
        tdbotoes.find('.btnaltuass').click(()=>{
            idpresoalterarpopmedass = dados.idpreso;
            abrirPopMedicAssistidos();
        })
    }
}
