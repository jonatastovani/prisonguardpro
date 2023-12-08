var idpopnovomedic = 0;
const tabelapopnovomedic = $('#table-pop-novomedic').find('tbody');

$("#openpopnovomedic").on("click",function(){
    abrirPopNovoMedicamento();
});

function abrirPopNovoMedicamento(){
    $("#pop-novomedic").addClass("active");
    $("#pop-novomedic").find(".popup").addClass("active");
    atualizaListagemNovoMedicamento();
    atualizaListagemComum('buscas_comuns',{tipo:39},0,$('#selectunidadepopnovomedic'));
    $("#valorpopnovomedic").focus();
}

//Fechar pop-up Artigo
$("#pop-novomedic").find(".close-btn").on("click",function(){
    fecharPopNovoMedicamento();
})

function fecharPopNovoMedicamento(){
    $("#pop-novomedic").removeClass("active");
    $("#pop-novomedic").find(".popup").removeClass("active");
    tabelapopnovomedic.html('')
    limparCamposPopNovoMedicamento();
}

function atualizaListagemNovoMedicamento(){
    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'post',
        data: {tipo:40},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        tabelapopnovomedic.html('');
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                let novoID = 'trnovomedic'+gerarID('.trnovomedic');
                var registro = '<tr id="'+novoID+'" class="cor-fundo-comum-tr trnovomedic"><td class="tdbotoes centralizado nowrap"></td><td>'+linha.NOME+'</td><td>'+linha.QTDESTOQUE+'</td></tr>';
                tabelapopnovomedic.append(registro);
                inserirBotaoAlterarPopNovoMedicamento($('#'+novoID).find('.tdbotoes'),linha.ID);
                inserirBotaoExcluirPopNovoMedicamento($('#'+novoID).find('.tdbotoes'),linha.ID,linha.NOME);
            });
        }
    });
}

function limparCamposPopNovoMedicamento(){
    $('.strtemppopnovomedic').val('');
    $('.seltemppopnovomedic').val(0);
    $('#nomepopnovomedic').focus();
    $('#label-nomepopnovomedic').html('Novo Medicamento')
    idpopnovomedic = 0;
}

$('#cancelarpopnovomedic').click(function(){
    if(idpopnovomedic==0 && $('#nomepopnovomedic').val()==''){
        fecharPopNovoMedicamento();
    }else{
        limparCamposPopNovoMedicamento();
        atualizaListagemNovoMedicamento();    
    }
})

function alterarPopNovoMedicamento(id){
    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'post',
        data: {tipo:41, idmedic:id},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
            limparCamposPopNovoMedicamento();
        }else{
            idpopnovomedic = result[0].ID;
            $('#nomepopnovomedic').val(result[0].NOME).focus();
            $('#label-nomepopnovomedic').html('Alterar medicamento: '+result[0].NOME)
            $('#selectunidadepopnovomedic').val(result[0].IDUNIDADE);
            $('#qtdpadraopopnovomedic').val(result[0].QTD);
            $('#qtdestoquepopnovomedic').val(result[0].QTDESTOQUE);
            $('#qtdminimopopnovomedic').val(result[0].MINIMOESTOQUE);
        }
    });
}

function inserirBotaoAlterarPopNovoMedicamento(tdbotoes,id,title='Editar medicamento'){
    if(tdbotoes.find('.altnovomedic').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro altnovomedic" title="'+title+'"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.altnovomedic').click(()=>{
            alterarPopNovoMedicamento(id);
        })
    }
}

function inserirBotaoExcluirPopNovoMedicamento(tdbotoes,id,nome,title='Excluir medicamento'){
    if(tdbotoes.find('.delnovomedic').length==0){
        tdbotoes.append('<button class="btnAcaoRegistro delnovomedic" title="'+title+'"><img src="imagens/delete-16.png" class="imgBtnAcao"></button>');
        tdbotoes.find('.delnovomedic').click(()=>{
            var resultado = confirm("Confirma a exclusão do medicamento "+nome+'?\r\r**ATENÇÃO**\rOs medicamentos já entregues não serão afetados, você somente não encontrará mais esta opção para inserir nas próximas entregas.');

            if(resultado===true){
                excluirPopNovoMedicamento(id)
            }    
        })
    }
}

$('#salvarpopnovomedic').click(function(){
    if(verificaSalvarPopNovoMedicamento()==true){
        salvarPopNovoMedicamento();
    }
})

function verificaSalvarPopNovoMedicamento(){
    var mensagem = '';

    var elementoVerificar = $('#nomepopnovomedic')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O campo Nome deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
    }

    elementoVerificar = $('#selectunidadepopnovomedic')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione a unidade de fornecimento! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopNovoMedicamento(){
    let nome = $('#nomepopnovomedic').val();
    let idunidadefornec = $('#selectunidadepopnovomedic').val();
    let qtdpadrao = $('#qtdpadraopopnovomedic').val();
    let qtdestoque = $('#qtdestoquepopnovomedic').val();
    let minestoque = $('#qtdminimopopnovomedic').val();

    let dados = {
        tipo:2,
        idmedic:idpopnovomedic,
        nome:nome,
        idunidadefornec:idunidadefornec,
        qtdpadrao:qtdpadrao,
        qtdestoque:qtdestoque,
        minestoque:minestoque
    };
    // console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/saude_gerenciar.php',
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

            atualizaListagemNovoMedicamento();
            limparCamposPopNovoMedicamento();
            atualizaListagemComum('buscas_comuns',{tipo:40},$('.listamedicamentos'),$('.selectmedicamentos'));
        }
    });
}

function excluirPopNovoMedicamento(id){
    $.ajax({
        url: 'ajax/inserir_alterar/saude_gerenciar.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {tipo:3, idmedic: id},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            inserirMensagemTela(result.OK)
            atualizaListagemNovoMedicamento();
            // limparCamposPopNovoMedicamento();
        }
    });
}