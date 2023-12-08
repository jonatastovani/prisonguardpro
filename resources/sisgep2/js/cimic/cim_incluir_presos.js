
let blnCadastroExistente = false;

//Ação do submit do formulário
$('#form1').submit(function(e){
    //No evento de submit do botão, não permitirá recarregar a página
    e.preventDefault();
})

atualizaListagemComum('buscas_comuns',{tipo: 45},0,$('#selectnacionalidade'));
atualizaListagemComum('buscas_comuns',{tipo: 46},0,$('#ufnasc'));

//Mostra ou oculta os campos de destino
$('#ckboutro').change(function(){
    if($('#ckboutro').prop('checked')){
        $('#origem').attr('hidden', 'hidden')
        $('#outro').removeAttr('hidden')
    }else{
        $('#outro').attr('hidden', 'hidden')
        $('#origem').removeAttr('hidden')
    }
})

//Verificação se o preso já passou na unidade. Se passou busca-se os dados do preso.
$('#matricula').change(function(){
    var matricula = $('#matricula').val();
    if(matricula!=0 && matricula!=""){
        $('#digito').val(verifica_digito(matricula));
        $('#nome').focus();

        var matriculadigito = $('#matricula').val();
        var digito = $('#digito').val();

        matriculadigito = matriculadigito+digito;
        //console.log(matriculadigito);

        $.ajax({
            url: 'ajax/consultas/busca_presos.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            data: {tipo:4, matric: matriculadigito},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            // console.log(result);

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);

            }else if(result.OK){
                //Quando não encontrou a matrícula, então como não vinculou a nenhuma matricula deixa o campo desbloqueado, para diferenciar de quando vincula uma matricula
                $('#matriculavinculada').val(0);
                inserirMensagemTela(result.OK);
                
            }else{
                //Quando retornar os dados já bloqueia os campos de matrícula e dígito
                $('#matricula').attr("disabled", "disabled");
                //Habilita a visibilidade do botão limpar
                $('#limpar').removeAttr("hidden");
                $('#matriculavinculada').val(matriculadigito);

                var nomechegada = $('#nome').val().trim()
                if(nomechegada!=result[0].NOME && nomechegada.length!=0){
                    $('#nomeencontrado').html(result[0].NOME)
                    $('#outronome').removeAttr('hidden')
                }else{
                    if(nomechegada.length==0){
                        $('#nome').val(result[0].NOME)
                    }else{
                        $('#nomeencontrado').html('')
                        $('#outronome').attr('hidden', 'hidden')    
                    }
                }

                var rgchegada = $('#rg').val().trim()
                if(rgchegada!=result[0].RG && rgchegada.length!=0){
                    $('#rgencontrado').html(result[0].RG)
                    $('#outrorg').removeAttr('hidden')
                }else{
                    if(rgchegada.length==0){
                        $('#rg').val(result[0].RG)
                    }else{
                        $('#rgencontrado').html('')
                        $('#outrorg').attr('hidden', 'hidden')    
                    }
                }

                var paichegada = $('#pai').val().trim()
                if(paichegada!=result[0].PAI && paichegada.length!=0){
                    $('#paiencontrado').html(result[0].PAI)
                    $('#outropai').removeAttr('hidden')
                }else{
                    if(paichegada.length==0){
                        $('#pai').val(result[0].PAI)
                    }else{
                        $('#paiencontrado').html('')
                        $('#outropai').attr('hidden', 'hidden')    
                    }
                }

                var maechegada = $('#mae').val().trim()
                if(maechegada!=result[0].MAE && maechegada.length!=0){
                    $('#maeencontrado').html(result[0].MAE)
                    $('#outromae').removeAttr('hidden')
                }else{
                    if(maechegada.length==0){
                        $('#mae').val(result[0].MAE)
                    }else{
                        $('#maeencontrado').html('')
                        $('#outromae').attr('hidden', 'hidden')    
                    }
                }

                $('#cpf').val(result[0].CPF);
                $('#outrodoc').val(result[0].OUTRODOC);
                $('#selectnacionalidade').val(result[0].NACIONALIDADE).trigger('change');
                if(result[0].IDESTADONASC!=null){
                    $('#ufnasc').val(result[0].IDESTADONASC).trigger('change');
                    $('#selectcidadenasc').val(result[0].IDCIDADENASC).trigger('change');
                }
                $('#datanascimento').val(result[0].DATANASC)                
            }
        });
    }else{
        $('#digito').val('');
    }
});

$('#cpf').change(function(){
    var conteudoVerificar = $('#cpf').val()
    if(conteudoVerificar.length>0){
        var retorno = validaCPF(conteudoVerificar);
        if(retorno!==true){
            inserirMensagemTela(retorno);
        }
    }
})

$('#usarnome').click(function(){
    $('#nome').val($('#nomeencontrado').html())
    $('#outronome').attr('hidden', 'hidden')
})

$('#usarrg').click(function(){
    $('#rg').val($('#rgencontrado').html())
    $('#outrorg').attr('hidden', 'hidden')
})

$('#usarpai').click(function(){
    $('#pai').val($('#paiencontrado').html())
    $('#outropai').attr('hidden', 'hidden')
})

$('#usarmae').click(function(){
    $('#mae').val($('#maeencontrado').html())
    $('#outromae').attr('hidden', 'hidden')
})

//Ação do botão de limpar. Limpa todos os campos e as variáveis
$('#limpar').click(function(){
    if($('#limpar').attr('hidden') == "hidden"){
        return;
    }
    $('#matricula').val('');
    $('#digito').val('');
    $('#outronome').attr("hidden", "hidden");
    $('#nomeencontrado').html('');
    $('#matricula').removeAttr("disabled");
    //$('#digito').removeAttr("disabled");
    $('#limpar').attr("hidden", "hidden");
    $('#matricula').focus();
})

//Quando o select raio alterar o seu valor
$('#raio').change(function (){
    var raioselecionado = $('#raio option:selected').val();
    //console.log(raioselecionado);

    if(raioselecionado!=0){
      
        $.ajax({
            url: 'ajax/consultas/busca_celas_existentes_no_raio.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            data: {raio: raioselecionado},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result)
            $('#cela').empty();
            
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                for(i=1;i<=result[0].QTD;i++){
                    $('#cela').append("<option value="+i+">"+i+"</option>");;
                }
            }
        });
    }else{
        $('#cela').empty();
        //Inclui campo com opção 'Cela' caso não tenha sido selecionado nenhum raio
        $('#cela').append('<option value="0">Cela</option>');
    }
});

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
    let id = $('#searchcidadenasc').val();
    
    if(id!=$('#selectcidadenasc').val()){
        buscaSearchComum('buscas_comuns',{tipo:16, idcidade:id},$('#searchcidadenasc'),$('#selectcidadenasc'),$('#datanascimento'));
    }

});

atualizaListagemComum('buscas_comuns',{tipo: 6, selecionados: '9,10,12'},0,$('#tipomovimentacao'));

//Quando o select tipomovimentacao alterar o seu valor
$('#tipomovimentacao').change(function (){
    var id = $('#tipomovimentacao').val();
    atualizaListagemComum('buscas_comuns',{tipo: 8, idtipo: id},$('#listamotivo'),$('#selectmotivo'),true,true);
});

adicionaEventoSelectChange(0,$('#selectmotivo'),$('#searchmotivo'));

//Executa função na saída do foco do campo search da motivo de movimentação
//Se o id motivo de movimentação não existir, limpa-se o campo select de motivo de movimentação
$('#searchmotivo').change(function(){
    let id = $('#searchmotivo').val();
    
    if(id!=$('#selectmotivo').val()){
        buscaSearchComum('buscas_comuns',{tipo:9, idmotivo:id},$('#searchmotivo'),$('#selectmotivo'),$('#searchartigopreso'));
    }
})

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
                let containerArtigo = containerPai.find('#artigopreso')

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
$('#searchartigopreso').change(()=>{
    var id = $('#searchartigopreso').val();
    
    if(id!=$('#selectartigopreso').val()){
        buscaSearchComum('buscas_comuns',{tipo:18, idartigo:id},$('#searchartigopreso'),$('#selectartigopreso'),$('#incluirartigo'));
    }
})

//Efetua a busca dos dados
const idpreso = $('#idpresobancodados').val();

if(idpreso>0){
    $.ajax({
        url: 'ajax/consultas/cim_busca_dados_incluir_presos.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {idpreso: idpreso},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{

            $('#nome').val(result[0].NOME)
            if(result[0].PAI!=null){
                $('#pai').val(result[0].PAI)
            }
            if(result[0].MAE!=null){
                $('#mae').val(result[0].MAE)
            }
            $('#rg').val(result[0].RG)
            $('#dataprisao').val(retornaDadosDataHora(result[0].DATAENTRADA,1))
            $('#dataentrada').val(retornaDadosDataHora(result[0].DATAENTRADA,1))
            $('#horaentrada').val(retornaDadosDataHora(result[0].DATAENTRADA,6));
            $('#identrada').html(result[0].IDENTRADA)
            $('#idpreso').html(result[0].ID)
            $('#idorigem').html(result[0].IDORIGEM)
            $('#nomeorigem').html(result[0].ORIGEM)
            $('#datahoraentrada').html(result[0].DATAHORAENTRADA)

            //Verifica se existe foto do preso baixada neste servidor
            if(result[0].IDANTIGO!=null){
                buscaFoto(1,'fotopreso1',result[0].IDANTIGO)
            }

            //Buscar dados dos artigos
            $.ajax({
                url: 'ajax/consultas/inc_busca_gerenciar.php',
                method: 'POST',
                data: {tipo: 3, idpreso: result[0].ID},
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
            
            if(result[0].MATRICULA != null){
                var matric = midMatricula(result[0].MATRICULA,1);
                $('#matricula').val(matric).trigger('change')
            }
        }
    });
}else{
    inserirMensagemTela("<li class='mensagem-erro'>Não foi possível encontrar os dados deste preso.</li>")
}

//Ação do submit do formulário executará a função SALVAR
$('#salvarinclusao').click(function(e){
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
            retorno = confirm('O CPF informado não é um número válido, por isso não será salvo. Deseja continuar?')
            if(retorno !== true){
                mensagem = ("<li class = 'mensagem-aviso'> CPF inválido. </li>")
                inserirMensagemTela(mensagem)
            }
        }
    }

    conteudoVerificar = $('#nome').val()
    if(conteudoVerificar == '' || conteudoVerificar == null || !conteudoVerificar.trim()){
        mensagem = ("<li class = 'mensagem-aviso'> O campo Nome deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
    }
    var matricula = $('#matricula').val()
    if(matricula.length<5){
        mensagem = ("<li class = 'mensagem-aviso'> Uma matricula deve ser atribuída a este preso. </li>")
        inserirMensagemTela(mensagem)
    }

    if($('#selectnacionalidade').val()==0){
        mensagem = "<li class = 'mensagem-aviso'> Nacionalidade não selecionada. </li>"
        inserirMensagemTela(mensagem)
    }else if($('#selectnacionalidade').val()==1){
        if($('#selectcidadenasc').val()==0){
            mensagem = "<li class = 'mensagem-aviso'> Cidade de Nascimento não selecionada. </li>"
            inserirMensagemTela(mensagem)
        }
    }

    var datanasc = $('#datanascimento').val();
    var diferenca = retornaDiferencaDeDataEHora(datanasc, new Date(), 3);

    if(diferenca<18){
        mensagem = "<li class = 'mensagem-aviso'> Data de nascimento inferior a 18 anos. Por favor, confira este campo. </li>"
        inserirMensagemTela(mensagem)
    }

    var dataprisao = $('#dataprisao').val();
    var dataentrada = $('#dataentrada').val();
    diferenca = retornaDiferencaDeDataEHora(dataprisao, dataentrada, 1);

    if(diferenca<0){
        mensagem = "<li class = 'mensagem-aviso'>A Data de Prisão está posterior a Data de Inclusão. Por favor, confira este campo. </li>"
        inserirMensagemTela(mensagem)
    }

    if($('#tipomovimentacao').val()>0){
        if($('#selectmotivo').val()==0){
            mensagem = "<li class = 'mensagem-aviso'>Motivo da Movimentação não selecionado. </li>"
            inserirMensagemTela(mensagem)
        }
    }else{
        mensagem = "<li class = 'mensagem-aviso'>Tipo de Movimentação não selecionado. </li>"
        inserirMensagemTela(mensagem)
    }

    if($('#ckboutro').prop('checked')){
        conteudoVerificar = $('#outrodescricao').val()
        if(conteudoVerificar == '' || conteudoVerificar == null || !conteudoVerificar.trim()){
            mensagem = ("<li class = 'mensagem-aviso'> O campo Outra Origem deve ser preenchido! </li>")
            inserirMensagemTela(mensagem)
        }
    }else{
        if($('#selectorigem').val()==0){
            mensagem = "<li class = 'mensagem-aviso'>Origem não selecionada. </li>"
            inserirMensagemTela(mensagem)
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

    let tipoAcao = $('#tipoacao').val(); //Ação de incluir ou alterar a entrada
    let idpresobancodados = $('#idpresobancodados').val();
    let nome = $('#nome').val()
    
    let matricula = $('#matricula').val();
    matricula +=verifica_digito(matricula); 

    let blnMatriculaVinculada = $('#matriculavinculada').val()
    /*if(blnMatriculaVinculada==matricula && matricula!=0){
        blnMatriculaVinculada = 1
    }*/
    let rg = $('#rg').val()
    let cpf = '';
    let outrodoc = $('#outrodoc').val()
    let conteudoVerificar = $('#cpf').val();
    if(conteudoVerificar.length>0){
        let retorno = validaCPF($('#cpf').val());
        if(retorno===true){
            cpf = retornaSomenteNumeros(conteudoVerificar)
        }
    }

    let pai = $('#pai').val()
    let mae = $('#mae').val()
    let ufnascimento = $('#ufnasc').val()
    let cidadenascimento = $('#selectcidadenasc').val()
    let datanascimento = $('#datanascimento').val()
    let nacionalidade = $('#selectnacionalidade').val()
    let dataentrada = $('#dataentrada').val()
    let dataprisao = $('#dataprisao').val()
    let regime = $('#regime').val()
    let provisorio = $('#provisorio').val()
    let reincidente = $('#reincidente').val()
    //let raio = $('#raio option:selected').val(); let cela = $('#cela').val();
    let tipomovimentacao = $('#tipomovimentacao').val()
    let motivo = $('#selectmotivo').val()
    let observacoes = $('#observacoes').val();
    /*
    let blnoutraorigem = false
    if($('#ckboutro').prop('checked')){
        blnoutraorigem = true
    }*/
    let artigos = []

    let campoArtigo = $('#artigopreso').find('.artigo-incluido'); //Obtem todos os Artigos que são desta classe e estão adicionados para este preso
    for(let iArtigo=0;iArtigo<campoArtigo.length;iArtigo++){
        let idartigo = $('#'+campoArtigo[iArtigo].id).data('idartigo');
        let idbanco = $('#'+campoArtigo[iArtigo].id).data('idbanco');
        let obsartigo = $('#'+campoArtigo[iArtigo].id).find('.obsartigo').val();

        artigos.push({
            ARTIGO: idartigo, 
            idbanco: idbanco, 
            obsartigo: obsartigo.toUpperCase()
        })
    }

    let dados = {
        acao: tipoAcao,
        idpresobancodados: idpresobancodados,
        nome: nome.toUpperCase(),
        matricula: matricula,
        blnMatriculaVinculada: blnMatriculaVinculada,
        rg: rg.toUpperCase(),
        cpf: cpf,
        outrodoc: outrodoc,
        pai: pai.toUpperCase(),
        mae: mae.toUpperCase(),
        ufnascimento: ufnascimento,
        cidadenascimento: cidadenascimento,
        datanascimento: datanascimento,
        nacionalidade: nacionalidade,
        dataentrada: dataentrada,
        dataprisao: dataprisao,
        regime: regime.toUpperCase(),
        provisorio: provisorio,
        reincidente: reincidente,
        tipomovimentacao: tipomovimentacao,
        motivo: motivo,
        observacoes: observacoes,
        artigos: artigos
    }
    //console.log('Dados enviados')
    // console.log(dados)

    //Insere os dados no banco de dados pelo ajax
    $.ajax({
        url: 'ajax/inserir_alterar/cim_incluir_presos.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result);

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            //Faz a busca novamente da matrícula caso tenha ocorrido algum erro no salvamento. Ex: Não salvou a movimentação, daí então pra não salvar uma nova matrícula já torna esse cadastro o TipoAcao de alterar
            $('#matricula').trigger('change')
        }else{
            alert('Dados salvos com sucesso!')

            //Direcionar para página onde se dá as opções de impressão de documentos após inserir o preso
            // imprimirDocumentos([{get:'documento',valor:['termo_abertura']},{get:'idmovimentacao',valor:[result.OK]}]);
            imprimirDocumentos([{get:'documento',valor:['termo_abertura']},{get:'idpreso',valor:[result.OK]}]);
            window.location.assign('principal.php?menuop=cim_presos_pendentes')
        }
    });
}

$('#regime').val('FE');
$('#tipomovimentacao').val(9).trigger('change');
$('#selectmotivo').val(36).trigger('change');