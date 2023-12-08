// Confirmação do retorno para salvamento das informações
let confirmacaopopautent = 0;
let idtiporetorno = 0;
let encerrartimerpopautent = true;
let idusuariopopautent = 0;

$("#openpopautent").on("click",function(){
    abrirPopPopAutenticacao();
});

function abrirPopPopAutenticacao(){
    limparCamposPopPopAutenticacao();
    $("#pop-autenticacao").addClass("active");
    $("#pop-autenticacao").find(".popup").addClass("active");
    $('#usuariopopautent').focus();
    encerrartimerpopautent = false;
    idusuariopopautent = 0;
}
//Fechar pop-up Artigo
$("#pop-autenticacao").find(".close-btn").on("click",function(){
    fecharPopPopAutenticacao();
})
function fecharPopPopAutenticacao(){
    $("#pop-autenticacao").removeClass("active");
    $("#pop-autenticacao").find(".popup").removeClass("active");
    $('#table-pop-autenticacao').find('tbody').html('')
    limparCamposPopPopAutenticacao();
    idusuariopopautent = 0;
}

function limparCamposPopPopAutenticacao(){
    $('.temppopautent').val('');
    confirmacaopopautent = 0;
    idfuncionariopopautent=0;
}

function buscaDadosPopAutenticacao(){
    let usuario = $('#usuariopopautent').val();
    let senha = $('#senhapopautent').val();

    $.ajax({
        url: 'ajax/consultas/busca_funcionarios.php',
        method: 'POST',
        data: {tipo: 9, usuario:usuario, senha:senha},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            idusuariopopautent = result.IDUSUARIO;
        }
    });
}

function adicionaEventoPopAutenticacao(){
    let seletores = [];
    seletores.push(['#confirmarpopautent','click']);
    seletores.push(['#usuariopopautent','enter']);
    seletores.push(['#senhapopautent','enter']);

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                verificaAutenticacao();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    verificaAutenticacao();
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                verificaAutenticacao();
            })
        }
    });
}

function verificaCamposPopAutenticacao(){
    let mensagem = '';

    let elementoVerificar = $('#usuariopopautent')
    if((elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN)){
        elementoVerificar.focus()
        mensagem = ("<li class = 'mensagem-aviso'> Usuário não informado! </li>")
        inserirMensagemTela(mensagem)
    }else{
        elementoVerificar = $('#senhapopautent')
        if(elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            if(mensagem==''){
                elementoVerificar.focus()
            }
            mensagem = ("<li class = 'mensagem-aviso'> Senha não informada! </li>")
            inserirMensagemTela(mensagem)
        }
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function verificaAutenticacao(){
    if(verificaCamposPopAutenticacao()==true){
        buscaDadosPopAutenticacao();
    }
}

$('#cancelarpopautent').click(()=>{
    encerrartimerpopautent = true;
})

adicionaEventoPopAutenticacao();