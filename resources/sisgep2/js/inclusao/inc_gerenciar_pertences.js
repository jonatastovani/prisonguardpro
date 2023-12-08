const tabelapertences = $('#table-pertences-gerenciar').find('tbody');
const tipopertencesedex = $('#tipopertencesedex').val();

$('#filtropendentes').click(()=>{
    atualizaListaGerenciarPertences();
})
$('#filtroretirados').click(()=>{
    atualizaListaGerenciarPertences();
})
$('#filtrodescartados').click(()=>{
    atualizaListaGerenciarPertences();
})
$('#filtrotodos').click(()=>{
    atualizaListaGerenciarPertences();
})
$('#exato').click(()=>{
    atualizaListaGerenciarPertences();
})
$('#parcial').click(()=>{
    atualizaListaGerenciarPertences();
})
$('#pesq-gerenciar-pertences').click(()=>{
    atualizaListaGerenciarPertences();
})

function atualizaListaGerenciarPertences(){
    var datainicio = $('#datainicio').val();
    var datafinal = $('#datafinal').val();
    var textobusca = $('#textobuscapertences').val();
    var situacao = 0;
    if($('#filtropendentes').prop('checked')==true){
        situacao = $('#filtropendentes').val();
    }
    else if($('#filtroretirados').prop('checked')==true){
        situacao = $('#filtroretirados').val();
    }
    else if($('#filtrodescartados').prop('checked')==true){
        situacao = $('#filtrodescartados').val();
    }
    
    var filtrotexto = 1;
    if($('#exato').prop('checked')==true){
        filtrotexto = $('#exato').val();
    }
    
    $.ajax({
        url: 'ajax/consultas/inc_busca_gerenciar_pertences.php',
        method: 'POST',
        data: {
            datainicio: datainicio,
            datafinal: datafinal,
            textobusca: textobusca,
            filtrotexto: filtrotexto,
            tipo: tipopertencesedex,
            situacao: situacao},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            tabelapertences.html(result);
            adicionaEventosGerenciar()
        }
    });
}

function adicionaEventosGerenciar(){
    adicionaEventoDescartar();
    adicionaEventoDesfazerDescartar();
    adicionaEventoAlterarPertence()
}

function adicionaEventoDescartar(){
    var pendente = tabelapertences.find('.pendente')
    if(pendente.length>0){
        for(var i=0;i<pendente.length;i++){
            var id = retornaSomenteNumeros(pendente[i].id);
            var botao = $('#'+pendente[i].id);
            eventoAlterarSituacao(id, true, botao);       
        }
    }
}

function adicionaEventoDesfazerDescartar(){
    var descartado = tabelapertences.find('.descartado')
    if(descartado.length>0){
        for(var i=0;i<descartado.length;i++){
            var id = retornaSomenteNumeros(descartado[i].id);
            var botao = $('#'+descartado[i].id);
            eventoAlterarSituacao(id, false, botao);       
        }
    }
}

function eventoAlterarSituacao(id, descartado, botao){
    botao.on('click',()=>{
        //console.log(tipopertencesedex)

        $.ajax({
            url: 'ajax/inserir_alterar/inc_pertences.php',
            method: 'POST',
            //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
            data: {
                descartado: descartado,
                idpertence: id,
                tipopertence: tipopertencesedex,
                tipo: 2
            },
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json',
            async: false
        }).done(function(result){
            //console.log(result)
    
            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM)
            }else{
                inserirMensagemTela(result.OK)
                atualizaListaGerenciarPertences();
            }
        });
    })
}

function adicionaEventoAlterarPertence(){
    var alterarpertence = tabelapertences.find('.alterarpertence')
    if(alterarpertence.length>0){
        for(var i=0;i<alterarpertence.length;i++){
            var id = retornaSomenteNumeros(alterarpertence[i].id);
            var botao = $('#'+alterarpertence[i].id);
            var idpreso = botao.parent().parent().find('input:checkbox').data('preso');
            alterarPertencePreso(id,botao,idpreso);
        }
    }
}

function alterarPertencePreso(id,botao,idpreso){
    botao.on('click',()=>{
        idpresopertences = idpreso;
        idpertencebuscar = id;
        abrirPertencesPreso()
    })
}

function obtemChecados(){
    var check = tabelapertences.find("input:checked");
    return check;
}

$('#descartartodos').click(()=>{
    let check = obtemChecados();
    if(check.length>0){
        for(var i=0;i<check.length;i++){
            var botao = $('#descartar'+check[i].id);
            botao.click();       
        }
    }
})

$('#checkall').click(()=>{
    var checks = tabelapertences.find('input:checkbox');
    var bln = $('#checkall').prop('checked');

    for(var i=0;i<checks.length;i++){
        var check = $('#'+checks[i].id);
        check.prop('checked', bln);
    }
})

$('#selvencidos').click(()=>{
    let datas = tabelapertences.find('.dataentrada');

    for(let i=0;i<datas.length;i++){
        let datapertence = $('#'+datas[i].id);
        let check = datapertence.parent().find('input:checkbox');
        let data = datapertence.html();
        data = retornaDadosDataHora(data,1,true)
        let diferenca = retornaDiferencaDeDataEHora(data,retornaDadosDataHora(new Date(),1),1)

        if(diferenca>20){
            check.prop('checked', true);
        }
    }
})

$('#novopertence').click(function(){
    abrirNovoPertence();
})

$('#textobuscapertences').keyup((e)=>{
    var key = e.which || e.keyCode;
    if (key == 13) { // codigo da tecla enter
        atualizaListaGerenciarPertences()
    }
})

atualizaListaGerenciarPertences();
