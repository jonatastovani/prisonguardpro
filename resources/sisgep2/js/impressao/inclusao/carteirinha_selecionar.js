
let arrpresosinseridos = [];
const formularios = $('#formularios');

function atualizaListaPresos(){
    atualizaListagemComum('busca_presos',{tipo:2, tipobusca:1, valor:1, tiporetorno:2},$('#listapresos'),$('#selectpreso'));
    $('#searchpreso').focus();
}

$('#inserir').click(function(){
    let idpreso = retornaSomenteNumeros($('#selectpreso').val());
    
    let result = consultaBanco('busca_presos',{tipo:3,valor:1,idpreso:idpreso});
    console.log(result);

    if(result.length){

        let index = arrpresosinseridos.findIndex((preso)=>preso.idpreso==idpreso);

        if(index<0){
            let result2 = consultaBanco('busca_presos',{tipo:9,idpreso:idpreso});
            // console.log(result2);

            if(result2.length){

                let arrinc = [];
                let inclusoes = '';
                let idckb = 'inc'+idpreso;

                let dataentrada = 'MIGRAÇÃO DE SISTEMA';
                if(result2.DATAENTRADA!='0000-00-00 00:00:00'){
                    dataentrada = retornaDadosDataHora(result2['DATAENTRADA'],2);
                }
                let matricula = 'N/C';
                if(result.MATRICULA!=null){
                    matricula = midMatricula(result[0].MATRICULA, 3);
                }

                formularios.append(`<div class="item-flex form-impr-carteirinha relative" id="${result[0].MATRICULA}"><p>Nome: <b>${result[0].NOME}</b></p><p>Matrícula: <b>${matricula}</b></p><p>Última inclusão:</p><div><input type="checkbox" id="${idckb}" checked><label for="${idckb}" class="espaco-esq"><p><b>${result2[0].TIPOMOV}</b> (${result2[0].MOTIVOMOV}) - <b>${dataentrada}</b></p><p>${result2[0].ORIGEM}</p></label></div><button class="fechar-absolute">&times;</button></div>`);
/*
                    arrinc.push({
                        idckb: idckb,
                        idorigem: inc.IDORIGEM,
                        idpreso: inc.IDPRESO,
                        origem: inc.ORIGEM,
                        dataentrada: inc.DATAENTRADA,
                        tipomov: inc.TIPOMOV,
                        motivomov: inc.MOTIVOMOV,
                        dataprisao: inc.DATAPRISAO,
                        selecionado: selecionado
                    })*/

                let dadospreso = {
                    matricula: result[0].MATRICULA,
                    nome: result[0].NOME
                }
                arrpresosinseridos.push(dadospreso);

                adicionaEventosPreso(dadospreso);

            }

        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Este preso já foi incluso </li>')
        }

    }

    $('#searchpreso').val('').trigger('change').focus();
})

$('#searchpreso').change(function(){
    var id = $('#searchpreso').val();
    
    if(id!=$('#selectpreso').val()){
        buscaSearchComum('busca_presos',{tipo: 1, idpreso:id},$('#searchpreso'),$('#selectpreso'),$('#inserir'));
    }
})

adicionaEventoSelectChange(0,$('#selectpreso'),$('#searchpreso'));

$('#imprimir').click(function(){
    //Seleciona todos os presos
    var presos = $(".form-impr-carteirinha");
    var idpreso = [];
    
    if(presos.length>0){
        for(var i = 0;i<presos.length;i++){
            //Seleciona todos os checkbox deste preso
            var preso = $('#'+presos[i].id);
            var checkbox = preso.find('input:checked');
            
            for(var check = 0;check<checkbox.length;check++){
                idpreso.push(codifica(checkbox[check].value));
            }
        }

        if(idpreso.length>0){
            var tipoDocumento = 'carteirinha';
            window.open('impressoes/impressao.php?documento='+codifica(tipoDocumento)+'&idpreso='+idpreso+'&opcaocabecalho='+codifica(5), '_blank')
        }
        else{
            inserirMensagemTela('<li class="mensagem-aviso">Nenhum preso foi selecionado</li>')
        }
    }
    else{
        inserirMensagemTela('<li class="mensagem-aviso">Nenhum preso foi selecionado</li>')
    }

})

atualizaListaPresos();