let listafuncionarios = '';
let idtipocontagem = 0;
const divcontagens = $('#divcontagens');
let arrcontagens = [];
const divtiposcontagem = $('#divtiposcontagem');
let timerbusca = '';
let timer = false;

function AtualizaListagemDiretores(){
    atualizaListagemComum('buscas_comuns',{tipo:34,selecionados:[53]},0,$('#selectdiretor'));
}

$('#atualizardiretores').click(()=>{
    AtualizaListagemDiretores();
})

function buscarTiposContagem(){

    let result = consultaBanco('buscas_comuns',{tipo:35});
    // console.log(result);
    if(result.length){
        let contador = 0;
        result.forEach(dados => {
            let idtipo = dados.VALOR;
            let nome = dados.NOMEEXIBIR;
            let descricao = dados.DESCRICAO;
            let espaco='';
            let checked='';

            if(contador>0){
                espaco = 'margin-espaco-esq'
            }
            // else{
            //     checked = 'checked';
            //     idtipocontagem = idtipo;
            // }

            let novoID = gerarID('.tipocontagem');
            $('#divtiposcontagem').append('<input type="radio" name="tipocontagem" id="tipocontagem'+novoID+'" class="tipocontagem '+espaco+'" title="'+descricao+'" '+checked+'><label for="tipocontagem'+novoID+'" title="'+descricao+'"> '+nome+'</label>');

            //Adiciona o evento do click
            $('#tipocontagem'+novoID).change(()=>{
                idtipocontagem = idtipo;

                if(idtipo>1 && $('#tipocontagem'+novoID).prop('checked')==true){
                    // $('#divacoestiposcontagem').addClass('grupo').removeAttr('hidden');
                    $('#divacoestiposcontagem').find('.titulo-grupo').html('Opções da '+nome);
                    $('#divacoestiposcontagem').find('.divacoes').html('<button id="iniciar'+novoID+'">Iniciar Contagem</button><button id="excluir'+novoID+'" class="margin-espaco-esq">Excluir Contagem</button><button id="imprimir'+novoID+'" class="margin-espaco-esq">Imprimir Contagem</button>');
                    $('#iniciar'+novoID).click(()=>{
                        IniciarContagem(idtipo);
                    })
                    $('#excluir'+novoID).click(()=>{
                        ExcluirContagem(idtipo);
                    })
                }else{
                    // $('#divacoestiposcontagem').removeAttr('class').attr('hidden','hidden');
                    $('#divacoestiposcontagem').find('.titulo-grupo').html('Opções da '+nome);
                    $('#divacoestiposcontagem').find('.divacoes').html('<button id="imprimir'+novoID+'">Imprimir Contagem</button>');
                }

                $('#imprimir'+novoID).click(()=>{
                    ImprimirContagem(idtipo);
                })
            })

            if(contador==0){
                $('#tipocontagem'+novoID).prop('checked',1).trigger('change');
            }

            contador++;
        });
    }
}

function buscaDadosBoletimVigente(){
    //Busca dasdos do BI
    let result = consultaBanco('chefia_busca_gerenciar',{tipo:12});
    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#data').html(retornaDadosDataHora(result[0].DATABOLETIM,2));            
            let periodo = 'Diurno';
            if(result[0].PERIODODIURNO==0){
                periodo = 'Noturno';
            }
            $('#numeracao').html(result[0].NUMERO+'/'+retornaDadosDataHora(result[0].DATABOLETIM,5)+' - '+periodo);
            $('#turno').html(result[0].NOMETURNO);
            $('#periodo').html(periodo);
            let iddiretor = result[0].IDDIRETOR;
            if(iddiretor==null){
                iddiretor = 0;
            }
            $('#selectdiretor').val(iddiretor);
        }
    };
}

function atualizaListaFuncionarios(){
    listafuncionarios = '';
    atualizaListagemComum('busca_funcionarios',{tipo: 8},$('#listafuncionario'),$('.selectsfuncionarios'));
    listafuncionarios = $('#listafuncionario').html();
}

function buscaDadosContagem(){
    let result = consultaBanco('chefia_busca_gerenciar',{tipo:13, idtipocontagem:idtipocontagem})
    // console.log(result);

    if(result.length){
        result.forEach(cont => {
            let idcontagem = cont.IDCONTAGEM;
            let idtipocontagem = cont.IDTIPO;
            let qtd = cont.QTD;
            let nomeraio = cont.NOMERAIO;
            let idfuncionario = 0;
            if(cont.IDUSUARIO!=null){
                idfuncionario = cont.IDUSUARIO;
            }
            let nomefuncionario = cont.NOMEUSUARIO;
            let autenticado = cont.AUTENTICADO;
            
            let index = arrcontagens.findIndex((cont)=>cont.idcontagem==idcontagem);
            if(index==-1){
                let novoID = gerarID('.contagem');
                let iddivcont = 'contagem'+novoID;

                divcontagens.append('<div id="'+iddivcont+'" class="grupo largura-total contagem">Raio/Local: <b>'+nomeraio+'</b> | Quantidade: <b>'+qtd+'</b><span class="margin-espaco-esq">Contado por:</span> <div class="inline contadopor"></div></div>')
                let divcont = $('#'+iddivcont);
    
                if(autenticado==1){
                    divcont.find('.contadopor').html('<b>'+nomefuncionario+'</b>');
                }else{
                    if(qtd==0){
                        divcont.find('.contadopor').html('<b>Local vazio</b>');
                    }else{
                        divcont.find('.contadopor').html('<select id="selectfunc'+iddivcont+'" class="selectsfuncionarios"></select>');
                        $('#selectfunc'+iddivcont).html(listafuncionarios);
                        adicionaEventoSelectFuncionarioContagem($('#selectfunc'+iddivcont),iddivcont);
                        $('#selectfunc'+iddivcont).val(idfuncionario);
                        inserirBotaoAutenticacao(divcont,idcontagem,idtipocontagem);
                    }
                }

                arrcontagens.push({
                    idtipocontagem: idtipocontagem,
                    iddivcont: iddivcont,
                    idcontagem: idcontagem,
                    qtd: qtd,
                    nomeraio: nomeraio,
                    idfuncionario: idfuncionario,
                    nomefuncionario: nomefuncionario,
                    autenticado: autenticado
                })
                
            }else{

                let iddivcont = arrcontagens[index].iddivcont;
                
                let divcont = $('#'+iddivcont);
                if(arrcontagens[index].idfuncionario!=idfuncionario && autenticado==1){
                    arrcontagens[index].idfuncionario=idfuncionario;
                    arrcontagens[index].nomefuncionario=nomefuncionario;
                    divcont.find('button').remove();
                }else{
                    if(arrcontagens[index].idfuncionario!=idfuncionario){
                        if(divcont.find('button.salvarcontagem').length==0){

                            divcont.append('<button class="salvarcontagem btntemp margin-espaco-esq" title="Salvar com o funcionário selecionado">Salvar</button><button class="cancelar btntemp margin-espaco-esq" title="Cancelar e selecionar para o funcionário já salvo">Cancelar</button>');
                            adicionaEventoBotoesContagem(iddivcont,idcontagem,idfuncionario,arrcontagens[index].idfuncionario,idtipocontagem);
                        }
                    }else {
                        divcont.find('.btntemp').remove(); 
                    }
                }

                if(autenticado!=arrcontagens[index].autenticado){
                    arrcontagens[index].autenticado=autenticado;
                    
                    let select = divcont.find('#selectfunc'+iddivcont);
                    if(autenticado==1 && select.length>0){
                        divcont.find('.contadopor').html('<b>'+nomefuncionario+'</b>');
                    }else if(autenticado==0 && select.length==0){
                        divcont.find('.contadopor').html('<select id="selectfunc'+iddivcont+'" class="selectsfuncionarios"></select>');
                        $('#selectfunc'+iddivcont).html(listafuncionarios);
                        adicionaEventoSelectFuncionarioContagem($('#selectfunc'+iddivcont),iddivcont);
                        inserirBotaoAutenticacao(divcont,idcontagem);
                    }
                }

            }
        });
    }else{
        arrcontagens=[];
    }
    verificaDivsContagem();
}

function verificaDivsContagem(){
    let divs = divcontagens.children();
    for(let i=0;i<divs.length;i++){
        let id = divs[i].id;
        let index = arrcontagens.findIndex((item)=>item.iddivcont==id);
        // console.log(index);

        if(index==-1 || arrcontagens[index].idtipocontagem!=idtipocontagem){
            divs[i].remove();
            if(index>-1){
                arrcontagens = arrcontagens.filter((item)=>item.iddivcont!=id);
            }
        }
    }
}

function adicionaEventoSelectFuncionarioContagem(select,iddivcont){
    select.change(()=>{
        let idfuncionario = select.val();
        if(idfuncionario!=NaN && idfuncionario!=undefined && idfuncionario!=null && idfuncionario>-1){
            let index = arrcontagens.findIndex((cont)=>cont.iddivcont==iddivcont);
            arrcontagens[index].idfuncionario=idfuncionario;
        }
    })
}

function adicionaEventoBotoesContagem(iddivcont,idcontagem,idfuncionario,idfuncionarioalterado,idtipocontagem){
    let divcont = $('#'+iddivcont);
    let btnSalvar = divcont.find('.salvarcontagem');
    let btnCancelar = divcont.find('.cancelar');

    btnSalvar.click(()=>{
        let dados = {
            tipo:6,
            idcontagem: idcontagem,
            idfuncionario: idfuncionarioalterado,
            idtipocontagem: idtipocontagem,
            blnvisuchefia: 1
        }
        salvarFuncionarioContagem(dados)
    })

    btnCancelar.click(()=>{
        $('#selectfunc'+iddivcont).val(idfuncionario).trigger('change');
    })
}

function adicionaEventoBotaoNovoBoletim(){
    $('#btnnovo').click(()=>{
        let dados = {
            tipo: 8
        }

        // console.log(dados);

        $.ajax({
            url: 'ajax/inserir_alterar/chefia_gerenciar.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            // console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
            }else{
                inserirMensagemTela(result.OK);
                buscaDadosBoletimVigente();
                AtualizaListagemDiretores();
            }
        });

    })
}

function IniciarContagem(idtipo){
    let dados = {
        tipo: 5,
        idtipocontagem: idtipo
    }

    // console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/chefia_gerenciar.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            inserirMensagemTela(result.OK);
        }
    });
}

function ExcluirContagem(idtipo){
    let dados = {
        tipo: 7,
        idtipocontagem: idtipo
    }

    // console.log(dados);

    let confirmacao = confirm('Com a exclusão dessa contagem informações inseridas serão totalmente excluídas.\r\rConfirma a exclusão dessa contagem?');

    if(!confirmacao){
        return;
    }

    $.ajax({
        url: 'ajax/inserir_alterar/chefia_gerenciar.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            inserirMensagemTela(result.OK);
        }
    });
}

function ImprimirContagem(idtipo){
    let result = consultaBanco('chefia_busca_gerenciar',{tipo:14, idtipocontagem:idtipo})
    // console.log(result);

    if(result.length){
        if(result[0].CONTAGEMEXISTE==0){
            inserirMensagemTela('<li class="mensagem-aviso"> A contagem ainda não foi iniciada </li>')
        }else if([0].includes(result[0].CONTAGEMLIBERADA)){
            inserirMensagemTela('<li class="mensagem-aviso"> Contagem não liberada para impressão pois algum Raio/Cela não foi contado. </li>')

        }else{
            let informacoes = [
                {get:'documento',valor:['imp_chef_cont']},
                {get:'idtipocontagem',valor:[idtipo]},
                {get:'boletimvigente',valor:[1]}
            ];
    
            // console.log(informacoes)
    
            imprimirDocumentos(informacoes);
        }

    }else{
        inserirMensagemTela('<li class="mensagem-erro"> Ocorreu um erro ao buscar informações do tipo de contagem solicitado para impressão. Contate o programador. </li>')
    }
}

function verificaContagemFimPlantao(){
    let arrbotoesexcluirevento = [
        'btnnovo',
        'btnliberar',
        'btncancelar'
    ]

    let result = consultaBanco('chefia_busca_gerenciar',{tipo:14, idtipocontagem:1})
    // console.log(result);

    if(result.length){
        if(result[0].CONTAGEMEXISTE==0){
            if($('#btnliberar').attr('disabled')=='disabled'){
                $('#btnliberar').removeAttr('disabled').click(()=>{
                    IniciarContagem(1);
                });
            }
            $('#btnliberar').removeAttr('disabled');
            arrbotoesexcluirevento = arrbotoesexcluirevento.filter((botao)=>botao!='btnliberar');                
        }else if([0,1].includes(result[0].CONTAGEMLIBERADA)){
            // if(result[0].CONTAGEMLIBERADA===0){
                if($('#btncancelar').attr('disabled')=='disabled'){
                    $('#btncancelar').removeAttr('disabled').click(()=>{
                        ExcluirContagem(1);
                    });
                }
                arrbotoesexcluirevento = arrbotoesexcluirevento.filter((botao)=>botao!='btncancelar');
            // }
            if(result[0].CONTAGEMLIBERADA===1){
                if($('#btnnovo').attr('disabled')=='disabled'){
                    $('#btnnovo').removeAttr('disabled');
                    adicionaEventoBotaoNovoBoletim();
                }
                arrbotoesexcluirevento = arrbotoesexcluirevento.filter((botao)=>botao!='btnnovo');
            }
        }
    };
    
    arrbotoesexcluirevento.forEach(botao => {
        $('#'+botao).off('click');
        if($('#'+botao).attr('disabled')!=true){
            $('#'+botao).attr('disabled',true);
        }
    });
}

function inserirBotaoAutenticacao(tdbotoes,id,idtipocontagem,title='Autenticar com usuário'){
    if(tdbotoes.find('.btnautentic').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnautentic margin-espaco-esq" title="'+title+'"><img src="imagens/autenticacao.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnautentic').click(()=>{
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

$('#salvarboletim').click(function(){
    if(verificaSalvarProcedimento()==true){
        salvar();
    }
})

function verificaSalvarProcedimento(){
    let mensagem = '';

    let elementoVerificar = $('#selectdiretor')
    if((elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar == undefined)){
        elementoVerificar.focus()
        mensagem = ("<li class = 'mensagem-aviso'> Selecione um Diretor! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvar(){

    let iddiretor = $('#selectdiretor').val();
    let dados = {
        tipo: 9,
        iddiretor: iddiretor
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
            buscaDadosBoletimVigente();
        }
    });
}

let arr = {
    seletor:'#imp_boletim',
    boletimvigente:1
}
eventoBotaoImprimirBoletim(arr);

buscarTiposContagem();
AtualizaListagemDiretores();
buscaDadosBoletimVigente();
atualizaListaFuncionarios();

timerbusca = setInterval(() => {
    buscaDadosContagem();
    verificaContagemFimPlantao();
}, 500);
