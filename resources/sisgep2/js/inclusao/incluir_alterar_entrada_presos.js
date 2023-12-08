//captura a ação do formulário. Se é inserir ou alterar.
let acao = $('#acao').val();
let identradabuscar = $('#identradabuscar').val();

//Ação do submit do formulário executará a função SALVAR
$('#form1').submit(function(e){
    //No evento de submit do botão, não permitirá recarregar a página
    e.preventDefault()
});

//Verifica a ação para habilitar ou não o campo de NUMERO DE ENTRADA
function verificaAcao(){
    limparCampos(true);
    if($('#nova').prop("checked")){
        acao = 'nova';
        $('#selectentrada').attr("disabled", "disabled");
        $('#searchentrada').attr("disabled", "disabled");
        $('#dataentrada').focus();
        $('#titulo').html('Incluir Entrada de Presos');
    }else{
        acao = 'alterar';
        $('#selectentrada').removeAttr("disabled");
        $('#searchentrada').removeAttr("disabled").focus();
        $('#titulo').html('Alterar Entrada de Presos');
        atualizarNumerosEntradas();
    }
}

//Limpa todos os campos
//blnLimparIDEntrada = true para limpar até o select identrada
function limparCampos(blnLimparIDEntrada){
    if(blnLimparIDEntrada){
        $('#searchentrada').val('').trigger('change');
    }
    $('#selectorigem').val(0)
    $('#searchorigem').val('')
    $('#dataentrada').val(retornaDadosDataHora(new Date(),1));
    $('#horaentrada').val(retornaDadosDataHora(new Date(),6));
    $('.temp').remove()

    $('#presosinclusao').html('');
}

$('.adicionarpreso').click(function(){
    adicionarPreso(0,0);
})

//Adiciona um preso no formulário
function adicionarPreso(idbanco, matriculavinculada){
    
    var novoID = gerarID('.form-preso-entrada');
    
    let cabecalho = '<div style="position: relative;"><h2 class="titulo-grupo">Preso '+novoID+' </h2><button style="position: absolute; top: 0; right: 25px;" class="desvincular" hidden>Desvincular Matr.</button><button class="fechar-absolute">&times;</button></div>';
    
    let dadospreso = '<div class="grupo-block"><h4 class="titulo-grupo">Nome</h4><input type="text" class="nome largura-total" autocomplete="off"></div>';

    dadospreso +='<div class="flex"><div class="grupo div-metade-aling-esquerda"><h4 class="titulo-grupo">Matrícula</h4><input type="text" class="matricula align-cen" style="width: 90px;"> - <input type="number" disabled class="digito" style="width: 25px; text-align: center"></div><div class="grupo margin-espaco-esq largura-restante"><h4 class="titulo-grupo">RG</h4><input type="text" maxlength="15" class="rg inp-rg largura-total"></div></div>'

    dadospreso += '<div class="align-rig"><button class="expandirpais">Expandir Pais</button></div><div class="dadospais" hidden><div class="grupo-block"><h4 class="titulo-grupo">Pai</h4><input type="text" class="pai largura-total" autocomplete="off"></div><div class="grupo-block"><h4 class="titulo-grupo">Mãe</h4><input type="text" class="mae largura-total" autocomplete="off"></div></div>';

    let artigos = '<div class="align-rig relative"><h3 style="position: absolute; top: 0; left: 5px;">Artigos</h3><div><label for="searchartigo'+novoID+'">Cód. Artigo</label><input type="search" class="margin-espaco-esq" id="searchartigo'+novoID+'" list="listaartigos" style="width: 90px;"><br><label for="selectartigo'+novoID+'">Artigo</label><select id="selectartigo'+novoID+'" class="artigos margin-espaco-esq"></select><button class="incluirartigo margin-espaco-esq">Incluir Artigo</button></div></div>';

    let camposadicionais = '<div class="align-rig"><button class="expandiradicionais">Expandir Dados Adicionais</button></div><div class="adicionais"><div class="grupo-block"><h4 class="titulo-grupo">Informações adicionais</h4><textarea class="informacoes largura-total" placeholder="Ex: Links de notícia do caso"></textarea></div><div class="grupo-block"><h4 class="titulo-grupo">Observações</h4><textarea class="observacoes largura-total"></textarea></div></div>';

    let presoprovisorio = verificaPermissao({tipo:1, permissoes: '9,16,17,18'});
    if(presoprovisorio == true){
        presoprovisorio = '<div class="divprovisorio"><input type="checkbox" class="provisorio" id="provisorio'+novoID+'"><label for="provisorio'+novoID+'" class="espaco-esq">Preso provisório (Somente para CIMIC)</label></div>';
    }else{
        presoprovisorio = '';
    }

    $('#presosinclusao').append('<div class="item-flex form-preso-entrada" id="preso'+novoID+'" data-idpresobancodados="'+idbanco+'" data-matriculavinculada="'+matriculavinculada+'">'+cabecalho+dadospreso+artigos+'<div class="artigopreso container-flex max-height-100"></div>'+camposadicionais+presoprovisorio+'</div>')

    containerPai = $('#preso'+novoID);
    //Atribui options no select de ARTIGOS
    $('#selectartigo'+novoID).append(listaTodosArtigos);

    //Adiciona evento de FOCUSOUT MATRICULA
    adicionaEventoMatricula(containerPai,novoID);
    
    //Adiciona evento do botao DESVINCULAR
    adicionaEventoDesvincularMatricula(containerPai)
    
    //Adiciona evento do botao Expandir dados dos Pais
    adicionaEventoExpandirDiv(containerPai,$('.expandirpais'),$('.dadospais'),false,'Expandir Pais','Ocultar Pais')
   
    //Adiciona evento do botao Expandir campos adicionais
    adicionaEventoExpandirDiv(containerPai,$('.expandiradicionais'),$('.adicionais'),false,'Expandir Dados Adicionais','Ocultar Dados Adicionais')

    //Adiciona evento de CHANGE ARTIGO
    adicionaEventoSelectChange(containerPai,$('#selectartigo'+novoID),$('#searchartigo'+novoID));
    
    //Adiciona evento de FOCUSOUT ARTIGO
    adicionaEventoSearchArtigo(containerPai,novoID);
 
    //Adiciona evento de EXCLUIR O PRESO DA ENTRADA
    adicionaEventoExcluir(containerPai);
    
    //Adiciona evento de INCLUIR ARTIGO
    adicionaEventoIncluirArtigo(novoID, containerPai)

    containerPai.find('.nome').focus();

    //Retorna somente o número do ID do preso
    return novoID;
}

function adicionaEventoExcluirEntrada(){
    $('#excluirentrada').on('click', function(){
        var identrada = $('#selectentrada').val()
        excluirEntrada(0, identrada, 1)
    })
}

function excluirEntrada(idpresobancodados, identrada, tipo){
    var resultado = false;
    if(tipo==1){
        resultado = confirm("Confirma a exclusão desta Entrada de Presos? Esta ação não poderá ser desfeita.")
    }else if(tipo==4){
        resultado = confirm("Este preso é o último desta entrada. Confirma a exclusão desta Entrada de Presos? Esta ação não poderá ser desfeita.")
    }
    
    //console.log(idpresobancodados)
    if(resultado==true){
        //Exclui os dados no banco de dados pelo ajax
        $.ajax({
            url: 'ajax/excluir/exluir_dados_entrada.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            //Tipo 1 Exclui a Entrada de preso somente
            //Tipo 4 Exclui o último preso da entrada e a Entrada também
            data: {tipo: tipo, idpresobancodados: idpresobancodados, identrada: identrada}
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            //dataType: 'json'
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                //console.log('Passou aqui')
                inserirMensagemTela(result.OK)
                $('#selectentrada').val(0).trigger('change')
                atualizarNumerosEntradas()
            }
        });
    }
}

//Adiciona evento do botao DESVINCULAR
function adicionaEventoDesvincularMatricula(containerPai){

    //Adiciona o evento
    containerPai.find('.desvincular').on('click',()=>{
        //Libera os campos de matrícula e dígito
        containerPai.find('.matricula').removeAttr("disabled");
        containerPai.find('.digito').val('');
        //Limpa o valor ao data-matriculavinculada
        containerPai.data('matriculavinculada',0)
        //Oculta o botão desvincular
        containerPai.find('.desvincular').attr("hidden","hidden");
    })
}

//Adiciona evento de INCLUIR ARTIGO
function adicionaEventoIncluirArtigo(id, containerPai){
    
    //Adiciona o evento
    containerPai.find('.incluirartigo').on('click',()=>{

        //Verifica se há algum artigo selecionado no select
        var idartigo = containerPai.find('#selectartigo'+id).val();
        
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
            containerPai.find('#selectartigo'+id).val(0);
            containerPai.find('#searchartigo'+id).val('');
        }    
    })
}

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

//Adiciona o evento FOCUSOUT para o SELECT DE ARTIGO
function adicionaEventoSearchArtigo(containerPai,id){
    let select = containerPai.find('#selectartigo'+id);
    let search = containerPai.find('#searchartigo'+id);

    search.on('focusout',()=>{
        var idartigo = search.val();
    
        if(idartigo>0){            
            $.ajax({
                url: 'ajax/consultas/buscas_comuns.php',
                method: 'POST',
                //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
                data: {tipo: 18, idartigo: idartigo},
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json'
            }).done(function(result){
                //console.log(result)
    
                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM)
                }else{
                    select.val(idartigo);
                    containerPai.find('.incluirartigo').focus();
                }
            });
        }else{
            select.val(0);
            search.val('');
        }
    })
}

//Adiciona o evento FOCUSOUT para a MATRÍCULA
function adicionaEventoMatricula(containerPai,id){
    let elmatricula = containerPai.find('.matricula');
    let eldigito = containerPai.find('.digito');
    let elnome = containerPai.find('.nome');
    let elrg = containerPai.find('.rg');
    let elpai = containerPai.find('.pai');
    let elmae = containerPai.find('.mae');

    elmatricula.on('focusout',()=>{
        let matricula = retornaSomenteNumeros(elmatricula.val());
        elmatricula.val(matricula);

        if(matricula!=0 && matricula!="" && matricula!=undefined){
            let digito = verifica_digito(matricula);
            eldigito.val(digito);
            //elnome.focus();

            let matric = matricula+digito;
            // console.log(matricula+digito)

            $.ajax({
                url: 'ajax/consultas/busca_presos.php',
                method: 'POST',
                //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
                data: {tipo: 4, matric: matric},
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json',
                async: false
            }).done(function(result){
                //console.log(result);

                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM)
                }else if(result.OK){
                    inserirMensagemTela(result.OK)
                }else{
                    //Quando retornar os dados já bloqueia os campos de matrícula
                    elmatricula.attr("disabled", "disabled");
                    //Habilita a visibilidade do botão desvincular
                    containerPai.find('.desvincular').removeAttr("hidden");
                    //Adiciona valor ao data-matriculavinculada
                    containerPai.data('matriculavinculada',result[0].MATRICULA)
                    elnome.val(result[0].NOME);
                    elrg.val(result[0].RG);
                    elpai.val(result[0].PAI);
                    elmae.val(result[0].MAE);
                    containerPai.find('#searchartigo'+id).focus();
                }
            });
        }else{
            eldigito.val('');
        }
    })

    //Permite somente teclar números
    eventoSomenteNumeros({elemento:elmatricula});
}

//Botões de acao
$('#nova').change(function(){
    verificaAcao();
});
$('#alterar').change(function(){
    verificaAcao();
});

//Evento de mudança do selectentrada
$('#selectentrada').change(function(){
    //$("#mensagem").html('');

    if($('#selectentrada').val()==0){
        limparCampos(false);
    }else{
        limparCampos(false);
        let identrada = $('#selectentrada').val()
        $('#searchentrada').val(identrada);

        //Buscar dados da entrada
        $.ajax({
            url: 'ajax/consultas/inc_busca_gerenciar.php',
            method: 'POST',
            data: {tipo: 1, identrada: identrada},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                $('#dataentrada').val(retornaDadosDataHora(result[0].DATAENTRADA,1));
                $('#horaentrada').val(retornaDadosDataHora(result[0].DATAENTRADA,6));
                $('.ferramentas').append("<button id='impr_entrada_presos' class='temp'>Imprimir Entrada de Presos</button>")
                eventoBotaoImprimir('#impr_entrada_presos',[{get:'documento',valor:['entrada_presos']},{get:'identrada',valor:[identrada]}]);
                $('.ferramentas').append("<button id='impr_digitais_presos' class='temp'>Imprimir Ficha Digitais</button>")
                eventoBotaoImprimir('#impr_digitais_presos',[{get:'documento',valor:['digitais_presos']},{get:'identrada',valor:[identrada]},{get:'opcaocabecalho',valor:[1]}]);
                $('.ferramentas').append("<button id='excluirentrada' class='temp btn-excluir'>Excluir Entrada</button>")

                adicionaEventoExcluirEntrada()
                $('#selectorigem').val(result[0].IDORIGEM).trigger('change')
                
                //Buscar dados dos presos
                $.ajax({
                    url: 'ajax/consultas/inc_busca_gerenciar.php',
                    method: 'POST',
                    data: {tipo: 2, identrada: identrada},
                    //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                    dataType: 'json'
                }).done(function(result){
                    //console.log(result)

                    if(result.MENSAGEM){
                        inserirMensagemTela(result.MENSAGEM)
                    }else{                        
                        result.forEach(linha => {
                            let idpreso = linha.ID
                            let matriculavinculada = linha.MATRICULAVINCULADA
                            //Se houver matricula vinculada então a variável obter o valor da matricula. Do contrário será 0;
                            if(matriculavinculada==true){
                                matriculavinculada = linha.MATRICULA
                            }
                            let idform = adicionarPreso(idpreso,matriculavinculada)
                            let containerPai = $('#preso'+idform);

                            containerPai.find('.nome').val(linha.NOME)
                            if(linha.MATRICULA){
                                containerPai.find('.matricula').val(midMatricula(linha.MATRICULA,1)).trigger('focusout');
                            }
                            if(linha.MAE){
                                containerPai.find('.mae').val(linha.MAE);
                            }
                            if(linha.PAI){
                                containerPai.find('.pai').val(linha.PAI);
                            }
                            if(linha.RG){
                                containerPai.find('.rg').val(linha.RG)
                            }
                            if(linha.INFORMACOES){
                                containerPai.find('.informacoes').val(linha.INFORMACOES)
                            }
                            if(linha.OBSERVACOES){
                                containerPai.find('.observacoes').val(linha.OBSERVACOES)
                            }
                            
                            if(linha.LANCADOCIMIC==true){
                                containerPai.addClass('cor-fundo-nao-editavel');
                                containerPai.find('.divprovisorio').remove();
                                containerPai.append('<div><p style="color: white; font-weight: bolder; font-size: 11pt;">Este registro não pode ser mais ser alterado</p></div>');
                            }else{
                                containerPai.addClass('cor-fundo-registro');
                            }
                            if(linha.PROVISORIO==1){
                                containerPai.find('.provisorio').prop('checked','checked')
                            }

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
                                        let observacoes = '';
                                        if(linha.OBSERVACOES!=null){
                                            observacoes = linha.OBSERVACOES;
                                        }
                                        adicionaArtigoPreso(containerPai,linha.IDARTIGO, linha.ID, observacoes)            
                                    });
                                }
                            });          
                        });
                    }
                });
            }
        });
    }
});

//Executa função na saída do foco do campo search da ORIGEM
//Se o id de ORIGEM não existir, limpa-se o campo select da ORIGEM
$('#searchentrada').change(function(){
    var id = $('#searchentrada').val();
    
    if(id!=$('#selectentrada').val()){
        buscaSearchComum('buscas_comuns',{tipo:20, identrada: id},$('#searchentrada'),$('#selectentrada'));
    }
})

//Função para preencher o select NUMERO DE ENTRADAS
function atualizarNumerosEntradas (){

    atualizaListagemComum('buscas_comuns',{tipo: 19},$('#listaentradas'),$('#selectentrada'))
    /*let option = '<option value="0">Selecione</option>';
    $('#listaentradas').html(option);

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'post',
        data: {tipo: 19},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async:false
    }).done(function(result){
        //console.log(result)

        $('#selectentrada').empty();
        $('#selectentrada').append(option);
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                $('#listaentradas').append(option);
            });
        }
    });

    $('#selectentrada').html($('#listaentradas').html());*/
}

//FUNÇÕES DAS ORIGENS
atualizaListagemComum('buscas_comuns',{tipo: 1},$('#listorigem'),$('#selectorigem'));
adicionaEventoSelectChange(0,$('#selectorigem'),$('#searchorigem'));

//Executa função na saída do foco do campo search da ORIGEM
//Se o id de ORIGEM não existir, limpa-se o campo select da ORIGEM
$('#searchorigem').change(function(){
    var id = $('#searchorigem').val();
    
    if(id!=$('#selectorigem').val()){
        buscaSearchComum('buscas_comuns',{tipo: 21, idorigem: id},$('#searchorigem'),$('#selectorigem'),$('.adicionarpreso'));
    }
})

//Ação do submit do formulário executará a função SALVAR
$('.salvarentrada').click(function(e){

    //Verifica os campos se estão preenchidos corretamente
    var verificacao = verificaCampos();

    if(verificacao===true){
        var idalterar = salvarDados();
    }
})

//Verifica os campos para poder salvar
function verificaCampos(){
    var mensagem = '';

    if($('#selectentrada').val()==0 && acao=='alterar'){
        mensagem = "<li class = 'mensagem-aviso'>Selecione uma entrada de presos. </li>"
        inserirMensagemTela(mensagem)
        $('#selectentrada').focus()
    }
    if($('#selectorigem').val()==0){
        mensagem = "<li class = 'mensagem-aviso'>Origem não selecionada. </li>"
        inserirMensagemTela(mensagem)
    }
    var presosadicionados = $('.form-preso-entrada');
    if(presosadicionados.length==0){
        mensagem = "<li class = 'mensagem-aviso'>Nenhum preso foi adicionado. </li>"
        inserirMensagemTela(mensagem)
    }else{
        for(i=0;i<presosadicionados.length;i++){
            var idpreso = presosadicionados[i].id;
            var preso = $('#'+idpreso);

            var conteudoVerificar = preso.find('.nome')
            if(conteudoVerificar.val() == '' || conteudoVerificar.val() == null || !conteudoVerificar.val().trim() || conteudoVerificar.val()==undefined){
                mensagem = ("<li class = 'mensagem-aviso'> O Campo nome do "+preso.find('.titulo-grupo').html()+" deve ser preenchido! </li>")
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
    let tipoAcao = acao; //Ação de nova ou alterar
    let identrada = $('#selectentrada').val();
    let dataentrada = $('#dataentrada').val()+' '+$('#horaentrada').val();
    let idorigem = $('#selectorigem').val();
    let presos = [];
    
    let presosadicionados = $('.form-preso-entrada'); //Obtem todos formulários de presos que são desta classe

    for(var iPreso=0;iPreso<presosadicionados.length;iPreso++){
        let idpreso = presosadicionados[iPreso].id;
        let preso = $('#'+idpreso);

        let nome = preso.find('.nome').val().trim();
        let pai = preso.find('.pai').val().trim();
        let mae = preso.find('.mae').val().trim();
        let provisorio = preso.find('.provisorio').prop('checked');
        let idpresobancodados = preso.data('idpresobancodados');
        let matricula = preso.find('.matricula').val()+preso.find('.digito').val();
        let blnMatriculaVinculada = 0;

        if(preso.data('matriculavinculada')==matricula && matricula!=0){
            blnMatriculaVinculada = 1
        }
        let rg = preso.find('.rg').val()
        let artigos = []

        let campoArtigo = preso.find('.artigo-incluido'); //Obtem todos os Artigos que são desta classe e estão adicionados para este preso
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

        let informacoes = preso.find('.informacoes').val()
        let observacoes = preso.find('.observacoes').val()

        presos.push({
            NOME: nome.toUpperCase(),
            pai: pai.toUpperCase(),
            mae: mae.toUpperCase(),
            provisorio: provisorio,
            IDPRESO: idpresobancodados,
            MATRICULA: matricula,
            MATRICULAVINCULADA: blnMatriculaVinculada,
            RG: rg.toUpperCase(),
            ARTIGOS: artigos,
            INFORMACOES: informacoes,
            OBSERVACOES: observacoes
        })
        // console.log(presos);
    }

    let dados = {
        ACAO: tipoAcao,
        IDENTRADA: identrada,
        DATAENTRADA: dataentrada,
        IDORIGEM: idorigem,
        PRESOS: presos
    }
    
    // console.log(dados)

    let idretorno = 0;
    //Insere os dados no banco de dados pelo ajax
    $.ajax({
        url: 'ajax/inserir_alterar/inc_entrada_presos.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async:false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            //limparCampos(true);
            alert('Dados salvos com sucesso!')
            idretorno = result.IDENTRADA;
            var tempo = 500;
            if(acao=='nova'){
                $('#alterar').prop('checked',true).trigger('change')
                /*tempo = 500
                setTimeout(() => {           
                    $('#selectentrada').val(idretorno).trigger('change')    
                }, tempo);*/
                $('#selectentrada').val(idretorno).trigger('change')    
            }else{
                if(result.OK){
                    inserirMensagemTela(result.OK);
                }
                $('#selectentrada').trigger('change')    
            }
        }
    });

    return idretorno;
}

if(acao=="alterar"){
    $('#alterar').prop('checked',true).trigger('change');
}else{
    $('#nova').prop('checked',true).trigger('change');
}

//Verifica a ação para habilitar ou não o campo de NUMERO DE ENTRADA
verificaAcao();

//Atualizar a listagem de artigos existentes e preencher o datalist
atualizarListaArtigos();
// console.log(identradabuscar);
// console.log(acao);

if(identradabuscar>0){
    $('#selectentrada').val(identradabuscar).trigger('change');
}
