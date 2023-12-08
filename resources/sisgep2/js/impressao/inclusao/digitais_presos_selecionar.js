let arrpresosinseridos = [];
const formularios = $('#formularios');

function atualizaListaPresos(){
    atualizaListagemComum('busca_presos',{tipo:2, tiporetorno:2, tipobusca:3, valor:2},$('#listapresos'),$('#selectmatricula'));
}

$('#inserir').click(function(){
    let matricula = retornaSomenteNumeros($('#selectmatricula').val());
    
    let result = consultaBanco('busca_presos',{tipo:3,valor:2,matric:matricula});
    // console.log(result);

    if(result.length){

        let index = arrpresosinseridos.findIndex((preso)=>preso.matricula==matricula);

        if(index<0){
            let result2 = consultaBanco('busca_presos',{tipo:9,matric:matricula});
            // console.log(result2);

            result2.forEach(inc => {

                let idckb = 'inc'+result2[0].IDPRESO;
                let dataentrada = 'DATA NÃO INFORMADA';
                if(result2[0].DATAENTRADA!='0000-00-00 00:00:00'){
                    dataentrada = retornaDadosDataHora(result2[0].DATAENTRADA,2);
                }
                
                formularios.append('<div class="item-flex form-impr-digitais-preso relative" id="'+matricula+'"><p>Nome: <b>'+result[0].NOME+'</b></p><p>Matrícula: <b>'+midMatricula(matricula,3)+'</b></p><p>Última inclusão:</p><p><input type="checkbox" id="'+idckb+'" checked><label for="inc'+result2[0].IDPRESO+'" class="espaco-esq"><b>'+result2[0].TIPOMOV+'</b> ('+result2[0].MOTIVOMOV+') - <b>'+dataentrada+'</b><br>'+result2[0].ORIGEM+'</label></p><button class="fechar-absolute">&times;</button></div>')
                
                let dadospreso = {
                    idckb: idckb,
                    matricula: matricula,
                    nome: result[0].NOME,
                    idorigem: result2[0].IDORIGEM,
                    origem: result2[0].ORIGEM,
                    idpreso: result2[0].IDPRESO,
                    dataentrada: result2[0].DATAENTRADA,
                    dataprisao: result2[0].DATAPRISAO,
                    motivomov: result2[0].MOTIVOMOV,
                    tipomov: result2[0].TIPOMOV,
                    selecionado: 1
                }
                arrpresosinseridos.push(dadospreso);

                adicionaEventosPreso(dadospreso);
                
            });

        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Este preso já foi incluso </li>')
        }

    }else{
        inserirMensagemTela("<li class = 'mensagem-aviso'> Nenhuma matricula foi selecionada </li>");
    }

    $('#searchmatricula').val('').trigger('change').focus();
})

function adicionaEventosPreso(dados){
    let matricula = dados.matricula;
    let divpreso = $('#'+matricula);
    let idckb = dados.idckb;
    let ckb = $('#'+idckb);

    // Adiciona evento de excluir o preso inserido
    adicionaEventoExcluir(divpreso);

    divpreso.find('.fechar-absolute').click(()=>{
        arrpresosinseridos = arrpresosinseridos.filter((preso)=>preso.matricula!=matricula);
        inserirMensagemTela("<li class = 'mensagem-exito'> Preso removido da listagem! </li>");
    })

    // Adiciona o evento de inclusões selecionadas
    ckb.click(()=>{
        let index = arrpresosinseridos.findIndex((preso)=>preso.matricula==matricula);            
        if(index>-1){
            arrpresosinseridos[index].selecionado = ckb.prop('checked')==true?1:0;
        }
        // console.log(arrpresosinseridos);
    })

}

$('#searchmatricula').change(function(){
    var id = $('#searchmatricula').val();
    
    if(id!=$('#selectmatricula').val()){
        buscaSearchComum('busca_presos',{tipo:3,valor:2,matric:id},$('#searchmatricula'),$('#selectmatricula'),$('#inserir'));
    }
})

adicionaEventoSelectChange(0,$('#selectmatricula'),$('#searchmatricula'));

$('#imprimir').click(function(){
    //Seleciona todos os idpresos
    let idspreso = [];
    
    arrpresosinseridos.forEach(preso => {
        if(preso.selecionado==1){
            idspreso.push(preso.idpreso);
        }
    });

    if(idspreso.length>0){
        let informacoes = [
            {get:'documento',valor:['digitais_presos']},
            {get:'idpreso',valor:idspreso},
            {get:'tipo',valor:['idpreso']},
            {get:'opcaocabecalho',valor:[1]}
        ];
        console.log(informacoes)

        imprimirDocumentos(informacoes);
        
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhum preso foi selecionado </li>')
    }

})

atualizaListaPresos()
