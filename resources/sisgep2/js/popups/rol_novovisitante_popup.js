// Confirmação do retorno para salvamento das informações
let confirmacaopopnovovis = 0;
let idpresopopnovovis = 0;
const tabelavisitantesadicionados = $('#table-visitantesadicionados').find('tbody');
const tabelavisitantesantigos = $('#table-visitantesantigos').find('tbody');
let arrvisitantes = [];
let arrvisitantesantigos = [];
//usar var ou invés de let, pois mais popup pode precisar dessa variável. O importante é o script da página que chamou ser carregado por último e atribuir o valor na variável
var blnrolvisu = 0;

$("#opennovovis").on("click",function(){
    abrirPopNovoVisitante();
});

//idpreso = preencher caso queira que se abra o novo atendimento preenchendo a busca do preso. Só funcionará se não estiver alterando a solicitação de atendimento;
function abrirPopNovoVisitante(){
    
    limparCamposPopNovoVisitante();

    if(idpresopopnovovis>0){
        $('#divselectpresopopnovovis').attr('hidden','hidden');
        buscaDadosPresoPopNovoVisitante(idpresopopnovovis);
    }else{
        atualizaListagemComum('busca_presos',{tipo: 2, tipobusca: 2, valor: 1, tiporetorno:2, idvisualizacao:0, blnvisuchefia:1},$('#listapresospopnovovis'),$('#selectpresopopnovovis'));

        $('#searchpresopopnovovis').focus();
    }
    
    if(blnrolvisu==1 || blnrolvisu==0 && idpresopopnovovis>0){
        atualizaListagemComum('buscas_comuns',{tipo: 37, idtipo:2},$('#listagraupopnovovis'),$('#selectgraupopnovovis'));

        $("#pop-novovis").addClass("active");
        $("#pop-novovis").find(".popup").addClass("active");        
    }else{
        fecharPopNovoVisitante();
    }
}
//Fechar pop-up Artigo
$("#pop-novovis").find(".close-btn").on("click",function(){
    fecharPopNovoVisitante();
})
function fecharPopNovoVisitante(){
    $('#listapresospopnovovis').html('');
    $('#selectpresopopnovovis').html('');
    $("#pop-novovis").removeClass("active");
    $("#pop-novovis").find(".popup").removeClass("active");
    limparCamposPopNovoVisitante();
    idpresopopnovovis = 0;
}

function limparCamposPopNovoVisitante(){
    $('#divselectpresopopnovovis').removeAttr('hidden');
    $('#divdadospresopopnovovis').attr('hidden','hidden');
    $('.strpopnovovis').val('');
    tabelavisitantesadicionados.html('');
    $('#divvisitantesatigos').attr('hidden','hidden');
    tabelavisitantesantigos.html('');
    arrvisitantes=[];
}

adicionaEventoSelectChange(0,$('#selectpresopopnovovis'),$('#searchpresopopnovovis'))

$('#selectpresopopnovovis').change(function(){
    limparCamposPopNovoVisitante();
    var idpreso = $('#selectpresopopnovovis').val();
    
    if(idpreso!=0 && idpreso!=null){
        buscaDadosPresoPopNovoVisitante(idpreso);
    }else{
        $('#divdadospresopopnovovis').attr('hidden','hidden');
    }
})

$('#searchpresopopnovovis').change(function(){
    var id = $('#searchpresopopnovovis').val();
    
    if(id!=$('#selectpresopopnovovis').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresopopnovovis'),$('#selectpresopopnovovis'),$('#nomevisitantepopnovovis'));
    }
})

function buscaDadosPresoPopNovoVisitante(idbuscar){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 1, idpreso: idbuscar},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
            idpresopopnovovis = 0;
        }else{
            idpresopopnovovis = idbuscar;
            $('#nomepresopopnovovis').html(result[0].NOME);
            $('#matriculapresopopnovovis').html(midMatricula(result[0].MATRICULA,3));
            $('#raiocelapresopopnovovis').html(result[0].RAIOCELA);
            $('#divdadospresopopnovovis').removeAttr('hidden');
        }
        buscaVisitantesPopNovoVisitante();
        buscaVisitantesPopNovoVisitante(1);
    });
}

adicionaEventoSelectChange(0,$('#selectgraupopnovovis'),$('#searchgraupopnovovis'))

$('#searchgraupopnovovis').change(function(){
    var id = $('#searchgraupopnovovis').val();
    
    if(id!=$('#selectgraupopnovovis').val()){
        buscaSearchComum('buscas_comuns',{tipo:42, idgrau:id},$('#searchgraupopnovovis'),$('#selectgraupopnovovis'),$('#btninserirpopnovovis'));
    }
})

function buscaVisitantesPopNovoVisitante(visitanteantigo=0){

    // let tabela = '';
    let arr = '';
    switch (visitanteantigo) {
        case 0:
            // tabela = tabelavisitantesadicionados;
            arr = arrvisitantes;
            break;
        
        case 1:
            // tabela = tabelavisitantesantigos;
            arr = arrvisitantesantigos;
            break;
        default:
            return;
    }

    let dados = {tipo: 2,
        idpreso: idpresopopnovovis,
        visitanteantigo:visitanteantigo
    }
    // console.log(dados)

    $.ajax({
        url: 'ajax/consultas/rol_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            if(visitanteantigo==1 && result.length>0){
                $('#divvisitantesatigos').removeAttr('hidden');
            }

            result.forEach(visi => {
                let NovoId = inserirVisitantePopNovoVisitante(visi.NOME,visi.IDPARENTESCO,visitanteantigo);
                $('#'+NovoId).removeClass('cor-fundo-comum-tr').addClass(visi.COR);
                $('#'+NovoId).find('.tdsitvisitante').html(visi.PARENTESCO);
                $('#'+NovoId).find('.tdsituacao').html(visi.SITUACAO);
                $('#'+NovoId).find('.tddatacadastro').html(retornaDadosDataHora(visi.DATACADASTRO,12));

                let index = arr.findIndex((visitante)=>visitante.idtr==NovoId);
                arr[index].idbanco=visi.ID;
                arr[index].idvisitante=visi.IDVISITANTE;
                arr[index].idsituacao=visi.IDSITUACAO;

                if(visitanteantigo==1){
                    inserirBotaoInserirVisitanteAntigo(arr[index].idtr)
                }
            });
        }
    });
}

function inserirVisitantePopNovoVisitante(nomevisitante,idparentesco,visitanteantigo=0){
    let parentesco = '';
  
    let tabela = '';
    let arr = '';
    switch (visitanteantigo) {
        case 0:
            tabela = tabelavisitantesadicionados;
            arr = arrvisitantes;
            break;
        
        case 1:
            tabela = tabelavisitantesantigos;
            arr = arrvisitantesantigos;
            break;
        default:
            return;
    }

    let result = consultaBanco('buscas_comuns',{tipo:42, idgrau:idparentesco});
    if(result.length){
        parentesco = result[0].NOME;
    }

    let NovoId = 'trnovovis'+gerarID('.trnovovis');
    tabela.append('<tr id="'+NovoId+'" class="trnovovis cor-fundo-comum-tr"><td class="tdbotoes"></td><td>'+nomevisitante+'</td><td>'+parentesco+'</td><td class="tdsitvisitante">Não inserido</td><td class="tdsituacao">Não inserido</td><td class="tddatacadastro centralizado">Não Cadastrado</td></tr>');

    arr.push({
        idtr:NovoId,
        idbanco:0,
        idvisitante:0,
        nomevisitante: nomevisitante,
        idparentesco:idparentesco,
        idsituacao:0,
        idtrantigo:0
    })
    return NovoId;
}

function inserirBotaoInserirVisitanteAntigo(idtr){
    let tr = $('#'+idtr);
    let tdbotoes = tr.find('.tdbotoes');

    tdbotoes.append('<button class="btnAcaoRegistro btninsant" title="Excluir visitante"><img src="imagens/adicionar.png" class="imgBtnAcao"></button>');
    tdbotoes.find('.btninsant').click(()=>{
        let indexantigos = arrvisitantesantigos.findIndex((visitante)=>visitante.idtr==idtr);

        let index = arrvisitantes.findIndex((visitante)=>visitante.idtrantigo==idtr);
        if(index<0){
            let NovoId = inserirVisitantePopNovoVisitante(arrvisitantesantigos[indexantigos].nomevisitante,arrvisitantesantigos[indexantigos].idparentesco);

            index = arrvisitantes.findIndex((visitante)=>visitante.idtr==NovoId);
            arrvisitantes[index].idvisitante=arrvisitantesantigos[indexantigos].idvisitante;
            arrvisitantes[index].idtrantigo=arrvisitantesantigos[indexantigos].idtr;

            inserirBotaoExcluirVisitante(NovoId);
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Este visitante já foi iserido </li>');
        }

    })
}

function inserirBotaoExcluirVisitante(idtr){
    let tr = $('#'+idtr);
    let tdbotoes = tr.find('.tdbotoes');

    tdbotoes.append('<button class="btnAcaoRegistro btnexcl" title="Excluir visitante"><img src="imagens/lixeira.png" class="imgBtnAcao"></button>');
    tdbotoes.find('.btnexcl').click(()=>{
        arrvisitantes = arrvisitantes.filter((visita)=>visita.idtr!=idtr);
        tr.remove();
    })
}

$('#btninserirpopnovovis').click(function(){
    
    let mensagem = '';
    let elementoVerificar = $('#nomevisitantepopnovovis');
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus();
        }
        mensagem = ("<li class = 'mensagem-aviso'> Insira o nome do visitante! </li>")
        inserirMensagemTela(mensagem);
    }
    
    elementoVerificar = $('#selectgraupopnovovis');
    if(elementoVerificar.val()<1 || $.isNumeric(elementoVerificar.val())==false || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus();
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o Grau de parentesco! </li>")
        inserirMensagemTela(mensagem);
    }

    if(mensagem==''){
        let idtr = inserirVisitantePopNovoVisitante($('#nomevisitantepopnovovis').val().trim(),$('#selectgraupopnovovis').val());
        inserirBotaoExcluirVisitante(idtr);
        $('#nomevisitantepopnovovis').val('').focus();
        $('#selectgraupopnovovis').val(0).trigger('change');
    }
})

$('#salvarpopnovovis').click(function(){
    if(verificaSalvarPopNovoVisitante()==true){
        salvarPopNovoVisitante();
    }
})

function verificaSalvarPopNovoVisitante(){
    let mensagem = '';

    if(arrvisitantes.length==0){
        $('#nomevisitantepopnovovis').focus();
        mensagem = ("<li class = 'mensagem-aviso'> Adicione pelo menos um visitante! </li>")
        inserirMensagemTela(mensagem);
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }
}

function salvarPopNovoVisitante(){
    
    let dados = {
        tipo: 1,
        idpreso: idpresopopnovovis,
        arrvisitantes: arrvisitantes,
        confirmacao: confirmacaopopnovovis
    };

    // console.log(dados)

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
            if(result.CONFIR){
                if(confirm(result.MSGCONFIR)==true){
                    confirmacaopopnovovis = result.CONFIR;
                    idpresopopnovovis = result.IDMOV;
                };
                if(confirmacaopopnovovis>0){
                    salvarPopNovoVisitante();
                }
            }else{
                inserirMensagemTela(result.OK);

                if(blnrolvisu==1){
                    limparCamposPopNovoVisitante();
                    idpresopopnovovis = 0;
                    $('#searchpresopopnovovis').val('').trigger('change').focus();            
                }else{
                    fecharPopNovoVisitante();
                }
            }
        }
    });
}

