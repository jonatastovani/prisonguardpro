const divfuncionarios = $('#divfuncionarios');
const divtiposproced = $('#divtiposproced');
let arrfuncionariosbatepisograde = [];
let idtipoprocedimento = 0;
let listafuncionarios = '';

function buscarTiposProcedimentos(){

    let result = consultaBanco('buscas_comuns',{tipo:33});
    // console.log(result);
    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            let contador = 0;
            result.forEach(dados => {
                let idproced = dados.VALOR;
                let nome = dados.NOMEEXIBIR;
                let espaco='';

                if(contador>0){
                    espaco = 'margin-espaco-esq'
                }

                let novoID = gerarID('.tipoproced');
                divtiposproced.append('<input type="radio" name="tipoproced" id="tipoproced'+novoID+'" class="tipoproced '+espaco+'"><label for="tipoproced'+novoID+'"> '+nome+'</label>')
                contador++;

                //Adiciona o evento do click
                $('#tipoproced'+novoID).click(()=>{
                    idtipoprocedimento = idproced;
                    $('#camposselectfuncionario').removeAttr('hidden');
                    efetuaBuscasProcedimentos();
                })
            });
        }
    }
}

function efetuaBuscasProcedimentos(){
    
    divfuncionarios.html('');
    arrfuncionariosbatepisograde = [];
    
    let result = consultaBanco('chefia_busca_gerenciar',{tipo:11, idtipoproced:idtipoprocedimento, boletimvigente:1});
    // console.log(result);

    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(dados => {
                let idfuncionario = dados.IDUSUARIO;
                let index = -1;
                let cela = dados.CELA;
                let idraio = dados.IDRAIO;
                let nome = dados.NOME;
                let nomecompleto = dados.NOMECOMPLETO;
                let idbanco = dados.ID;

                while (index==-1) {
                    index = arrfuncionariosbatepisograde.findIndex((func)=>func.idfuncionario==idfuncionario);

                    if(index==-1){
                        buscarFuncionario(idfuncionario);
                    }
                }
                
                let indexraio = arrfuncionariosbatepisograde[index].celas.findIndex((raio)=>raio.idraio==idraio);
                if(indexraio==-1){
                    arrfuncionariosbatepisograde[index].celas.push({
                        idraio: idraio,
                        nome: nome,
                        nomecompleto: nomecompleto,
                        celas: [{
                            cela: cela,
                            idbanco: idbanco
                        }]
                    })
                }else{
                    arrfuncionariosbatepisograde[index].celas[indexraio].celas.push({
                        cela: cela,
                        idbanco: idbanco
                    })
                }
            });
            verificaQuantidadeDeCelas();
        }
    }
}

function atualizaListaFuncionarios(){
    listafuncionarios = '';
    atualizaListagemComum('busca_funcionarios',{tipo: 8},$('#listafuncionario'),$('#selectfuncionario'),true,false,false);
    listafuncionarios = $('#listafuncionario').html();
}

$('#selectfuncionario').change(function(){
    var idfuncionario = $('#selectfuncionario').val();
    
    if(idfuncionario!=0 && idfuncionario!=null){
        $('#searchfuncionario').val(idfuncionario);
    }
})

$('#searchfuncionario').change(function(){
    var id = $('#searchfuncionario').val();
    
    if(id!=$('#selectfuncionario').val()){
        buscaSearchComum('busca_funcionarios',{tipo:4, idfuncionario:id},$('#searchfuncionario'),$('#selectfuncionario'),$('#inserir'));
    }
})

$('#inserir').click(()=>{
    let idfuncionario = $('#selectfuncionario').val();
    if(idfuncionario>0 && idfuncionario!=undefined && idfuncionario!=NaN){

        let iddivfunc = buscarFuncionario(idfuncionario);

        if(iddivfunc!=false){
            $('#searchfuncionario').val('').trigger('change');
            $('#'+iddivfunc).find('.selectraio').trigger('change').focus(); //Executar para poder ativar a coloração da divfunc
        }
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Selecione um funcionário! </li>');
    }
})

function buscarFuncionario(idfuncionario){
    let funcexiste = false;
    //Verifica se o funcionário já não está inserido
    arrfuncionariosbatepisograde.forEach(func => {
        if(func.idfuncionario==idfuncionario){
            inserirMensagemTela('<li class="mensagem-aviso"> Funcionário <b>'+func.nomefuncionario+'</b> já consta na escala apresentada. </li>');
            funcexiste = func.divfunc;
        }
    });

    if(funcexiste!=false){
        return funcexiste;
    }

    let retorno = false;
    let dados = {
        tipo: 4,
        idfuncionario: idfuncionario
    }
    //console.log(dados);

    let result = consultaBanco('busca_funcionarios',dados);

    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(dados => {
                let nomefuncionario = dados.NOME;
                let idturnofunc = dados.IDTURNO;
                let idescala = dados.IDESCALA;

                let novoID = gerarID('.funcionario');
                let linha = '<div id="funcionario'+novoID+'" class="funcionario relative largura-total"><div class="flex"><div class="nomefuncionario">Nome: <b>'+nomefuncionario+'</b></div><span class="botoes margin-espaco-esq"></span><div class="celas largura-restante" title="Nenhuma seleção">Celas: Nenhuma seleção</div></div></div>';
                divfuncionarios.append(linha);
                
                let iddivfunc = 'funcionario'+novoID
                arrfuncionariosbatepisograde.push({
                    divfunc: iddivfunc,
                    idfuncionario: idfuncionario,
                    nomefuncionario: nomefuncionario,
                    celas: []
                })

                retorno = iddivfunc;
                let divfunc = $('#'+iddivfunc)
                divfunc.append('<button class="fechar-absolute">&times;</button>');
                adicionaEventoExcluir(divfunc);
                adicionaEventoExcluirFuncionario(divfunc.find('.fechar-absolute'),iddivfunc);

                inserirBotaoAlterarCelasSelecionadas(divfunc.find('.botoes'),iddivfunc,1)
                adicionaEventosFuncionario($('#selectraio'+novoID),iddivfunc);
            });
        }
    }
    return retorno;
}

function adicionaEventosFuncionario(select,iddivfunc){
    select.change(()=>{
        if(select.val()!=undefined && select.val()!=NaN){
            let index = arrfuncionariosescala.findIndex((item)=>item.divfunc==iddivfunc);
            arrfuncionariosescala[index].idposto = select.val();
            if(select.val()>0){
                $('#'+iddivfunc).removeClass('postopendente');
                $('#'+iddivfunc).addClass('postook');
            }else{
                $('#'+iddivfunc).removeClass('postook');
                $('#'+iddivfunc).addClass('postopendente');
            }
        }
    })
    select.keydown((e)=>{
        // console.log(e.which);
        let key = e.which || e.keyCode;
        if(key==46){
            select.val(0).trigger('change');
        }
    })
}

function adicionaEventoExcluirFuncionario(botao,iddivfunc){
    botao.click(()=>{
        arrfuncionariosbatepisograde = arrfuncionariosbatepisograde.filter((item)=>item.divfunc!=iddivfunc)
    })
}

function adicionaEventoPesquisaEscala(){
    let seletores = [];
    seletores.push(['#selectturno','change']);
    seletores.push(['#rbdiaria','click']);
    seletores.push(['#rbpadrao','click']);

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                efetuaBuscasProcedimentos();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    efetuaBuscasProcedimentos();
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                efetuaBuscasProcedimentos();
            })
        }
    });
}

function verificaQuantidadeDeCelas(){
    let padraoretorno = 'Nenhuma seleção';
    arrfuncionariosbatepisograde.forEach(func => {
        let divcelas = $('#'+func.divfunc).find('.celas');
        let html = '';
        let title = '';
        let qtdraios = func.celas.length;
        let qtdcelas = 0;
        let nomeraio = '';
        func.celas.forEach(raios => {
            if(nomeraio!=raios.nome){
                nomeraio = raios.nome;
                if(title!=''){
                    title+='\r'+nomeraio+': ';
                }else{
                    title+=nomeraio+': ';
                }
                nomeraio = raios.nome;
            }
            let contador = 0;
            raios.celas.forEach(cela => {
                if(contador>0){
                    title+=', '+cela.cela;
                }else{
                    title+=cela.cela;
                }
                contador++;
                qtdcelas++;
            });
        });
        if(qtdraios>0){
            if(qtdraios==1){
                html='1 raio, ';
            }else{
                html=qtdraios+' raios, ';
            }
            if(qtdcelas==1){
                html+='1 cela';
            }else{
                html+=qtdcelas+' celas';
            }
            html = '<b>'+html+'</b>'
        }else{
            html=padraoretorno;
            title=padraoretorno;
        }
        divcelas.html('Celas: '+ html).attr('title',title);
    });
}

$('#atualizarlista').click(()=>{
    atualizaListaFuncionarios();
})

$('#imp_proced').click(()=>{
    if(idtipoprocedimento>0){
        let dados = {
            idtipo:idtipoprocedimento,
            boletimvigente:1
        }
        ImprimirProcedimentoChefia(dados)
    }else{
        inserirMensagemTela('<li class="mensagem-erro"> Selecione um Tipo de Procedimento </li>');
    }
})

function ImprimirProcedimentoChefia(arr){
    let idtipo = arr.idtipo;
    let boletimvigente = 0;
    if(arr.boletimvigente!=undefined){
        boletimvigente = arr.boletimvigente;
    }

    let result = consultaBanco('chefia_busca_gerenciar',{tipo:11, idtipoproced:idtipo, boletimvigente:boletimvigente});

    // console.log(result);

    if(result.length){
        let informacoes = [
            {get:'documento',valor:['imp_chef_proced']},
            {get:'idtipoproced',valor:[idtipo]},
            {get:'boletimvigente',valor:[boletimvigente]}
        ];

        // console.log(informacoes)

        imprimirDocumentos(informacoes);
    }
}

$('#salvarbatepisograde').click(function(){
    if(verificaSalvarProcedimento()==true){
        salvarProcedimento();
    }
})

function verificaSalvarProcedimento(){
    let mensagem = '';

    // let elementoVerificar = $('#selectpresopopnovamud')
    // if((elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN)){
    //     elementoVerificar.focus()
    //     mensagem = ("<li class = 'mensagem-aviso'> Selecione um Preso! </li>")
    //     inserirMensagemTela(mensagem)
    // }
    if(arrfuncionariosbatepisograde.length==0){
        $('#searchfuncionario').focus();
        mensagem = ("<li class = 'mensagem-aviso'> Nenhum funcionário foi adicionado! </li>");
        inserirMensagemTela(mensagem);
    }
    arrfuncionariosbatepisograde.forEach(func => {
        if(func.celas.length==0){
            if(mensagem==""){
                $('#'+func.divfunc).find('.selectraio').focus();
            }
            mensagem = ("<li class = 'mensagem-aviso'> Nenhuma cela selecionada para o(a) funcionário(a) <b>"+func.nomefuncionario+"</b>! </li>");
            inserirMensagemTela(mensagem);
        }
    });

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarProcedimento(){
    
    let dados = {
        tipo: 4,
        idtipoproced: idtipoprocedimento,
        arrfuncionarios: arrfuncionariosbatepisograde
    }

    // console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/chefia_gerenciar.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            inserirMensagemTela(result.OK);
            efetuaBuscasProcedimentos();
        }
    });
}

buscarTiposProcedimentos();
atualizaListaFuncionarios();
// efetuaBuscasProcedimentos();

