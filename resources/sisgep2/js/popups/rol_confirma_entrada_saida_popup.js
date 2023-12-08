var idtipomov = 0; //Variável da entrada e saida de visitantes
let confirmacaopopconfentsai = 0;
let idvisitapopconfentsai = 0;
const divvisitantespopconfentsai = $('#divvispopconfentsai');
var blnentradaumclick = false;
let arrpopconfentsai = [];

$("#openpopconfentsai").on("click",function(){
    abrirPopPopConfirmacaoEntSai();
});

function abrirPopPopConfirmacaoEntSai(){
    if(idtipomov>0 && idvisitapopconfentsai>0){
        limparCamposPopPopConfirmacaoEntSai();
        buscaDadosPopConfirmacaoEntSai(idvisitapopconfentsai)
        $("#pop-confentsai").addClass("active");
        $("#pop-confentsai").find(".popup").addClass("active");
        $('#usuariopopconfentsai').focus();
    }else{
        fecharPopPopConfirmacaoEntSai();
    }
}
//Fechar pop-up Artigo
$("#pop-confentsai").find(".close-btn").on("click",function(){
    fecharPopPopConfirmacaoEntSai();
})
function fecharPopPopConfirmacaoEntSai(){
    $("#pop-confentsai").removeClass("active");
    $("#pop-confentsai").find(".popup").removeClass("active");
    $('#table-pop-confentsai').find('tbody').html('')
    limparCamposPopPopConfirmacaoEntSai();
    idvisitapopconfentsai = 0;
}

$('#cancelarpopconfentsai').click(()=>{
    fecharPopPopConfirmacaoEntSai();
})

function limparCamposPopPopConfirmacaoEntSai(){
    $('.htmltemppopconfentrsai').html('');
    $('#conftodospopconfentsai').attr('hidden',true);
    confirmacaopopconfentsai = 0;
}

function buscaDadosPopConfirmacaoEntSai(idvisita){
    let retorno = false;
    let result = consultaBanco('rol_busca_gerenciar',{tipo: 3, idvisita:idvisita, buscadependentes:1});
    // console.log(result)

    if(result.length){
        if(result[0].IDRESPONSAVEL!=null){
            buscaDadosPopConfirmacaoEntSai(result[0].IDRESPONSAVEL);
        }else{
            arrpopconfentsai = [];
            let result2 = consultaBanco('busca_presos',{tipo: 1, idpreso: result[0].IDPRESO});
           
            if(result2.length){
                $('#nomepresoconfentrsai').html(result2[0].NOME);
                if(result2[0].MATRICULA!=null){
                    $('#matriculaconfentrsai').html(midMatricula(result2[0].MATRICULA,3));
                }else{
                    $('#matriculaconfentrsai').html('Não Atribuída');
                }
                $('#raiocelaconfentrsai').html(result2[0].RAIOCELA);

                // defeito aqui quando tem mais que um visitante
                result.forEach(linha => {
                    console.log(linha);

                    let result4 = consultaBanco('rol_busca_gerenciar',{tipo: 7, idvisita:linha.ID, dataconsulta:retornaDadosDataHora(new Date(),1)});
                    console.log(result4);
                    
                    let idmov = 0;
                    let datasaida = '';
                    if(result4.length){
                        idmov = result4[0].ID;
                        if(result4[0].DATASAIDA!=null){
                            datasaida = result4[0].DATASAIDA;
                        }
                    }

                    if(idtipomov==1 && idmov==0 || idtipomov==2 && idmov>0 && datasaida==''){
                        let result3 = consultaBanco('rol_busca_gerenciar',{tipo: 4, idvisitante:linha.IDVISITANTE});
                        console.log(result3)
                        let idsituacao = linha.IDSITUACAO;
                        let idsituacaovisi = result3[0].IDSITUACAO;

                        let botaoindividual = '';
                        let cor = '';
    
                        if(idtipomov==1){
                            cor = 'cor-bloqueado';
                            if([23].includes(idsituacao) && [25].includes(idsituacaovisi)){
                                cor = 'cor-aprovado';
                                botaoindividual = '<button class="confirmar btnverde" title="Confirmar entrada para este visitante somente">Realizar Entrada</button>';
                            }else if([27].includes(idsituacaovisi)){
                                cor = 'cor-bloqueado';
                            }else if([28,29,30].includes(idsituacao)){
                                cor = 'cor-excluido';
                            }
                        }else{
                            botaoindividual = '<button class="confirmar btnverde" title="Confirmar saída para este visitante somente">Realizar Saída</button>';
                        }
    
                        let nomesocial = '';
                        if(result3[0].NOMESOCIAL!=null){
                            nomesocial = '<p>Nome Social: <b><span class="nomesocial">'+result3[0].NOMESOCIAL+'</span></b></p>';
                        }
    
                        let observacoes = '';
                        if(result3[0].OBSERVACOES!=null){
                            observacoes = '<span class="destaque-atencao">Observações: <b><span>'+result3[0].OBSERVACOES+'</span></b></span>';
                        }
    
                        let novoID = 'visipopconfentsai' + gerarID('.visipopconfentsai');
                        divvisitantespopconfentsai.append('<div id="'+novoID+'" class="visipopconfentsai grupo-block '+cor+'"><div class="flex" style="align-items: center;"><div class="divfotovisita margin-espaco-dir max-height-150" style="max-width:106px;"><img src="imagens/sem-foto.png" alt="foto-visitante"></div><div class="largura-restante"><p>Nome: <b><span>'+result3[0].NOME+'</span></b></p>'+nomesocial+'<p>RG: <b><span>'+result3[0].RG+'</span></b></p><p>CPF: <b><span>'+result3[0].CPF+'</span></b></p>'+observacoes+'</div></div><div class="align-rig">'+botaoindividual+'</div></div>');
    
                        baixarFotoFrontalServidorRemoto(2,$('#'+novoID).find('img'),result3[0].CPF);
    
                        let botao = $('#'+novoID).find('.confirmar');
                        if(botao.length){

                            arrpopconfentsai.push({
                                iddivvisi: novoID,
                                clicado: false
                            });
    
                            if(idmov>0 && idtipomov==1){
                                botao.attr('disabled',true);
                            }else{
                                acaoEntradaSaidaConfirmacaoEntSai(botao,linha.ID,idmov)
                            }
                        }
                    }
                });

                if(divvisitantespopconfentsai.find('.confirmar').length>1){
                    adicionaEventoBotaoConfirmarTodosConfirmacaoEntSai();
                }
                retorno = true;
            }
        }
    }
    return retorno;
}

function adicionaEventoBotaoConfirmarTodosConfirmacaoEntSai(){
    let title = 'Confirmar entrada para todos os visitantes listados acima';
    let html = 'Realizar entrada para todos';

    if(idtipomov==2){
        title = 'Confirmar saída para todos os visitantes listados acima';
        html = 'Realizar saída para todos';
    }

    $('#conftodospopconfentsai').html(html).attr('title',title).attr('hidden',false).click(()=>{
        if(idtipomov==1){
            for(let i=0;i<arrpopconfentsai.length;i++){
                if(arrpopconfentsai[i].clicado==0){
                    $('#'+arrpopconfentsai[i].iddivvisi).find('.confirmar').click();
                }
            }
        }else{
            for(let i=arrpopconfentsai.length-1;i>-1;i--){
                if(arrpopconfentsai[i].clicado==0){
                    $('#'+arrpopconfentsai[i].iddivvisi).find('.confirmar').click();
                }
            }
        }
    });
}

function acaoEntradaSaidaConfirmacaoEntSai(elemento,idvisita,idmov,title=''){
    
    if(title=''){
        title = 'Clique para inserir a entrada deste visitante';
        if(idtipomov==2){
            title = 'Clique para efetuar a saída deste visitante';
        }
    }

    elemento.attr('title',title).click(()=>{

        let dados = {
            tipo:5,
            idtipomov,idtipomov,
            idvisita:idvisita,
            idmovimentacao:idmov
        };
        // console.log(dados)

        $.ajax({
            url: 'ajax/inserir_alterar/rol_gerenciar.php',
            method: 'POST',
            data: dados,
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
            }else{
                inserirMensagemTela(result.OK);
                elemento.attr('disabled',true);
                let index = arrpopconfentsai.findIndex((id)=>id.iddivvisi==elemento.parent().parent().attr('id'));
                arrpopconfentsai[index].clicado=1;
            }
        });

        atualizaListaEntVis();

        //Verifica se já pode fechar o popup. Se estiver todos clicados, fecha..
        let blnfechar = true;
        for(let i=0;i<arrpopconfentsai.length;i++){
            if(arrpopconfentsai[i].clicado==0){
                blnfechar = false;
            }
        }
        blnfechar?fecharPopPopConfirmacaoEntSai():false;
    })
}

