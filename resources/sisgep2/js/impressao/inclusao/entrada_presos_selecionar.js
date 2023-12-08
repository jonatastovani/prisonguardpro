let arrentradasinseridas = [];
const formularios = $('#formularios');

$('#inserir').click(function(){
    var id = $('#selectentrada').val();

    let result = consultaBanco('buscas_comuns',{tipo:20, identrada: id});
    // console.log(result);

    if(result.length){

        let index = arrentradasinseridas.findIndex((entrada)=>entrada.id==id);

        if(index<0){

            let idckbent = 'ent'+result[0].ID;
            let iddivent = 'div'+idckbent;

            formularios.append('<div class="item-flex form-impr-entrada-presos relative" id="'+iddivent+'"><input type="checkbox" id="'+idckbent+'" checked><label for="'+idckbent+'" class="espaco-esq"><p class="inline">Entrada nº: <b>'+result[0].ID+'</b>; Presos inclusos: <b>'+result[0].QTDPRESOS+'</b>;</p><p>Origem Entrada nº: <b>'+result[0].IDORIGEM+'</b>, Nome Origem: <b>'+result[0].ORIGEM+'</b>;</p><p>Data/Hora Entrada: <b>'+retornaDadosDataHora(result[0].DATAENTRADA,12)+'</b></label></p><button class="fechar-absolute">&times;</button></div>');
            
            let dadosentrada = {
                iddivent: iddivent,
                idckbent: idckbent,
                id: result[0].ID,
                dataentrada: result[0].DATAENTRADA,
                idorigem: result[0].IDORIGEM,
                origem: result[0].ORIGEM,
                qtdpresos: result[0].QTDPRESOS,
                selecionado: 1
            }
            arrentradasinseridas.push(dadosentrada);

            adicionaEventosEntrada(dadosentrada);

        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Esta entrada já foi inclusa! </li>')
        }

    }else{
        inserirMensagemTela("<li class = 'mensagem-aviso'> Nenhuma entrada foi selecionada! </li>");
    }

    $('#searchentrada').val('').trigger('change').focus();
})

function adicionaEventosEntrada(dados){
    let id = dados.id;
    let iddivent = dados.iddivent;
    let diventrada = $('#'+iddivent);
    let idckbent = dados.idckbent;
    let ckbent = $('#'+idckbent);
    
    // Adiciona evento de excluir o preso inserido
    adicionaEventoExcluir(diventrada);

    diventrada.find('.fechar-absolute').click(()=>{
        arrentradasinseridas = arrentradasinseridas.filter((entrada)=>entrada.id!=id);
        inserirMensagemTela("<li class = 'mensagem-exito'> Entrada removida da listagem! </li>");
    })

    // Adiciona o evento de entrad selecionada
    ckbent.click(()=>{
        let index = arrentradasinseridas.findIndex((entrada)=>entrada.id==id);            
        if(index>-1){
            arrentradasinseridas[index].selecionado = ckbent.prop('checked')==true?1:0;
        }
    })

}

$('#searchentrada').change(function(){
    var id = $('#searchentrada').val();
    
    if(id!=$('#selectentrada').val()){
        buscaSearchComum('buscas_comuns',{tipo:20, identrada: id},$('#searchentrada'),$('#selectentrada'),$('#inserir'));
    }
})

adicionaEventoSelectChange(0,$('#selectentrada'),$('#searchentrada'));

$('#imprimir').click(function(){
    //Seleciona todos os idpresos
    let idsentrada = [];
    
    arrentradasinseridas.forEach(entrada => {
        if(entrada.selecionado==1){
            idsentrada.push(entrada.id);
        }
    });

    if(idsentrada.length>0){
        let informacoes = [
            {get:'documento',valor:['entrada_presos']},
            {get:'identrada',valor:idsentrada},
            {get:'opcaocabecalho',valor:[2]}
        ];
        // console.log(informacoes)

        imprimirDocumentos(informacoes);
        
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhuma entrada foi selecionada </li>')
    }

})

atualizaListagemComum('buscas_comuns',{tipo: 19},$('#listaentradas'),$('#selectentrada'))
