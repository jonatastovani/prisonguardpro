//Abrir pop-up itemkit
var iditemkitalterar = 0;
//array que armazena a listagem de itemkits para inserir nos selects e datalist.
var listaTodosItemKits='';
const tabelaitemkit = $('#table-pop-itemkit').find('tbody');

$("#novoitem").on("click",function(){
    $("#pop-itemkit").addClass("active");
    $("#pop-itemkit").find(".popup").addClass("active");
    atualizaListagemItemKit();
    limparCamposPopItemKit();
    $("#pop-itemkit").find("#nomeitemkit").focus();
});
//Fechar pop-up itemkit
$("#pop-itemkit").find(".close-btn").on("click",function(){
    $("#pop-itemkit").removeClass("active");
    $("#pop-itemkit").find(".popup").removeClass("active");
    $('#table-pop-itemkit').find('tbody').html('')
    limparCamposPopItemKit();
    //Atualiza a listagem de todos os campos de itemkits que houver na página.
    atualizarListaItemKits();
})

function atualizaListagemItemKit (){
    $.ajax({
        url: 'ajax/consultas/popup_busca_kit_preso.php',
        method: 'POST',
        data: {tipo: 2},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        tabelaitemkit.html('');
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                if(linha.PADRAOENTREGA==true){
                    linha.PADRAOENTREGA = 'Sim';
                }else{
                    linha.PADRAOENTREGA = '<span class="red bolder">Não</span>';
                    linha.QTD= '';
                }
                let registro = '<tr class="cor-fundo-comum-tr"><td>'+linha.NOMEEXIBIR+'</td><td class="centralizado">'+linha.QTD+'</td><td class="centralizado">'+linha.PADRAOENTREGA+'</td><td><div class="centralizado"><button id="alt'+linha.VALOR+'" class="btnAcaoRegistro"><img src="imagens/alterar.png" class="imgBtnAcao" title="Alterar Kit" alt="Alterar"></button><button id="del'+linha.VALOR+'" class="btnAcaoRegistro"><img src="imagens/delete-16.png" alt="Deletar"></button></td></tr>';
                tabelaitemkit.append(registro);
                adicionaEventosPopItemKit(linha.VALOR,linha.NOMEEXIBIR)
            });
        }
    });
}

function limparCamposPopItemKit(){
    $('#nomeitemkit').val('').focus();
    $('#qtdentrega').val('');
    $('#label-itemkit').html('Novo Ítem');
    $('#ckbitemnovo').prop('checked',false);
    $('#ckbpadrao').prop('checked',false);
    $('#ckbpadrao').trigger('change');
    iditemkitalterar = 0;
    $('#cancelaritemkit').attr('hidden','hidden');
}

$('#ckbpadrao').change(()=>{
    let bln = $('#ckbpadrao').prop('checked');
    if(bln==true){
        $('#divqtd').addClass('inline margin-espaco-esq');
        $('#divqtd').removeAttr('hidden');
    }else{
        $('#divqtd').removeAttr('class');
        $('#divqtd').attr('hidden','hidden');   
    }
})

$('#cancelaritemkit').click(function(){
    limparCamposPopItemKit();
    atualizaListagemItemKit();
})

function adicionaEventosPopItemKit(id,nomeexibir){
    $('#alt'+id).on('click', function(){
        limparCamposPopItemKit();
        buscarDadosPopItemKit(id);
    })
    $('#del'+id).on('click', function(){
        var resultado = confirm("Confirma a exclusão do Ítem "+nomeexibir+'? **ATENÇÃO** Os presos que receberam este ítem não serão afetados, você somente não encontrará mais esta opção para inserir nos próximos kits.');

        if(resultado===true){
            excluirPopItemKit(id)
        }
    })
}

function buscarDadosPopItemKit(id){
    $.ajax({
        url: 'ajax/consultas/popup_busca_kit_preso.php',
        method: 'POST',
        data: {tipo: 3, iditem: id},
        dataType: 'json',
        async: false
}).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
            atualizaListagemItemKit();
            limparCamposPopItemKit();
        }else{
            if(result.EXCLUIDO!=null){
                inserirMensagemTela('<li class="mensagem-aviso">O ítem já não está mais disponível para edição, foi excluído em '+result.DATAEXCLUIDO+'.</li>');
                atualizaListagemItemKit();
                limparCamposPopItemKit();
            }else{
                iditemkitalterar = id;
                $('#nomeitemkit').val(result[0].NOME).focus();
                $('#label-itemkit').html('Alterar Ítem: '+result[0].NOME)
                if(result[0].ITEMNOVO==true){
                    $('#ckbitemnovo').prop('checked',true);
                }
                if(result[0].PADRAOENTREGA==true){
                    $('#ckbpadrao').prop('checked',true).trigger('change');
                    $('#qtdentrega').val(result[0].QTD);
                }
                $('#cancelaritemkit').removeAttr('hidden');    
            }
        }
    });
}

function excluirPopItemKit(id){
    $.ajax({
        url: 'ajax/inserir_alterar/inc_kitpreso.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {tipo: 6, iditem: id},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            atualizaListagemItemKit();
        }else{
            inserirMensagemTela(result.OK)
            atualizaListagemItemKit();
            limparCamposPopItemKit();
        }
    });
}

$('#salvaritemkit').click(function(){
    if(verificaSalvarPopItemKit()==true){
        salvarPopItemKit();
    }
})

function verificaSalvarPopItemKit(){
    var mensagem = '';

    var conteudoVerificar = $('#nomeitemkit').val().trim()
    if(conteudoVerificar == '' || conteudoVerificar == null){
        mensagem = ("<li class = 'mensagem-aviso'> O Campo Nome do ítem deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
            $('#nomeitemkit').focus();
    }
    if($('#ckbpadrao').prop('checked')==true){
        var conteudoVerificar = $('#qtdentrega').val().trim();
        conteudoVerificar = retornaSomenteNumeros(conteudoVerificar);
        if(conteudoVerificar == '' || conteudoVerificar == null){
            mensagem = ("<li class = 'mensagem-aviso'> A quantidade padrão de entrega deve ser preenchida! </li>")
            inserirMensagemTela(mensagem)
            $('#qtdentrega').focus();
        }else{
            $('#qtdentrega').val(conteudoVerificar);
        }
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopItemKit(){
    var nome = $('#nomeitemkit').val();
    var itemnovo = $('#ckbitemnovo').prop('checked');
    var padrao = $('#ckbpadrao').prop('checked');
    var qtd = $('#qtdentrega').val();
    var tipo = 4;
    if(iditemkitalterar!=0){
        tipo = 5;
    }
    //console.log(nome, itemnovo, padrao, qtd, tipo);

    $.ajax({
        url: 'ajax/inserir_alterar/inc_kitpreso.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {
            nome: nome,
            tipo: tipo,
            iditem: iditemkitalterar,
            itemnovo: itemnovo,
            padrao: padrao,
            qtd: qtd
        },
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            inserirMensagemTela(result.OK)
            atualizaListagemItemKit();
            limparCamposPopItemKit();
        }
    });
}

//Função para preencher o select de ÍTENS
function atualizarListaItemKits(){
    
    var option = '<option value="0">Selecione</option>';
    $.ajax({
        url: 'ajax/consultas/popup_busca_kit_preso.php',
        method: 'POST',
        data: {tipo: 2},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        listaTodosItemKits=option;
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                listaTodosItemKits += option;
            });

            //Adiciona em todos os selects
            $('.itemkits').html(listaTodosItemKits);;
        }
    });
}