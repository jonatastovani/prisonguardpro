//Abrir pop-up Artigo
let idunidadealterar = 0;
//array que armazena a listagem de artigos para inserir nos selects e datalist.
let listaTodasUnidades='';
const tabelapopunidades = $('#table-pop-unidades').find('tbody');

$("#openunidades").on("click",function(){
    abrirPopUnidades();
});
function abrirPopUnidades(){
    $("#pop-unidades").addClass("active");
    $("#pop-unidades").find(".popup").addClass("active");
    atualizaListagemPopUnidades()
    $("#pop-unidades").find("#valornovoartigo").focus();
    $('#textobusca').focus();
}
//Fechar pop-up Artigo
$("#pop-unidades").find(".close-btn").on("click",function(){
    fecharPopUnidades();
})
function fecharPopUnidades(){
    $("#pop-unidades").removeClass("active");
    $("#pop-unidades").find(".popup").removeClass("active");
    $('#table-pop-unidades').find('tbody').html('')
    limparCamposPopUnidades();
    //Atualiza a listagem de todos os campos de artigos que houver na página.
    atualizaListagemComum('buscas_comuns',{tipo:4},$('#listaunidades'),$('.locaisunidades'));
    $('#textobusca').val('');
}

$('#textobusca').keydown(()=>{
    atualizaListagemPopUnidades();
})

function atualizaListagemPopUnidades(){

    let dados = {
        tipo: 1,
        textobusca: $('#textobusca').val().trim()
    }
    //console.log(dados);

    $.ajax({
        url: 'ajax/consultas/popup_busca_unidades.php',
        method: 'post',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        tabelapopunidades.html('');
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                let id = linha.ID;
                let codigo = linha.CODIGO;
                let nome = linha.NOMEUNIDADE;
                let atribuido = linha.NOMEATRIBUIDO;
                if(atribuido==null){
                    atribuido='';
                }
                let diretor = linha.DIRETOR;
                if(diretor==null){
                    diretor='';
                }
                let notes = linha.EMAILNOTES;
                if(notes==null){
                    notes='';
                }
                let cimic = linha.EMAILCIMIC;
                if(cimic==null){
                    cimic='';
                }
                let endereco = linha.ENDERECO;
                if(endereco==null){
                    endereco='';
                }
                let cep = linha.CEP;
                if(cep==null){
                    cep='';
                }
                let telefones = linha.TELEFONES;
                if(telefones==null){
                    telefones='';
                }
                let cidade = linha.CIDADE;
                let perfil = linha.PERFIL;
                let tipo = linha.TIPO;
                let coord = linha.COORD;
                
                let registro = '<tr class="cor-fundo-comum-tr"><td><div class="centralizado"><button id="alt'+id+'" class="btnAcaoRegistro"><img src="imagens/alterar.png" class="imgBtnAcao" title="Alterar Unidade" alt="Alterar"></button><button id="del'+id+'" class="btnAcaoRegistro"><img src="imagens/delete-16.png" alt="Deletar"></button></td><td>'+codigo+'</td><td>'+nome+'</td><td>'+atribuido+'</td><td>'+diretor+'</td><td>'+notes+'</td><td>'+cimic+'</td><td>'+endereco+'</td><td>'+cep+'</td><td>'+telefones+'</td><td>'+cidade+'</td><td>'+perfil+'</td><td>'+tipo+'</td><td>'+coord+'</td></tr>';
                tabelapopunidades.append(registro);
                adicionaEventosPopUnidades(id,tipo+' '+nome)
            });
        }
    });
}

function buscaDadosAlterarPopUnidades(){

    let dados = {
        tipo: 2,
        idunidade: idunidadealterar
    }
    //console.log(dados);

    $.ajax({
        url: 'ajax/consultas/popup_busca_unidades.php',
        method: 'post',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(linha => {
                let id = linha.ID;
                let codigo = linha.CODIGO;
                let nome = linha.NOMEUNIDADE;
                let atribuido = linha.NOMEATRIBUIDO;
                if(atribuido==null){
                    atribuido='';
                }
                let diretor = linha.DIRETOR;
                if(diretor==null){
                    diretor='';
                }
                let notes = linha.EMAILNOTES;
                if(notes==null){
                    notes='';
                }
                let cimic = linha.EMAILCIMIC;
                if(cimic==null){
                    cimic='';
                }
                let endereco = linha.ENDERECO;
                if(endereco==null){
                    endereco='';
                }
                let cep = linha.CEP;
                if(cep==null){
                    cep='';
                }
                let telefones = linha.TELEFONES;
                if(telefones==null){
                    telefones='';
                }
                let cidade = linha.IDCIDADE;
                let perfil = linha.IDPERFIL;
                let tipo = linha.IDTIPO;
                let coord = linha.IDCOORD;
                
                $('#popuninome').val(nome);
                $('#popuniatribuido').val(atribuido);
                $('#selectpopunitipo').val(tipo);
                $('#selectpopunicoord').val(coord);
                $('#selectpopuniperfil').val(perfil);
                $('#popunidiretor').val(diretor);
                $('#popuninotes').val(notes);
                $('#popunicimic').val(cimic);
                $('#popunicodigo').val(codigo);
                $('#popuniendereco').val(endereco);
                $('#popunicep').val(cep);
                $('#popunitelefones').val(telefones);
                $('#selectpopunicidade').val(cidade).trigger('change');
                $('#camposalterarpopuni').removeAttr('hidden')
            });
        }
    });
}

function limparCamposPopUnidades(){
    $('.campotexto').val('');
    $('.camposelect').val(0);
    $('#textobusca').focus();
    $('.visibilidade1').attr('hidden','hidden')
    $('.visibilidade2').removeAttr('hidden')
    idunidadealterar = 0;

}

$('#cancelarpopunidades').click(function(){
    limparCamposPopUnidades();
    atualizaListagemPopUnidades();
})

$('#novopopunidades').click(function(){
    limparCamposPopUnidades();
    atualizaListagemPopUnidades();
    $('#camposalterarpopuni').removeAttr('hidden')
    $('.visibilidade1').removeAttr('hidden')
    $('.visibilidade2').attr('hidden','hidden')
})

function adicionaEventosPopUnidades(id,nome){
    $('#alt'+id).on('click', function(){
        idunidadealterar = id;
        buscaDadosAlterarPopUnidades();
        $('#valornovoartigo').focus();
        $('#popuninome').focus();
        $('.visibilidade1').removeAttr('hidden')
        $('.visibilidade2').removeAttr('hidden')
        })
    $('#del'+id).on('click', function(){
        let resultado = confirm("Confirma a exclusão da Unidade Prisional "+nome+'? **ATENÇÃO** Os presos que foram ou vieram deste local não serão afetados, você somente não encontrará mais esta opção para inserir nas próximas movimentações.');

        if(resultado===true){
            excluirPopUnidades(id)
        }
    })
}

adicionaEventoSelectChange($('#popunidades'),$('#selectpopunicidade'),$('#searchpopunicidade'));

$('#selectpopunicidade').change(function(){
    var idcidade = $('#selectpopunicidade').val();
    if(idcidade!=0){
        $('#searchpopunicidade').val(idcidade);
    }else{
        $('#searchpopunicidade').val('');
    }
})

$('#searchpopunicidade').change(function(){
    var id = $('#searchpopunicidade').val();
    
    if(id!=$('#selectpopunicidade').val()){
        buscaSearchComum('buscas_comuns',{tipo:16, idcidade:id},$('#searchpopunicidade'),$('#selectpopunicidade'),$('#salvarpopuni'));
    }
    var idcidade = $('#searchpopunicidade').val();
    
    /*if(idcidade!=0){
     
        $.ajax({
            url: 'ajax/consultas/buscas_comuns.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            data: {tipo: 16, idcidade: idcidade},
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
                //Limpa os campos pois o valor digitado não existe
                $('#selectpopunicidade').val(0);
                $('#searchpopunicidade').val('');
            }else{
                //Verifica se foi selecionado o estado
                if($('#ufnasc').val()!=0){
                    $('#selectpopunicidade').val(result[0].ID);
                }else{
                    $('#searchpopunicidade').val('');
                    $('#selectpopunicidade').val(0);
                }

                //Verifica se o código inserido pertence a alguma cidade do estado selecionado. Isso funciona quando se muda a UF e já existe uma cidade previamente selecionada
                if($('#selectpopunicidade').val()==null){
                    var mensagem = "<li class='mensagem-aviso'>O campo cidade foi limpo pois o valor previamente inserido não existe na UF atualmente selecionada.</li>"
                    inserirMensagemTela(mensagem);
                    $('#searchpopunicidade').val('');
                    $('#selectpopunicidade').val(0);
                }
            }
        });
    }else{
        $('#searchpopunicidade').val('');
        $('#selectpopunicidade').val(0);
    }*/
})

$('#salvarpopuni').click(function(){
    if(verificaSalvarPopUnidades()==true){
        salvarPopUnidades();
    }
})

function verificaSalvarPopUnidades(){
    let mensagem = '';

    let elementoVerificar = $('#popuninome')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        elementoVerificar.focus()
        mensagem = ("<li class = 'mensagem-aviso'> O Campo Nome da Unidade deve ser preenchido! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#selectpopunitipo')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione um Tipo de Unidade! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#selectpopunicoord')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione uma Coordenadoria! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#selectpopuniperfil')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o Perfil da Unidade! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#popunidiretor')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O Campo Nome da Diretor deve ser preenchido!! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#popunicodigo')
    if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> O Campo Código da Unidade deve ser preenchido!! </li>")
        inserirMensagemTela(mensagem)
    }
    elementoVerificar = $('#selectpopunicidade')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            elementoVerificar.focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione a cidade! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopUnidades(){
    
    let cidade = $("#selectpopunicidade").val();
    let perfil = $("#selectpopuniperfil").val();
    let codigo = $("#popunicodigo").val().trim().toUpperCase();
    let nome = $("#popuninome").val().trim();
    let atribuido = $("#popuniatribuido").val().trim();
    let tipounidade = $("#selectpopunitipo").val();
    let coord = $("#selectpopunicoord").val();
    let diretor = $("#popunidiretor").val().trim();
    let notes = $("#popuninotes").val().trim();
    let cimic = $("#popunicimic").val().trim();
    let endereco = $("#popuniendereco").val().trim();
    let cep = $("#popunicep").val();
    let telefones = $("#popunitelefones").val().trim();

    let dados = {
        tipo: 1,
        id: idunidadealterar,
        cidade: cidade,
        perfil: perfil,
        codigo: codigo,
        nome: nome,
        atribuido: atribuido,
        tipounidade: tipounidade,
        coord: coord,
        diretor: diretor,
        notes: notes,
        cimic: cimic,
        endereco: endereco,
        cep: cep,
        telefones: telefones
    }
    //console.log(dados);
    $.ajax({
        url: 'ajax/inserir_alterar/popunidades.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            inserirMensagemTela(result.OK)
            atualizaListagemPopUnidades();
            limparCamposPopUnidades();
        }
    });
}

function excluirPopUnidades(id){
    
    let dados = {
        tipo: 2,
        id: id
    }
    //console.log(dados);
    $.ajax({
        url: 'ajax/inserir_alterar/popunidades.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            inserirMensagemTela(result.OK)
            atualizaListagemPopUnidades();
        }
    });
}

atualizaListagemComum('buscas_comuns',{tipo:13},0,$('#selectpopunitipo'));
atualizaListagemComum('buscas_comuns',{tipo:12},0,$('#selectpopunicoord'));
atualizaListagemComum('buscas_comuns',{tipo:14},0,$('#selectpopuniperfil'));
atualizaListagemComum('buscas_comuns',{tipo:15, iduf:26},$('#listpopunicidade'),$('#selectpopunicidade'));
