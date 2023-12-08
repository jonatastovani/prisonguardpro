var blnrolvisu = 1;
const tabela = $('#table-rol-gerenciar').find('tbody');
//Array de ids de movimentações para envio
let idsmovimentacoes = [];
let arrconsulta = [];
let situacao = 0;
let ordem = 3;
let texto = 1;
let buscatexto = 1;

function adicionaEventoOpcoesConsulta(){
    let seletores = [];
    seletores.push(['#ativos',1])
    seletores.push(['#inativos',1])
    seletores.push(['#todos',1])
    seletores.push(['#ordemmatricula',2])
    seletores.push(['#ordemnome',2])
    seletores.push(['#ordemvisita',2])
    seletores.push(['#dividirtexto',3])
    seletores.push(['#todotexto',3])
    seletores.push(['#buscaparte',4])
    seletores.push(['#buscaexata',4])
    seletores.push(['#buscainicio',4])
    seletores.push(['#buscafinal',4])

    seletores.forEach(linha => {
        let valor = 0;
        $(linha[0]).on('click', (e)=>{
            valor = $('#'+e.target.id).val();
            valor = parseInt(valor);

            if(linha[1]==1){
                situacao = valor;
            }else if(linha[1]==2){
                ordem = valor;
            }else if(linha[1]==3){
                texto = valor;
            }else if(linha[1]==4){
                buscatexto = valor;
            }
        })
    });
    
}

function atualizaListaGerenciarVisitantes(){

    // elementoVerificar = $('#datainicio')
    // if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
    //     elementoVerificar.focus()
    //     mensagem = ("<li class = 'mensagem-aviso'> Data início inválida! </li>")
    //     inserirMensagemTela(mensagem)
    //     clearInterval(timer);
    //     tabela.html('');
    //     return;
    // }

    // elementoVerificar = $('#datafinal')
    // if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
    //     if(mensagem==''){
    //         elementoVerificar.focus()
    //     }
    //     mensagem = ("<li class = 'mensagem-aviso'> Data final inválida! </li>")
    //     inserirMensagemTela(mensagem)
    //     clearInterval(timer);
    //     tabela.html('');
    //     return;
    // }

    // let datainicio = $('#datainicio').val();
    // let datafinal = $('#datafinal').val();
    let textobusca = $('#textobusca').val().trim();

    let dados = {
        tipo: 1,
        // datainicio: datainicio,
        // datafinal: datafinal,
        situacao: situacao,
        ordem: ordem,
        buscatexto: buscatexto,
        texto: texto,
        textobusca: textobusca
    }

    // console.log(dados);

    if(blnLimparConsulta==true){
        tabela.html('');
        blnLimparConsulta = false;
    }
    idsmovimentacoes = [];

    $.ajax({
        url: 'ajax/consultas/rol_busca_gerenciar.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result);
        
        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
            // clearInterval(timer);
            tabela.html('');
        }else{

            //Atualizo os valores do array
            result.forEach(linha => {
                let index = arrconsulta.findIndex((visi)=>visi.idbanco==linha.ID);
                let idtr = 0;
                if(index!=-1){
                    if($('#'+arrconsulta[index].idtr).length>0){
                        idtr = arrconsulta[index].idtr;
                    }
                    arrconsulta = arrconsulta.filter((visi)=>visi.idbanco!=linha.ID);
                }

                arrconsulta.push({
                    idbanco:linha.ID,
                    idtr:idtr,
                    idpreso:linha.IDPRESO,
                    idpresoatual:linha.IDPRESOATUAL,
                    nome: linha.NOME,
                    matricula: linha.MATRICULA,
                    cpf: linha.CPF,
                    idvisitante: linha.IDVISITANTE,
                    nomevisitante: linha.NOMEVISITANTE,
                    idparentesco: linha.IDPARENTESCO,
                    parentesco: linha.PARENTESCO,
                    dataaprovado: linha.DATAAPROVADO,
                    datacadastro: linha.DATACADASTRO,
                    idsituacao: linha.IDSITUACAO,
                    situacao: linha.SITUACAO,
                    comentariosit: linha.COMENTARIO,
                    situacaovisi: linha.SITUACAOVISI,
                    comentariovisi: linha.COMENTARIOVISI,
                    seguro: linha.SEGURO,
                    cor: linha.COR,
                    idraio:linha.IDRAIO,
                    raio: linha.RAIO,
                    cela: linha.CELA
                });
            });

            //Remove do array os aendimentos que não estão no array da busca
            arrconsulta.forEach(visi => {
                let idbanco = visi.idbanco;
                let index = result.findIndex((linha)=>linha.ID==idbanco);

                if(index<0){
                    arrconsulta = arrconsulta.filter((visi)=>visi.idbanco!=idbanco);
                }
            });

            //Atualiza a tela
            verificaRegistrosGerAtend();

            let registros = tabela.children();
            // console.log(registros);
            // console.log(arratendimentos);

            for(let i=0;i<registros.length;i++){
                let id = registros[i].id;
                if(id==undefined || id==null || id==''){
                    registros[i].remove();
                }else{
                    if(arrconsulta.findIndex((visi)=>visi.idtr==id)<0){
                        registros[i].remove();
                    }
                }
            }
        }
    });
}

//Verifica se o registro já existe e atualiza os dados. Se não existir então é inserido
function verificaRegistrosGerAtend(){
    // console.log(arratendimentos);

    arrconsulta.forEach(visi => {
        let matricula = midMatricula(visi.matricula,3);
        let datacadastro = '';
        if(visi.datacadastro!=null){
            datacadastro = retornaDadosDataHora(visi.datacadastro,12);
        }
        let dataaprovado = 'Não aprovado';
        if(visi.dataaprovado!=null){
            dataaprovado = retornaDadosDataHora(visi.dataaprovado,12);
        }
        let comentario = '';
        if(visi.comentario!=null){
            comentario = 'title="'+visi.comentario+'"';
        }
        let comentariovisi = '';
        if(visi.comentariovisi!=null){
            comentariovisi = 'title="'+visi.comentariovisi+'"';
        }
        let raiocelaatual = visi.raio+'/'+visi.cela;

        // if(visi.idtr!=0){
            // let tr = $('#'+visi.idtr);
            // // let idmov = tr.data('idmov');
            
            // if(matricula!=tr.find('.tdmatricula').html()){
            //     tr.find('.tdmatricula').html(matricula);
            // }
            // if(visi.nome!=tr.find('.tdnome').html()){
            //     tr.find('.tdnome').html(visi.nome);
            // }
            // if(comentario!=tr.find('.tddescpedido').html()){
            //     tr.find('.tddescpedido').html(comentario);
            // }
            // if(datacadastro!=tr.find('.tddataatend').html()){
            //     tr.find('.tddataatend').html(datacadastro);
            // }
            // if(dataaprovado!=tr.find('.tddataaprovado').html()){
            //     tr.find('.tddataaprovado').html(dataaprovado);
            // }
            // if(raiocelaatual!=tr.find('.tdraioatual').html()){
            //     tr.find('.tdraioatual').html(raiocelaatual);
            // }
            // if(visi.tipo!=tr.find('.tdtipo').html()){
            //     tr.find('.tdtipo').html(visi.tipo);
            // }
            // if(visi.situacao!=tr.find('.tdsituacao').html()){
            //     tr.find('.tdsituacao').html(visi.situacao);
            // }
            // if(visi.idbanco!=tr.data('idbanco')){
            //     tr.data('idbanco',visi.idbanco);
            // }
            // if(visi.cor!=tr.data('cor')){
            //     tr.removeClass(tr.data('cor')).addClass(visi.cor);
            //     tr.data('cor',visi.cor);
            // }
        // }else{
            let novoID = gerarID('.trvisi');
            //<td><input type="checkbox" id="check'+novoID+'" class="ckbraio"></input></td>

            let linha = '<tr id="trvisi'+novoID+'" class="trvisi '+visi.cor+' nowrap" data-idbanco="'+visi.idbanco+'" data-tabela="9" data-cor="'+visi.cor+'"><td class="centralizado tdbotoes nowrap" style="min-width: 70px;"></td><td class="centralizado min-width-100 tdmatricula">'+matricula+'</td><td class="tdnomevisitante min-width-200 max-width-450">'+visi.nomevisitante+'</td><td class="min-width-350 max-width-450 tdnome">'+visi.nome+'</td><td class="tdparentesco centralizado nowrap">'+visi.parentesco+'</td><td class="centralizado tdraioatual">'+raiocelaatual+'</td><td class="tdsituacaovisi nowrap" '+comentariovisi+'>'+visi.situacaovisi+'</td><td class="min-width-250 max-width-350 tdsituacao" '+comentario+'>'+visi.situacao+'</td><td class="centralizado tddataaprovado">'+dataaprovado+'</td><td class="centralizado nowrap tddatacadastro">'+datacadastro+'</td></tr>';
            tabela.append(linha);
            visi.idtr = 'trvisi'+novoID;
        // }

        adicionaEventoBotoesAtend(visi.idtr, visi);
    });
}

function adicionaEventoBotoesAtend(idtr, arrvisi){
    let tr = tabela.find('#'+idtr);
    let arrbotoesexcluir = [
        'btnaltvisi'
    ]
    let id = tr.data('idbanco');

    // if(blnpresoatual==true){
        inserirBotaoAbrirVisitante(tr.find('.tdbotoes'),id);
        arrbotoesexcluir = arrbotoesexcluir.filter((atend)=>atend!='btnaltvisi');
    // }

    if(arrvisi.cpf!=null){
        inserirBotaoFotoVisitante(tr.find('.tdbotoes'),arrvisi.idvisitante)
    }

    arrbotoesexcluir.forEach(botao => {
        tr.find('.'+botao).remove();
    });
}

function adicionaEventoPesquisaGerMov(){
    let seletores = [];
    seletores.push(['#pesquisar-gerenciar','click'])
    // seletores.push(['#datainicio','enter'])
    // seletores.push(['#datafinal','enter'])
    seletores.push(['#ordemmatricula','change'])
    seletores.push(['#ordemnome','change'])
    seletores.push(['#ordemvisita','change'])
    seletores.push(['#textobusca','enter'])
    seletores.push(['#dividirtexto','change'])
    seletores.push(['#todotexto','change'])
    seletores.push(['#buscaparte','change'])
    seletores.push(['#buscaexata','change'])
    seletores.push(['#buscainicio','change'])
    seletores.push(['#buscafinal','change'])
    seletores.push(['#ativos','change'])
    seletores.push(['#inativos','change'])
    seletores.push(['#todos','change'])

    seletores.forEach(linha => {
        if(['click','change'].includes(linha[1])){
            $(linha[0]).on(linha[1], (e)=>{
                blnLimparConsulta=true;
                iniciaConsultaTimer();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    blnLimparConsulta=true;
                    iniciaConsultaTimer();
                }
            })
        }
    });
}

function adicionaEventoCheck(){
    var checks = tabela.find('input:checkbox');

    for(var i=0;i<checks.length;i++){
        let check = $('#'+checks[i].id);
        let idmov = check.data('idmov');
        let tab = check.data('tabela');

        check.on('change', ()=>{
            if(check.prop('checked')==true){
                if(tab==1){
                    idsmovimentacoes.push(idmov); 
                }else if(tab==2){
                    idsmovretorno.push(idmov); 
                }else if(tab==3){
                    idsmovreceb.push(idmov); 
                }
            }else{
                if(tab==1){
                    idsmovimentacoes = idsmovimentacoes.filter((item)=>item!=idmov); 
                }else if(tab==2){
                    idsmovretorno = idsmovretorno.filter((item)=>item!=idmov); 
                }else if(tab==3){
                    idsmovreceb = idsmovreceb.filter((item)=>item!=idmov); 
                }
            }
        })
    }
}

function obtemChecados(){
    var check = tabela.find("input:checked");
    return check;
}

$('#checkall').click(()=>{
    var checks = tabela.find('input:checkbox');
    var bln = $('#checkall').prop('checked');

    for(var i=0;i<checks.length;i++){
        var check = $('#'+checks[i].id);
        check.prop('checked', bln).trigger('change');
    }
})

adicionaEventoPesquisaGerMov();
adicionaEventoOpcoesConsulta();

$('#textobusca').focus();

let timer;
let blnLimparConsulta = false;

function iniciaConsultaTimer(){
    // clearInterval(timer);
    // timer = setInterval(() => {
        atualizaListaGerenciarVisitantes();
    // }, 500);
};

iniciaConsultaTimer();

