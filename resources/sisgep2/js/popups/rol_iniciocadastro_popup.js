// Confirmação do retorno para salvamento das informações
let confirmacaopopcadvisi = 0;
let idvisitanteatualpopcadvisi = 0;
let idvisitapopcadvisi = 0;
const tabelapopcadvisi = $('#table-pop-popcadvisi').find('tbody');

$("#openpopcadvisi").on("click",function(){
    abrirPopCadVisita();
});

function abrirPopCadVisita(){
    if(idvisitapopcadvisi>0){
        buscaDadosVisitaPopCadVisita();
        $("#pop-popcadvisi").addClass("active");
        $("#pop-popcadvisi").find(".popup").addClass("active");
        $('#cpfpopcadvisi').focus();
    }
}
//Fechar pop-up Artigo
$("#pop-popcadvisi").find(".close-btn").on("click",function(){
    fecharPopCadVisita();
})
function fecharPopCadVisita(){
    $("#pop-popcadvisi").removeClass("active");
    $("#pop-popcadvisi").find(".popup").removeClass("active");
    limparCamposPopCadVisita();
    idmovpopcadvisi = 0;
    idvisita = 0;
}

$('#cancelarpopcadvisi').click(()=>{
    fecharPopCadVisita();
})

$('#conferirpopcadvisi').click(()=>{
    verificaCPFVisitante($('#cpfpopcadvisi').val());
})

$('#cpfpopcadvisi').keyup(function(e){
    $('#continuarpopcadvisi').attr('hidden','hidden').off('click');
    if(e.key === 'Enter') {
        verificaCPFVisitante($('#cpfpopcadvisi').val());
    }
});

function verificaCPFVisitante(cpfvisitante){
    tabelapopcadvisi.parent().attr('hidden','hidden');
    tabelapopcadvisi.html('');
    let retorno = validaCPF(cpfvisitante);
    if(retorno!==true){
        inserirMensagemTela(retorno);
    }else{
        buscaCPFExistentePopCadVisita(cpfvisitante);
    }
}

function limparCamposPopCadVisita(){
    $('#continuarpopcadvisi').attr('hidden','hidden').off('click');
    $('.strtemppopcadvisi').val('');
    $('.htmltemppopcadvisi').html('');
    confirmacaopopcadvisi = 0;
    idvisitapopcadvisi = 0;
    idvisitanteatualpopcadvisi = 0;
}

function buscaCPFExistentePopCadVisita(cpf){
    let result = consultaBanco('rol_busca_gerenciar',{tipo: 5, cpf:cpf});
    // console.log(result);

    if(result.MENSAGEM){
        inserirMensagemTela(result.MENSAGEM);
    }else{
        if(result.length>0){
            result.forEach(visita => {
                tabelapopcadvisi.parent().removeAttr('hidden');
                let novoID = 'trpopcadvisi'+gerarID('.trpopcadvisi')
                tabelapopcadvisi.append('<tr id="'+novoID+'" class="trpopcadvisi cor-fundo-comum-tr nowrap"><td class="tdbotoes" style="width: 40px;"></td><td>'+visita.NOME+'</td><td class="centralizado" style="width: 120px;">'+visita.CPF+'</td><td class="centralizado" style="width: 120px;">'+visita.RG+'</td></tr>');

                insereBotaoConfirmarIDVisitanteNovo(novoID,visita.ID);
            });
        }else{
            $('#continuarpopcadvisi').removeAttr('hidden').on('click',()=>{
                salvarCPFPopCadVisita(cpf);
            }).focus();
        }
    }
}

function insereBotaoConfirmarIDVisitanteNovo(idtr,idnovo){
    let tr = $('#'+idtr);
    let tdbotoes = tr.find('.tdbotoes');

    tdbotoes.append('<button class="btnAcaoRegistro btnconfvisi" title="Selecionar este visitante existente para prosseguir com o cadastro"><img src="imagens/confirmar.png" class="imgBtnAcao"></button>');

    tdbotoes.find('.btnconfvisi').click(()=>{
        let dados = {
            tipo:2,
            idvisitantealterar:idnovo,
            idvisitanteatual:idvisitanteatualpopcadvisi,
            idvisita:idvisitapopcadvisi
        }

        $.ajax({
            url: 'ajax/inserir_alterar/rol_gerenciar.php',
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
                inserirMensagemTela(result.OK);
                acaoBotaoAbrirVisitante(tdbotoes,idvisitapopcadvisi);
                fecharPopCadVisita();
            }
        });
    })
}

function buscaDadosVisitaPopCadVisita(){
    let result = consultaBanco('rol_busca_gerenciar',{tipo: 3, idvisita: idvisitapopcadvisi});
    if(result.MENSAGEM){
        inserirMensagemTela(result.MENSAGEM);
    }else{
        if(result.length){
            idvisitanteatualpopcadvisi = result[0].IDVISITANTE;
            buscaDadosVisitantePopCadVisita(result[0].IDVISITANTE);
            buscaDadosPresoPopCadVisita(result[0].IDPRESO);
            
            let result2 = consultaBanco('buscas_comuns',{tipo: 42, idgrau: result[0].IDPARENTESCO});
            // console.log(result2);
            if(result2.MENSAGEM){
                inserirMensagemTela(result2.MENSAGEM);
            }else{
                if(result2.length){
                    $('#parentescopopcadvisi').html(result2[0].NOME);
                }
            }
        }
    }
}

function buscaDadosPresoPopCadVisita(idpreso){
    let result = consultaBanco('busca_presos',{tipo: 1, idpreso: idpreso});
    if(result.MENSAGEM){
        inserirMensagemTela(result.MENSAGEM);
    }else{
        if(result.length){
            $('#nomepresopopcadvisi').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matriculapopcadvisi').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matriculapopcadvisi').html('Não Atribuída');
            }
            $('#raiocelapopcadvisi').html(result[0].RAIOCELA);
        }
    }
}

function buscaDadosVisitantePopCadVisita(idvisitante){
    let result = consultaBanco('rol_busca_gerenciar',{tipo: 4, idvisitante: idvisitante});
    if(result.MENSAGEM){
        inserirMensagemTela(result.MENSAGEM);
    }else{
        if(result.length){
            $('#datacadastropopcadvisi').html(retornaDadosDataHora(result[0].DATACADASTRO,12));
            $('#nomevisitantepopcadvisi').html(result[0].NOME);
        }
    }
}

function salvarCPFPopCadVisita(cpfvisitante){
    // let cpfvisitante = $('#cpfpopcadvisi').val();
    // let retorno = validaCPF(cpfvisitante);
    
    // if(retorno!==true){
    //     inserirMensagemTela(retorno);
    // }else{
        
        let dados = {
            tipo: 3,
            idvisitante: idvisitanteatualpopcadvisi,
            cpf: cpfvisitante
        }
    
        //console.log(dados);
    
        $.ajax({
            url: 'ajax/inserir_alterar/rol_gerenciar.php',
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
                acaoBotaoAbrirVisitante($('#cpfpopcadvisi').parent(),idvisitapopcadvisi);
                fecharPopCadVisita();
            }
        });
    // }
}

abrirPopCadVisita();