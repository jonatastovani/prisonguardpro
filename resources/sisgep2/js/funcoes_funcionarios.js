function inserirBotaoAlterarFuncionario(tdbotoes,id,title='Alterar Dados'){
    if(tdbotoes.find('.btnaltfunc').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaltfunc" title="'+title+'"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnaltfunc').click(()=>{
            idfuncionariopopfunc = id;
            abrirPopPopFuncionario();
        })
    }
}

function inserirBotaoPermissaoTemporaria(tdbotoes,id,title='Inserir Permissão Temporária'){
    if(tdbotoes.find('.btnpermtemp').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnpermtemp" title="'+title+'"><img src="imagens/relogio2.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnpermtemp').click(()=>{
            idfuncionariopopfunc = id;
            permtemporaria = 1
            abrirPopPopFuncionario();
        })
    }
}

