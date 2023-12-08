function inserirBotaoAbrirVisitante(tdbotoes,idbanco,title='Alterar visitante'){
    if(tdbotoes.find('.btnaltvisi').length==0){
        tdbotoes.append('<button type="submit" class="btnAcaoRegistro btnaltvisi" title="'+title+'"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnaltvisi').click(()=>{
            let result = consultaBanco('rol_busca_gerenciar',{tipo: 3, idvisita: idbanco});
            // console.log(result);
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
            }else{
                if(result.length){
                    let result2 = consultaBanco('rol_busca_gerenciar',{tipo: 4, idvisitante: result[0].IDVISITANTE});
                    // console.log(result2);
                    if(result2.MENSAGEM){
                        inserirMensagemTela(result2.MENSAGEM);
                    }else{
                        if(result2.length){
                            if(result2[0].CPF!=null){
                                acaoBotaoAbrirVisitante(tdbotoes,idbanco);
                            }else{
                                idvisitapopcadvisi=idbanco;
                                abrirPopCadVisita();
                            }
                        }
                    }
                }
            }
        })
    }
}

function acaoBotaoAbrirVisitante(tdbotoes,idbanco){
    if(tdbotoes.find('.btnabriraltvisi').length==0){
        tdbotoes.append('<form action="principal.php?menuop=rol_alt_vis" method="post" class="inline" target="_blank"><input type="hidden" name="idbancovisi" value="'+codifica(idbanco)+'"><button type="submit" class="btnAcaoRegistro btnabriraltvisi"><img src="imagens/acao.png" class="imgBtnAcao" hidden></button></form>');
        tdbotoes.find('.btnabriraltvisi').click();
        tdbotoes.find('.btnabriraltvisi').parent().remove();
    }
}

function inserirBotaoFotoVisitante(tdbotoes,idbanco,tipobotao=1,title='Alterar foto visitante'){
    if(tdbotoes.find('.btnaltfotovisi').length==0){
        if(tipobotao==1){
            tdbotoes.append('<button type="submit" class="btnAcaoRegistro btnaltfotovisi" title="'+title+'"><img src="imagens/camera.png" class="imgBtnAcao"></button>');
        }else{
            tdbotoes.append('<button type="submit" class="btnaltfotovisi" title="'+title+'">Alterar Foto</button>');
        }

        tdbotoes.find('.btnaltfotovisi').click(()=>{
            idvisitantepopfotovisitante = idbanco;
            abrirPopFotoVisitante();
        })
    }
}



