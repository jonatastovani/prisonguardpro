let arrpresosinseridos = [];
const formularios = $('#formularios');

function atualizaListaPresos(){
    atualizaListagemComum('busca_presos',{tipo:2, tiporetorno:2, tipobusca:3, valor:2},$('#listapresos'),$('#selectmatricula'));
    $('#searchmatricula').focus();
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

                result2.forEach(inc => {
                    let result3 = consultaBanco('popup_busca_kit_preso',{tipo:1, idpreso: inc.IDPRESO});
                    // console.log(result3);

                    let kits = '';
                    let arrkits = [];
                    let dataentrada = 'MIGRAÇÃO DE SISTEMA';
                    if(inc.DATAENTRADA!='0000-00-00 00:00:00'){
                        dataentrada = retornaDadosDataHora(inc['DATAENTRADA'],2);
                    }

                    if(result3.length){
                        result3.forEach(kit => {
                            let idckbkit = 'kit'+kit.ID;

                            kits += '<div class="item-flex largura-total"><p><input type="checkbox" id="'+idckbkit+'"><label for="'+idckbkit+'" class="espaco-esq">'+kit.NOMEEXIBIR+'</label></p></div>';

                            arrkits.push({
                                idckbkit: idckbkit,
                                id: kit.ID,
                                tipoentrega: kit.TIPOENTREGA,
                                dataentrega: kit.DATAENTREGA,
                                selecionado: 0
                            })
                        });
                    }else{
                        kits = '<div class="item-flex largura-total"><p>Não foi entregue nenhum kit nesta passagem</p></div>';
                    }

                    let idckbpreso = 'inc'+inc.IDPRESO;

                    inclusoes +='<div class="item-flex largura-total"><p><input type="checkbox" id="'+idckbpreso+'"><label for="'+idckbpreso+'" class="espaco-esq"><b>'+inc.TIPOMOV+'</b> ('+inc.MOTIVOMOV+') - <b>'+dataentrada+'</b><br>'+inc.ORIGEM+'</label></p><div id="kits'+idckbpreso+'" class="container-flex max-height-150 kitspreso">'+kits+'</div></div>';

                    arrinc.push({
                        idckbpreso: idckbpreso,
                        idorigem: inc.IDORIGEM,
                        idpreso: inc.IDPRESO,
                        origem: inc.ORIGEM,
                        dataentrada: inc.DATAENTRADA,
                        tipomov: inc.TIPOMOV,
                        motivomov: inc.MOTIVOMOV,
                        dataprisao: inc.DATAPRISAO,
                        arrkits: arrkits
                    })
                });

                let idckbtudo = 'selall'+matricula;

                formularios.append('<div class="item-flex form-impr-kit-entregue relative" id="'+matricula+'"><p>Nome: <b>'+result[0].NOME+'</b></p><p>Matrícula: <b>'+midMatricula(matricula,3)+'</b></p><p><input type="checkbox" id="'+idckbtudo+'"><label for="'+idckbtudo+'" class="espaco-esq">Selecionar Tudo</label></p><div class="container-flex height-200">'+inclusoes+'</div><button class="fechar-absolute">&times;</button></div>');

                let dadospreso = {
                    matricula: result[0].MATRICULA,
                    nome: result[0].NOME,
                    idckbtudo: idckbtudo,
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

    let ckbtudo = $('#'+dados.idckbtudo);

    dados.arrinclusoes.forEach(inc => {
        let idpreso = inc.idpreso;
        let idckbpreso = inc.idckbpreso;
        let ckbpreso = $('#'+idckbpreso);

        inc.arrkits.forEach(kit => {
            let idckbkit = kit.idckbkit;
            let ckbkit = $('#'+idckbkit);

            ckbkit.change(()=>{
                let index = arrpresosinseridos.findIndex((preso)=>preso.matricula==matricula);            
                if(index>-1){
                    let indexinc = arrpresosinseridos[index].arrinclusoes.findIndex((inc)=>inc.idpreso==idpreso);
                    if(indexinc>-1){
                        let indexkit = arrpresosinseridos[index].arrinclusoes[indexinc].arrkits.findIndex((kit)=>kit.idckbkit==idckbkit);
                        if(indexkit>-1){
                            arrpresosinseridos[index].arrinclusoes[indexinc].arrkits[indexkit].selecionado = ckbkit.prop('checked')==true?1:0;

                            verificaSelecionados({matricula: matricula});
                        }
                    }
                }
            })
    
        });

        ckbpreso.change(()=>{
            let index = arrpresosinseridos.findIndex((preso)=>preso.matricula==matricula);            
            if(index>-1){
                let indexinc = arrpresosinseridos[index].arrinclusoes.findIndex((inc)=>inc.idpreso==idpreso);
                if(indexinc>-1){
                    arrpresosinseridos[index].arrinclusoes[indexinc].arrkits.forEach(kit => {
                        $('#'+kit.idckbkit).prop('checked',ckbpreso.prop('checked'));
                        kit.selecionado = ckbpreso.prop('checked')==true?1:0;
                    });
                    verificaSelecionados({matricula: matricula});
                }
            }
        })

    });

    ckbtudo.change(()=>{
        let index = arrpresosinseridos.findIndex((preso)=>preso.matricula==matricula);

        if(index>-1){
            arrpresosinseridos[index].arrinclusoes.forEach(preso => {
                $('#'+preso.idckbpreso).prop('checked',ckbtudo.prop('checked')).trigger('change');
            });
        }
    })

}

function verificaSelecionados(dados){
    let matricula = dados.matricula;
    
    let index = arrpresosinseridos.findIndex((preso)=>preso.matricula==matricula);            

    if(index>-1){
        
        let arrkitssel = [];
        let arrkitsnao = [];
        let arrincsel = [];
        let arrincnao = [];
        let ckbtudo = $('#'+arrpresosinseridos[index].idckbtudo);

        arrpresosinseridos[index].arrinclusoes.forEach(inc => {
            let ckbpreso = $('#'+inc.idckbpreso);

            inc.arrkits.forEach(kit => {
                if(kit.selecionado==1){
                    arrkitssel.push(kit);
                }else{
                    arrkitsnao.push(kit);
                }
            });

            if(arrkitssel.length&&!arrkitsnao.length){
                ckbpreso.prop('checked',true);
                arrincsel.push(arrkitssel);
            }else{
                ckbpreso.prop('checked',false);
                arrincnao.push(arrkitsnao);
            }

        });

        if(arrincsel.length&&!arrkitsnao.length){
            ckbtudo.prop('checked',true);
        }else{
            ckbtudo.prop('checked',false);
        }
        
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
    let idskit = [];
    
    arrpresosinseridos.forEach(preso => {
        preso.arrinclusoes.forEach(inc => {
            inc.arrkits.forEach(kit => {
                if(kit.selecionado==1){
                    idskit.push(kit.id);
                }
            });
        });
    });

    if(idskit.length>0){
        let informacoes = [
            {get:'documento',valor:['kit_entregue']},
            {get:'idkitentregue',valor:idskit},
            {get:'opcaocabecalho',valor:[2]}
        ];
        // console.log(informacoes)

        imprimirDocumentos(informacoes);
        
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Nenhuma inclusão foi selecionada </li>');
    }

})

atualizaListaPresos()
