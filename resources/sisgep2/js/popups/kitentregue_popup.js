//Abrir pop-up Artigo
let acaokitentregue='';
let idpresokitentregue = 0;
let idkitbuscar = 0;
const tabelakitentregue = $('#table-pop-kitentregue').find('tbody');

$('#novo-kitentregue').click(()=>{
    verificaAcaoKitEntregue();
})
$('#alterar-kitentregue').click(()=>{
    verificaAcaoKitEntregue();
})

function verificaAcaoKitEntregue(){
    limparCamposPopKitEntregue();
    $('#dataentrega').val(retornaDadosDataHora(new Date(),1));
    $('#obskitentregue').val('');

    atualizarListaItemKits();
    //atualizaSelectPopItens();
    $('#table-pop-kitentregue').find('tbody').html('');
    if($('#novo-kitentregue').prop('checked')==true){
        $('#kitentregueselect').attr('hidden', 'hidden');
        $('#imprimirkit').attr('hidden', 'hidden');
        acaokitentregue='incluir';
    }else if($('#alterar-kitentregue').prop('checked')==true){
        $('#kitentregueselect').removeAttr('hidden');
        $('#imprimirkit').removeAttr('hidden');
        acaokitentregue='alterar';
        atualizaSelectPopKitsEntregues();
    }
}

$("#openkitentregue").on("click",function(){
    abrirKitEntregue()
});
function abrirKitEntregue(){
    if(idpresokitentregue!=0){
        $("#pop-kitentregue").addClass("active");
        $("#pop-kitentregue").find(".popup").addClass("active");
        verificaAcaoKitEntregue()
        if(idkitbuscar!=0){
            setTimeout(() => {
                $('#selectkit').val(idkitbuscar).trigger('change');
            }, 200);
        }
        $("#pop-kitentregue").find("#selectitenskit").focus();
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhum ID Preso foi informado. </li>')
    }
}
//Fechar pop-up Artigo
$("#pop-kitentregue").find(".close-btn").on("click",function(){
    $('#cancelarkitentregue').click();
});

$('#cancelarkitentregue').click(function(){
    $("#pop-kitentregue").removeClass("active");
    $("#pop-kitentregue").find(".popup").removeClass("active");
    $('#table-pop-kitentregue').find('tbody').html('')
    $('#selectkit').html('').focus();
    $('#selectitenskit').html('');
    idkitbuscar = 0;
    limparCamposPopKitEntregue();
})

function atualizaSelectPopKitsEntregues (){
    $.ajax({
        url: 'ajax/consultas/popup_busca_kit_preso.php',
        method: 'POST',
        data: {idpreso: idpresokitentregue, tipo: 1},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        var select = $('#selectkit');
        select.html('');
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            $('#novo-kitentregue').prop('checked',true).trigger('click')
        }else{
            result.forEach(linha => {
                select.append('<option value='+linha.VALOR+'>'+linha.NOMEEXIBIR+'</option>');
            });
            select.trigger('change');
        }
    });
}

$('#selectkit').change(()=>{
    var idkit = $('#selectkit').val();

    if(idkit>0){
        buscarDadosPopItens(idkit);
    }
})

function buscarDadosPopItens(idkit){

    tabelakitentregue.html('');

    //Busca informações do kit
    $.ajax({
        url: 'ajax/consultas/popup_busca_kit_preso.php',
        method: 'POST',
        data: {idkit: idkit, tipo: 4},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            $('#dataentrega').val(retornaDadosDataHora(new Date(),1));
            $('#obskitentregue').val('')
        }else{
            $('#dataentrega').val(result[0].DATAENTREGA);
            $('#obskitentregue').val(result[0].OBSERVACOES)
        }
    });    

    //Busca os ítens entregues no kit
    $.ajax({
        url: 'ajax/consultas/popup_busca_kit_preso.php',
        method: 'POST',
        data: {idkit: idkit, tipo: 5},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                tabelakitentregue.append('<tr class="cor-fundo-comum-tr" id="item'+linha.IDITEM+'"><td class="nome-item">'+linha.NOMEEXIBIR+'</td><td><input type="number" style="width: 90%;" class="centralizado num-inteiro item-kitentregue" data-idbanco="'+linha.IDITEMENTREGUE+'" id="valitem'+linha.IDITEM+'" value="'+linha.QTD+'"></td><td><div class="centralizado"><button id="del'+linha.IDITEM+'" class="btnAcaoRegistro"><img src="imagens/delete-16.png" alt="Deletar"></button></div></td></tr>')
                adicionaEventosPopKitEntregue(linha.IDITEM)
            });
        }
    });
}

function atualizaSelectPopItens(){
    $.ajax({
        url: 'ajax/consultas/popup_busca_kit_preso.php',
        method: 'POST',
        data: {idpreso: idpresokitentregue, tipo: 2},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        var select = $('#selectitenskit');
        select.html('<option value=0>Selecione</option>');
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                select.append('<option value='+linha.VALOR+'>'+linha.NOMEEXIBIR+'</option>');
            });
        }
    });
}

function limparCamposPopKitEntregue(){
    $('#selectitenskit').val(0).focus();
    $('#quantidade').val(1);
}

$('#deletekit').click(function(){
    var idkit = $('#selectkit').val();
    var nomekit = $('#selectkit').find('option:selected').text();

    if(idkit!=0){
        var confirmacao = confirm('Confirma a exclusão do Kit Entregue '+nomekit+'? Obs: Esta ação não poderá ser desfeita.')

        if(confirmacao===true){
            $.ajax({
                url: 'ajax/inserir_alterar/inc_kitpreso.php',
                method: 'POST',
                data: {tipo: 3, idkit: idkit},
                //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
                dataType: 'json',
                async: false
            }).done(function(result){
                //console.log(result)
        
                if(result.MENSAGEM){
                    inserirMensagemTela(result.MENSAGEM)
                }else{
                    inserirMensagemTela(result.OK)
                    atualizaSelectPopKitsEntregues();
                }
            });    
        }
    }
})

$('#inseriritem').click(()=>{
    var iditem = $('#selectitenskit').val();
    var quantidade = $('#quantidade').val()

    if(verificaItemAdicionado(iditem)===true){
        inserirMensagemTela('<li class="mensagem-aviso"> O ítem já foi adicionado. </li>')
        limparCamposPopKitEntregue();
        $('#valitem'+iditem).focus();
    }else if(iditem>0 && quantidade>0){
        $.ajax({
            url: 'ajax/consultas/popup_busca_kit_preso.php',
            method: 'POST',
            data: {tipo: 3, iditem: iditem},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)
    
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                tabelakitentregue.append('<tr class="cor-fundo-comum-tr" id="item'+result[0].VALOR+'"><td class="nome-item">'+result[0].NOMEEXIBIR+'</td><td><input type="number" style="width: 90%;" class="centralizado num-inteiro item-kitentregue" data-idbanco="0" id="valitem'+result[0].VALOR+'" value="'+quantidade+'"></td><td><div class="centralizado"><button id="del'+result[0].VALOR+'" class="btnAcaoRegistro"><img src="imagens/delete-16.png" alt="Deletar"></button></div></td></tr>')
            }
            adicionaEventosPopKitEntregue(result[0].VALOR)
            limparCamposPopKitEntregue();
        });
    
    }else{
        if(iditem==0){
            inserirMensagemTela('<li class="mensagem-aviso"> Nenhum ítem foi selecionado. </li>')
        }
        if(quantidade==0){
            inserirMensagemTela('<li class="mensagem-aviso"> Nenhuma quantidade foi inserida. </li>')
        }
    }
})

$('#inserirpadrao').click(()=>{

    $.ajax({
        url: 'ajax/consultas/popup_busca_kit_preso.php',
        method: 'POST',
        data: {tipo: 6},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        tabelakitentregue.html('');
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                tabelakitentregue.append('<tr class="cor-fundo-comum-tr" id="item'+linha.IDITEM+'"><td class="nome-item">'+linha.NOMEEXIBIR+'</td><td><input type="number" style="width: 90%;" class="centralizado num-inteiro item-kitentregue" data-idbanco="0" id="valitem'+linha.IDITEM+'" value="'+linha.QTD+'"></td><td><div class="centralizado"><button id="del'+linha.IDITEM+'" class="btnAcaoRegistro"><img src="imagens/delete-16.png" alt="Deletar"></button></div></td></tr>')
                adicionaEventosPopKitEntregue(linha.IDITEM)
            });
        }
    });
})

function verificaItemAdicionado(iditem){
    var tr = tabelakitentregue.find('#item'+iditem)
    if(tr.length){
        return true;
    }else{
        return false;
    }
}

function adicionaEventosPopKitEntregue(id){
    $('#del'+id).on('click', function(){
        $('#item'+id).remove();
    })
}

$('#salvarkitentregue').click(function(){
    if(verificaSalvarPopKitEntregue()==true){
        salvarPopKitEntregue();
    }
})

function verificaSalvarPopKitEntregue(){
    var mensagem = '';
    var itens = $('#table-pop-kitentregue').find('tbody').find('.item-kitentregue');
    if(itens.length==0){
        mensagem = ("<li class = 'mensagem-aviso'> Nenhum ítem compõe o kit. Favor adicionar ítens. </li>")
        inserirMensagemTela(mensagem)
        $('#selectitenskit').focus();
    }else{
        for(var i=0;i<itens.length;i++){
            var item = $('#'+itens[i].id);
            var quantidade = item.val();
            var nome = item.parent().parent().find('.nome-item').html();

            var conteudoVerificar = quantidade
            if(conteudoVerificar == '' || conteudoVerificar == null || !conteudoVerificar.trim() || parseInt(conteudoVerificar) < 1){
                mensagem = ("<li class = 'mensagem-aviso'> A quantidade do ítem "+nome+" deve ser maior que zero! </li>");
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

function salvarPopKitEntregue(){
    var idkit = 0;
    if(acaokitentregue!='incluir'){
        idkit = $('#selectkit').val();
    }
    var observacoes = $('#obskitentregue').val();
    var dataentrega = $('#dataentrega').val();
    
    var itens = [];
    var tabela = tabelakitentregue.find('.item-kitentregue');
    for(var i=0;i<tabela.length;i++){
        var item = $('#'+tabela[i].id);
        var id = retornaSomenteNumeros(tabela[i].id);
        var idbanco = item.data('idbanco');
        var quantidade = item.val();
        itens.push({
            iditem: id,
            idbanco: idbanco,
            quantidade,quantidade
        })
    }

    var dados = {
        acao: acaokitentregue,
        idpreso: idpresokitentregue,
        idkit: idkit,
        observacoes: observacoes,
        dataentrega: dataentrega,
        itens: itens
    }

    // console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/inc_kitpreso.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {kitentregue: dados, tipo: 2},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            if(acaokitentregue=='incluir'){
                $('#alterar-kitentregue').prop('checked',true).trigger('click')
            }
            limparCamposPopKitEntregue();
            atualizaSelectPopKitsEntregues();
            setTimeout(() => {
                $('#selectkit').val(result.OK).trigger('change');
            }, 200);
        }
    });
}

$('#imprimirkit').click(function(){
    var idkit = $('#selectkit').val();
    if(idkit>0){
        var idkitentregue = [];
        idkitentregue.push(codifica(idkit));
        var tipoDocumento = 'kit_entregue';
        window.open('impressoes/impressao.php?documento='+codifica(tipoDocumento)+'&idkitentregue='+idkitentregue+'&opcaocabecalho='+codifica(2), '_blank')
    }
})
