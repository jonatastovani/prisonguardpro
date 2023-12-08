let arrraioscelas = []; // array de celas para retornar
let arrlistacelas = []; // array de celas existentes na tela
let idarr = '';
let idtipoarr = 0;
const divraioslocais = $('#divraioslocais');

$("#openraioslocais").on("click",function(){
    abrirPopRaiosLocais();
});

function abrirPopRaiosLocais(arr){
    buscaRaiosLocaisExistentes();
    if(arr.length>0){
        marcarCelasSelecionadas(arr);
    }
    $("#pop-raioslocais").addClass("active");
    $("#pop-raioslocais").find(".popup").addClass("active");
}

//Fechar pop-up Artigo
$("#pop-raioslocais").find(".close-btn").on("click",function(){
    fecharPopRaiosLocais();
})
function fecharPopRaiosLocais(){
    $("#pop-raioslocais").removeClass("active");
    $("#pop-raioslocais").find(".popup").removeClass("active");
    divraioslocais.html('')
    idarr = 0;
    arrraioscelas = [];
}

function buscaRaiosLocaisExistentes(){

    divraioslocais.html('');
    arrlistacelas = [];
    let result = consultaBanco('buscas_comuns',{tipo:24});
    // console.log(result);
    if(result!=[]){
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            
            result.forEach(dados => {
                let contador = 0;
                let idraio = dados.VALOR;
                let nomecompleto = dados.NOMECOMPLETO;
                let nome = dados.NOME;
                
                divraioslocais.append('<div id="divraio'+idraio+'" class="grupo-block divraio"><h4 class="titulo-grupo">'+nomecompleto+'</h4></div>');

                let divcelas = $('#divraio'+idraio);
                for(let i=0;i<dados.QTD;i++){
                    let cela = i+1;
                    let espaco = '';
                    if(contador>0){
                        if(cela==9){
                            divcelas.append('<br>');
                        }else{
                            espaco = 'margin-espaco-esq';
                        }
                    }
                    let novoID = gerarID('.celasraio');
                    divcelas.append('<div class="inline"><input type="checkbox" id="cela'+novoID+'" class="celasraio '+espaco+'"><label for="cela'+novoID+'"> '+nome+'/'+cela+'</label></div>');

                    arrlistacelas.push({
                        idraio: idraio,
                        nome: nome,
                        nomecompleto: nomecompleto,
                        cela: cela,
                        idckb: 'cela'+novoID
                    })
    
                    adicionaEventosCkBCelas(idraio,$('#cela'+novoID),cela);
                    contador++;
                }
            });
        }
    }
}

function adicionaEventosCkBCelas(idraio,ckb,cela){
    ckb.on('change', ()=>{
        let index = -1;
        if(arrraioscelas.length>0){
            index = arrraioscelas.findIndex((raio)=>raio.idraio==idraio);
        }
        if(index==-1){
            let indexraio = arrlistacelas.findIndex((raio)=>raio.idraio==idraio);
            arrraioscelas.push({
                idraio:idraio,
                nome: arrlistacelas[indexraio].nome,
                nomecompleto: arrlistacelas[indexraio].nomecompleto,
                celas:[{cela: cela, idbanco:0}]
            })
        }else{
            if(ckb.prop('checked')==true){
                if(arrraioscelas[index].celas.includes(cela)==false){
                    arrraioscelas[index].celas.push({cela: cela, idbanco:0});
                }
            }else{
                arrraioscelas[index].celas = arrraioscelas[index].celas.filter((celas)=>celas.cela!=cela);
            }
            if(arrraioscelas[index].celas.length==0){
                arrraioscelas = arrraioscelas.filter((raio)=>raio.idraio!=idraio);
            }
        }
    });
};

function marcarCelasSelecionadas(arr){
    if(arr.length>0){
        arr.forEach(raio => {
            let idraio = raio.idraio;
            arrraioscelas.push(raio);
            raio.celas.forEach(cela => {
                let index = arrlistacelas.findIndex((cel)=>cel.idraio==idraio&&cel.cela==cela.cela);
                $('#'+arrlistacelas[index].idckb).prop('checked',true);
            });
        });
    }
}

$('#salvarraioslocais').click(()=>{
    if(idtipoarr>0){
        let index = -1;
        if(idtipoarr==1){
            index = arrfuncionariosbatepisograde.findIndex((idfunc)=>idfunc.divfunc==idarr);
            if(index>-1){
                arrfuncionariosbatepisograde[index].celas=arrraioscelas;
            }
            verificaQuantidadeDeCelas();
        }

        if(index>-1){
            inserirMensagemTela('<li class="mensagem-exito"> Dados atualizados provisóriamente!! Não se esqueça de salvar para atualizar no banco de dados. </li>');
            fecharPopRaiosLocais();
        }else{
            inserirMensagemTela('<li class="mensagem-erro"> Os valores selecionados não foram atualizados pois não foi encontrado indentificador na array informada. </li>');
        }    
    }else{
        inserirMensagemTela('<li class="mensagem-erro"> O tipo de array não foi definido. Consulte o desenvolvedor. </li>');
    }
});
