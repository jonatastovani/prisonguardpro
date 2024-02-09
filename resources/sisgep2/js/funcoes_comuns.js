//Array para atribuição de id nas mensagens
var arrayIDMensagens = [];

//Retorna o dígito verificador da matrícula
function verifica_digito(matricula){
    var i;
    var mult = 2;
    var soma = 0;
    var s = "";

    for (i=matricula.length-1; i>=0; i--){
        s = (mult * parseInt(matricula.charAt(i))) + s;
        if (--mult<1){
            mult = 2;
            }
    }
    for (i=0; i<s.length; i++){
        soma = soma + parseInt(s.charAt(i));
        }
    soma = soma % 10;
    if (soma != 0){
        soma = 10 - soma;
        }
   return soma;
}

//Função para retornar parte da matricula
//tipo 1 = matricula sem o dígito
//tipo 2 = dígito da matricula
//tipo 3 = matricula-digito
function midMatricula(matricula, tipo){
    matricula = String(matricula)
    let digito = matricula.substring(matricula.length-1,matricula.length);
    matricula = matricula.substring(0,matricula.length-1);
    
    if(tipo==1){
        return matricula;
    }else if(tipo==2){
        return digito;
    }else if(tipo==3){
        return matricula+'-'+digito;
    }
}

//Função para retornar CPF formatado com pontos e traços
function retornaCPFFormatado(cpf){
    cpf = String(cpf)
    return cpf.substring(0,3)+"."+cpf.substring(3,6)+"."+cpf.substring(6,9)+"-"+cpf.substring(9,11);
}

//Retorna um ID para a mensagem a ser exibida, para assim a função de settime possa excluir a mensagem quando passar o tempo limite
function retornarNovoIDMensagens(){
    var NovoId = arrayIDMensagens.length;
    arrayIDMensagens.push(NovoId)
    return NovoId;
}

//Configura e exibe a mensagem no topo da tela
function inserirMensagemTela(texto){
    var idmensagem = 'mensagem'+retornarNovoIDMensagens();
    $("#mensagem").append(texto);
    var mensagem = $("#mensagem").children();
    mensagem = mensagem[--mensagem.length]
    mensagem.setAttribute('id',idmensagem)
    mensagem = $('#'+idmensagem)
    var tempolimite = retornaSomaDataEHora(new Date(),5,6)
    tempolimite = retornaDadosDataHora(tempolimite,11)
    mensagem.attr('data-tempolimite',tempolimite)
}

//Função para retornar dados de Data e Hora
//Tipo = tipo de dados a ser retornado
//converter = true para caso a DataHora informada estiver no padrão brasileiro
function retornaDadosDataHora(DataHora,tipo,converter=false){

    if(converter==true){
        DataHora = retornaDataHoraConvertida(DataHora);
    }

    if(DataHora.length==10){
        DataHora += ' 00:00:00';
    }
    var hoje = new Date(DataHora);
    var dd = String(hoje.getDate()).padStart(2, '0');
    var mm = String(hoje.getMonth() + 1).padStart(2, '0'); //Janeiro é 0!
    var yyyy = hoje.getFullYear();
    var H = String(hoje.getHours()).padStart(2, '0');
    var i = String(hoje.getMinutes()).padStart(2, '0');
    var s = String(hoje.getSeconds()).padStart(2, '0');

    hoje = yyyy + '-' + mm + '-' + dd ;
    agora = H + ':' + i ;
    //Tipo 1 = YYYY-mm-dd Ano, mes e Dia
    if(tipo===1){
        return yyyy + '-' + mm + '-' + dd;
    }
    //Tipo 2 = dd/mm/YYYY Dia, mes e ano
    else if(tipo===2){
        return dd + '/' + mm + '/' + yyyy;
    }
    //Tipo 3 = dd Dia
    else if(tipo===3){
        return dd;
    }
    //Tipo 4 = mm Mês
    else if(tipo===4){
        return mm;
    }
    //Tipo 5 = YYYY Ano
    else if(tipo===5){
        return yyyy;
    }
    //Tipo 6 = H:i Horas e minutos
    else if(tipo===6){
        return H + ':' + i;
    }
    //Tipo 7 = H:i:s Horas, minutos e segundos
    else if(tipo===7){
        return H + ':' + i + ':' + s;
    }
    //Tipo 8 = H Horas
    else if(tipo===8){
        return H;
    }
    //Tipo 9 = i Minutos
    else if(tipo===9){
        return i;
    }
    //Tipo 10 = s Segundos
    else if(tipo===10){
        return s;
    }
    //Tipo 11 = YYYY-mm-dd H:i:s Ano, mes, Dia, Horas, minutos e segundos
    else if(tipo===11){
        return yyyy + '-' + mm + '-' + dd + ' ' + H + ':' + i + ':' + s;
    }
    //Tipo 12 = dd/mm/YYYY H:i Dia, mes, Ano, Horas, minutos
    else if(tipo===12){
        return dd + '/' + mm + '/' + yyyy + ' ' + H + ':' + i;
    }

}

//Converte uma data do padrão brasileiro para gringo
function retornaDataHoraConvertida(data){
    var ms = moment(data,"DD-MM-YYYY HH:mm:ss");
    return ms.year()+'-'+(ms.month()+1)+'-'+ms.date()+' '+ms.hour()+':'+ms.minute()+':'+ms.second();
}

//Retorna diferença entre duas datas
//Tipo = tipo de dados a ser retornado
function retornaDiferencaDeDataEHora(DataMenor,DataMaior,tipo){
    //var dtInicio  = "2022-03-29 16:40:00";
    //var dtFinal = "2022-03-28 16:20:00";
    var ms = moment(DataMaior,"YYYY-MM-DD HH:mm:ss").diff(moment(DataMenor,"YYYY-MM-DD HH:mm:ss"));
    //var d = moment.duration(ms);
    //var s = Math.floor(d.asHours()) + "h" + moment.utc(ms).format(" mm") +"m";
  
    //Tipo 1 = Dia
    if(tipo===1){
        return moment.duration(ms).asDays();
    }
    //Tipo 2 = Mês
    /*else if(tipo===2){
        return moment.duration(ms).asMonths();
    }*/
    //Tipo 3 = Ano
    else if(tipo===3){
        return moment.duration(ms).asYears();
    }
    //Tipo 4 = Horas
    else if(tipo===4){
        return moment.duration(ms).asHours();
    }
    //Tipo 5 = Minutos
    else if(tipo===5){
        return moment.duration(ms).asMinutes();
    }
    //Tipo 6 = Segundos
    else if(tipo===6){
        return moment.duration(ms).asSeconds();
    }  
}

//Função para adicionar Data ou Hora em uma data informada
//Tipo = tipo de dados a ser adicionado
function retornaSomaDataEHora(dataAdicionar,intQuantidade, tipo){
    //console.log(dataAdicionar);

    //Tipo 1 = Dia
    if(tipo===1){
        dataAdicionar.setDate(dataAdicionar.getDate()+intQuantidade);
    }
    //Tipo 2 = Mês
    else if(tipo===2){
        dataAdicionar.setMonth(dataAdicionar.getMonth()+intQuantidade);
    }
    //Tipo 3 = Ano
    else if(tipo===3){
        dataAdicionar.setYear(dataAdicionar.getFullYear()+intQuantidade);
    }
    //Tipo 4 = Horas
    else if(tipo===4){
        dataAdicionar.setHours(dataAdicionar.getHours()+intQuantidade);
    }
    //Tipo 5 = Minutos
    else if(tipo===5){
        dataAdicionar.setMinutes(dataAdicionar.getMinutes()+intQuantidade);
    }
    //Tipo 6 = Segundos
    else if(tipo===6){
        dataAdicionar.setSeconds(dataAdicionar.getSeconds()+intQuantidade);
    }  
    return dataAdicionar
}

//Retorna um código MD5 do valor inserido
function codifica(valor){
    var retorno;
    // console.log(valor)

    $.ajax({
        url: 'ajax/consultas/retornaMD5.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {valor: valor},
        async: false
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        //dataType: 'json'
    }).done(function(result){
        // console.log(result) 
       
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            retorno = result.OK
        }
    });
    return retorno
}

//Verifica se a foto existe e insere no id informado
/*********************** Alterar a utilização para a proxima função ******************/
function buscaFoto(tipo, idfoto, nomefoto) {
	var img = new Image();
    var caminhoPasta;
    switch (tipo) {
        case 1:
            caminhoPasta = 'fotos/Fotos_sentenciados';
            break;
    
        case 2:
            caminhoPasta = 'fotos/Fotos_visitantes';
            break;
    
        default:
            return false;
    }

	img.src = caminhoPasta+"/"+nomefoto+".jpg";
    //$('#fotopreso1').html('')
	img.onload = function() {
		//console.log("A imagem existe");
        $('#'+idfoto).html("<img src='"+caminhoPasta+"/"+nomefoto+".jpg' alt='Foto'>")
	}
	img.onerror = function() {
        $('#'+idfoto).html("<img src='imagens/sem-foto.png' alt='Sem foto'>")
		//console.log("A imagem não existe");
	}
    
}

//Baixa a foto e insere no img informado
function baixarFotoFrontalServidorRemoto(tipo,img,nomefoto){
    
    let dados = [];
    switch (tipo) {
        case 1:
            dados = {
                tipo:1,
                idpreso: nomefoto
            };
            break;
    
        case 2:
            dados = {
                tipo:2,
                cpfvisitante: nomefoto
            };
            break;
    
        default:
            inserirMensagemTela('<li class="mensagem-erro"> Tipo não exitente </li>');
            return false;
    }
    
    img.attr('src', 'imagens/sem-foto.png');
    $.ajax({
        url: 'ajax/consultas/baixa_foto_servidor.php',
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
            let timestamp = '?t=' + new Date().getTime();
            img.attr('src', result.OK + timestamp);
        }
    }); 
}

//Função para retornar somente números contido em uma string
function retornaSomenteNumeros(string){
    // console.log(string);
    string = String(string);
    // console.log(string);
    
    // let str = '';

    // for(let i=0;i<string.length;i++){
    //     // str += string.substring()
    //     console.log(string.substring(i,i+1));
    // }

    numsStr = string.replace(/[^0-9]/g,'');
    return numsStr;
    //return parseInt(numsStr);
}

// Função que permite somente teclar números
function eventoSomenteNumeros(dados) {
    elemento = dados.elemento;

    elemento.on('keypress',(e)=>{
        var tecla = e.keyCode || e.which;
        // console.log(tecla);
        if ((tecla > 47 && tecla < 58))
            return true;
        else {
            if (tecla === 8 || tecla === 0)
                return true;
            else
                return false;
        }
    })
}

//Executa a validação do CPF inserido
//strCPF = string CPF
function validaCPF(strCPF){
    var NumCPF = retornaSomenteNumeros(strCPF);
    var Soma = 0;
    var Multiplicador
    var ultnum
    var CaracterAnalisado
    var Repeticao
    var Resto

    if(NumCPF.length<11){
        return "<li class = 'mensagem-aviso'> O CPF informado não possui a quantidade de dígitos correta. </li>";
    }

    Multiplicador = 10
    ultnum = 9
    
    //Primeiro for é para retornar duas vezes
    for(Repeticao = 1; Repeticao < 3; Repeticao++ ){
        //For para fazer as multiplicações número a número
        for(CaracterAnalisado = 0; CaracterAnalisado < ultnum; CaracterAnalisado++){
            //Faz a conversão para integer para poder efetuar as multiplicações porque a função substring "corta" texto           
            Soma = Soma + (parseInt(NumCPF.substring(CaracterAnalisado,CaracterAnalisado+1)) * Multiplicador)
            //Diminui 1 do fator multiplicador a cada vez que passar no for
            Multiplicador = Multiplicador - 1 
        }
        //Já multiplica a soma por 10 porque utilizaremos ela sempre multiplicado por 10
        Soma = Soma * 10

        //Faz um for analisando os números da divisão, quando encontrar a vírgula é só extrair os números da esquerda
        Resto = Soma - (parseInt(Soma / 11) * 11);

        //Se o resto for 10 o  dígito deverá ser 0
        if(Resto == 10){
            Resto = 0
        }

        //Faz a verificação do dígito em cada vez que passar, se estiver errado é mostrado a mensagem e interrompido a execução do código
        if(Resto != parseInt(NumCPF.substring(ultnum,ultnum+1))){
            return "<li class = 'mensagem-aviso'> O número digitado não é um CPF válido. </li>";
        }

        //Define novamente os parâmetros para a segunda repetição
        ultnum = 10
        Multiplicador = 11
        Soma = 0

        if(Repeticao==2){
            return true;
        }
    }
}

//gera um ID conforme o seletor indicado
function gerarID(seletor){
    let id = 0;
    let encontrados = $(seletor);
    if(encontrados.length>0){
        for(let i=0;i<encontrados.length;i++){
            let idexistente = parseInt(retornaSomenteNumeros(encontrados[i].id));
            if(idexistente>id){
                id=idexistente;
            }
        }
    }
    return id+1;
}

//atribui o evento click do botao de imprimir
//extra = variáveis extra para a url (ex: variaveis sem codificar)
function eventoBotaoImprimir(seletor,arrvariaveis,extra=''){
    $(seletor).on('click', ()=>{
        imprimirDocumentos(arrvariaveis,extra);
    })
}

function imprimirDocumentos(arrvariaveis,extra=''){
    let strvar = '';
    
    arrvariaveis.forEach(variavel => {
        let strvalores = '';            
        variavel.valor.forEach(val => {
            if(strvalores==''){
                strvalores = codifica(val);
            }else{
                strvalores += ','+codifica(val);
            }
        });
        if(strvar==''){
            strvar = variavel.get+'='+strvalores;
        }else{
            strvar += '&'+variavel.get+'='+strvalores;
        }
    });
    setTimeout(() => {
        window.open('impressoes/impressao.php?'+strvar+extra, '_blank')
    }, 100);
}

//Adiciona evento de excluir. Se não for informado o ID ou classe do botão, por padrão será o botão da classe fechar-absolute
function adicionaEventoExcluir(containerPai, botao = '.fechar-absolute'){
    containerPai.find(botao).click(()=>{
        containerPai.remove();
    })
}

//Adiciona evento de expandir uma div oculta.
//container pai, que a div pertence, nos casos de ter várias div iguais e o seletor ser pela classe
//botao = botão que irá receber o evento
//blnHidden = padrão inicial da div, true: para iniciar mostrando, do contrário: false
//strexpandir = texto para exibir no botão quando a div estiver oculta
//strocultar = texto para exibir no botão quando a div estiver visível
function adicionaEventoExpandirDiv(containerPai, botao, div, blnHidden = false, strexpandir = 'Expandir', strocultar = 'Ocultar'){

    if(blnHidden==true){
        div.removeAttr('hidden')
        botao.html(strocultar)
    }else{
        div.attr('hidden','hidden');
        botao.html(strexpandir)
    }

    botao = containerPai.find(botao);
    div = containerPai.find(div);

    botao.click(()=>{
        if(div.attr('hidden')=='hidden'){
            div.removeAttr('hidden')
            botao.html(strocultar)
        }else{
            div.attr('hidden','hidden');
            botao.html(strexpandir)
        }
    })
}

/*Adiciona o evento CHANGE para um SELECT de maneira simples
containerPai = container que contém o select e o search
select = select que vai receber o evento
search = search que vai receber o valor selecionado pelo select*/
function adicionaEventoSelectChange(containerPai,select,search=0){
    //containerPai = 0 significa que o select não tem container, geralmente são os selects fixos na página
    if(containerPai==0){
        select.on('change',()=>{
            var id = select.val();
            if(search!=0){
                if(id!=0){
                    search.val(id);
                }else{
                    search.val('');
                }
            }
        })
    }else{
        select = containerPai.find(select);
        if(search!=0){
            search = containerPai.find(search);
        }

        select.on('change',()=>{
            var id = select.val();
            if(search!=0){
                if(id!=0){
                    search.val(id);
                }else{
                    search.val('');
                }
            }
        })
    }
}

/*Função para efetuar uma validação de algum valor do search search de maneira genérica
url = nome do arquivo que fará a consulta
dados = array de dados
search = search que contém o valor procurado
select = objeto select que irá receber a listagem
elementofoco = próximo elemento que receberá o foco caso o valor seja encontrado
blntrigger = executar trigger do select ao receber o valor
trigger = nome do evento trigger
blnasync = executar ajax de maneira assíncrona*/
function buscaSearchComum(url,dados,search,select,elementofoco=0,blntrigger=true,trigger='change',blnasync=false){
    let id = search.val();
    
    if(id>0){
        $.ajax({
            url: 'ajax/consultas/'+url+'.php',
            method: 'POST',
            data: dados,
            dataType: 'json',
            async: blnasync
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
                id=0;
            }
        });
    }else{
        id=0;
    }
    if(blntrigger==true){
        select.val(id).trigger(trigger);
    }else{
        select.val(id);
    }
    if(select.val()==null){
        let mensagem = '<li class="mensagem-aviso"> O código digitado não confere com nenhuma informação da listagem. </li>';
        inserirMensagemTela(mensagem);
    }else{
        if(elementofoco!=0){
            elementofoco.focus();
        }
    }
}

/*Função para atualizar uma listagem de select e search de maneira genérica
url = nome do arquivo que fará a consulta
dados = array de dados
lista = 0 para quando não houver lista, ou informar o objeto datalist
select = objeto ou objetos selects que irá receber a listagem
blnmantervalor = manter o valor do select ou não após a atualização da listagem do select
blntrigger = executar alguma trigger ao atualizar a listagem
trigger = nome do evento trigger
blnasync = executar ajax de maneira assíncrona
blnvalor0 = true para inserir por padrão o primeiro valor = 0 com texto "Selecione" sendo o padrão
strvalor0 = padrão do texto da primeira opção é "Selecione"
*/
function atualizaListagemComum(url,dados,lista,select,blnmantervalor=true,blntrigger=true,trigger='change',blnasync=false,blnvalor0=true,strvalor0='Selecione'){
    let valores = [];

    //Reserva os valores selecionados
    for(let i=0;i<select.length;i++){
        let idselect = select[i].id;
        let valor = $('#'+select[i].id).val();
        if(valor==null){
            valor=0;
        }
        valores.push({idselect,valor})
    }

    let option = '';
    if(blnvalor0==true){
        option = "<option value=0>"+strvalor0+"</option>";
    }
    let retorno = option;

    // console.log(url,dados,lista,select,retorno);
    // console.log(dados);

    $.ajax({
        url: 'ajax/consultas/'+url+'.php',
        method: 'POST',
        data: dados,
        dataType: 'json',
        async: blnasync
    }).done(function(result){
        // console.log(result);

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            result.forEach(linha => {
                // console.log(linha);
                let valor = '';
                if(linha['VALOR']!=null){
                    valor = 'value="'+linha['VALOR']+'"';
                }

                option = '<option '+valor+'>'+linha['NOMEEXIBIR']+'</option>';
                retorno += option;
            });
        }
    });
    // console.log(select)
    if(select!=0){
        // console.log(retorno)
        select.html(retorno)
        // console.log(select.html());
        if(blntrigger==true){
            if(blnmantervalor==true){
                valores.forEach(linha => {
                    $('#'+linha.idselect).val(linha.valor).trigger(trigger);
                });
            }else{
                valores.forEach(linha => {
                    $('#'+linha.idselect).trigger(trigger);
                });
            }
        }else{
            if(blnmantervalor==true){
                valores.forEach(linha => {
                    $('#'+linha.idselect).val(linha.valor);
                });
            }
        }
    }
    if(lista!=0){
        lista.html(retorno);
    }
};

//Faz a verificação se o usuário possui a permissão necessária
function verificaPermissao(dados){
    let bln = false;

    $.ajax({
        url: 'ajax/consultas/verifica_permissoes.php',
        method: 'POST',
        data: dados,
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            bln = result.PERMISSAO;
        }
    });
    return bln;
}

//Preenche o select com as celas existente no raio informado
function preencheCelas(idraio,select,blnvalor0=false){
    let retorno = "<option value=0>0</option>";
    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo: 25, idraio: idraio},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            let quantidade = result[0].QTD;
            if(blnvalor0==false || blnvalor0==true && quantidade==1){
                retorno = '';
            }
            for(let i=1;i<quantidade+1;i++){
                let option = "<option value="+i+">"+i+"</option>";
                retorno += option;
            }
        }
    });
    select.html(retorno)
}

/*Verifica se o raio ou a cela é cela de excessão
idraio = id do raio a ser verificado

*/
function verificaRaioCelaSeguro(idraio,cela,arridtipos=[],dataconsulta=''){
    let result = consultaBanco('buscas_comuns',{tipo:48,idraio:idraio,cela:cela,dataconsulta:dataconsulta,arridtipos:arridtipos});
    // console.log(result);
    return result;
}

function alterarSituacaoComum(id, idsituacao, idtabela, botao, mensagemconfirm='',blnvisuchefia=0){
    //console.log(id, idsituacao, idtabela, botao)
    botao.on('click',()=>{
        // console.log(id, idsituacao, idtabela)
        let conf = true;
        if(mensagemconfirm!=''){
            conf = confirm(mensagemconfirm);
        }

        let dados = {
            situacao: idsituacao,
            tabela: idtabela,
            id: id,
            blnvisuchefia: blnvisuchefia
        }

        if(conf){
            $.ajax({
                url: 'ajax/inserir_alterar/alterar_situacao_registro.php',
                method: 'POST',
                //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
                data: dados,
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json'
            }).done(function(result){
                //console.log(result)
        
                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM)
                }else{
                    inserirMensagemTela(result.OK)
                }
            });
        }
    })
}

/*Função para efetuar uma consulta no banco de dados, retornando o valor da consulta somente
dados = array de dados
blnasync = executar ajax de maneira assíncrona*/
function consultaBanco(url,dados,blnasync=false){
    let retorno = [];
    // console.log(url,dados)

    $.ajax({
        url: 'ajax/consultas/'+url+'.php',
        method: 'POST',
        data: dados,
        dataType: 'json',
        async: blnasync
    }).done(function(result){
        //console.log(result)
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            retorno = result;
        }
    });

    return retorno;
}

/*Função para buscar o id do usuário logado*/
function buscaIDUsuarioLogado(){
    return consultaBanco('buscas_comuns',{tipo: 36})
}

/*Busca o ID decodificado*/
function buscaIDDecodificado(idtipo,idbuscar,tiporetorno=1){
    let result = [];
    if(idbuscar==0 || idbuscar==undefined || idbuscar==NaN || idbuscar==null){
        inserirMensagemTela('<li class="mensagem-aviso"> ID do Atendimento não informado.\nSe o problema persistir, informe ao programador. </li>')
        idbuscar = 0;
    }else{
        $.ajax({
            url: 'ajax/consultas/buscas_comuns.php',
            method: 'POST',
            data: {tipo:38, idtipo:idtipo, idbuscar:idbuscar},
            dataType: 'json',
            async: false
        }).done(function(result){
            // console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                idbuscar = result[0].ID;
            }
        });
    }
    if(tiporetorno==1){
        return idbuscar;
    }else if(tiporetorno==2){
        return result;
    }
}

/*Função para efetuar uma consulta DESC da tabela informada, retornando um array das colunas informadas
dados = array de dados
blnasync = executar ajax de maneira assíncrona*/
function consultaDESCTabela(tabela,arrcolunas,tiporetorno=1,blnasync=false){
    let retorno = [];

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo:43, tabela:tabela},
        dataType: 'json',
        async: blnasync
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            switch (tiporetorno) {
                case 1:
                    arrcolunas.forEach(linha => {
                        retorno.push({coluna: linha, valor: result[result.findIndex((coluna)=>coluna.Field==linha)].Default});
                    });
                    break;
            }
        }
    });

    return retorno;
}

//Adiciona evento de onoff e atualização do estado
function adicionaEventosBotaoOnOff(botaoonoff,elementoestado='',strativar='Clique para ativar',strdesativar='Clique para desativar'){
    let idonoff = botaoonoff.attr('id');
    let el = $('#'+idonoff)[0];
    botaoonoff.on('change', function() {
        if(elementoestado!=0){
            elementoestado.html(el.checked ? 'Ligado' : 'Desligado');
            elementoestado.attr('title',el.checked ? strdesativar : strativar);
        }
        $('label[for="'+idonoff+'"]').attr('title',el.checked ? strdesativar : strativar);
    });

    if(elementoestado!=0){
        //Fazer com que o estado tenha ação de click também
        elementoestado.on('click', function() {
            botaoonoff.trigger('click');
        });
    }
}