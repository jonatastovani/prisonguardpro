const idbancovisi = buscaIDVisita();
let idbancovisitante = 0;
const containermedicamentos = $('#containermedicamentos');

let intsitvisitanteatual = 0;
let intsitvisitantenova = 0;
let comvisitanteatual = '';
let comvisitantenovo = '';

let intsitvisitaatual = 0;
let intsitvisitanova = 0;
let comvisitaatual = '';
let comvisitanovo = '';

let arrmedicamentos = [];
let strCPFVisitante = 0;

function buscaIDVisita(){
    let idbuscar = $('#idbancovisi').val();
    return buscaIDDecodificado(9,idbuscar);
}

function buscaDadosVisita(){
    // console.log(idbancovisi)

    $.ajax({
        url: 'ajax/consultas/rol_busca_gerenciar.php',
        method: 'POST',
        data: {tipo: 3, idvisita: idbancovisi},
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            buscaDadosPresoVisita(result[0].IDPRESO);
            $('#parentesco').html(result[0].PARENTESCO);
            $('#selectparentesco').val(result[0].IDPARENTESCO).trigger('change');
            $('#sitvisitaatual').find('.situacao').html(result[0].SITUACAO);
            $('#sitvisitaatual').find('.comentario').html(result[0].COMENTARIO);
            $('#sitvisitaatual').find('.data').html(retornaDadosDataHora(result[0].DATASITUACAO,12));
            intsitvisitaatual = result[0].IDSITUACAO;
            intsitvisitanova = result[0].IDSITUACAO;
            comvisitaatual = result[0].COMENTARIO;
            comvisitanovo = result[0].COMENTARIO;

            buscaDadosVisitante(result[0].IDVISITANTE);
            $('#selectresponsavel').val(result[0].IDRESPONSAVEL).trigger('change');
            $('#selectparentresp').val(result[0].IDPARENTRESP).trigger('change');

            idbancovisitante = result[0].IDVISITANTE;
        }
    });
}

function buscaDadosVisitante(idvisitante){
    // console.log(idvisitante)

    $.ajax({
        url: 'ajax/consultas/rol_busca_gerenciar.php',
        method: 'POST',
        data: {tipo: 4, idvisitante: idvisitante},
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            $('#datacadastro').html(retornaDadosDataHora(result[0].DATACADASTRO,12));
            $('#nomevisitante').html(result[0].NOME);

            $('#nome').val(result[0].NOME);
            $('#nomesocial').val(result[0].NOMESOCIAL);
            $('#rg').val(result[0].RG);
            if(result[0].IDEMISSORRG!=null){
                $('#selectemissorrg').val(result[0].IDEMISSORRG);
            }
            if(result[0].IDESTADORG!=null){
                $('#selectufrg').val(result[0].IDESTADORG);
            }
            $('#cpf').html(result[0].CPF);
            strCPFVisitante = result[0].CPF;
            $('#pai').val(result[0].PAI);
            $('#mae').val(result[0].MAE);
            $('#observacoes').val(result[0].OBSERVACOES);
            if(result[0].IDNACIONALIDADE!=null){
                $('#selectnacionalidade').val(result[0].IDNACIONALIDADE).trigger('change');
                if(result[0].UFNASC!=null){
                    $('#selectufnasc').val(result[0].UFNASC).trigger('change');
                    $('#selectcidadenasc').val(result[0].IDCIDADENASC).trigger('change');
                }
            }
            $('#datanascimento').val(result[0].DATANASC).trigger('change');
            $('#ckbemancipado').prop('checked',result[0].EMANCIPADO?true:false).trigger('change');
            $('#logradouro').val(result[0].ENDERECO);
            $('#numero').val(result[0].NUMERO);
            $('#complemento').val(result[0].COMPLEMENTO);
            $('#bairro').val(result[0].BAIRRO);
            if(result[0].UFNASC!=null){
                $('#selectufmorad').val(result[0].UFMORADIA).trigger('change');
                $('#selectcidademorad').val(result[0].IDCIDADEMORADIA).trigger('change');
            }
            $('#sitvisitanteatual').find('.situacao').html(result[0].SITUACAO);
            $('#sitvisitanteatual').find('.comentario').html(result[0].COMENTARIO);
            $('#sitvisitanteatual').find('.data').html(retornaDadosDataHora(result[0].DATASITUACAO,12));
            intsitvisitanteatual = result[0].IDSITUACAO;
            intsitvisitantenova = result[0].IDSITUACAO;
            comvisitanteatual = result[0].COMENTARIO;
            comvisitantenovo = result[0].COMENTARIO;

            inserirBotaoFotoVisitante($('#divbtnsfotovisita'),idvisitante,2)
            atualizaFotoVisitante();
        }
    });
}

function buscaDadosPresoVisita(idpreso){
    let result = consultaBanco('busca_presos',{tipo: 1, idpreso: idpreso});
    // console.log(result)
    if(result.length>0 || result!=[]){
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#nomepreso').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matricula').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matricula').html('Não Atribuída');
            }
            $('#raiocela').html(result[0].RAIOCELA);
            if(result[0].MAE!=null){
                $('#maepreso').html(result[0].MAE);
            }else{
                $('#maepreso').html('Não informado');
            }
            if(result[0].PAI!=null){
                $('#paipreso').html(result[0].PAI);
            }else{
                $('#paipreso').html('Não informado');
            }
        }
    }

}

atualizaListagemComum('buscas_comuns',{tipo:47},0,$('#selectemissorrg'));

atualizaListagemComum('buscas_comuns',{tipo:46},0,$('#selectufrg'));

atualizaListagemComum('buscas_comuns',{tipo:45},0,$('#selectnacionalidade'));

$('#selectnacionalidade').change(function(){
    if($('#selectnacionalidade').val()!=1){
        $('#localnascimento').attr('hidden', 'hidden');
    }else {
        $('#localnascimento').removeAttr('hidden');
        $('#selectufnasc').focus();
    }
})

atualizaListagemComum('buscas_comuns',{tipo:46},0,$('#selectufnasc'));

$('#selectufnasc').change(function (){
    var id = $('#selectufnasc').val();
    atualizaListagemComum('buscas_comuns',{tipo: 15, iduf: id},$('#listacidadenasc'),$('#selectcidadenasc'));
});

adicionaEventoSelectChange(0,$('#selectcidadenasc'),$('#searchcidadenasc'));

$('#searchcidadenasc').change(function(){
    var id = $('#searchcidadenasc').val();
    
    if(id!=$('#selectcidadenasc').val()){
        buscaSearchComum('buscas_comuns',{tipo:16, idcidade:id},$('#searchcidadenasc'),$('#selectcidadenasc'),$('#datanascimento'));
    }
});

atualizaListagemComum('buscas_comuns',{tipo:46},0,$('#selectufmorad'));

$('#selectufmorad').change(function (){
    var id = $('#selectufmorad').val();
    atualizaListagemComum('buscas_comuns',{tipo: 15, iduf: id},$('#listacidademorad'),$('#selectcidademorad'));
});

adicionaEventoSelectChange(0,$('#selectcidademorad'),$('#searchcidademorad'));

$('#searchcidademorad').change(function(){
    var id = $('#searchcidademorad').val();
    
    if(id!=$('#selectcidademorad').val()){
        buscaSearchComum('buscas_comuns',{tipo:16, idcidade:id},$('#searchcidademorad'),$('#selectcidademorad'),$('#nometelefone'));
    }
});

atualizaListagemComum('buscas_comuns',{tipo: 37, idtipo:2},$('#listagrau'),$('.selectgrau'));

adicionaEventoSelectChange(0,$('#selectparentesco'),$('#searchgrau'))

$('#searchgrau').change(function(){
    var id = $('#searchgrau').val();
    
    if(id!=$('#selectparentesco').val()){
        buscaSearchComum('buscas_comuns',{tipo:42, idgrau:id},$('#searchgrau'),$('#selectparentesco'));
    }
})

adicionaEventoSelectChange(0,$('#selectparentresp'),$('#searchparentresp'))

$('#searchparentresp').change(function(){
    var id = $('#searchparentresp').val();
    
    if(id!=$('#selectparentesco').val()){
        buscaSearchComum('buscas_comuns',{tipo:42, idgrau:id},$('#searchparentresp'),$('#selectparentresp'),$('#logradouro'));
    }
})

$('#datanascimento').change(()=>{
    let data = $('#datanascimento').val();
    if(retornaDiferencaDeDataEHora(data,retornaDadosDataHora(new Date(),1),3)<18){
        atualizaListaResponsavel();
        $('#divresponsavel').attr('hidden',false);
        $('#searchresponsavel').focus();
    }else{
        $('#divresponsavel').attr('hidden',true);
    }
})

function atualizaListaResponsavel(){
    atualizaListagemComum('rol_busca_gerenciar',{tipo:6, idvisita:idbancovisi, idvisitante:idbancovisitante, responsavel:1},$('#listaresponsavel'),$('#selectresponsavel'));
}

adicionaEventoSelectChange(0,$('#selectresponsavel'),$('#searchresponsavel'))

$('#searchresponsavel').change(function(){
    var id = $('#searchresponsavel').val();
    
    if(id!=$('#selectresponsavel').val()){
        buscaSearchComum('rol_busca_gerenciar',{tipo:6, idvisita:idbancovisi, idvisitante:id},$('#searchresponsavel'),$('#selectresponsavel'),$('#searchparentresp'));
    }
});

$('#ckbemancipado').change(function(){
    let ckb = this;
    $('#divpesqresponsavel').children().attr('disabled',ckb.checked?true:false);
})

atualizaListagemComum('buscas_comuns',{tipo:28,idtipo:9},0,$('#selectsitvisitante'));
atualizaListagemComum('buscas_comuns',{tipo:28,idtipo:10},0,$('#selectsitvisita'));

function adicionarEventoMensagensProntas(tipo){
    let botoes = 0;
    let div = 0;
    if(tipo==1){
        botoes = $('#mensagens-visitante').find('button');
        div = $('#situacaovisitante');
    }else{
        botoes = $('#mensagens-visita').find('button');
        div = $('#situacaovisita');
    }
    let campo = div.find('.txtcomentario');
    
    for(let i=0;i<botoes.length;i++){
        let id = botoes[i].id;
        if(id!=''){
            $('#'+id).click(()=>{
                let texto = $('#'+id).attr('title');
                if(campo.val().trim()!=''){
                    texto = "\r"+texto;
                }
                campo.val(campo.val()+texto);
            })
        }else{
            botoes[i].remove();
        }
    }
}

function adicionarEventoBotoesSituacaoNova(tipo){
    let div = '';
    let botaoinserir = '';
    let tiposituacao = 0;

    if(tipo==1){
        div = $('#sitvisitantenova');
        botaoinserir = $('#inserirsitvisitante');
        tiposituacao = 9;
    }else{
        div = $('#sitvisitanova');
        botaoinserir = $('#inserirsitvisita');
        tiposituacao = 10;
    }

    botaoinserir.click(()=>{
        let idsituacao = div.parent().find('.selectsituacao').val();
        if(idsituacao==0){
            inserirMensagemTela('<li class="mensagem-aviso"> Selecione uma situação para inserir. </li>');
            div.parent().find('.selectsituacao').focus();
            return;
        }

        let result = consultaBanco('buscas_comuns',{tipo:44,idsituacao:idsituacao,tiposituacao:tiposituacao});
        // console.log(result)
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            if(result.length>0){
                let strsituacao = result[0].NOME;
                let strcomentario = div.parent().find('.txtcomentario').val().trim();

                if(tipo==1){
                    intsitvisitantenova=idsituacao;
                    comvisitantenovo=strcomentario;
                }else{
                    intsitvisitanova=idsituacao;
                    comvisitanovo=strcomentario;
                }
                div.find('.situacao').html(strsituacao);
                div.find('.comentario').html(strcomentario);
                div.find('.data').html('Não inserido');
                div.removeAttr('hidden');
                div.parent().find('.txtcomentario').val('');
                div.parent().find('.selectsituacao').val(0);

            }else{
                inserirMensagemTela('<li class="mensagem-erro"> Ocorreu um erro ao buscar a situação. Tente novamente mais tarde! </li>');
            }
        }
    })

    div.find('.fechar-absolute').click(()=>{
        if(tipo==1){
            intsitvisitantenova=intsitvisitanteatual;
            comvisitantenovo=comvisitanteatual;
            $('.htmlvisitante').html('');
        }else{
            intsitvisitanova=intsitvisitaatual;
            comvisitanovo=comvisitaatual;
            $('.htmlvisita').html('');
        }
        div.attr('hidden','hidden');
    })
}

$('#salvarvisitante').click(function(){
    if(verificaSalvar()==true){
        salvar();
    }
})

function verificaSalvar(){
    var mensagem = '';
    
    var elementoVerificar = $('#nome');
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Nome deve ser preenchido! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#rg');
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo RG deve ser preenchido! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectemissorrg');
    if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o Órgão Emissor do RG! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectufrg');
    if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o Estado Emissor do RG! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#mae');
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Mãe deve ser preenchido! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectnacionalidade');
    if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione a Nacionalidade! </li>")
        inserirMensagemTela(mensagem);
    }else{
        if(elementoVerificar.val().trim()==1){
            elementoVerificar = $('#selectufnasc');
            if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
                if(mensagem==''){
                    elementoVerificar.focus()
                }
                mensagem = ("<li class = 'mensagem-aviso'> Selecione o Estado de nascimento! </li>")
                inserirMensagemTela(mensagem);
            }

            elementoVerificar = $('#selectcidadenasc');
            if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
                if(mensagem==''){
                    elementoVerificar.focus()
                }
                mensagem = ("<li class = 'mensagem-aviso'> Selecione a Cidade de nascimento! </li>")
                inserirMensagemTela(mensagem);
            }
        }
    }

    elementoVerificar = $('#datanascimento');
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Insira a Data de nascimento! </li>")
        inserirMensagemTela(mensagem);
    }else{
        let diferenca = retornaDiferencaDeDataEHora(elementoVerificar.val(), new Date(), 3);

        if(diferenca<18){

            elementoVerificar = $('#ckbemancipado');
            if(elementoVerificar.prop('checked')==null || elementoVerificar.prop('checked')==NaN){
                mensagem = ("<li class = 'mensagem-aviso'> Erro no elemento ckbemancipado </li>")
                inserirMensagemTela(mensagem);
            }else{
                if(elementoVerificar.prop('checked')==false){
                    
                    elementoVerificar = $('#selectresponsavel');
                    if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
                        if(mensagem==''){
                            $('#searchresponsavel').focus()
                        }
                        mensagem = "<li class = 'mensagem-aviso'> Data de nascimento inferior a 18 anos. Por favor, selecione um responsável. </li>";
                        inserirMensagemTela(mensagem);
                    }
                    
                    elementoVerificar = $('#selectparentresp');
                    if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
                        if(mensagem==''){
                            $('#searchparentresp').focus()
                        }
                        mensagem = "<li class = 'mensagem-aviso'> Selecione o parentesco do responsável para com o menor. </li>";
                        inserirMensagemTela(mensagem);
                    }
                }else{
                    if(diferenca<16){
                        mensagem = "<li class = 'mensagem-aviso'> O visitante não pode ser emancipado e menor de 16 anos. </li>";
                        inserirMensagemTela(mensagem);
                    }
                }
            }           
        }
    }

    elementoVerificar = $('#logradouro');
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Logradouro deve ser preenchido! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#numero');
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Número deve ser preenchido! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#bairro');
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Bairro deve ser preenchido! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectufmorad');
    if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o Estado de moradia! </li>")
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectcidademorad');
    if(elementoVerificar.val()<1 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione a Cidade de moradia! </li>")
        inserirMensagemTela(mensagem);
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvar(){

    if($('#selectsitvisitante').val()>0 || $('#situacaovisitante').find('.txtcomentario').val().trim()!=''){
        let confirmacao = confirm('Há um preenchimento de situação não finalizado. \r\rDeseja continuar mesmo assim?');

        if(confirmacao==false){
            return;
        }else{
            $('#selectsitvisitante').focus();
        }
    }
    if($('#selectsitvisita').val()>0 || $('#situacaovisita').find('.txtcomentario').val().trim()!=''){
        let confirmacao = confirm('Há um preenchimento de situação não finalizado. \r\rDeseja continuar mesmo assim?');

        if(confirmacao==false){
            $('#selectsitvisita').focus();
            return;
        }
    }

    let nome = $('#nome').val().trim();
    let nomesocial = $('#nomesocial').val().trim();
    let rg = $('#rg').val().trim();
    let idemissorrg = $('#selectemissorrg').val();
    let idestadorg = $('#selectufrg').val();
    let pai = $('#pai').val().trim();
    let mae = $('#mae').val().trim();
    let idnacionalidade = $('#selectnacionalidade').val();
    let idufnasc = $('#selectufnasc').val();
    let idcidadenasc = $('#selectcidadenasc').val();
    let datanasc = $('#datanascimento').val();
    let idresponsavel = 0;
    let idparentresp = 0;
    if(!$('#divresponsavel').attr('hidden')){
        console.log($('#ckbemancipado').prop('checked')?0:$('#selectresponsavel').val())
        idresponsavel = $('#ckbemancipado').prop('checked')?0:$('#selectresponsavel').val();
        idparentresp = $('#ckbemancipado').prop('checked')?0:$('#selectparentresp').val();
    }
    let emancipado = $('#ckbemancipado').prop('checked')?1:0;
    let logradouro = $('#logradouro').val().trim();
    let numero = $('#numero').val().trim();
    let complemento = $('#complemento').val().trim();
    let bairro = $('#bairro').val().trim();
    let idufmorad = $('#selectufmorad').val();
    let idcidademorad = $('#selectcidademorad').val();
    let observacoes = $('#observacoes').val().trim();
    let idparentesco = $('#selectparentesco').val();

    let dados = {
        tipo:4,
        idvisita:idbancovisi,
        idvisitante:idbancovisitante,
        nome:nome,
        nomesocial:nomesocial,
        rg:rg,
        idemissorrg:idemissorrg,
        idestadorg:idestadorg,
        pai:pai,
        mae:mae,
        observacoes:observacoes,
        idnacionalidade:idnacionalidade,
        idufnasc:idufnasc,
        idcidadenasc:idcidadenasc,
        datanasc:datanasc,
        idresponsavel:idresponsavel,
        idparentresp:idparentresp,
        emancipado:emancipado,
        logradouro:logradouro,
        numero:numero,
        complemento:complemento,
        bairro:bairro,
        idufmorad:idufmorad,
        idcidademorad:idcidademorad,
        idparentesco:idparentesco,
        idsitvisitante:intsitvisitantenova,
        comvisitante:comvisitantenovo,
        idsitvisita:intsitvisitanova,
        comvisita:comvisitanovo,
    };
    // console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/rol_gerenciar.php',
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
            alert("Dados enviados com sucesso!");
            window.close();
        }
    });
}

function atualizaFotoVisitante(){
    $('#fotovisita1').attr('src', 'imagens/sem-foto.png');
    $.ajax({
        url: 'ajax/consultas/baixa_foto_servidor.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {tipo:2, cpfvisitante: strCPFVisitante},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            let timestamp = '?t=' + new Date().getTime();
            $('#fotovisita1').attr('src', result.OK + timestamp);
        }
    }); 
}

$('#atualizarfoto').click(()=>{
    atualizaFotoVisitante();
})

adicionarEventoBotoesSituacaoNova(1);
adicionarEventoBotoesSituacaoNova(2);
adicionarEventoMensagensProntas(1);
adicionarEventoMensagensProntas(2);
buscaDadosVisita();
