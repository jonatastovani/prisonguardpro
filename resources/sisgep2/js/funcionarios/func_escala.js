const divfuncionarios = $('#divfuncionarios');
const idtipoescala = $('#idtipoescala').val();
let idcomentario = '';
let arrfuncionariosescala = [];
let idturno = 0;
let idmodelo = 0;
let listafuncionarios = '';
let listapostos = '';
let listapostoscomassinatura = '';

atualizaListagemComum('busca_funcionarios',{tipo: 2,blnescala:1},0,$('#selectturno'),false,false,false,false,false);

function buscarEscalaPlantao(){

    divfuncionarios.html('');
    arrfuncionariosescala = [];
    
    let verifica = verificaTurno();
    if(verifica==false){
        inserirMensagemTela('<li class="mensagem-aviso">O Boletim vigente não é do turno que está sendo visualizado, então não será possível gerenciar a escala de plantão.</li>')
        $('#excluirescala').attr('hidden','hidden');
        $('#inserirpadrao').attr('hidden',true);
        $('#salvarescala').attr('hidden',true);
        return;
    }

    let dados = {
        tipo: 5,
        idturno: idturno,
        idtipoescala: idtipoescala
    }
    // console.log(dados);

    let result = consultaBanco('busca_funcionarios',dados);
    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(dados => {
                let idbanco = dados.IDBANCO;
                let idposto = dados.IDPOSTO;
                let idfuncionario = dados.IDUSUARIO;
                let observacoes = dados.OBSERVACOES;
                let idfunctroca = dados.IDUSUARIOTROCA;
                let idpostotroca = dados.IDPOSTOTROCA;

                let iddivfunc = buscarFuncionario(idfuncionario);
                let divfunc = $('#'+iddivfunc);

                let index = arrfuncionariosescala.findIndex((item)=>item.divfunc==iddivfunc);
                arrfuncionariosescala[index].idbanco = idbanco;
                
                let novonome = "Nome: <b>"+arrfuncionariosescala[index].nomefuncionario+"</b>";
                if(observacoes!=null){
                    arrfuncionariosescala[index].observacoes = observacoes;
                    novonome += " ("+arrfuncionariosescala[index].observacoes+")";
                }else{
                    arrfuncionariosescala[index].observacoes = '';
                }

                divfunc.find('.nomefuncionario').html(novonome);
                divfunc.find('.selectsetor').val(idposto).trigger('change');

                if(idfunctroca!=null){
                    divfunc.find('.selecttroca').val(idfunctroca).trigger('change');
                    divfunc.find('.selectsetortroca').val(idpostotroca).trigger('change');
                }
                
            });
            if(divfuncionarios.find('.funcionario').length>0){
                $('#excluirescala').removeAttr('hidden');
            }
            $('#inserirpadrao').removeAttr('hidden');
            $('#salvarescala').removeAttr('hidden');
        }
    }
    // adicionaEventoExtraSelectPosto();
}

function buscarEscalaPadrao(blninseriridbanco=true){

    divfuncionarios.html('');
    arrfuncionariosescala = [];

    let dados = {
        tipo: 6,
        idturno: idturno,
        idtipoescala: idtipoescala
    }
    // console.log(dados);

    let result = consultaBanco('busca_funcionarios',dados);
    // console.log(result);

    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(dados => {
                let idbanco = 0;
                if(dados.IDBANCO!=null){
                    idbanco = dados.IDBANCO;
                }
                let idposto = 0;
                if(dados.IDPOSTO!=null){
                    idposto = dados.IDPOSTO;
                }
                let nomeposto = dados.NOMEPOSTO;
                let idfuncionario = dados.IDUSUARIO;
                let nomefuncionario = dados.NOMEUSUARIO;

                let iddivfunc = buscarFuncionario(idfuncionario);
                let divfunc = $('#'+iddivfunc);

                if(blninseriridbanco==true){
                    let index = arrfuncionariosescala.findIndex((item)=>item.divfunc==iddivfunc);
                    arrfuncionariosescala[index].idbanco = idbanco;
                    
                    let observacoes = dados.OBSERVACOES;
                    let novonome = "Nome: <b>"+arrfuncionariosescala[index].nomefuncionario+"</b>";
                    if(observacoes!=null){
                        arrfuncionariosescala[index].observacoes = observacoes;
                        novonome += " ("+arrfuncionariosescala[index].observacoes+")";
                    }else{
                        arrfuncionariosescala[index].observacoes = '';
                    }

                    divfunc.find('.nomefuncionario').html(novonome);
                    divfunc.find('.selectsetor').val(idposto).trigger('change');
    
                }

                divfunc.find('.selectsetor').val(idposto).trigger('change');
            });
        }
    }
    // adicionaEventoExtraSelectPosto();
}

//Verifica se o turno que está sendo visualizado é o turno que está vigente
function verificaTurno(){
    let retorno = [];

    let dados = {
        tipo: 7,
        idturno: idturno,
    }

    let result = consultaBanco('busca_funcionarios',dados);

    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            if(result.RETORNO==1){
                retorno = true;
            }else{
                retorno = false;
            }
        }
    }
    return retorno;
}

function buscarFuncionario(idfuncionario){
    let funcexiste = false;
    //Verifica se o funcionário já não está inserido
    arrfuncionariosescala.forEach(func => {
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
                let linha = '<div id="funcionario'+novoID+'" class="funcionario relative largura-total"><span class="botoes"></span><div class="inline nomefuncionario" style="width: 45%;">Nome: <b>'+nomefuncionario+'</b></div><div class="inline" style="width: 45%;"><label for="selectsetor'+novoID+'">Posto/Setor: </label><select id="selectsetor'+novoID+'" class="selectsetor"></select></div></div>';
                divfuncionarios.append(linha);
                
                let iddivfunc = 'funcionario'+novoID
                arrfuncionariosescala.push({
                    divfunc: iddivfunc,
                    idbanco: 0,
                    idfuncionario: idfuncionario,
                    nomefuncionario: nomefuncionario,
                    idposto: 0,
                    observacoes: '',
                    troca: 0
                })

                retorno = iddivfunc;
                let divfunc = $('#'+iddivfunc)
                //Adiciona o botão de excluir somente se não for funcinário da própria escala
                if(idturno!=idturnofunc || idescala!=idtipoescala){
                    divfunc.append('<button class="fechar-absolute">&times;</button>');
                    adicionaEventoExcluir(divfunc);
                    adicionaEventoExcluirFuncionario(divfunc.find('.fechar-absolute'),iddivfunc);
                    divfunc.find('.selectsetor').html(listapostossemassinatura);
                }else{
                    divfunc.find('.selectsetor').html(listapostos);
                }
                // if($('#rbdiaria').prop('checked')==true){
                //     divfunc.append('<button class="fechar-absolute">&times;</button>');
                //     adicionaEventoExcluir(divfunc);
                //     adicionaEventoExcluirFuncionario(divfunc.find('.fechar-absolute'),iddivfunc);
                // }
                adicionaEventosFuncionario($('#selectsetor'+novoID),iddivfunc);
                inserirBotaoAlterarFuncionario(divfunc.find('.botoes'),idfuncionario)
                inserirBotaoComentario(divfunc.find('.botoes'),iddivfunc)
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
        organizaEscala();
    })
    select.keydown((e)=>{
        // console.log(e.which);
        let key = e.which || e.keyCode;
        if(key==46){
            select.val(0).trigger('change');
        }
    })
}

function inserirBotaoComentario(tdbotoes,id,title='Alterar Comentário'){
    if(tdbotoes.find('.btnaltcoment').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro btnaltcoment" title="'+title+'"><img src="imagens/comentario.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.btnaltcoment').click(()=>{
            idcomentario = id;
            abrirPopComentario();
        })
    }
}

function adicionaEventoExcluirFuncionario(botao,iddivfunc){
    botao.click(()=>{
        arrfuncionariosescala = arrfuncionariosescala.filter((item)=>item.divfunc!=iddivfunc)
    })
}

$('#inserir').click(()=>{
    let idfuncionario = $('#selectfuncionario').val();
    if(idfuncionario>0 && idfuncionario!=undefined && idfuncionario!=NaN && $('#rbdiaria').prop('checked')==true){

        let iddivfunc = buscarFuncionario(idfuncionario);

        if(iddivfunc!=false){
            $('#searchfuncionario').val('').trigger('change');
            $('#'+iddivfunc).find('.selectsetor').trigger('change').focus(); //Executar para poder ativar a coloração da divfunc
        }
    }
})

function efetuaBuscaEscalas(){
    idturno = $('#selectturno').val();

    if($('#rbdiaria').prop('checked')==true){
        idmodelo = 1;
    }else if($('#rbpadrao').prop('checked')==true){
        idmodelo = 2;
    }

    $('#listapostos').html('');
    atualizaListagemComum('buscas_comuns',{tipo: 32,idturno:idturno,idmodelo:2,comassinatura:1},$('#listapostos'),0,false,false,false,false);
    listapostossemassinatura = $('#listapostos').html();
    atualizaListagemComum('buscas_comuns',{tipo: 32,idturno:idturno,idmodelo:idmodelo},$('#listapostos'),0,false,false,false,false);
    listapostos = $('#listapostos').html();

    if($('#rbdiaria').prop('checked')==true){
        buscarEscalaPlantao();
        $('#camposselectfuncionario').removeAttr('hidden');
    }else if($('#rbpadrao').prop('checked')==true){
        buscarEscalaPadrao();
        $('#camposselectfuncionario').attr('hidden','hidden');
        $('#excluirescala').attr('hidden','hidden');
        $('#inserirpadrao').attr('hidden',true);
        $('#salvarescala').removeAttr('hidden');
    }
    atualizaListaFuncionarios();
}

function adicionaEventoPesquisaEscala(){
    let seletores = [];
    seletores.push(['#selectturno','change']);
    seletores.push(['#rbdiaria','click']);
    seletores.push(['#rbpadrao','click']);

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                efetuaBuscaEscalas();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    efetuaBuscaEscalas();
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                efetuaBuscaEscalas();
            })
        }
    });
}

$('#atualizarlista').click(()=>{
    atualizaListaFuncionarios();
})

function organizaEscala(){
    let dados = {
        tipo: 32,
        idturno: idturno,
        idmodelo:idmodelo
    }
    // console.log(dados);

    let result = consultaBanco('buscas_comuns',dados);
    //console.log(result);

    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            arrfuncionariosescala.forEach(func => {
                let indexfunc = arrfuncionariosescala.findIndex((funcionario)=>funcionario.divfunc==func.divfunc);

                let divfunc = $('#'+func.divfunc);
                if(func.idposto==0){
                    divfunc.css('order',0);
                }else{
                    let index = result.findIndex((posto)=>posto.ID==func.idposto);
                    divfunc.css('order',result[index].ORDEM);

                    if(result[index].IDTROCA==1){
                        if(divfunc.find('.troca').length<1){                           
                            divfunc.append('<div class="troca"><div class="inline" style="width: 45%;"><label for="selecttroca'+func.divfunc+'">Troca: </label><select id="selecttroca'+func.divfunc+'" class="selecttroca"></select><datalist id=listafuncionarios'+func.divfunc+'></datalist></div><div class="inline" style="width: 45%;"><label for="selectsetortroca'+func.divfunc+'">Posto/Setor: </label><select id="selectsetortroca'+func.divfunc+'" class="selectsetortroca">'+listapostossemassinatura+'</select></div></div>');

                            atualizaListagemComum('busca_funcionarios',{tipo: 8, turnonaoselecionados:idturno},$('#listafuncionarios'+func.divfunc),$('#selecttroca'+func.divfunc),true,false,false);

                            arrfuncionariosescala[indexfunc].troca={
                                idfuncionario:0,
                                idposto:0
                            };
                            adicionaEventosTroca(divfunc,func.divfunc);
                        }
                    }else{
                        divfunc.find('.troca').remove();
                        arrfuncionariosescala[indexfunc].troca=0;
                    }
                }
            });
        }
    }
}

function adicionaEventosTroca(divfunc,iddivfunc){
    let selectfunc = divfunc.find('.selecttroca');
    let selectposto = divfunc.find('.selectsetortroca');
    selectfunc.change(()=>{
        if(selectfunc.val()!=undefined && selectfunc.val()!=NaN){
            let index = arrfuncionariosescala.findIndex((item)=>item.divfunc==iddivfunc);
            arrfuncionariosescala[index].troca.idfuncionario = selectfunc.val();
        }
    })
    selectposto.change(()=>{
        if(selectposto.val()!=undefined && selectposto.val()!=NaN){
            let index = arrfuncionariosescala.findIndex((item)=>item.divfunc==iddivfunc);
            arrfuncionariosescala[index].troca.idposto = selectposto.val();
        }
    })
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

$('#inserirpadrao').click(()=>{
    if($('#rbdiaria').prop('checked')==true){
        let confirmacao = true;
        if(arrfuncionariosescala==[]){
            confirmacao = confirm('Deseja realmente inserir a escala Padrão?\rA escala de plantão atual não será excluída enquanto não for salvo esta próxima escala.');
        }
        if(confirmacao==true){
            buscarEscalaPadrao(false);
        }    
    }else{
        $('#inserirpadrao').attr('hidden',true);
    }
})

$('#excluirescala').click(()=>{
    if($('#rbdiaria').prop('checked')==true){
        let confirmacao = confirm('Confirma a exclusão da escala de plantão?\r\rNão será possível desfazer esta ação!');
        if(confirmacao==true){
            //excluir escala
            $.ajax({
                url: 'ajax/inserir_alterar/funcionarios_gerenciar.php',
                method: 'POST',
                //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
                data: {tipo: 3},
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json'
            }).done(function(result){
                // console.log(result)
        
                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM);
                }else{
                    inserirMensagemTela(result.OK);
                    $('#excluirescala').attr('hidden',true);
                    divfuncionarios.html('');
                    arrfuncionariosescala = [];
                }
            });
        }    
    }else{
        $('#excluirescala').attr('hidden',true);
    }
})

function abrirPopComentario(){
    $("#pop-comentario").addClass("active");
    $("#pop-comentario").find(".popup").addClass("active");
    let index = arrfuncionariosescala.findIndex((item)=>item.divfunc==idcomentario);
    $('#comentario').val(arrfuncionariosescala[index].observacoes).focus();
}

//Fechar pop-up Artigo
$("#pop-comentario").find(".close-btn").on("click",function(){
    fecharPopComentario();
})

function fecharPopComentario(){
    $("#pop-comentario").removeClass("active");
    $("#pop-comentario").find(".popup").removeClass("active");
    $('#comentario').val('');
    idcomentario = '';
}

$('#imp_escala').click(()=>{

    let dados = [];
    if(idmodelo==1){
        dados = {
            tipo: 5,
            idturno: idturno,
            idtipoescala: idtipoescala
        }
        // console.log(dados);
    }else if(idmodelo==2){
        dados = {
            tipo: 6,
            idturno: idturno,
            idtipoescala: idtipoescala
        }
    }else{
        inserirMensagemTela('<li class="mensagem-erro"> Modelo de escala não encontrado. Contate o programador. </li>')
        return;
    }

    let result = consultaBanco('busca_funcionarios',dados);
    // console.log(result);

    if(result.length){
        // Se for modelo 2 então é feito a consulta para gerar uma escala para cada diretor que existe como responsável pelo turno
        if(idmodelo==1){
            let informacoes = [
                {get:'documento',valor:['imp_escala']},
                {get:'modelo',valor:[idmodelo]},
                {get:'idturno',valor:[dados.idturno]},
                {get:'idtipoescala',valor:[dados.idtipoescala]},
                {get:'iddiretor',valor:[2]}
            ];
        
            // console.log(informacoes)
        
            imprimirDocumentos(informacoes);

        }else if(idmodelo==2){
            // Buscar os diretores que possam responder parar o turno que está se observando e gerar uma escala para cada um (Para o caso do diretor estar de férias, gera-se para os outros diretores que estiverem cadastrados);
            result = consultaBanco('buscas_comuns',{tipo:34,selecionados:[53]});
            // console.log(result);

            if(result.length==0){
                inserirMensagemTela('<li class="mensagem-erro"> Não foi encontrado nenhum Diretor para gerar a Escala Mensal. </li>')
                return;
            }
            
            for(let $i=0;$i<result.length;$i++){
                let informacoes = [
                    {get:'documento',valor:['imp_escala']},
                    {get:'modelo',valor:[idmodelo]},
                    {get:'idturno',valor:[dados.idturno]},
                    {get:'idtipoescala',valor:[dados.idtipoescala]},
                    {get:'iddiretor',valor:[result[$i].IDDIRETOR]}
                ];
            
                // console.log(informacoes)
            
                imprimirDocumentos(informacoes);
            }
        }

    }else{
        inserirMensagemTela('<li class="mensagem-erro"> A escala solicitada não existe. </li>')
    }

    






})

$('#salvarcomentario').click(()=>{
    let index = arrfuncionariosescala.findIndex((item)=>item.divfunc==idcomentario);
    arrfuncionariosescala[index].observacoes = $('#comentario').val();

    let novonome = "Nome: <b>"+arrfuncionariosescala[index].nomefuncionario+"</b>";
    if(arrfuncionariosescala[index].observacoes!=''){
        novonome += " ("+arrfuncionariosescala[index].observacoes+")";
    }
    $('#'+idcomentario).find('.nomefuncionario').html(novonome);
    fecharPopComentario();
})

$('#salvarescala').click(function(){
    if(verificaSalvarEscala()==true){
        salvarEscala();
    }
})

function verificaSalvarEscala(){
    let mensagem = '';

    // let elementoVerificar = $('#selectpresopopnovamud')
    // if((elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN)){
    //     elementoVerificar.focus()
    //     mensagem = ("<li class = 'mensagem-aviso'> Selecione um Preso! </li>")
    //     inserirMensagemTela(mensagem)
    // }
    if(arrfuncionariosescala.length==0){
        $('#searchfuncionario').focus();
        mensagem = ("<li class = 'mensagem-aviso'> Nenhum funcionário(a) foi adicionado(a)! </li>");
        inserirMensagemTela(mensagem);
    }
    arrfuncionariosescala.forEach(func => {
        if(func.idposto==0){
            if(mensagem==""){
                $('#'+func.divfunc).find('.selectsetor').focus();
            }
            mensagem = ("<li class = 'mensagem-aviso'> Funcionário(a) <b>"+func.nomefuncionario+"</b> não está inserido(a) em nenhum Posto/Setor! </li>");
            inserirMensagemTela(mensagem);
        }
        if(func.troca!=0){
            if(func.troca.idfuncionario==0){
                if(mensagem==""){
                    $('#'+func.divfunc).find('.selecttroca').focus();
                }
                mensagem = ("<li class = 'mensagem-aviso'> Funcionário(a) troca do funcionário(a) <b>"+func.nomefuncionario+"</b> não foi selecionado(a)! </li>");
                inserirMensagemTela(mensagem);
            }else{
                if(func.troca.idposto==0){
                    if(mensagem==""){
                        $('#'+func.divfunc).find('.selectsetortroca').focus();
                    }
                    mensagem = ("<li class = 'mensagem-aviso'> O funcionário(a) troca do funcionário(a) <b>"+func.nomefuncionario+"</b> não está inserido(a) em nenhum Posto/Setor! </li>");
                    inserirMensagemTela(mensagem);
                }            
            }
        }
    });

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarEscala(){
    
    let dados = {
        tipo: 2,
        idturno: idturno,
        idtipoescala: idtipoescala,
        idmodelo: idmodelo,
        arrfuncionarios: arrfuncionariosescala
    }

    // console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/funcionarios_gerenciar.php',
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
            efetuaBuscaEscalas();
        }
    });
}

atualizaListaFuncionarios();
adicionaEventoPesquisaEscala();
efetuaBuscaEscalas();

