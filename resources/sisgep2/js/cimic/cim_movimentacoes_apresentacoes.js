const containerPresos = $('#presosordemsaida')
//Array de números de ofícios já adicionados ao imprimir tudo.
let oficiosdestino=[];
let acao;
let confirmacaoapres = 0;

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
        $('#titulo').html('Incluir Apresentação Externa');
        $('#impr_entrada_presos').attr("hidden", "hidden");
        $('#impr_digitais_presos').attr("hidden", "hidden");
        acao = 'incluir';
    }else{
        $('#selectordem').removeAttr("disabled");
        $('#searchordem').removeAttr("disabled").focus();
        $('#impr_entrada_presos').removeAttr("hidden");
        $('#impr_digitais_presos').removeAttr("hidden");
        $('#titulo').html('Alterar Apresentação Externa');
        acao = 'alterar';
    }
    atualizarListasApresentacoesExternas();
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
    let oficio = '';
    let idmotivoapres = 1;


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

    let novoID = gerarID('.apresentacao');

    let dadosapresentacao = '<div>Hora: <input type="time" class="horaapresentacao"></div><div><span class="numero-oficio">'+oficio+'</span></div><div><label for="searchmotivoapres'+novoID+'">Cód. Motivo </label><input type="search" id="searchmotivoapres'+novoID+'" list="listamotivosapres" style="width: 90px;" autocomplete="off"><label for="selectmotivoapres'+novoID+'" class="margin-espaco-esq">Motivo </label><select id="selectmotivoapres'+novoID+'" class="motivoapres" style="width: 100%; max-width: 574px;"><option value="0">Selecione o Motivo</option></select></div>';

    containerPresos.append('<div class="item-flex apresentacao form-preso-mov-cimic largura-total relative" id="preso'+novoID+'" data-idbanco="'+idbanco+'" data-idpreso="'+idpreso+'"><button class="fechar-absolute">&times;</button><div>Matrícula: <b>'+matricula+'</b></div><div>Nome: <b><span class="nomepreso">'+nome+'</span></b> - Raio/Cela: <b>'+raiocela+'</b></div>'+dadosapresentacao+'</div>')

    let containerPai = $('#preso'+novoID);
    adicionaEventoExcluir(containerPai);

    adicionaEventoSearch($('#searchmotivoapres'+novoID),$('#selectmotivoapres'+novoID),11,$('#selectmotivoapres'+novoID))
    adicionaEventoSelect($('#searchmotivoapres'+novoID),$('#selectmotivoapres'+novoID),11)
    $('#selectmotivoapres'+novoID).html($('#listamotivosapres').html());
    $('#selectmotivoapres'+novoID).val(idmotivoapres).trigger('change');

    if(idbanco==0){
        containerPai.find('.horaapresentacao').focus();
    }else{
        containerPai.append('<button id="impapres'+novoID+'" class="temp" style="position: absolute; right: 20px; top: 0px;">Imprimir</button>');
        eventoBotaoImprimir('#impapres'+novoID,[{get:'documento',valor:['oficio_apresentacao']},{get:'apresentacoes',valor:[idbanco]},{get:'opcaocabecalho',valor:[2]},{get:'query',valor:[2]}]);
        eventoBotaoImprimir('.imp_tudo',[{get:'documento',valor:['oficio_apresentacao']},{get:'apresentacoes',valor:[idbanco]},{get:'opcaocabecalho',valor:[2]},{get:'query',valor:[2]}]);
    }

    return novoID;
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
            atualizarListaMotivos(id,matric)
        }
    })
}

//Botões de acao
$('#incluir').change(function(){
    verificaAcao();
});
$('#alterar').change(function(){
    verificaAcao();
});

//Atualiza todas as listas da página
function atualizarListasApresentacoesExternas(){
    atualizarListaPresos();
    atualizarListaDestino();
    atualizarListaMotivosApresentacao();
    if(acao=='alterar'){
        atualizarSelectOrdemSaidaExistentes();
    }
}

//Busca todas as ordens de saída existentes
function atualizarSelectOrdemSaidaExistentes() {
    var option = '<option value="0">Selecione</option>';
    $('#listaordem').empty().append(option);

    $.ajax({
        url: 'ajax/consultas/cim_busca_dados_apresentacoes.php',
        method: 'POST',
        data: {tipo:1},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            $('#incluir').prop('checked', 'checked').trigger('change')
        }else{
            result.forEach(linha => {
                var option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                $('#listaordem').append(option);            
            });
        }
    });
    $('#selectordem').html($('#listaordem').html());    
}

//Busca todas os Destinos existentes
function atualizarListaPresos(){
    $('#listapresos').html('');

    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo:2, tipobusca:1, valor:1},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#listapresos').append(result);            
        }
    });
    $('#selectpresos').html($('#listapresos').html());    
}

//Busca todas os Destinos existentes
function atualizarListaDestino(){
    var option = '<option value="0">Selecione</option>';
    $('#listadestino').empty().html(option);

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo:2},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                var option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                $('#listadestino').append(option);            
            });
        }
    });
    $('.locaisdestinos').html($('#listadestino').html());    
}

//Busca todas os Locais de Apresentação existentes
function atualizarListaMotivosApresentacao(){
    var option = '<option value="0">Selecione</option>';
    $('#listamotivosapres').html(option);

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo:10},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                var option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                $('#listamotivosapres').append(option);            
            });
        }
    });
    $('.motivoapres').html($('#listamotivosapres').html());    
}

function eventoExcluirApresentacaoExterna(botao){
    botao.click(()=>{
        if(confirm('Confirma a exclusão desta Apresentação Interna?\r\r***ATENÇÃO***\rEsta ação não poderá ser desfeita!')==true){
            let idordem = $('#selectordem').val();
            if(idordem!=0 && idordem!=null && idordem!=undefined){
                excluirApresentacaoExterna(idordem);
            }
        }
    })
}

function excluirApresentacaoExterna(idordem){
    $.ajax({
        url: 'ajax/inserir_alterar/cim_movimentacoes_apresentacoes.php',
        method: 'POST',
        data: {tipo:3, idordem: idordem},
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
            atualizarListasApresentacoesExternas()
        }
    })
}

//Busca os dados da ordem de saída
function buscarDadosOrdemSaida(idordem){

    $.ajax({
        url: 'ajax/consultas/cim_busca_dados_apresentacoes.php',
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
            eventoBotaoImprimir('.imp_ordem',[{get:'documento',valor:['ordem_saida_presos']},{get:'ordens',valor:[idordem]},{get:'query',valor:[2]}]);

            $('#botoesimpressao').append('<button class="imp_escolta temp margin-espaco-esq">Imp Ofício Escolta</button>');
            eventoBotaoImprimir('.imp_escolta',[{get:'documento',valor:['oficio_escolta']},{get:'ordens',valor:[idordem]},{get:'query',valor:[2]}]);

            $('#botoesimpressao').append('<button class="imp_tudo temp margin-espaco-esq">Imp Todos Docs</button>');
            eventoBotaoImprimir('.imp_tudo',[{get:'documento',valor:['oficio_escolta']},{get:'ordens',valor:[idordem]},{get:'query',valor:[2]}]);
            eventoBotaoImprimir('.imp_tudo',[{get:'documento',valor:['ordem_saida_presos']},{get:'ordens',valor:[idordem]},{get:'query',valor:[2]}]);

            $('#botoesimpressao').append('<button class="btn-excluir temp margin-espaco-esq">Excluir Apresentação</button>');
            eventoExcluirApresentacaoExterna($('#botoesimpressao').find('.btn-excluir'));

            result.forEach(linha => {
                let idmovimentacao = linha.IDMOVIMENTACAO;
                let novoID = adicionarPreso(idmovimentacao, linha.IDPRESO);

                let container = $('#preso'+novoID);

                let ids=[];
                ids.push(linha.IDMOVIMENTACAO);
    
                container.find('#selectmotivoapres'+novoID).val(linha.IDMOTIVOAPRES).trigger('change');
                container.find('.horaapresentacao').val(linha.HORAAPRES);
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

//Executa função na saída do foco do campo search
//Se o id de DESTINO não existir, limpa-se o campo select da DESTINO
$('#searchordem').change(function(){
    var id = $('#searchordem').val();
    
    if(id!=$('#selectordem').val()){
        if(id!=0){        
        $.ajax({
            url: 'ajax/consultas/cim_busca_dados_apresentacoes.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            data: {tipo:5, idordem: id},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
            }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
                //Limpa os campos pois o valor digitado não existe
                $('#selectordem').val(0);
                $('#searchordem').val('');
            }else{
                $('#selectordem').val(result[0].VALOR).trigger('change');
            }
        });
        }else{
            $('#selectordem').val(0);
            $('#searchordem').val('');
        }
    }
})

adicionaEventoSelectChange(0,$('#selectdestino'),$('#searchdestino'));

//Executa função na saída do foco do campo search
//Se o id de DESTINO não existir, limpa-se o campo select da DESTINO
$('#searchdestino').change(function(){
    var id = $('#searchdestino').val();
    
    if(id!=$('#selectdestino').val()){
        if(id!=0){
            $.ajax({
                url: 'ajax/consultas/buscas_comuns.php',
                method: 'POST',
                //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
                data: {tipo:3, idlocal: id},
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json',
                async: false
                }).done(function(result){
                //console.log(result)

                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM)
                    //Limpa os campos pois o valor digitado não existe
                    $('#selectdestino').val(0);
                    $('#searchdestino').val('');
                }else{
                    $('#selectdestino').val(result[0].VALOR);
                    $('#datasaida').focus();
                }
            });
        }else{
            $('#selectdestino').val(0);
            $('#searchdestino').val('');
        }
    }
})

//Executa função na saída do foco do campo select
$('#selectpresos').change(function(){
    var id = $('#selectpresos').val();
    if(id!=0){
        $('#searchpresos').val(id);
    }else{
        $('#searchpresos').val('');
    }
})

//Executa função na saída do foco do campo search
//Se o id de PRESO não existir, limpa-se o campo select da PRESO
$('#searchpresos').change(function(){
    var id = $('#searchpresos').val();
    
    if(id!=$('#selectpresos').val()){
        if(id!=0){
            $.ajax({
                url: 'ajax/consultas/busca_presos.php',
                method: 'POST',
                //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
                data: {tipo:1, idpreso:id},
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json',
                async: false
                }).done(function(result){
                //console.log(result)

                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM)
                    //Limpa os campos pois o valor digitado não existe
                    $('#selectpresos').val(0);
                    $('#searchpresos').val('');
                }else{
                    $('#selectpresos').val(id);
                    $('.adicionarpreso').focus();
                }
            });
        }else{
            $('#selectpresos').val(0);
            $('#searchpresos').val('');
        }
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
            // console.log('aqui')
        if(idalterar!=0){
            // console.log('aqui 2')
            $('#selectordem').val(idalterar).trigger('change')    
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

    let elementoVerificar = $('#selectdestino')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'>Destino não selecionado. </li>"
        inserirMensagemTela(mensagem)
    }

    elementoVerificar = $('#datasaida')
    if(elementoVerificar.val()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val().length>10){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'>Data de Saída inválida. </li>"
        inserirMensagemTela(mensagem)
    }

    elementoVerificar = $('#horasaida')
    if(elementoVerificar.val()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
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
                mensagem = "<li class = 'mensagem-aviso'>Tipo de Movimentação do Preso "+preso.find('.nomepreso').html()+" não foi selecionado. </li>"
                inserirMensagemTela(mensagem)
            }else{
                if($('#selectmotivo'+matric).val()==0){
                    if(mensagem==''){
                        preso.find('#selectmotivo'+matric).focus()
                    }
                    mensagem = "<li class = 'mensagem-aviso'>Motivo de Movimentação do Preso "+preso.find('.nomepreso').html()+" não foi selecionado. </li>"
                    inserirMensagemTela(mensagem)
                }
            }

            if(preso.find('.horaapresentacao').val() == ''){
                if(mensagem==''){
                    preso.find('.horaapresentacao').focus()
                }
                mensagem = "<li class = 'mensagem-aviso'>Hora da Apresentação do preso "+preso.find('.nomepreso').html()+", inválida. </li>"
                inserirMensagemTela(mensagem)
            }

            if(preso.find('.motivoapres').val()==0 || preso.find('.motivoapres').val()==null || preso.find('.motivoapres').val()==NaN){
                if(mensagem==''){
                    preso.find('.motivoapres').focus()
                }
                mensagem = "<li class = 'mensagem-aviso'>Motivo da Apresentação do preso "+preso.find('.nomepreso').html()+", inválido. </li>"
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
        let horaapres = preso.find('.horaapresentacao').val();
        let idmotivoapres = preso.find('.motivoapres').val();

        presos.push({
            idpreso: idpreso,
            idmovimentacao: idmovimentacao,
            idmotivoapres: idmotivoapres,
            horaapres: horaapres
        })
    }

    let dados = {
        tipo: 1,
        confirmacao: confirmacaoapres,
        acao: acao,
        idordem: idordem,
        datasaida: datasaida,
        iddestinoordem: iddestinoordem,
        presos: presos
    }
    
    //console.log(dados);

    let idretorno = 0;
    //Insere os dados no banco de dados pelo ajax
    $.ajax({
        url: 'ajax/inserir_alterar/cim_movimentacoes_apresentacoes.php',
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
                alert(result.MSGCONFIR)
                idretorno = 0;
                confirmacaoapres = result.CONFIR;
                salvar();
            }else{
                alert('Dados salvos com sucesso!')
                idretorno = result.IDORDEM;
                //let tempo = 500;
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

    console.log(idretorno);
    confirmacaoapres = 0;
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
