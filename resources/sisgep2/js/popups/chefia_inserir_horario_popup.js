// Confirmação do retorno para salvamento das informações
let confirmacaopoptime = 0;
let idmovpoptime = 0;
let tabpoptime = 0;

$("#openpoptime").on("click",function(){
    abrirPopPopHorario();
});

function abrirPopPopHorario(){
    $("#pop-pophorario").addClass("active");
    $("#pop-pophorario").find(".popup").addClass("active");
    $('#horariopoptime').focus();
    buscaDadosPopHorario();
}
//Fechar pop-up Artigo
$("#pop-pophorario").find(".close-btn").on("click",function(){
    fecharPopPopHorario();
})
function fecharPopPopHorario(){
    $("#pop-pophorario").removeClass("active");
    $("#pop-pophorario").find(".popup").removeClass("active");
    limparCamposPopPopHorario();
    idmovpoptime = 0;
    tabpoptime = 0;
}

$('#cancelarpoptime').click(()=>{
    fecharPopPopHorario();
})

function limparCamposPopPopHorario(){
    $('.temppoptime').val('');
    confirmacaopoptime = 0;
    tabpoptime = 0;
    idmovpoptime = 0;
}

function buscaDadosPopHorario(){
    let dados = {
        tipo: 16,
        tabela:tabpoptime,
        idmovimentacao:idmovpoptime
    }
    // console.log(dados)
    $.ajax({
        url: 'ajax/consultas/chefia_busca_gerenciar.php',
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
            $('#horarioatualpoptime').html(retornaDadosDataHora(result[0].DATAMOV,12));
            $('#horariopoptime').val(retornaDadosDataHora(result[0].DATAMOV,6));
        }
    });
}

function adicionaEventoPopHorario(){
    let seletores = [];
    seletores.push(['#confirmarpoptime','click']);
    seletores.push(['#horariopoptime','enter']);

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                salvarPopHorario();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    salvarPopHorario();
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                salvarPopHorario();
            })
        }
    });
}

function verificaCamposPopHorario(){
    let mensagem = '';

    let elementoVerificar = $('#horariopoptime')
    if((elementoVerificar.val().trim()=='' || elementoVerificar.val()==null || elementoVerificar.val()==NaN || elementoVerificar.val()==0)){
        elementoVerificar.focus()
        mensagem = ("<li class = 'mensagem-aviso'> Horário inválido! </li>")
        inserirMensagemTela(mensagem)
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopHorario(){
    if(verificaCamposPopHorario()==true){

        let horario = $("#horariopoptime").val();

        let dados = {
            tipo: 10,
            idmovimentacao: idmovpoptime,
            tabela: tabpoptime,
            horario: horario
        }
    
        //console.log(dados);
    
        $.ajax({
            url: 'ajax/inserir_alterar/chefia_gerenciar.php',
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
                fecharPopPopHorario();
            }
        });
    }
}

adicionaEventoPopHorario();