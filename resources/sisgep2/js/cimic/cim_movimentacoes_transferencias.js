const containerPresos = $('#presosordemsaida')
//Array de números de ofícios já adicionados ao imprimir tudo.
let oficiosdestino=[];
let acao;
let confirmacaotrans=0;

$('#form1').submit(function(e){
    //No evento de submit do botão, não permitirá recarregar a página
    e.preventDefault()
});

//Verifica a ação para habilitar ou não o campo de SELECIONAR ORDEM
function verificaAcao(){
    limparCampos(true);
    if($('#incluir').prop('checked')){
        $('#selectordem').attr("disabled", "disabled");
        $('#searchordem').attr("disabled", "disabled");
        $('#titulo').html('Incluir Transferência');
        $('#impr_entrada_presos').attr("hidden", "hidden");
        $('#impr_digitais_presos').attr("hidden", "hidden");
        acao = 'incluir';
    }else{
        $('#selectordem').removeAttr("disabled");
        $('#searchordem').removeAttr("disabled").focus();
        $('#impr_entrada_presos').removeAttr("hidden");
        $('#impr_digitais_presos').removeAttr("hidden");
        $('#titulo').html('Alterar Transferência');
        acao = 'alterar';
    }
    atualizarListasTransferencias();
}

//Limpa todos os campos
//blnLimparIDEntrada = true para limpar até o select identrada
function limparCampos(blnLimparIDOrdem=false){
    if(blnLimparIDOrdem===true){
        $('#searchordem').val('');
        $('#selectordem').val(0);
    }
    $('#selectdestino').val(0)
    $('#searchdestino').val('')
    $('#selectpresos').val(0)
    $('#searchpresos').val('')
    $('#datasaida').val(retornaDadosDataHora(new Date(),1));
    $('#horasaida').val(retornaDadosDataHora(new Date(),6));
    $('#ordemsaida').empty().parent().removeAttr('class');
    $('#oficioescolta').empty().parent().removeAttr('class');
    $('.ferramentas #excluirordem').remove()
    $('.temp').remove();
    $('#botoesimpressao').removeAttr('class');
    containerPresos.empty();
}

$('.adicionarpreso').click(function(){
    let idpreso = $('#selectpresos').val();
    if(idpreso>0){
        adicionarPreso(0,idpreso);
        $('#searchpresos').val('');
        $('#selectpresos').val(0);
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhum preso foi selecionado! </li>')
    }
})

function verificaElementoExistente(containerPai, seletor, datapesq='', valorpesq=''){
    let encontrados = containerPai.find(seletor)
    if(encontrados.length>0){
        if(datapesq!=''){
            for(let i=0;i<encontrados.length;i++){
                if($('#'+encontrados[i].id).data(datapesq) == valorpesq){
                    return $('#'+encontrados[i].id);
                }else{
                    return false;
                }
            }
        }else{
            return true;
        }
    }else{
        return false;
    }
}

//Adiciona um preso no formulário
function adicionarPreso(idbanco, idpreso){
    
    let nome;
    let matric;
    let matricula;
    let raiocela;

    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo:1, idpreso:idpreso},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            return;
        }else{
            nome = result[0].NOME;
            matric = result[0].MATRICULA;
            matricula = midMatricula(result[0].MATRICULA,3);
            raiocela = result[0].RAIOCELA;
        }
    });

    if(verificaElementoExistente(containerPresos,'#'+matric)===true){
        inserirMensagemTela('<li class="mensagem-aviso"> Este preso já está inserido! </li>')
        $('#searchdestinterm'+matric).focus();
        return;
    }
    
    let dadosmovimentacao = '<div class="grupo-block"><h4 class="titulo-grupo">Dados da Movimentação</h4><div><label for="searchtipo'+matric+'">Cód. Tipo </label><input type="search" id="searchtipo'+matric+'" list="listatipos" style="width: 90px;" autocomplete="off"><label for="selecttipo'+matric+'" class="margin-espaco-esq">Tipo </label><select id="selecttipo'+matric+'" class="tipos" style="width: 100%; max-width: 565px;"><option value="0">Selecione o Tipo</option></select></div><div><label for="searchmotivo'+matric+'">Cód. Motivo </label><input type="search" id="searchmotivo'+matric+'" list="listamotivos'+matric+'" style="width: 90px;" autocomplete="off"><label for="selectmotivo'+matric+'" class="margin-espaco-esq">Motivo </label><select id="selectmotivo'+matric+'" class="motivos" style="width: 100%; max-width: 530px;"><option value="0">Selecione o Motivo</option></select><datalist id="listamotivos'+matric+'"></datalist></div></div>';

    let apresentacoes = '<div class="grupo-block"><h4 class="titulo-grupo">Apresentações</h4><div id="apresentacoes'+matric+'" class="container-flex max-height-200"></div><div class="grupo-block"><label for="searchapres'+matric+'">Cód. Local </label><input type="search" id="searchapres'+matric+'" list="listaapresentacao" style="width: 90px;" autocomplete="off"><label for="selectapres'+matric+'" class="espaco-esq">Local </label><select id="selectapres'+matric+'" class="locaisapresentacao" style="width: 100%; max-width: 410px;"><option value="0">Selecione o Local</option></select><button id="inserirapres'+matric+'" class="margin-espaco-esq">Adicionar</button></div></div>';

    let destinos = '<div class="grupo-block"><h4 class="titulo-grupo">Destinos</h4><div id="destinos'+matric+'" class="container-flex max-height-200"></div><div class="grupo-block"><label for="searchdestinterm'+matric+'">Cód. Destino</label><input type="search" id="searchdestinterm'+matric+'" list="listaunidades" style="width: 90px;" autocomplete="off"><label for="selectdestinterm'+matric+'" class="margin-espaco-esq">Destino </label><select id="selectdestinterm'+matric+'" class="locaisunidades" style="width: 100%; max-width: 380px;"><option value="0">Selecione o Destino</option></select><button id="inserirdest'+matric+'" class="margin-espaco-esq">Adicionar</button></div></div>';

    containerPresos.append('<div class="item-flex form-preso-mov-cimic largura-total relative" id="'+matric+'" data-idbanco="'+idbanco+'" data-idpreso="'+idpreso+'"><button class="fechar-absolute">&times;</button><div>Matrícula: <b>'+matricula+'</b></div><div>Nome: <b><span class="nomepreso">'+nome+'</span></b> - Raio/Cela: <b>'+raiocela+'</b></div><div><label for="dataretorno'+matric+'">Data Retorno </label><input type="date" id="dataretorno'+matric+'" class="dataretorno"></div>'+dadosmovimentacao+apresentacoes+destinos+'</div>')
    
    $('#selectdestinterm'+matric).html($('#listaunidades').html());    
    $('#selecttipo'+matric).html($('#listatipos').html());    
    $('#selectapres'+matric).html($('#listaapresentacao').html());    
    
    let containerPai = $('#'+matric);

    adicionaEventoExcluir(containerPai);
    adicionaEventoAdicionarApresentacao(matric);
    adicionaEventoAdicionarDestino(matric);
    adicionaEventoSearch($('#searchapres'+matric),$('#selectapres'+matric),3,$('#inserirapres'+matric));
    adicionaEventoSelect($('#searchapres'+matric),$('#selectapres'+matric),3);
    adicionaEventoSearch($('#searchdestinterm'+matric),$('#selectdestinterm'+matric),5,$('#inserirdest'+matric));
    adicionaEventoSelect($('#searchdestinterm'+matric),$('#selectdestinterm'+matric),5);
    adicionaEventoSearch($('#searchtipo'+matric),$('#selecttipo'+matric),7,$('#searchmotivo'+matric));
    adicionaEventoSelect($('#searchtipo'+matric),$('#selecttipo'+matric),7,matric);
    adicionaEventoSearch($('#searchmotivo'+matric),$('#selectmotivo'+matric),9,$('#searchapres'+matric));
    adicionaEventoSelect($('#searchmotivo'+matric),$('#selectmotivo'+matric),9);

    if(idbanco==0){
        $('#searchtipo'+matric).focus();
    }
    return matric;
}

function adicionaEventoAdicionarApresentacao(matric){
    $('#inserirapres'+matric).click(()=>{
        let idapres = $('#selectapres'+matric).val();

        if(idapres>0){
            adicionarApresentacoes(0,matric,idapres);
            $('#selectapres'+matric).val(0);
            $('#searchapres'+matric).val('');
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Nenhum local foi selecionado! </li>')
        }
    })
}

function adicionaEventoAdicionarDestino(matric){
    $('#inserirdest'+matric).click(()=>{
        let iddest = $('#selectdestinterm'+matric).val();

        if(iddest>0){
            let destinoexistente = verificaElementoExistente($('#destinos'+matric),".destinos",'iddestino',iddest);
            if(destinoexistente!==false){
                inserirMensagemTela('<li class="mensagem-aviso"> Este destino já foi inserido! </li>')
                destinoexistente.find('.datadestino').focus();
                return;
            }
            adicionarDestinos(0,matric,iddest);
            $('#selectdestinterm'+matric).val(0);
            $('#searchdestinterm'+matric).val('');
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Nenhum destino foi selecionado! </li>')
        }
    })
}

function adicionaEventoDataDestino(iddestinterm, iddestino){
    let elemento = $('#dest'+iddestinterm).find('.datadestino');
    elemento.on('change', ()=>{
        alterarDataDestinoExistente(iddestino,elemento.val());
    })
}

function adicionaEventoSearch(search, select, tipo, elementofoco=''){

    search.change(function(){
        let id = search.val();
        let dados = [];

        if(tipo==3){
            dados = {
                tipo: tipo,
                idlocal: id
            }
        }else if(tipo==5){
            dados = {
                tipo: tipo,
                iddestino: id
            }
        }else if(tipo==7){
            dados = {
                tipo: tipo,
                idtipo: id
            }
        }else if(tipo==9 || tipo==11){
            dados = {
                tipo: tipo,
                idmotivo: id
            }
        }

        if(id!=select.val()){
            if(id>0){
                $.ajax({
                    url: 'ajax/consultas/buscas_comuns.php',
                    method: 'POST',
                    //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
                    data: dados,
                    //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                    dataType: 'json',
                    async: false
                    }).done(function(result){
                    //console.log(result)

                    if(result.MENSAGEM){
                        inserirMensagemTela(result.MENSAGEM)
                        //Limpa os campos pois o valor digitado não existe
                        select.val(0);
                        search.val('');
                    }else{
                        select.val(result[0].VALOR).trigger('change');
                        if(select.val()==null){
                            select.val(0);
                            search.val('');
                        }else{
                            elementofoco.focus();
                        }                    
                    }
                });
            }else{
                select.val(0);
                search.val('');
            }
        }
    })
}

function adicionaEventoSelect(search, select, tipo, matric=''){

    select.change(function(){
        var id = select.val();
        if(id!=0){
            search.val(id);
        }else{
            search.val('');
        }
        if(tipo==7 && matric!='' && matric>0){
            atualizaListagemComum('buscas_comuns',{tipo:8, idtipo: id},$('#listamotivos'+matric),$('#selectmotivo'+matric));
        }
    })
}

//Adiciona apresentacoes do preso
function adicionarApresentacoes(idbanco, matric, idlocal=0){

    let localapres = '';
    let dataapres = '';
    let horaapres = '';
    let oficio = '';
    let container = $('#apresentacoes'+matric);
    let idmotivoapres = 1;

    if(idbanco>0){
        //Busca os dados da apresentação inserida
        $.ajax({
            url: 'ajax/consultas/cim_busca_dados_transferencias.php',
            method: 'POST',
            data: {tipo:4, idbanco:idbanco},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                idlocal = result[0].IDLOCAL;
                localapres = result[0].NOMELOCAL;
                idoficio = result[0].IDOFICIO;
                idmotivoapres = result[0].IDMOTIVOAPRES;
                dataapres = retornaDadosDataHora(result[0].DATAAPRES,1);
                horaapres = retornaDadosDataHora(result[0].DATAAPRES,6);
                oficio = 'Ofício <b>'+result[0].OFICIO+'</b>';
            }
        });
    }else{
        //Busca os dados da apresentação
        $.ajax({
            url: 'ajax/consultas/buscas_comuns.php',
            method: 'POST',
            data: {tipo:3, idlocal:idlocal},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                localapres = result[0].NOMEEXIBIR;
            }
        });
    }

    let novoID = gerarID('.apresentacao');

    container.append('<div id="apres'+novoID+'" class="grupo apresentacao largura-total relative" data-idbanco="'+idbanco+'" data-idlocal="'+idlocal+'"><div>Local: <b><span class="localapresentacao">'+localapres+'</span></b></div><div><span class="numero-oficio">'+oficio+'</span></div><div>Data de apresentacao: <input type="date" class="dataapresentacao" value="'+dataapres+'"> Hora: <input type="time" class="horaapresentacao" value="'+horaapres+'"></div><button class="fechar-absolute">&times;</button><div><label for="searchmotivoapres'+novoID+'">Cód. Motivo </label><input type="search" id="searchmotivoapres'+novoID+'" list="listamotivosapres" style="width: 90px;" autocomplete="off"><label for="selectmotivoapres'+novoID+'" class="margin-espaco-esq">Motivo </label><select id="selectmotivoapres'+novoID+'" class="motivoapres" style="width: 100%; max-width: 470px;"><option value="0">Selecione o Motivo</option></select></div></div>')

    adicionaEventoExcluir($('#apres'+novoID));
    container = container.find('#apres'+novoID)
    adicionaEventoSearch($('#searchmotivoapres'+novoID),$('#selectmotivoapres'+novoID),11,$('#selectmotivoapres'+novoID))
    adicionaEventoSelect($('#searchmotivoapres'+novoID),$('#selectmotivoapres'+novoID),11)
    $('#selectmotivoapres'+novoID).html($('#listamotivosapres').html());
    $('#selectmotivoapres'+novoID).val(idmotivoapres).trigger('change');

    if(idbanco==0){
        container.find('.dataapresentacao').focus();
    }else{
        container.append('<button id="impapres'+novoID+'" class="temp" style="position: absolute; right: 20px; top: 0px;">Imprimir</button>');
        eventoBotaoImprimir('#impapres'+novoID,[{get:'documento',valor:['oficio_apresentacao']},{get:'apresentacoes',valor:[idbanco]},{get:'opcaocabecalho',valor:[2]},{get:'query',valor:[1]}]);
        eventoBotaoImprimir('.imp_tudo',[{get:'documento',valor:['oficio_apresentacao']},{get:'apresentacoes',valor:[idbanco]},{get:'opcaocabecalho',valor:[2]},{get:'query',valor:[1]}]);
    }

}

//Adiciona destinos de parada do preso
function adicionarDestinos(idbanco, matric, iddestino=0){

    let container = $('#destinos'+matric);
    let localdest = '';
    let datadest = '';
    let oficio = '';
    let comentario = '';
    let destfinal = 'checked';
    let primlocal = '';
    
    if(idbanco>0){
        //Busca os dados da apresentação inserida
        $.ajax({
            url: 'ajax/consultas/cim_busca_dados_transferencias.php',
            method: 'POST',
            data: {tipo:6, idbanco:idbanco},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                iddestino = result[0].IDLOCAL;
                localdest = result[0].NOMEUNIDADE;
                idoficio = result[0].IDOFICIO;
                if(result[0].DATAINTERM!=null){
                    datadest = result[0].DATAINTERM;
                }
                if(result[0].COMENTARIO!=null){
                    comentario = result[0].COMENTARIO;
                }
                oficio = 'Ofício <b>'+result[0].OFICIO+'</b>';
                primlocal = result[0].PRIMEIROLOCAL;
                destfinal = result[0].DESTINOFINAL;
            }
        });
    }else{
        //Busca os dados da apresentação
        $.ajax({
            url: 'ajax/consultas/buscas_comuns.php',
            method: 'POST',
            data: {tipo:5, iddestino:iddestino},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                localdest = result[0].NOMEEXIBIR;
                if(container.find('.destinos').length>0){
                    primlocal='';
                }else{
                    primlocal='checked';
                }
            }
        });
    }

    if(localdest==''){
        return false;
    }
    let novoID = gerarID('.destinos');

    container.append('<div id="dest'+novoID+'" class="grupo destinos largura-total relative" data-idbanco="'+idbanco+'" data-iddestino="'+iddestino+'"><div>Local: <b>'+localdest+'</b></div><div><span class="numero-oficio">'+oficio+'</span></div><div>Data da chegada: <input type="date" class="datadestino" value="'+datadest+'"></div><div class="flex"><div>Comentário após motivo:</div><input type="text" class="largura-restante comentario" placeholder="Ex: Pinheiros IV" value="'+comentario+'"></div><div><input type="radio" name="primlocal'+matric+'" id="primlocal'+novoID+'" class="primeirolocal" '+primlocal+'><label for="primlocal'+novoID+'">Primeiro Destino</label><input type="radio" name="destfinal'+matric+'" id="destfinal'+novoID+'" class="destinofinal margin-espaco-esq" '+destfinal+'><label for="destfinal'+novoID+'">Destino Final</label></div><button class="fechar-absolute">&times;</button></div>')

    container = $('#dest'+novoID)
    adicionaEventoExcluir(container);
    adicionaEventoDataDestino(novoID, iddestino);
    obterDataDestinoExistente(novoID, iddestino);

    if(idbanco==0){
        if(container.find('.datadestino').val()!=''){
            container.find('.comentario').focus();
        }else{
            container.find('.datadestino').focus();
        }
    }else{
        container.append('<button id="impdest'+novoID+'" class="temp" style="position: absolute; right: 20px; top: 0px;">Imprimir</button>');
        eventoBotaoImprimir('#impdest'+novoID,[{get:'documento',valor:['oficio_transferencia']},{get:'oficios',valor:[idoficio]},{get:'query',valor:[1]}]);
        if(oficiosdestino.includes(idoficio)===false){
            eventoBotaoImprimir('.imp_tudo',[{get:'documento',valor:['oficio_transferencia']},{get:'oficios',valor:[idoficio]},{get:'query',valor:[1]}]);
            oficiosdestino.push(idoficio)
        }
    }
}

function alterarDataDestinoExistente(iddestino,datachegada){
    let todosdestinos = $('.destinos').closest('[data-iddestino]');
    
    for(let i=0;i<todosdestinos.length;i++){
        let destino = $('#'+todosdestinos[i].id);
        if(destino.data('iddestino')==iddestino){
            destino.find('.datadestino').val(datachegada);
        }
    }
}

function obterDataDestinoExistente(iddestinterm, iddestino){
    let todosdestinos = $('.destinos').closest('[data-iddestino]');
    let elemento = $('#dest'+iddestinterm).find('.datadestino');

    for(let i=0;i<todosdestinos.length;i++){
        let destino = $('#'+todosdestinos[i].id);
        if(destino.data('iddestino')==iddestino){
            elemento.val(destino.find('.datadestino').val());
        }
    }
}

//Botões de acao
$('#incluir').change(function(){
    verificaAcao();
});
$('#alterar').change(function(){
    verificaAcao();
});

//Atualiza todas as listas da página
function atualizarListasTransferencias(){
    atualizaListagemComum('busca_presos',{tipo:2, tipobusca:1, valor:1, tiporetorno:2},$('#listapresos'),$('#selectpresos'));
    atualizaListagemComum('buscas_comuns',{tipo:4},$('#listaunidades'),$('.locaisunidades'));
    atualizaListagemComum('buscas_comuns',{tipo:2},$('#listaapresentacao'),$('.locaisapresentacao'));
    atualizaListagemComum('buscas_comuns',{tipo:6, selecionados: '4,5,6'},$('#listatipos'),$('.tipos'));
    atualizaListagemComum('buscas_comuns',{tipo:10},$('#listamotivosapres'),$('.motivoapres'));
    if(acao=='alterar'){
        atualizaListagemComum('cim_busca_dados_transferencias',{tipo:1},$('#listaordem'),$('#selectordem'));
    }
}

function eventoExcluirOrdemSaida(botao){
    botao.click(()=>{
        if(confirm('Confirma a exclusão desta Ordem de Saída?\r\r***ATENÇÃO***\rEsta ação não poderá ser desfeita!')==true){
            let idordem = $('#selectordem').val();
            if(idordem!=0 && idordem!=null && idordem!=undefined){
                excluirOrdemSaida(idordem);
            }
        }
    })
}

function excluirOrdemSaida(idordem){
    $.ajax({
        url: 'ajax/inserir_alterar/cim_movimentacoes_transferencias.php',
        method: 'POST',
        data: {tipo:5, idordem: idordem},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            inserirMensagemTela(result.OK)
            limparCampos(true);
            atualizarListasTransferencias()
        }
    })
}

//Busca os dados da ordem de saída
function buscarDadosOrdemSaida(idordem){

    $.ajax({
        url: 'ajax/consultas/cim_busca_dados_transferencias.php',
        method: 'POST',
        data: {tipo:2, idordem: idordem},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#datasaida').val(retornaDadosDataHora(result[0].DATASAIDA,1));            
            $('#horasaida').val(retornaDadosDataHora(result[0].DATASAIDA,6));            
            $('#selectdestino').val(result[0].IDDESTINO).trigger('change');
            $('#ordemsaida').html('Ordem de Saída <b>'+result[0].ORDEM+'</b>').parent().addClass('grupo');
            $('#oficioescolta').html('Ofício Escolta <b>'+result[0].OFICIO+'</b>').parent().addClass('grupo');
            
            $('#botoesimpressao').addClass('ferramentas').append('<button class="imp_ordem temp">Imp Ordem Saída</button>');
            eventoBotaoImprimir('.imp_ordem',[{get:'documento',valor:['ordem_saida_presos']},{get:'ordens',valor:[idordem]},{get:'query',valor:[1]}]);

            $('#botoesimpressao').append('<button class="imp_escolta temp margin-espaco-esq">Imp Ofício Escolta</button>');
            eventoBotaoImprimir('.imp_escolta',[{get:'documento',valor:['oficio_escolta']},{get:'ordens',valor:[idordem]},{get:'query',valor:[1]}]);

            $('#botoesimpressao').append('<button class="imp_tudo temp margin-espaco-esq">Imp Todos Docs</button>');
            eventoBotaoImprimir('.imp_tudo',[{get:'documento',valor:['ordem_saida_presos']},{get:'ordens',valor:[idordem]},{get:'query',valor:[1]}]);
            eventoBotaoImprimir('.imp_tudo',[{get:'documento',valor:['oficio_escolta']},{get:'ordens',valor:[idordem]},{get:'query',valor:[1]}]);
            
            $('#botoesimpressao').append('<button class="btn-excluir temp margin-espaco-esq">Excluir Ordem de Saída</button>');
            eventoExcluirOrdemSaida($('#botoesimpressao').find('.btn-excluir'));
            
            result.forEach(linha => {
                let idmovimentacao = linha.IDMOVIMENTACAO;
                let matric = adicionarPreso(idmovimentacao, linha.IDPRESO);

                $('#selecttipo'+matric).val(linha.IDTIPOMOV).trigger('change');
                $('#selectmotivo'+matric).val(linha.IDMOTIVOMOV).trigger('change');
                if(linha.DATARETORNO!=null){
                    $('#dataretorno'+matric).val(linha.DATARETORNO);
                }
                
                //Inserir mensagem de aviso que o registro não poderá ser mais alterado pois já foi executado. A excessão de alteração se dá somente a data de retorno 
                if(linha.REALIZADO == 1){
                    $('#'+matric).append('<div class="grupo-block red"><b>Esta movimentação já foi executada, não será mais possível alterar as informações, sendo a excessão somente à Data de Retorno.</b></div>')
                }
                if(matric!=null && matric!=undefined){
                    //busca as apresentações
                    $.ajax({
                        url: 'ajax/consultas/cim_busca_dados_transferencias.php',
                        method: 'POST',
                        data: {tipo:3, idmovimentacao: idmovimentacao},
                        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                        dataType: 'json',
                        async: false
                    }).done(function(result){
                        //console.log(result)
                
                        if(result.MENSAGEM){
                            inserirMensagemTela(result.MENSAGEM)
                        }else{                           
                            result.forEach(linha => {
                                adicionarApresentacoes(linha.IDAPRES,matric,0);
                            });
                        }
                    });

                    //busca os destinos intermediários
                    $.ajax({
                        url: 'ajax/consultas/cim_busca_dados_transferencias.php',
                        method: 'POST',
                        data: {tipo:5, idmovimentacao: idmovimentacao},
                        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                        dataType: 'json',
                        async: false
                    }).done(function(result){
                        //console.log(result)
                
                        if(result.MENSAGEM){
                            inserirMensagemTela(result.MENSAGEM)
                        }else{                           
                            result.forEach(linha => {
                                adicionarDestinos(linha.IDDEST,matric,0);
                            });
                        }
                    });
                }
            });
        }
    });
}

//Executa função na saída do foco do campo select
$('#selectordem').change(function(){
    var id = $('#selectordem').val();
    if(id!=0){
        $('#searchordem').val(id);
        limparCampos(false)
        buscarDadosOrdemSaida(id);
    }else{
        limparCampos(true)
        $('#searchordem').val('');
    }
})

$('#searchordem').change(function(){
    var id = $('#searchordem').val();
    
    if(id!=$('#selectordem').val()){
        buscaSearchComum('cim_busca_dados_transferencias',{tipo:9, idordem:id},$('#searchordem'),$('#selectordem'),$('#searchpresos'));
    }
})

adicionaEventoSelectChange(0,$('#selectdestino'),$('#searchdestino'));

//Executa função na saída do foco do campo search
//Se o id de DESTINO não existir, limpa-se o campo select da DESTINO
$('#searchdestino').change(function(){
    var id = $('#searchdestino').val();
    
    if(id!=$('#selectdestino').val()){
        buscaSearchComum('buscas_comuns',{tipo:5, iddestino:id},$('#searchdestino'),$('#selectdestino'),$('#datasaida'));
    }
})

adicionaEventoSelectChange(0,$('#selectpresos'),$('#searchpresos'));

//Executa função na saída do foco do campo search
//Se o id de PRESO não existir, limpa-se o campo select da PRESO
$('#searchpresos').change(function(){
    var id = $('#searchpresos').val();
    
    if(id!=$('#selectpresos').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresos'),$('#selectpresos'),$('.adicionarpreso'));
    }
})

//Ação do submit do formulário executará a função SALVAR
$('.salvarordemsaida').click(function(e){
    salvar();
})

function salvar(){
    //Verifica os campos se estão preenchidos corretamente
    var verificacao = verificaCampos();

    if(verificacao===true){
        var idalterar = salvarDados();
        if(idalterar!=0){
            $('#selectordem').val(idalterar).trigger('change')    
            /*setTimeout(() => {     
                $('#selectordem').val(idalterar).trigger('change')    
            }, 1000);*/
        }
    }
}

//Verifica os campos para poder salvar
function verificaCampos(){
    var mensagem = '';

    if($('#selectordem').val()==0 && acao=='alterar'){
        mensagem = "<li class = 'mensagem-aviso'>Selecione uma Ordem de Saída. </li>"
        inserirMensagemTela(mensagem)
        $('#selectordem').focus()
        return false;
    }

    if($('#selectdestino').val()==0 || $('#selectdestino').val()==null || $('#selectdestino').val()==NaN){
        if(mensagem==''){
            $('#selectdestino').focus()
        }
        mensagem = "<li class = 'mensagem-aviso'>Destino não selecionado. </li>"
        inserirMensagemTela(mensagem)
    }
    if($('#datasaida').val() == '' || $('#datasaida').val().length>10){
        if(mensagem==''){
            $('#datasaida').focus()
        }
        mensagem = "<li class = 'mensagem-aviso'>Data de Saída inválida. </li>"
        inserirMensagemTela(mensagem)
    }

    if($('#horasaida').val() == ''){
        if(mensagem==''){
            $('#horasaida').focus()
        }
        mensagem = "<li class = 'mensagem-aviso'>Hora de Saída inválida. </li>"
        inserirMensagemTela(mensagem)
    }

    var presosadicionados = containerPresos.find('.form-preso-mov-cimic');
    if(presosadicionados.length==0){
        if(mensagem==''){
            $('#searchpresos').focus()
        }
        mensagem = "<li class = 'mensagem-aviso'>Nenhum preso foi adicionado. </li>"
        inserirMensagemTela(mensagem)
    }else{
        for(i=0;i<presosadicionados.length;i++){
            let matric = presosadicionados[i].id;
            let preso = $('#'+matric);

            if($('#selecttipo'+matric).val()==0){
                if(mensagem==''){
                    preso.find('#selecttipo'+matric).focus()
                }
                mensagem = "<li class = 'mensagem-aviso'>O tipo de Movimentação do Preso "+preso.find('.nomepreso').html()+" não foi selecionado. </li>"
                inserirMensagemTela(mensagem)
            }else{
                if($('#selectmotivo'+matric).val()==0){
                    if(mensagem==''){
                        preso.find('#selectmotivo'+matric).focus()
                    }
                    mensagem = "<li class = 'mensagem-aviso'>O motivo de Movimentação do Preso "+preso.find('.nomepreso').html()+" não foi selecionado. </li>"
                    inserirMensagemTela(mensagem)
                }
            }

            let apresentacoes = preso.find('#apresentacoes'+matric).find('.apresentacao');          
            for(let apres=0;apres<apresentacoes.length;apres++){
                let apresentacao = $('#'+apresentacoes[apres].id);
                if(apresentacao.find('.dataapresentacao').val() == '' || apresentacao.find('.dataapresentacao').val().length>10){
                    if(mensagem==''){
                        apresentacao.find('.dataapresentacao').focus()
                    }
                    mensagem = "<li class = 'mensagem-aviso'>Data da Apresentação "+apresentacao.find('.localapresentacao').html()+", inválida. </li>"
                    inserirMensagemTela(mensagem)
                }
            
                if(apresentacao.find('.horaapresentacao').val() == ''){
                    if(mensagem==''){
                        apresentacao.find('.horaapresentacao').focus()
                    }
                    mensagem = "<li class = 'mensagem-aviso'>Hora da Apresentação "+apresentacao.find('.localapresentacao').html()+", inválida. </li>"
                    inserirMensagemTela(mensagem)
                }

                if(apresentacao.find('.motivoapres').val()==0 || apresentacao.find('.motivoapres').val()==null || apresentacao.find('.motivoapres').val()==NaN){
                    if(mensagem==''){
                        apresentacao.find('.motivoapres').focus()
                    }
                    mensagem = "<li class = 'mensagem-aviso'>Motivo da Apresentação "+apresentacao.find('.localapresentacao').html()+", inválido. </li>"
                    inserirMensagemTela(mensagem)
                }
            }

            let destinos = preso.find('#destinos'+matric).find('.destinos');          
            if(destinos.length>0){
                let blndestinofinal = false;
                let blnprimeirolocal = false;
                let primeirolocal;
                let destinofinal;
                let ultdestino;

                for(let dest=0;dest<destinos.length;dest++){
                    let destino = $('#'+destinos[dest].id);
                    
                    ultdestino = destino.find('.comentario');
                    if(destino.find('.primeirolocal').prop('checked')==true){
                        blnprimeirolocal = true;
                        primeirolocal = destino;
                    }
                    if(destino.find('.destinofinal').prop('checked')==true){
                        blndestinofinal = true;
                        destinofinal = destino;
                    }
                }
                if(blnprimeirolocal==false){
                    mensagem = "<li class = 'mensagem-aviso'>O primeiro local não foi selecionado para o preso "+preso.find('.nomepreso').html()+". </li>"
                    inserirMensagemTela(mensagem)
                }
                if(blndestinofinal==false){
                    if(mensagem==''){
                        ultdestino.focus()
                    }
                    mensagem = "<li class = 'mensagem-aviso'>Nenhum destino final foi selecionado para o preso "+preso.find('.nomepreso').html()+". </li>"
                    inserirMensagemTela(mensagem)
                }
                if(blnprimeirolocal==true && blndestinofinal==true && destinos.length>1){
                    if(primeirolocal===destinofinal){
                        mensagem = "<li class = 'mensagem-aviso'>Primeiro local e o Destino final do preso "+preso.find('.nomepreso').html()+", incorreto. </li>"
                        inserirMensagemTela(mensagem)
                    }
                }
            }else{
                if(mensagem==''){
                    $('#searchdestinterm'+matric).focus()
                }
                mensagem = "<li class = 'mensagem-aviso'>Nenhum destino inserido para o preso: "+preso.find('.nomepreso').html()+". </li>"
                inserirMensagemTela(mensagem)
            }
        }
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }
}

//Função para SALVAR os dados no banco de dados
function salvarDados(){
    let idordem = $('#selectordem').val();
    let datasaida = $('#datasaida').val()+' '+$('#horasaida').val();
    let iddestinoordem = $('#selectdestino').val();
    let presos = [];
    
    let presosadicionados = $('.form-preso-mov-cimic'); //Obtem todos formulários de presos que são desta classe

    for(let iPreso=0;iPreso<presosadicionados.length;iPreso++){
        let preso = $('#'+presosadicionados[iPreso].id);
        let matric = retornaSomenteNumeros(presosadicionados[iPreso].id);

        let idpreso = preso.data('idpreso');
        let idmovimentacao = preso.data('idbanco');
        let idtipo = preso.find('#selecttipo'+matric).val();
        let idmotivo = preso.find('#selectmotivo'+matric).val();
        let dataretorno = preso.find('#dataretorno'+matric).val();

        let destinos = []

        let campoDestinos = preso.find('.destinos'); //Obtem todos os Destinos que são desta classe e estão adicionados para este preso
        for(let iDest=0;iDest<campoDestinos.length;iDest++){
            let destinointerm = $('#'+campoDestinos[iDest].id)
            let iddestinointerm = destinointerm.data('iddestino');
            let idbanco = destinointerm.data('idbanco');
            let datainterm = destinointerm.find('.datadestino').val();
            let comentario = destinointerm.find('.comentario').val().trim();
            let blnprimeirolocal = destinointerm.find('.primeirolocal').prop('checked');
            let blndestinofinal = destinointerm.find('.destinofinal').prop('checked');

            destinos.push({
                idbanco: idbanco, 
                iddestinointerm: iddestinointerm, 
                datainterm: datainterm,
                comentario: comentario,
                blnprimeirolocal: blnprimeirolocal,
                blndestinofinal: blndestinofinal
            })
        }

        let apresentacoes = []

        let campoApresentacoes = preso.find('.apresentacao'); //Obtem todos os Destinos que são desta classe e estão adicionados para este preso
        for(let iApres=0;iApres<campoApresentacoes.length;iApres++){
            let destinoapres = $('#'+campoApresentacoes[iApres].id)
            let idlocalapres = destinoapres.data('idlocal');
            let idmotivoapres = destinoapres.find('.motivoapres').val();
            let idbanco = destinoapres.data('idbanco');
            let dataapres = destinoapres.find('.dataapresentacao').val()+' '+destinoapres.find('.horaapresentacao').val();

            apresentacoes.push({
                idbanco: idbanco, 
                idlocalapres: idlocalapres,
                idmotivoapres: idmotivoapres,
                dataapres: dataapres,
            })
        }

        presos.push({
            idpreso: idpreso,
            idmovimentacao: idmovimentacao,
            idtipo: idtipo,
            idmotivo: idmotivo,
            dataretorno: dataretorno,
            destinos: destinos,
            apresentacoes: apresentacoes
        })
    }

    let dados = {
        tipo: 1,
        confirmacao: confirmacaotrans,
        acao: acao,
        idordem: idordem,
        datasaida: datasaida,
        iddestinoordem: iddestinoordem,
        presos: presos
    }
    
    //console.log(dados)

    let idretorno = 0;
    //Insere os dados no banco de dados pelo ajax
    $.ajax({
        url: 'ajax/inserir_alterar/cim_movimentacoes_transferencias.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async:false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            if(result.CONFIR){
                confirmacaotrans = result.CONFIR;
                if(confirmacaotrans==1){
                    alert(result.MSGCONFIR)
                    salvar();
                }else{
                    if(confirm(result.MSGCONFIR)==true){
                        salvar();
                    }
                }
                idretorno = 0;
            }else{
                alert('Dados salvos com sucesso!')
                idretorno = result.IDORDEM;
                if(acao=='incluir'){
                    $('#alterar').prop('checked',true).trigger('change')
                }else{
                    if(result.OK){
                        inserirMensagemTela(result.OK);
                    }else{
                        $('#alterar').prop('checked',true).trigger('change')
                    }
                }
            }
        }
    });

    confirmacaotrans = 0;
    return idretorno;
}

let idbuscar = $('#ordempost').val();
if(idbuscar>0){
    $('#alterar').prop('checked',true);
    verificaAcao();
    $('#selectordem').val(idbuscar).trigger('change');
}else{
    verificaAcao();
    $('#incluir').focus();
}
