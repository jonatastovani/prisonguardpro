// Confirmação do retorno para salvamento das informações
let confirmacaopopatend = 0;
let idmovpopatend = 0;
const tabelaatend = $('#table-atendimentos').find('tbody');

$("#openatend").on("click",function(){
    //Fechar a visibilidade da população do raio antes de abrir o popup
    if($('.visibilidadepop').length>0){
        fecharPopulacaoRaio();
    }
    abrirPopAtend();
});

function abrirPopAtend(){
    atualizaListagemComum('busca_presos',{tipo: 2, tipobusca: 2, valor: 1, tiporetorno:2, idvisualizacao:idvisu},$('#listapresospopatend'),$('#selectpresopopatend'));
    atualizaListagemComum('buscas_comuns',{tipo: 27, idgrupo:2},0,$('#selecttipopopatend'),false,true,'change',false,false);
    $("#pop-atend").addClass("active");
    $("#pop-atend").find(".popup").addClass("active");
}

//Fechar pop-up Artigo
$("#pop-atend").find(".close-btn").on("click",function(){
    fecharPopAtend();
})
function fecharPopAtend(){
    $("#pop-atend").removeClass("active");
    $("#pop-atend").find(".popup").removeClass("active");
    $('#table-pop-atend').find('tbody').html('')
}

adicionaEventoSelectChange(0,$('#selecttipopopatend'))

$('#selecttipopopatend').change(function(){
    atualizaListaAtend();
})

function atualizaListaAtend(){
    let idatend = $('#selecttipopopatend').val();

    tabelaatend.html('');
    if(idatend!=null && idatend!=undefined && idatend>0){

        let dados = {
            tipo: 6,
            idvisualizacao: idvisu,
            idtipoatend: idatend
        }
        //console.log(dados);

        $.ajax({
            url: 'ajax/consultas/chefia_busca_gerenciar.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            // console.log(result);

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);

            }else{
                result.forEach(linha => {
                    let matricula = midMatricula(linha.MATRICULA,3);
                    let nome = linha.NOME;
                    let datasolic = retornaDadosDataHora(linha.DATASOLICITACAO,12);
                    let descpedido = linha.DESCPEDIDO;
                    let cela = 'Não encontrado';
                    if(linha.RAIO!=null){
                        cela = linha.RAIO + '/' + linha.CELA;
                    }
                    let dataatend = 'Não agendado';
                    if(linha.DATAATEND!=null){
                        dataatend = retornaDadosDataHora(linha.DATAATEND,12);
                    }
                    let descatend = '';
                    if(linha.DESCATEND!=null){
                        descatend = linha.DESCATEND;
                    }
                    let tipoatd = linha.TIPOATEND;
                    let situacao = linha.SITUACAO;
                    let idsituacao = linha.IDSITUACAO;
                    let idtabela = linha.TABELA;
                    let idatend = linha.IDSOLICITACAO;
                    let cor = linha.COR;

                    let novoID = gerarID('.tratendimentos');
                    tabelaatend.append('<tr id="tratend'+novoID+'" class="tratendimentos '+cor+'" data-tabela="'+idtabela+'" data-idatend="'+idatend+'"><td class="tdbotoes"></td><td class="centralizado">'+matricula+'</td><td>'+nome+'</td><td class="centralizado">'+datasolic+'</td><td>'+descpedido+'</td><td class="centralizado">'+cela+'</td><td class="centralizado">'+dataatend+'</td><td>'+descatend+'</td><td>'+tipoatd+'</td><td class="min-width-250 max-width-350 tdsituacao">'+situacao+'</td></tr>');

                    adicionaBotoesAtendimentos($('#tratend'+novoID),idsituacao);
                });
            }
        });
    }
}

function adicionaBotoesAtendimentos(tr,idsituacao){
    let tdbotoes = tr.find('.tdbotoes');
    let idtabela = tr.data('tabela');
    let idatend = tr.data('idatend');

    if(idtabela==5){
        if(idsituacao==10){
            //Se estiver Aguardando resposta então ainda pode alterar a solicitação
            tdbotoes.append('<button class="btnAcaoRegistro btnaltsol" title="Alterar Dados da Solicitação"><img src="imagens/alterar.png" class="imgBtnAcao"></button>');
            tdbotoes.find('.btnaltsol').click(()=>{
                idmovpopnovoatend = idatend;
                abrirPopNovoAtend();
            })
            
            //Se estiver Aguardando resposta então ainda pode cancelar a solicitação
            tdbotoes.append('<button class="btnAcaoRegistro btncancsol" title="Cancelar Solicitação de Atendimento"><img src="imagens/cancelar.png" class="imgBtnAcao"></button>')
            alterarSituacaoComum(idatend,15,6,tdbotoes.find('.btncancsol'),'Confirma o cancelamento da Solicitação de Atendimento?');
        }
    }
};
