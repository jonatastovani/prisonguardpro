let arrcelaspopcelvis = []; // array de celas para retornar
let listaturnospopcelvis = '';
const divcelaspopcelvis = $('#divcelasvisitas');

$("#opencelasvisitas").on("click",function(){
    abrirPopCelasVisitas();
});

function abrirPopCelasVisitas(){
    atualizaListagemComum('buscas_comuns',{tipo:30,selecionados:[1,3]},$('#listaturnospopcelvis'),0,false,'','',false,true,'Todos os turnos');
    listaturnospopcelvis = $('#listaturnospopcelvis').html();
    // console.log(listaturnospopcelvis);

    buscaCelasVisitasExistentes();
    
    $("#pop-celasvisitas").addClass("active");
    $("#pop-celasvisitas").find(".popup").addClass("active");
}

//Fechar pop-up Artigo
$("#pop-celasvisitas").find(".close-btn").on("click",function(){
    fecharPopCelasVisitas();
})
function fecharPopCelasVisitas(){

    $("#pop-celasvisitas").removeClass("active");
    $("#pop-celasvisitas").find(".popup").removeClass("active");
    divcelaspopcelvis.html('')
    arrcelaspopcelvis = [];
    listaturnospopcelvis = '';
}

function buscaCelasVisitasExistentes(){

    divcelaspopcelvis.html('');
    arrcelaspopcelvis = [];
    let result = consultaBanco('rol_busca_gerenciar',{tipo:8});
    // console.log(result);
    if(result.length){
        
        let idfs = '';
        let blnpermtodos = -1;

        result.forEach(dados => {
            let idraiocela = dados.ID;
            let idfieldset = 'raio'+dados.IDRAIO;

            if(idfs!=idfieldset){
                idfs=idfieldset;
                blnpermtodos = -1;

                divcelaspopcelvis.append('<fieldset id="'+idfs+'" class="grupo-block"><legend>'+dados.NOMECOMPLETO+'</legend><div class="listagem"><table id="table-pop-'+idfs+'" class="largura-total"><thead><tr><th>Cela</th><th><div class="inline">Permitido</div><div class="onoff"><input type="checkbox" class="toggle" id="onoff'+idfs+'"><label id="label" for="onoff'+idfs+'" title="Clique para permitir para todos"></label></div></th><th><label for="selectturnogerais'+idfs+'">Turno</label><div><select id="selectturno'+idfs+'" title="Selecione o turno permitido para visitação para todos">'+listaturnospopcelvis+'</select></div></th></tr></thead><tbody></tbody></table></div></fieldset>');

                adicionaEventosTodosPopCelVis(idfs);
            }

            let fsraio = $('#'+idfs).find('tbody');
            let idtr = 'cela'+idraiocela;

            fsraio.append('<tr id="'+idtr+'" class="cor-negado"><td class="centralizado">'+dados.NOMERAIO+'/'+dados.CELA+'</td><td class="centralizado"><div class="onoff"><input type="checkbox" class="toggle" id="onoff'+idtr+'"><label id="label" for="onoff'+idtr+'" title="Clique para permitir"></label></div></td><td class="centralizado"><select id="selectturno'+idtr+'" title="Selecione o turno permitido para visitação">'+listaturnospopcelvis+'</select></td></tr>');

            let idturno = 0;
            if(dados.IDTURNO!=null){
                idturno = dados.IDTURNO;
            }

            //Define se vai ficar checado o onoff geral do raio;
            if(dados.PERMITIDO && blnpermtodos == -1 || !dados.PERMITIDO){
                blnpermtodos = dados.PERMITIDO;
            }

            arrcelaspopcelvis.push({
                idbanco: idraiocela,
                idfs: idfs,
                idtr: idtr,
                idraio: dados.IDRAIO,
                cela: dados.CELA,
                nomeraio: dados.NOMERAIO,
                nomecompleto: dados.NOMECOMPLETO,
                permitido:dados.PERMITIDO,
                idturno:idturno
            })
            
            adicionaEventosCelasPopCelVis(idtr);

            $('#selectturno'+idtr).val(idturno).trigger('change');;
            $('#onoff'+idtr).prop('checked',dados.PERMITIDO?true:false).trigger('change');
            $('#onoff'+idfs).prop('checked',blnpermtodos?true:false);
            
        });
    }
}

function adicionaEventosCelasPopCelVis(idtr){
    let tr = $('#'+idtr);
    let onoff = $('#onoff'+idtr);
    let selectturno = $('#selectturno'+idtr);

    adicionaEventosBotaoOnOff(onoff,'','Clique para permitir','Clique para negar');
    onoff.change(function(){
        let ckb = this;
        let index = arrcelaspopcelvis.findIndex((cel)=>cel.idtr==idtr);
        arrcelaspopcelvis[index].permitido = ckb.checked?1:0;
        ckb.checked?tr.removeClass('cor-negado').addClass('cor-aprovado'):tr.removeClass('cor-aprovado').addClass('cor-negado');
        // console.log(arrcelaspopcelvis[index])
    })

    selectturno.change(()=>{
        let idturno = selectturno.val();
        let index = arrcelaspopcelvis.findIndex((cel)=>cel.idtr==idtr);
        if([0,1,3].includes(parseInt(idturno))){
            arrcelaspopcelvis[index].idturno = idturno;
        }else{
            inserirMensagemTela('<li class="mensagem-exito"> Turno inexistente!. </li>');
        }
        // console.log(arrcelaspopcelvis[index])
    });
};

function adicionaEventosTodosPopCelVis(idfs){
    let fs = $('#'+idfs);
    let onoff = $('#onoff'+idfs);
    let selectturno = $('#selectturno'+idfs);

    adicionaEventosBotaoOnOff(onoff,'','Clique para permitir para todos','Clique para negar para todos');
    onoff.change(function(){
        let ckb = this;
        let timeout = 0;
        
        for(let i=0;i<arrcelaspopcelvis.length;i++){
            if(arrcelaspopcelvis[i].idfs==idfs){
                setTimeout(() => {
                    $('#onoff'+arrcelaspopcelvis[i].idtr).prop('checked',ckb.checked).trigger('change');
                }, timeout);
                timeout = timeout + 50;
            }
        }
    })

    selectturno.change(()=>{
        let idturno = selectturno.val();
        let timeout = 0;

        if([0,1,3].includes(parseInt(idturno))){
            for(let i=0;i<arrcelaspopcelvis.length;i++){
                if(arrcelaspopcelvis[i].idfs==idfs){
                    setTimeout(() => {
                        $('#selectturno'+arrcelaspopcelvis[i].idtr).val(idturno).trigger('change');
                    }, timeout);
                    timeout = timeout + 50;
                }
            }
        }else{
            inserirMensagemTela('<li class="mensagem-exito"> Turno inexistente!. </li>');
        }
    });
};

$('#salvarpopcelvis').click(()=>{
    
    let dados = {
        tipo:6,
        arrcelas:arrcelaspopcelvis
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
            fecharPopCelasVisitas();
        }
    });
});
