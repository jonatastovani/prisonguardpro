
const matric = $('#matric').val();
const idpreso = $('#idpreso').val();

//Ação do submit do formulário
$('#form1').submit(function(e){
    //No evento de submit do botão, não permitirá recarregar a página
    e.preventDefault();
})

$('#cpf').change(function(){
    var conteudoVerificar = $('#cpf').val()
    if(conteudoVerificar.length>0){
        var retorno = validaCPF(conteudoVerificar);
        if(retorno!==true){
            inserirMensagemTela(retorno);
        }
    }
})

$('#ufmorad').change(function (){
    var id = $('#ufmorad').val();
    atualizaListagemComum('buscas_comuns',{tipo: 15, iduf: id},$('#listacidade'),$('#selectcidade'));
});

adicionaEventoSelectChange(0,$('#selectcidade'),$('#searchcidade'))

$('#searchcidade').change(function(){
    var id = $('#searchcidade').val();
    
    if(id!=$('#selectcidade').val()){
        buscaSearchComum('buscas_comuns',{tipo:16, idcidade:id},$('#searchcidade'),$('#selectcidade'),$('#nometelefone'));
    }
});

$('#incluirtelefone').click(()=>{
    var mensagem='';
    var nomecontato = $('#nometelefone').val().trim();
    var numerocontato = $('#numerotelefone').val().trim();

    var conteudoVerificar = nomecontato
    if(conteudoVerificar == '' || conteudoVerificar == null || !conteudoVerificar.trim()){
        mensagem = ("<li class = 'mensagem-aviso'> O campo Novo Contato deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
        $('#nometelefone').focus()
    }
    conteudoVerificar = numerocontato
    if(conteudoVerificar == '' || conteudoVerificar == null || !conteudoVerificar.trim()){
        mensagem = ("<li class = 'mensagem-aviso'> O campo Número deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
        if(mensagem==''){
            $('#numerotelefone').focus()
        }
    }

    if(mensagem!=''){
        return false;
    }
    //Adiciona o contato no formulário do preso
    adicionaContato(nomecontato,numerocontato,0);

    $('#nometelefone').val('').focus();
    $('#numerotelefone').val('').mask('(00) 0000-00009');
})

//Adiciona o contato no formulário do preso
function adicionaContato(nomecontato,numerocontato,idbanco=0){
    let novoID = gerarID('.telefone-incluido');

    $('.telefonespreso').append('<div class="telefone-incluido" id="telefone'+novoID+'" data-idbanco="'+idbanco+'"><div class="descricao-telefone"><span class="nomecontato">'+nomecontato+'</span></div><span class="numerocontato">'+numerocontato+'</span><button class="fechar-absolute">&times;</button></div>');
    adicionaEventoExcluir($('#telefone'+novoID));
}

$('#incluirvulgo').on('click',()=>{
    var mensagem='';
    var novovulgo = $('#novovulgo').val().trim();

    var conteudoVerificar = novovulgo
    if(conteudoVerificar == '' || conteudoVerificar == null || !conteudoVerificar.trim()){
        mensagem = ("<li class = 'mensagem-aviso'> O campo Novo vulgo deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
        $('#novovulgo').focus()
    }

    if(mensagem!=''){
        return false;
    }
    //Adiciona o vulgo no formulário do preso
    adicionaVulgo(novovulgo,0);

    //Criar um ajax que vai inserir o contato e depois vai chamar outro ajax para atualizar todos os contatos de uma vez só
    $('#novovulgo').val('').focus();
})

//Adiciona o vulgo no formulário do preso
function adicionaVulgo(novovulgo,idbanco=0){
    let novoID = gerarID('.vulgo-incluido');

    $('.vulgospreso').append('<div class="vulgo-incluido" id="vulgo'+novoID+'" data-idbanco="'+idbanco+'"><div class="descricao-vulgo"><po class="vulgopreso">'+novovulgo+'</p></div><button class="fechar-absolute">&times;</button></div>');
    adicionaEventoExcluir($('#vulgo'+novoID));
}

//Adiciona evento de INCLUIR ARTIGO
$('#incluirartigo').on('click',()=>{

    let containerPai = $('#campoartigos');
    //Verifica se há algum artigo selecionado no select
    var idartigo = $('#selectartigopreso').val();
    
    if(idartigo!=0){
        //Verifica se o artigo já existe incluso
        var artigosencontrados = containerPai.find('.artigopreso').find('.artigo-incluido')

        for(let i=0;i<artigosencontrados.length;i++){
            let artigo = $('#'+artigosencontrados[i].id);
            let idencontrado = artigo.data('idartigo');
            if(idartigo==idencontrado){
                //Se houver exibirá uma mensagem e não executará a inserção do novo artigo
                inserirMensagemTela("<li class='mensagem-aviso'> Artigo já incluso! </li>");
                return;
            }
        };

        //Adiciona o artigo no formulário do preso
        adicionaArtigoPreso(containerPai,idartigo);
        containerPai.find('#selectartigopreso').val(0);
        containerPai.find('#searchartigopreso').val('');
    }      
})

//Adiciona o artigo no formulário do preso
function adicionaArtigoPreso(containerPai,idartigo,idbanco=0,observacoes=''){
    
    if(idartigo>0){
        $.ajax({
            url: 'ajax/consultas/buscas_comuns.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            data: {tipo: 18, idartigo: idartigo},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                let containerArtigo = containerPai.find('.artigopreso')

                let novoID = gerarID('.artigo-incluido');

                containerArtigo.append('<div id="artigo'+novoID+'" class="artigo-incluido" data-idartigo="'+idartigo+'" data-idbanco="'+idbanco+'"><h4 class="descricao-artigo">'+result[0].NOMEEXIBIR+'</h4><div><input type="text" class="obsartigo" value="'+observacoes+'" class="largura-total"></div><button class="fechar-absolute">&times;</button></div>');
                
                if(idbanco==0){
                    containerArtigo.find('#artigo'+novoID).find('.obsartigo').focus();
                }
                containerPai = containerArtigo.find('#artigo'+novoID);
                adicionaEventoExcluir(containerPai);
            }
        });
    }
}

atualizaListagemComum('buscas_comuns',{tipo: 17},$('#listaartigos'),$('#selectartigopreso'));
adicionaEventoSelectChange(0,$('#selectartigopreso'),$('#searchartigopreso'));

//Adiciona o evento FOCUSOUT para o SELECT DE ARTIGO
$('#searchartigopreso').on('focusout',()=>{
    var id = $('#searchartigopreso').val();
    
    if(id!=$('#selectartigopreso').val()){
        buscaSearchComum('buscas_comuns',{tipo:18, idartigo:id},$('#searchartigopreso'),$('#selectartigopreso'),$('#incluirartigo'));
    }
})

$('#numerotelefone').mask('(00) 0000-00009');

$('#numerotelefone').blur(function(event){
    var numero = $(this).val().replace(/[^0-9]/g,'');
    if(numero.length<11){
        $('#numerotelefone').mask('(00) 0000-00009');
    }else{
        $('#numerotelefone').mask('(00) 0 0000-0009');
    }
})

$('#selectnacionalidade').change(function(){
    if($('#selectnacionalidade').val()!=1){
        $('#localnascimento').attr('hidden', 'hidden')
    }else {
        $('#localnascimento').removeAttr('hidden')
    }
})

$('#ufnasc').change(function (){
    var id = $('#ufnasc').val();
    atualizaListagemComum('buscas_comuns',{tipo: 15, iduf: id},$('#listacidadenasc'),$('#selectcidadenasc'));
});

adicionaEventoSelectChange(0,$('#selectcidadenasc'),$('#searchcidadenasc'))

$('#searchcidadenasc').change(function(){
    var id = $('#searchcidadenasc').val();
    
    if(id!=$('#selectcidadenasc').val()){
        buscaSearchComum('buscas_comuns',{tipo:16, idcidade:id},$('#searchcidadenasc'),$('#selectcidadenasc'),$('#datanascimento'));
    }
});

atualizaListagemComum('buscas_comuns',{tipo: 22},$('#listaprofissao'),$('#selectprofissao'));
adicionaEventoSelectChange(0,$('#selectprofissao'),$('#searchprofissao'))

$('#searchprofissao').change(function(){
    var id = $('#searchprofissao').val();
    
    if(id!=$('#selectprofissao').val()){
        buscaSearchComum('buscas_comuns',{tipo:23, idprofissao:id},$('#searchprofissao'),$('#selectprofissao'),$('#selectescolaridade'));
    }
})

//Ação do submit do formulário executará a função SALVAR
$('#salvarqualificativa').click(function(e){
    //Verifica os campos se estão preenchidos corretamente
    var verificacao = verificaCampos();

    if(verificacao===true){
        salvarDados();
    }
})

//Verifica os campos para poder salvar
function verificaCampos(){
    var mensagem = '';

    var conteudoVerificar = $('#cpf').val()
    if(conteudoVerificar.length>0){
        var retorno = validaCPF($('#cpf').val());
        if(retorno!==true){
            retorno = confirm('O CPF informado não é um número válido, por isso não será salvo. Deseja continuar?');
            if(retorno !== true){
                mensagem = ("<li class = 'mensagem-aviso'> CPF inválido. </li>");
                inserirMensagemTela(mensagem);
            }
        }
    }

    let elementoVerificar = $('#nome')
    if(elementoVerificar.val()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Nome deve ser preenchido! </li>");
        inserirMensagemTela(mensagem);
    }
    
    elementoVerificar = $('#datanascimento')
    if(elementoVerificar.val()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Data deve ser preenchido! </li>");
        inserirMensagemTela(mensagem);
    }else{
        var diferenca = retornaDiferencaDeDataEHora(elementoVerificar.val(), new Date(), 3);

        if(diferenca<18){
            mensagem = "<li class = 'mensagem-aviso'>Data de nascimento inferior a 18 anos. Por favor, confira este campo. </li>";
            inserirMensagemTela(mensagem);
        }
    }

    if($('#selectnacionalidade').val()==0){
        mensagem = "<li class = 'mensagem-aviso'> Nacionalidade não selecionada. </li>";
        inserirMensagemTela(mensagem)
    }else if($('#selectnacionalidade').val()==1){
        if($('#selectcidadenasc').val()==0){
            mensagem = "<li class = 'mensagem-aviso'> Cidade de Nascimento não selecionada. </li>";
            inserirMensagemTela(mensagem)
        }
    }

    elementoVerificar = $('#selectcutis')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Cútis não selecionada. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selecttipocabelo')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Tipo de cabelo não selecionado. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectcorcabelo')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Cor de cabelo não selecionado. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectolhos')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Cor de olhos não selecionado. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#estatura')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Estatura não informada. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#peso')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Peso não informado. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectescolaridade')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Escolaridade não selecionada. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectestadocivil')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Estado Civil não selecionado. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#selectreligiao')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = "<li class = 'mensagem-aviso'> Religião não selecionada. </li>";
        inserirMensagemTela(mensagem);
    }

    elementoVerificar = $('#logradouro');
    console.log(elementoVerificar)
    console.log(elementoVerificar.val())
    if(elementoVerificar.val().trim()!=='' && elementoVerificar.val()!==null && elementoVerificar.val()!==NaN && elementoVerificar.val()!==undefined){

        elementoVerificar = $('#selectcidade')
        console.log(elementoVerificar)
        if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==undefined){
            if(mensagem==''){
                elementoVerificar.focus()
            }
            mensagem = "<li class = 'mensagem-aviso'> Cidade de Moradia não selecionada. </li>";
            inserirMensagemTela(mensagem);
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
    
    var nome = $('#nome').val()
    if(nome!==undefined){
        nome = nome.toUpperCase()
    }
    var rg = $('#rg').val()
    var cpf = '';
    var conteudoVerificar = $('#cpf').val();
    if(conteudoVerificar.length>0){
        var retorno = validaCPF($('#cpf').val());
        if(retorno===true){
            cpf = retornaSomenteNumeros(conteudoVerificar)
        }
    }
    var outrodoc = $('#outrodoc').val()

    var pai = $('#pai').val()
    if(pai!==undefined){
        pai = pai.toUpperCase()
    }
    var mae = $('#mae').val()
    if(mae!==undefined){
        mae = mae.toUpperCase()
    }
    var nacionalidade = $('#selectnacionalidade').val()
    var ufnasc = $('#ufnasc').val()
    var cidadenasc = $('#selectcidadenasc').val()
    var datanasc = $('#datanascimento').val();
    var cutis = $('#selectcutis').val()
    var tipocabelo = $('#selecttipocabelo').val()
    var corcabelo = $('#selectcorcabelo').val()
    var olhos = $('#selectolhos').val()
    var estatura = $('#estatura').val()
    var peso = $('#peso').val()
    var profissao = $('#selectprofissao').val()
    var escolaridade = $('#selectescolaridade').val()
    var estcivil = $('#selectestadocivil').val()
    var religiao = $('#selectreligiao').val()
    var logradouro = $('#logradouro').val()
    var numero = $('#numero').val()
    var complemento = $('#complemento').val()
    var bairro = $('#bairro').val()
    var ufmorad = $('#ufmorad').val()
    var cidademorad = $('#selectcidade').val()
    
    var telefones = []
    var campoTelefone = $('.telefone-incluido'); //Obtem todos os TELEFONES que são desta classe e estão adicionados para este preso
    for(var iTelefone=0;iTelefone<campoTelefone.length;iTelefone++){
        var idbanco = $('#'+campoTelefone[iTelefone].id).data('idbanco');
        var nomecontato = $('#'+campoTelefone[iTelefone].id).find('.nomecontato').html();
        var numerocontato = $('#'+campoTelefone[iTelefone].id).find('.numerocontato').html();

        telefones.push({
            idbanco: idbanco, 
            nomecontato: nomecontato.toUpperCase(),
            numerocontato: numerocontato.toUpperCase()
        })
    }

    var vulgos = []
    var campoVulgos = $('.vulgo-incluido'); //Obtem todos os Vulgos que são desta classe e estão adicionados para este preso
    for(var iVulgo=0;iVulgo<campoVulgos.length;iVulgo++){
        var idbanco = $('#'+campoVulgos[iVulgo].id).data('idbanco');
        var vulgo = $('#'+campoVulgos[iVulgo].id).find('.vulgopreso').html();

        vulgos.push({
            idbanco: idbanco, 
            vulgo: vulgo.toUpperCase()
        })
    }
   
    var sinais = $('#sinais').val()

    var artigos = []
    var campoArtigo = $('.artigo-incluido'); //Obtem todos os Artigos que são desta classe e estão adicionados para este preso
    for(var iArtigo=0;iArtigo<campoArtigo.length;iArtigo++){
        var idartigo = $('#'+campoArtigo[iArtigo].id).data('idartigo');
        var idbanco = $('#'+campoArtigo[iArtigo].id).data('idbanco');
        var obsartigo = $('#'+campoArtigo[iArtigo].id).find('.obsartigo').val();

        //console.log(idartigo, idbanco, obsartigo)
        artigos.push({
            ARTIGO: idartigo, 
            idbanco: idbanco, 
            obsartigo: obsartigo.toUpperCase()
        })
    }

    var anos = $('#anos').val()
    var meses = $('#meses').val()
    var dias = $('#dias').val()
    var observacoes = $('#observacoes').val()

    var dados = {
        idpreso: idpreso,
        nome: nome,
        matricula: matric,
        rg: rg.toUpperCase(),
        cpf: cpf,
        outrodoc: outrodoc,
        pai: pai,
        mae: mae,
        nacionalidade: nacionalidade,
        ufnasc: ufnasc,
        cidadenasc: cidadenasc,
        datanasc: datanasc,
        cutis: cutis,
        tipocabelo: tipocabelo,
        corcabelo: corcabelo,
        olhos: olhos,
        estatura: estatura,
        peso: peso,
        profissao: profissao,
        escolaridade: escolaridade,
        estcivil: estcivil,
        religiao: religiao,
        logradouro: logradouro.toUpperCase(),
        numero: numero.toUpperCase(),
        complemento: complemento.toUpperCase(),
        bairro: bairro.toUpperCase(),
        ufmorad: ufmorad,
        cidademorad: cidademorad,
        telefones: telefones,
        vulgos: vulgos,
        sinais: sinais,
        artigos: artigos,
        anos: anos,
        meses: meses,
        dias: dias,
        observacoes: observacoes
    }
    // console.log(dados)

    //Insere os dados no banco de dados pelo ajax
    $.ajax({
        url: 'ajax/inserir_alterar/inc_alterar_qualificativa.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console. log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            alert('Dados salvos com sucesso!')
            window.close();
        }
    });
}

$('#atualizarfoto').click(()=>{
    
    $.ajax({
        url: 'ajax/consultas/baixa_foto_servidor.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {tipo:1, matric: matric},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            $('#fotopreso1').attr('src', result.OK);
            $('#atualizarfoto').remove()
        }
    });    
})

if(idpreso>0){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {tipo: 4, matric: matric},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{

            $('#nome').val(result[0].NOME);
            if(result[0].PAI!=null){
                $('#pai').val(result[0].PAI);
            }
            if(result[0].MAE!=null){
                $('#mae').val(result[0].MAE);
            }
            if(result[0].RG!=null){
                $('#rg').val(result[0].RG);
            }
            if(result[0].CPF!=null){
                $('#cpf').val(result[0].CPF);
            }
            if(result[0].OUTRODOC!=null){
                $('#outrodoc').val(result[0].OUTRODOC);
            }
            $('#selectnacionalidade').val(result[0].NACIONALIDADE);
            if(result[0].IDESTADONASC!=null){
                $('#ufnasc').val(result[0].IDESTADONASC).trigger('change');
                $('#selectcidadenasc').val(result[0].IDCIDADENASC).trigger('change');
            }
            $('#datanascimento').val(result[0].DATANASC);
            if(result[0].CUTIS!=null){
                $('#selectcutis').val(result[0].CUTIS);
            }
            if(result[0].TIPOCABELO!=null){
                $('#selecttipocabelo').val(result[0].TIPOCABELO);
            }
            if(result[0].CORCABELO!=null){
                $('#selectcorcabelo').val(result[0].CORCABELO);
            }
            if(result[0].OLHOS!=null){
                $('#selectolhos').val(result[0].OLHOS);
            }
            if(result[0].ESTATURA!=null){
                $('#estatura').val(result[0].ESTATURA);
            }
            if(result[0].PESO!=null){
                $('#peso').val(result[0].PESO);
            }
            if(result[0].PROFISSAO!=null){
                $('#selectprofissao').val(result[0].PROFISSAO).trigger('change');
            }
            if(result[0].INSTRUCAO!=null){
                $('#selectescolaridade').val(result[0].INSTRUCAO);
            }
            if(result[0].ESTADOCIVIL!=null){
                $('#selectestadocivil').val(result[0].ESTADOCIVIL);
            }
            if(result[0].RELIGIAO!=null){
                $('#selectreligiao').val(result[0].RELIGIAO);
            }
            $('#logradouro').val(result[0].ENDERECO);
            $('#numero').val(result[0].NUMERO);
            $('#complemento').val(result[0].COMPLEMENTO);
            $('#bairro').val(result[0].BAIRRO);
            if(result[0].IDESTADOMORADIA!=null){
                $('#ufmorad').val(result[0].IDESTADOMORADIA).trigger('change');
                $('#selectcidade').val(result[0].IDCIDADEMORADIA).trigger('change');
            }
            $('#sinais').val(result[0].SINAIS);
            $('#anos').val(result[0].ANOS);
            $('#meses').val(result[0].MESES);
            $('#dias').val(result[0].DIAS);
            $('#observacoes').val(result[0].OBSERVACOES).trigger('change');
        }

        buscaFoto(1,'fotopreso1',idpreso);

        //Buscar dados dos artigos
        $.ajax({
            url: 'ajax/consultas/inc_busca_gerenciar.php',
            method: 'POST',
            data: {tipo: 3, idpreso: idpreso},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{                                    
                result.forEach(linha => {
                    let obsartigo = '';
                    if(linha.OBSERVACOES!=null){
                        obsartigo = linha.OBSERVACOES;
                    }
                    adicionaArtigoPreso($('#campoartigos'),linha.IDARTIGO,linha.ID, obsartigo);      
                });
            }
        });
        
        //Buscar telefones
        $.ajax({
            url: 'ajax/consultas/busca_presos.php',
            method: 'POST',
            data: {tipo: 5, idpreso: idpreso},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{                                    
                result.forEach(linha => {
                    adicionaContato(linha.NOMECONTATO,linha.NUMERO,linha.ID);
                });
            }
        });

        //Buscar vulgos
        $.ajax({
            url: 'ajax/consultas/busca_presos.php',
            method: 'POST',
            data: {tipo: 6, idpreso: idpreso},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{                                    
                result.forEach(linha => {
                    adicionaVulgo(linha.NOME,linha.ID);
                });
            }
        });
    });
}else{
    inserirMensagemTela("<li class='mensagem-erro'>Não foi possível encontrar os dados deste preso.</li>")
}
