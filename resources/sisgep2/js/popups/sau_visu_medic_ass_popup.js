let idpresopopvismed = 0;
let idperiodopopvismed = 0;
let datapopvismed = 0;
const tabelapopvismed = $('#table-pop-visumedicass').find('tbody');

$("#openpopvismed").on("click",function(){
    abrirPopVisuMedicAss();
});

function abrirPopVisuMedicAss(){
    limparCamposPopVisuMedicAss();
    buscaDadosPresoPopVisuMedicAss();
    if(buscaDadosPopVisuMedicAss()==true){
        $("#pop-visumedicass").addClass("active");
        $("#pop-visumedicass").find(".popup").addClass("active");

    }else{
        fecharPopVisuMedicAss();
    }
}
//Fechar pop-up Artigo
$("#pop-visumedicass").find(".close-btn").on("click",function(){
    fecharPopVisuMedicAss();
})
$('#cancelarpopvismed').click(()=>{
    fecharPopVisuMedicAss();
})
function fecharPopVisuMedicAss(){
    $("#pop-visumedicass").removeClass("active");
    $("#pop-visumedicass").find(".popup").removeClass("active");
    limparCamposPopVisuMedicAss();
    idpresopopvismed = 0;
    idperiodopopvismed = 0;
    datapopvismed = 0;
}

function limparCamposPopVisuMedicAss(){
    $('.temphtmlpopvismed').html('');
}

function buscaDadosPresoPopVisuMedicAss(){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 1, idpreso: idpresopopvismed},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#nomepopvismed').html(result[0].NOME);
            if(result[0].MATRICULA!=null){
                $('#matriculapopvismed').html(midMatricula(result[0].MATRICULA,3));
            }else{
                $('#matriculapopvismed').html('Não Atribuída');
            }
            $('#raiocelapopvismed').html(result[0].RAIOCELA);
        }
    });
}

function buscaDadosPopVisuMedicAss(){

    let retorno = false;

    if(datapopvismed==null){
        datapopvismed = retornaDadosDataHora(new Date(),1);
    }else{
        datapopvismed = retornaDadosDataHora(datapopvismed,1);
    }
    
    tabelapopvismed.html('');

    let dados = {
        tipo: 5,
        datainicio: datapopvismed,
        datafinal: datapopvismed,
        situacao: 0,
        periodo: idperiodopopvismed,
        idpreso: idpresopopvismed
    }

    // console.log(dados);

    $.ajax({
        url: 'ajax/consultas/saude_busca_gerenciar.php',
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
            
            $('#datapopvismed').html(retornaDadosDataHora(datapopvismed,2));

            result.forEach(linha => {
                $('#periodopopvismed').html(linha.PERIODO);
                
                let novoID = 'trgerass'+gerarID('.trgerass');
                let dataentregue = '*******';
                if(linha.DATAENTREGUE!=null){
                    dataentregue = retornaDadosDataHora(linha.DATAENTREGUE,12);
                }
                let datainicio = '*******';
                if(linha.DATAINICIO!=null){
                    datainicio = retornaDadosDataHora(linha.DATAINICIO,2);
                }
                let datatermino = '*******';
                if(linha.DATATERMINO!=null){
                    datatermino = retornaDadosDataHora(linha.DATATERMINO,2);
                }

                let tr = '<tr id="'+novoID+'" class="trgerass '+linha.COR+' nowrap" data-idpreso="'+linha.IDPRESO+'" data-cor="'+linha.COR+'" data-idass="'+linha.IDASS+'"><td class="centralizado tdbotoes nowrap" style="min-width: 70px;"></td><td class="centralizado tdidmedicamento">'+linha.IDMEDICAMENTO+'</td><td class="tdnomemedicamento min-width-200 max-width-450">'+linha.NOMEMEDICAMENTO+'</td><td class="centralizado tdqtdentrega">'+linha.QTDENTREGA+'</td><td class="tdperiodo">'+linha.PERIODO+'</td><td class="centralizado nowrap dataentregue">'+dataentregue+'</td><td class="centralizado nowrap datainicio">'+datainicio+'</td><td class="centralizado nowrap datatermino">'+datatermino+'</td></tr>';

                tabelapopvismed.append(tr);

                arrconsulta.push({
                    idtr:novoID,
                    idass: linha.IDASS,
                    idpreso:linha.IDPRESO,
                    idperiodo:linha.IDPERIODO,
                    periodoentrega:linha.PERIODO,
                    idraio:linha.IDRAIO,
                    raio: linha.RAIO,
                    cela: linha.CELA,
                    cor: linha.COR,
                    qtd: linha.QTDMED
                });

                if(linha.DATAENTREGUE==null){
                    let dados = {
                        tipo:5,
                        arrentregar: [
                            {idass:linha.IDASS}
                        ],
                        idtipo: 1
                    }
                    inserirBotaoEntregarAssistido($('#'+novoID).find('.tdbotoes'),dados,'Entregar este medicamento');
                }

                retorno = true;
            });
        }
    });

    return retorno;
}
