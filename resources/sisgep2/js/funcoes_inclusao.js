
function inserirBotaoPendente(tdbotoes,idmov,tab,idsituacao=1,strpergunta='',title='Marcar como pendente'){
    if(tdbotoes.find('.btnpend').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnpend" title="'+title+'"><img src="imagens/checked-false.png" class="imgBtnAcao"></button>')
        alterarSituacaoComum(idmov,idsituacao,tab,tdbotoes.find('.btnpend'),strpergunta);
    }
}

function inserirBotaoOK(tdbotoes,idmov,tab,idsituacao=2,strpergunta='',title='Marcar como OK'){
    if(tdbotoes.find('.btnok').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnok" title="'+title+'"><img src="imagens/checked.png" class="imgBtnAcao"></button>')
        alterarSituacaoComum(idmov,idsituacao,tab,tdbotoes.find('.btnok'),strpergunta);
    }
}

function inserirBotaoAlterarQualificativa(tdbotoes,matric,title='Alterar qualificativa'){
    if(tdbotoes.find('.btnqual').length==0){
        tdbotoes.append('<form action="principal.php?menuop=inc_alt_qualificativa_preso" method="post" class="inline" target="_blank"><input type="hidden" name="matric" value="'+codifica(matric)+'"><button type="submit" class="btnAcaoRegistro btnqual" title="'+title+'"><img src="imagens/cadastro-preso-alterar.png" class="imgBtnAcao"></button></form>');
    }
}

function inserirBotaoAlterarKitPreso(tdbotoes,idpreso,title='Alterar Kit Entregue'){
    if(tdbotoes.find('.btnaltkit').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaltkit" title="'+title+'"><img src="imagens/kit-entregue.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnaltkit').click(()=>{
            idpresokitentregue = idpreso;
            $('#alterar-kitentregue').prop('checked',true);
            abrirKitEntregue();
        })
    }
}

function inserirBotaoInseirKitPadraoPreso(tdbotoes,idpreso,title='Inserir Kit Padrão'){
    if(tdbotoes.find('.novokit').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro novokit" title="'+title+'"><img src="imagens/adicionar-kit.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.novokit').click(()=>{
            $.ajax({
                url: 'ajax/inserir_alterar/inc_kitpreso.php',
                method: 'POST',
                data: {idpreso: idpreso, tipo: 1},
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json'
            }).done(function(result){
    
                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM)
                }else{
                    inserirMensagemTela(result.OK)
                    atualizaListaGerenciarEntrada();
                }
            });
        })
    }
}

function inserirBotaoVisitantesPreso(tdbotoes,idpreso,title='Inserir visitantes'){
    if(tdbotoes.find('.btnvisi').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnvisi" title="'+title+'"><img src="imagens/pessoas.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnvisi').click(()=>{
            idpresopopnovovis = idpreso;
            abrirPopNovoVisitante();
        })
    }
}