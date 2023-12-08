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

            if(result2.length){

                let arrinc = [];
                let inclusoes = '';
                let contador = 0;

                result2.forEach(inc => {
                    let marcado = '';
                    let selecionado = 0;
                    if(contador==0){
                        marcado = "checked";
                        selecionado = 1;
                        contador++;
                    }

                    let dataentrada = 'MIGRAÇÃO DE SISTEMA';
                    if(inc.DATAENTRADA!='0000-00-00 00:00:00'){
                        dataentrada = retornaDadosDataHora(inc['DATAENTRADA'],2);
                    }
                    
                    let idckb = 'inc'+inc['IDPRESO'];

                    inclusoes +='<div class="item-flex largura-total"><p><input type="checkbox" id="'+idckb+'" '+marcado+'><label for="'+idckb+'" class="espaco-esq"><b>'+inc['TIPOMOV']+'</b> ('+inc['MOTIVOMOV']+') - <b>'+dataentrada+'</b></p><p>'+inc['ORIGEM']+'</p></label></div>';

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
                    })

                });

                formularios.append('<div class="item-flex form-impr-termo-abertura relative" id="'+matricula+'"><p>Nome: <b>'+result[0].NOME+'</b></p><p>Matrícula: <b>'+midMatricula(matricula,3)+'</b></p><div class="container-flex height-200">'+inclusoes+'</div><button class="fechar-absolute">&times;</button></div>');

                let dadospreso = {
                    matricula: result[0].MATRICULA,
                    nome: result[0].NOME,
                    arrinclusoes: arrinc
                }
                arrpresosinseridos.push(dadospreso);

                adicionaEventosPreso(dadospreso);

            }

        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Este preso já foi incluso </li>')
        }

    }

    $('#searchmatricula').val('').trigger('change').focus();
})

function adicionaEventosPreso(dados){
    let matricula = dados.matricula;
    let divpreso = $('#'+matricula);
    
    // Adiciona evento de excluir o preso inserido
    adicionaEventoExcluir(divpreso);

    divpreso.find('.fechar-absolute').click(()=>{
        arrpresosinseridos = arrpresosinseridos.filter((preso)=>preso.matricula!=matricula);
        inserirMensagemTela("<li class = 'mensagem-exito'> Preso removido da listagem! </li>");
    })

    // Adiciona o evento de inclusões selecionadas
    let arrckb = divpreso.find('input:checkbox');

    for(let i=0;i<arrckb.length;i++){
        let idckb = arrckb[i].id;
        let ckb = $('#'+idckb);

        ckb.click(()=>{
            let index = arrpresosinseridos.findIndex((preso)=>preso.matricula==matricula);            
            if(index>-1){
                let indexinc = arrpresosinseridos[index].arrinclusoes.findIndex((inc)=>inc.idckb==idckb);
                if(index>-1){
                    arrpresosinseridos[index].arrinclusoes[indexinc].selecionado = ckb.prop('checked')==true?1:0;
                }
            }
        })

    }

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
        preso.arrinclusoes.forEach(inc => {
            if(inc.selecionado==1){
                idspreso.push(inc.idpreso);
            }
        });
    });

    if(idspreso.length>0){
        let informacoes = [
            {get:'documento',valor:['termo_declaracao']},
            {get:'idpreso',valor:idspreso},
            {get:'opcaocabecalho',valor:[2]}
        ];
        // console.log(informacoes)

        imprimirDocumentos(informacoes);
        
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhuma inclusão foi selecionada </li>');
    }

})

atualizaListaPresos()
