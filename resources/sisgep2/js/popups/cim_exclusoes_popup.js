// Confirmação do retorno para salvamento das informações
let confirmacaopopexcl = 0;
let idexclusaopopexcl = 0;

$("#openpopexclusoes").on("click",function(){
    abrirPopExclusoes();
});

function abrirPopExclusoes(idfunc=0){
    atualizaListagemComum('busca_presos',{tipo:2, tipobusca:1, valor:1, tiporetorno:2},$('#listapresos'),$('#selectpresospopexcl'));
    atualizaListagemComum('buscas_comuns',{tipo: 6, selecionados: '1,2,3'},$('#listatipos'),$('#selecttipopopexcl'));

    let blnencontrado = false;

    if(idexclusaopopexcl>0){
        blnencontrado = buscaDadosPopExclusoes();
        $("#titulopopexcl").html('Alterar Exclusão')
        $("#camposselectpreso").attr('hidden','hidden')
    }else{
        $("#camposselectpreso").removeAttr('hidden')
        $("#titulopopexcl").html('Nova Exclusão')
        blnencontrado=true;
    }

    if(blnencontrado==true){
        $("#pop-popexclusoes").addClass("active");
        $("#pop-popexclusoes").find(".popup").addClass("active");
        $("#searchpresospopexcl").focus();
    }else{
        limparCamposPopExclusoes();
        $('#searchpresopopexcl').focus();
    }
}
//Fechar pop-up Artigo
$("#pop-popexclusoes").find(".close-btn").on("click",function(){
    fecharPopExclusoes();
})
function fecharPopExclusoes(){
    $("#pop-popexclusoes").removeClass("active");
    $("#pop-popexclusoes").find(".popup").removeClass("active");
    $('#table-pop-popexclusoes').find('tbody').html('')
    limparCamposPopExclusoes();
}

function limparCamposPopExclusoes(){
    $('.temppopexcl').val('').trigger('change');
    confirmacaopopexcl = 0;
    idexclusaopopexcl=0;
}

function buscaDadosPopExclusoes(){
    let retorno = false;
    let dados = {
        tipo: 2,
        idmovimentacao: idexclusaopopexcl
    }

    $.ajax({
        url: 'ajax/consultas/cim_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            buscaDadosPresoPopExclusoes(result[0].IDPRESO)
            $('#selecttipopopexcl').val(result[0].IDTIPO).trigger('change');
            $('#selectmotivopopexcl').val(result[0].IDMOTIVO).trigger('change');
            retorno = true;
        }
    });
    return retorno;
}

adicionaEventoSelectChange(0,$('#selectpresospopexcl'),$('#searchpresospopexcl'))

$('#searchpresospopexcl').change(function(){
    var id = $('#searchpresospopexcl').val();
    
    if(id!=$('#selectpresospopexcl').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresospopexcl'),$('#selectpresospopexcl'),$('#searchtipopopexcl'));
    }
})

$('#selectpresospopexcl').change(function(){
    var idpreso = $('#selectpresospopexcl').val();
    
    if(idpreso!=0 && idpreso!=null){
        buscaDadosPresoPopExclusoes(idpreso);
    }else{
        $('#dadospresopopexcl').attr('hidden','hidden');
    }
})

function buscaDadosPresoPopExclusoes(idpreso){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 1, idpreso: idpreso},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#nomepopexcl').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matriculapopexcl').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matriculapopexcl').html('Não Atribuída');
            }
            $('#raiocelapopexcl').html(result[0].RAIOCELA);
            $('#dadospresopopexcl').removeAttr('hidden');
        }
    });
}

adicionaEventoSelectChange(0,$('#selecttipopopexcl'),$('#searchtipopopexcl'))

$('#searchtipopopexcl').change(()=>{
    var id = $('#searchtipopopexcl').val();
    
    if(id!=$('#selecttipopopexcl').val()){
        buscaSearchComum('buscas_comuns',{tipo:7, idtipo:id},$('#searchtipopopexcl'),$('#selecttipopopexcl'),$('#searchmotivopopexcl'));
    }
})

$('#selecttipopopexcl').change(function (){
    var id = $('#selecttipopopexcl').val();
    atualizaListagemComum('buscas_comuns',{tipo: 8, idtipo: id},$('#listamotivos'),$('#selectmotivopopexcl'),true,true);
});

adicionaEventoSelectChange(0,$('#selectmotivopopexcl'),$('#searchmotivopopexcl'));

$('#searchmotivopopexcl').change(function(){
    let id = $('#searchmotivopopexcl').val();
    
    if(id!=$('#selectmotivopopexcl').val()){
        buscaSearchComum('buscas_comuns',{tipo:9, idmotivo:id},$('#searchmotivopopexcl'),$('#selectmotivopopexcl'),$('#salvarpopexcl'));
    }
})

$('#salvarpopexcl').click(function(){
    if(verificaSalvarPopExclusoes()==true){
        salvarPopExclusoes();
    }
})

function verificaSalvarPopExclusoes(){
    let mensagem = '';
    let elementoVerificar = 0;

    if(idexclusaopopexcl==0){
        elementoVerificar = $('#selectpresospopexcl')
        if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            $('#searchpresospopexcl').focus()
            mensagem = ("<li class = 'mensagem-aviso'> Selecione o preso! </li>")
            inserirMensagemTela(mensagem)
        }
    }

    elementoVerificar = $('#selecttipopopexcl')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            $('#searchtipopopexcl').focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o tipo de exclusão! </li>")
        inserirMensagemTela(mensagem)
    }

    elementoVerificar = $('#selectmotivopopexcl')
    if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
        if(mensagem==''){
            $('#searchmotivopopexcl').focus()
        }
        mensagem = ("<li class = 'mensagem-aviso'> Selecione o motivo! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopExclusoes(){

    let idmotivo = $("#selectmotivopopexcl").val();
    let idpreso = $("#selectpresospopexcl").val();
    let idtipo = $("#selecttipopopexcl").val();

    let dados = {
        tipo: 1,
        idmovimentacao: idexclusaopopexcl,
        idmotivo: idmotivo,
        idpreso: idpreso,
        idtipo: idtipo
    }

    // console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/cimic_gerenciar.php',
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
            if(result.MSGCONFIR){
                if(confirm(result.MSGCONFIR)==true){
                    confirmacaopopexcl = result.CONFIR;
                    idexclusaopopexcl = result.IDMOV;
                };
                confirmacaopopexcl = 0;
                idexclusaopopexcl = 0;
            }else{
                inserirMensagemTela(result.OK);
                if(idexclusaopopexcl!=0){
                    fecharPopExclusoes();
                }else{
                    limparCamposPopExclusoes();
                }
                if($('#table-exc-gerenciar').length>0){
                    atualizaListaGerenciarExclusoes();
                }
            }
        }
    });
}
