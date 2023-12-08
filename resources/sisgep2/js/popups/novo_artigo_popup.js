//Abrir pop-up Artigo
var idartigoalterar = 0;
//array que armazena a listagem de artigos para inserir nos selects e datalist.
var listaTodosArtigos='';
const tabelapopartigo = $('#table-pop-artigo').find('tbody');

$("#novoartigo").on("click",function(){
    $("#pop-artigo").addClass("active");
    $("#pop-artigo").find(".popup").addClass("active");
    atualizaListagemPopArtigos()
    $("#pop-artigo").find("#valornovoartigo").focus();
});
//Fechar pop-up Artigo
$("#pop-artigo").find(".close-btn").on("click",function(){
    $("#pop-artigo").removeClass("active");
    $("#pop-artigo").find(".popup").removeClass("active");
    tabelapopartigo.html('')
    limparCamposPopArtigos();
    //Atualiza a listagem de todos os campos de artigos que houver na página.
    atualizarListaArtigos();
})

function atualizaListagemPopArtigos (){
    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'post',
        data: {tipo:17},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        tabelapopartigo.html('');
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                var registro = '<tr class="cor-fundo-comum-tr"><td>'+linha.NOMEEXIBIR+'</td><td><div class="centralizado"><button id="alt'+linha.VALOR+'" class="btnAcaoRegistro"><img src="imagens/alterar.png" class="imgBtnAcao" alt="Alterar" title="Alterar artigo"></button><button id="del'+linha.VALOR+'" class="btnAcaoRegistro"><img src="imagens/delete-16.png" alt="Deletar"></button></td></tr>';
                tabelapopartigo.append(registro);
                adicionaEventosPopArtigo(linha.VALOR,linha.NOMEEXIBIR)
            });
        }
    });
}

function limparCamposPopArtigos(){
    $('#valornovoartigo').val('');
    $('#valornovoartigo').focus();
    $('#label-artigo').html('Novo Artigo:')
    idartigoalterar = 0;
    $('#cancelarartigo').attr('hidden','hidden');
}

$('#cancelarartigo').click(function(){
    limparCamposPopArtigos();
    atualizaListagemPopArtigos();
})

function adicionaEventosPopArtigo(id,valor){
    $('#alt'+id).on('click', function(){
        idartigoalterar = id;
        $('#valornovoartigo').val(valor);
        $('#label-artigo').html('Alterar artigo: '+valor)
        $('#valornovoartigo').focus();
        $('#cancelarartigo').removeAttr('hidden');
    })
    $('#del'+id).on('click', function(){
        var resultado = confirm("Confirma a exclusão do artigo "+valor+'? **ATENÇÃO** Os presos que estão atribuídos com este artigo não serão afetados, você somente não encontrará mais esta opção para inserir nos próximos presos.');

        if(resultado===true){
            excluirPopArtigo(id)
        }
    })
}

$('#salvarartigo').click(function(){
    if(verificaSalvarPopArtigo()==true){
        salvarPopArtigo();
    }
})

function verificaSalvarPopArtigo(){
    var mensagem = '';

    var conteudoVerificar = $('#valornovoartigo').val()
    if(conteudoVerificar == '' || conteudoVerificar == null || !conteudoVerificar.trim()){
        mensagem = ("<li class = 'mensagem-aviso'> O Campo valor do artigo deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopArtigo(){
    var valor = $('#valornovoartigo').val();
    var acaoartigo = 'incluir';
    if(idartigoalterar!=0){
        acaoartigo = 'alterar';
    }
    $.ajax({
        url: 'ajax/inserir_alterar/popartigo_incluir_artigo.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {valor: valor, acao: acaoartigo, id: idartigoalterar},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            atualizaListagemPopArtigos();
            limparCamposPopArtigos();
        }
    });
}

function excluirPopArtigo(id){
    $.ajax({
        url: 'ajax/excluir/popartigo_excluir_artigo.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {id: id},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            atualizaListagemPopArtigos();
            limparCamposPopArtigos();
        }
    });
}

//Função para preencher o select e datalist de ARTIGOS (Primeiro atribui o array arrayArtigos e depois atualiza todos os selects e datalist)
function atualizarListaArtigos (){
    
    var option = '<option value="0">Selecione</option>';
    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo: 17},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        listaTodosArtigos=option;
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                option = "<option value="+linha.VALOR+">"+linha.NOMEEXIBIR+"</option>"
                listaTodosArtigos += option;
            });

            //Limpa o datalist e preenche com os valores atualizados
            $('#listaartigos').empty();
            $('#listaartigos').append(listaTodosArtigos);

            //Obtem todos os selects
            $('.artigos').empty();
            $('.artigos').append(listaTodosArtigos);
            
            //Limpa e adiciona a lista de arquivos em todos selects da classe
            /*var selectsArtigos = $('.artigos');
            for(i=0;i<selectsArtigos.length;i++){
                $(selectsArtigos[i]).empty();
                $(selectsArtigos[i]).append(listaTodosArtigos);
            }*/
        }
    });
}
