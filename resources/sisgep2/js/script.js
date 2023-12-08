//Scripts para rodar em todas telas

//Armazena o id do usuário que está logado. Se o usuário for trocado, a tela se atualiza para ver se o novo usuário logado tem permissões para acessar a tela que já estava aberta
const idusuariologado = buscaIDUsuarioLogado();

setInterval(() => {
    if(buscaIDUsuarioLogado()!=idusuariologado){
        location.reload();
        console.log(idusuariologado)
    }
}, 500);

function verificaMensagensExibidas(){
    var elementos = $('#mensagem').children()
    for(var i=0;i<elementos.length;i++){
        var mensagem = $('#'+elementos[i].id)
        var tempolimite = mensagem.data('tempolimite')
        var tempo = retornaDiferencaDeDataEHora(new Date(),tempolimite,6);
        if(tempo<0){
            mensagem.remove()
        }
    }
}

// Variável do temporizador de mensagens
let temporizadorMensagens = setInterval(verificaMensagensExibidas, 1000);
// Função que será executada para bloquear a ação do temporizador definido no setInterval
function pararTemporizadorMensagens() {
    clearInterval(temporizadorMensagens);
    console.log("temporizador parado")
}

//Captura as teclas apertadas
document.onkeydown = function(e) {
    if(e.key === 'Escape') {
        if($('#pop-artigo').find(".popup.active").length==1){
            $("#pop-artigo").find(".close-btn").trigger('click');
        }
        else if($('#pop-itemkit').find(".popup.active").length==1){
            $("#pop-itemkit").find(".close-btn").trigger('click');
        }
        else if($('#pop-kitentregue').find(".popup.active").length==1){
            $("#pop-kitentregue").find(".close-btn").trigger('click');
        }
        else if($('#pop-novopertence').find(".popup.active").length==1){
            $("#pop-novopertence").find(".close-btn").trigger('click');
        }
        else if($('#pop-pertencespreso').find(".popup.active").length==1){
            $("#pop-pertencespreso").find(".close-btn").trigger('click');
        }
        else if($('#pop-movimentacaotrans').find(".popup.active").length==1){
            $("#pop-movimentacaotrans").find(".close-btn").trigger('click');
        }
        else if($('#pop-unidades').find(".popup.active").length==1){
            $("#pop-unidades").find(".close-btn").trigger('click');
        }
        else if($('#pop-recebimentotrans').find(".popup.active").length==1){
            $("#pop-recebimentotrans").find(".close-btn").trigger('click');
        }
        else if($('#pop-novamudanca').find(".popup.active").length==1){
            $("#pop-novamudanca").find(".close-btn").trigger('click');
        }
        else if($('#pop-novoatend').find(".popup.active").length==1){
            $("#pop-novoatend").find(".close-btn").trigger('click');
        }
        else if($('#pop-novogerais').find(".popup.active").length==1){
            $("#pop-novogerais").find(".close-btn").trigger('click');
        }
        else if($('#pop-book').find(".popup.active").length==1){
            $("#pop-book").find(".close-btn").trigger('click');
        }
        else if($('#pop-atend').find(".popup.active").length==1){
            $("#pop-atend").find(".close-btn").trigger('click');
        }
        else if($('.visibilidadepop').length>0){
            $('#btn'+$('.visibilidadepop').attr('id')).trigger('click');
        }
        else if($('#pop-popfuncionario').find(".popup.active").length==1){
            $("#pop-popfuncionario").find(".close-btn").trigger('click');
        }
        else if($('#pop-comentario').find(".popup.active").length==1){
            $("#pop-comentario").find(".close-btn").trigger('click');
        }
        else if($('#pop-raioslocais').find(".popup.active").length==1){
            $("#pop-raioslocais").find(".close-btn").trigger('click');
        }
        else if($('#pop-autenticacao').find(".popup.active").length==1){
            // $("#pop-autenticacao").find(".close-btn").trigger('click');
            encerrartimerpopautent = true;
        }
        else if($('#pop-pophorario').find(".popup.active").length==1){
            $("#pop-pophorario").find(".close-btn").trigger('click');
        }
        else if($('#pop-popexclusoes').find(".popup.active").length==1){
            $("#pop-popexclusoes").find(".close-btn").trigger('click');
        }
        else if($('#pop-atendenf').find(".popup.active").length==1){
            $("#pop-atendenf").find(".close-btn").trigger('click');
        }
        else if($('#pop-novomedic').find(".popup.active").length==1){
            $("#pop-novomedic").find(".close-btn").trigger('click');
        }
        else if($('#pop-novovis').find(".popup.active").length==1){
            $("#pop-novovis").find(".close-btn").trigger('click');
        }
        else if($('#pop-popcadvisi').find(".popup.active").length==1){
            $("#pop-popcadvisi").find(".close-btn").trigger('click');
        }
        else if($('#pop-popfotovisitante').find(".popup.active").length==1){
            $("#pop-popfotovisitante").find(".close-btn").trigger('click');
        }
        else if($('#pop-semcela').find(".popup.active").length==1){
            $("#pop-semcela").find(".close-btn").trigger('click');
        }
        else if($('#pop-confentsai').find(".popup.active").length==1){
            $("#pop-confentsai").find(".close-btn").trigger('click');
        }
        else if($('#pop-celasvisitas').find(".popup.active").length==1){
            $("#pop-celasvisitas").find(".close-btn").trigger('click');
        }
        else if($('#pop-medass').find(".popup.active").length==1){
            $("#pop-medass").find(".close-btn").trigger('click');
        }
        else if($('#pop-visumedicass').find(".popup.active").length==1){
            $("#pop-visumedicass").find(".close-btn").trigger('click');
        }
    }
    /*if(e.key === 'Enter') {
        if($('#pop-artigo').find(".popup.active").length==1){
            $("#pop-artigo").find("#salvarartigo").trigger('click')
        }
    }*/
}

document.onclick = function(e){
    //console.log('Zerar contagem de inatividade')
}
