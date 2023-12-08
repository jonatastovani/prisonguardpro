// Confirmação do retorno para salvamento das informações
let confirmacaopopmedass = 0;
let idpresopopmedass = 0;
let idpresoalterarpopmedass = 0; //Somente quando vem alterar um preso
const divperiodos = $('#divperiodospopmedass');
const divfsperiodos = $('#divfsperiodos');
let arrperiodos = [];

$("#openpopmedass").on("click",function(){
    abrirPopMedicAssistidos();
});

//idpreso = preencher caso queira que se abra o novo atendimento preenchendo a busca do preso. Só funcionará se não estiver alterando a solicitação de atendimento;
function abrirPopMedicAssistidos(){
    
    limparCamposPopMedicAssistidos();

    atualizaListagemComum('buscas_comuns',{tipo: 40},$('#listamedicpopmedass'),$('#selectmedicpopmedass'));
    buscaPeriodosExistentesMedicAssistidos();

    if(idpresoalterarpopmedass>0){
        $('#divselectpresopopmedass').attr('hidden','hidden');
        buscaDadosPresoPopMedicAssistidos(idpresoalterarpopmedass);
    }else{
        atualizaListagemComum('busca_presos',{tipo: 2, tipobusca: 2, valor: 1, tiporetorno:2, idvisualizacao:0, blnvisuchefia:1},$('#listapresospopmedass'),$('#selectpresopopmedass'));
        
        $('#searchpresopopmedass').focus();
    }
    
    $("#pop-medass").addClass("active");
    $("#pop-medass").find(".popup").addClass("active");
}

//Fechar pop-up Artigo
$("#pop-medass").find(".close-btn").on("click",function(){
    fecharPopMedicAssistidos();
})
function fecharPopMedicAssistidos(){
    $('#listapresospopmedass').html('');
    $('#selectpresopopmedass').html('');
    $("#pop-medass").removeClass("active");
    $("#pop-medass").find(".popup").removeClass("active");
    limparCamposPopMedicAssistidos();
    idpresopopmedass = 0;
    idpresoalterarpopmedass = 0;
}

function limparCamposPopMedicAssistidos(){
    $('#divselectpresopopmedass').removeAttr('hidden');
    $('#divdadospresopopmedass').attr('hidden','hidden');
    $('.temphtmlpopmedass').html('');
    arrperiodos.forEach(per => {
        per.medicass=[];
    });
}

adicionaEventoSelectChange(0,$('#selectpresopopmedass'),$('#searchpresopopmedass'))

$('#selectpresopopmedass').change(function(){
    limparCamposPopMedicAssistidos();
    var idpreso = $('#selectpresopopmedass').val();
    
    if(idpreso!=0 && idpreso!=null){
        buscaDadosPresoPopMedicAssistidos(idpreso);
    }else{
        $('#divdadospresopopmedass').attr('hidden','hidden');
        idpresopopmedass=0;
    }
})

$('#searchpresopopmedass').change(function(){
    var id = $('#searchpresopopmedass').val();
    
    if(id!=$('#selectpresopopmedass').val()){
        buscaSearchComum('busca_presos',{tipo:1, idpreso:id},$('#searchpresopopmedass'),$('#selectpresopopmedass'),$('#searchmedicpopmedass'));
    }
})

function buscaDadosPresoPopMedicAssistidos(idbuscar){
    $.ajax({
        url: 'ajax/consultas/busca_presos.php',
        method: 'POST',
        data: {tipo: 1, idpreso: idbuscar},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
            idpresopopmedass = 0;
        }else{
            idpresopopmedass = idbuscar;
            $('#nomepresopopmedass').html(result[0].NOME);
            $('#matriculapresopopmedass').html(midMatricula(result[0].MATRICULA,3));
            $('#raiocelapresopopmedass').html(result[0].RAIOCELA);
            $('#divdadospresopopmedass').removeAttr('hidden');

            buscaAssistidosMedicAssistidos();
        }
    });
}

function buscaPeriodosExistentesMedicAssistidos(){
    arrperiodos = [];
    divfsperiodos.html('');
    divperiodos.html('');

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
        method: 'POST',
        data: {tipo: 49},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            let contador = 0;
            let check = 'checked';

            result.forEach(linha => {
                let novoID = 'periodo'+linha.ID+'popmedass';
                
                let tabelaperiodo = '<fieldset class="grupo-block"><legend>'+linha.NOME+'</legend><div class="listagem max-height-200"><table id="'+novoID+'" class="largura-total"><thead><tr class="nowrap"><th>Ação</th><th>ID</th><th>Medicamento</th><th>Qtd</th><th>Data Início</th><th>Data Término</th></tr></thead><tbody class="temphtmlpopmedass"></tbody></table></div></fieldset>';

                arrperiodos.push({
                    idtab: novoID,
                    idperiodo: linha.ID,
                    nomeperiodo: linha.NOME,
                    medicass:[]
                })

                let espaco = '';
                if(contador!=0){
                    espaco = 'margin-espaco-esq';
                    check = '';
                }

                divfsperiodos.append('<div class="inline '+espaco+'"><input type="radio" name="periodopopmedass" id="inp'+novoID+'" value="'+linha.ID+'" '+check+'><label for="inp'+novoID+'"> '+linha.NOME+'</label></div>');
                contador++;
                
                divperiodos.append(tabelaperiodo);
            });
        }
    });
}

adicionaEventoSelectChange(0,$('#selectmedicpopmedass'),$('#searchmedicpopmedass'))

$('#searchmedicpopmedass').change(function(){
    var id = $('#searchmedicpopmedass').val();
    
    if(id!=$('#selectmedicpopmedass').val()){
        buscaSearchComum('buscas_comuns',{tipo:41, idmedic:id, verificaativo:1},$('#searchmedicpopmedass'),$('#selectmedicpopmedass'),$('#btninserirpopmedass'));
    }
})

function buscaAssistidosMedicAssistidos(){
    
    let dados = {
        tipo: 6,
        idpreso: idpresopopmedass
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

            result.forEach(linha => {             
                let novoID = inserirAssistidoMedicAssistidos(linha.IDMEDICAMENTO,linha.IDPERIODOENTREGA,linha.QTDENTREGA);

                let index = arrperiodos.findIndex((per)=>per.idperiodo==linha.IDPERIODOENTREGA);
                if(index!=-1){
                    let indextr = arrperiodos[index].medicass.findIndex((medic)=>medic.idtr==novoID);
                                       
                    let datainicio = retornaDadosDataHora(linha.DATAINICIO,1);
                    let datatermino = '';
                    if(linha.DATATERMINO!=null){
                        datatermino = retornaDadosDataHora(linha.DATATERMINO,1);
                    }

                    arrperiodos[index].medicass[indextr].idbanco = linha.ID;
                    arrperiodos[index].medicass[indextr].datainicio = datainicio;
                    arrperiodos[index].medicass[indextr].datatermino = datatermino;

                    $('#'+novoID).find('.datainicio').val(datainicio);
                    $('#'+novoID).find('.datatermino').val(datatermino);

                }

                retorno = true;
            });
        }
    });
}

function inserirAssistidoMedicAssistidos(idmedic,idperiodo,qtd=0){
    let retorno = false;

    let dados = {
        tipo: 41,
        idmedic: idmedic,
        verificaativo:1
    }

    // console.log(dados);

    $.ajax({
        url: 'ajax/consultas/buscas_comuns.php',
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

            let novoID = 'trmedass'+gerarID('.trmedass');
            if(qtd==0){
                qtd = result[0].QTD;
            }

            let datainicio = retornaDadosDataHora(new Date(),1);

            let tr = '<tr id="'+novoID+'" class="trmedass cor-fundo-comum-tr nowrap"><td class="centralizado tdbotoes nowrap" style="min-width: 70px;"></td><td class="centralizado tdidmedicamento">'+result[0].ID+'</td><td class="tdnomemedicamento min-width-200 max-width-450">'+result[0].NOME+'</td><td class="centralizado tdqtdentrega"><input type="text" class="qtdentrega num-inteiro centralizado" style="width: 50px;" value="'+qtd+'"></td><td class="centralizado nowrap tddatainicio"><input type="date" class="datainicio" value="'+datainicio+'"></td><td class="centralizado nowrap tddatatermino"><input type="date" class="datatermino"></td></tr>';

            let index = arrperiodos.findIndex((per)=>per.idperiodo==idperiodo);
            if(index!=-1){
                $('#'+arrperiodos[index].idtab).find('tbody').append(tr);
                //Para funcionar o mask é preciso adicionar toda vez esta linha
                $('.num-inteiro').mask('999990');

                arrperiodos[index].medicass.push({
                    idtr:novoID,
                    idbanco: 0,
                    idmedic: result[0].ID,
                    nomemedicamento: result[0].NOME,
                    qtd: qtd,
                    datainicio: datainicio,
                    datatermino: '',
                    excluido: 0
                });

                inserirEventosTrPopMedicAssistidos(idperiodo,novoID);
            }
            retorno = novoID;
        }
    });

    return retorno;
}

function inserirEventosTrPopMedicAssistidos(idperiodo,idtr){
    let qtdentrega = $('#'+idtr).find('.qtdentrega');
    let datainicio = $('#'+idtr).find('.datainicio');
    let datatermino = $('#'+idtr).find('.datatermino');

    qtdentrega.change(function(){
        let index = arrperiodos.findIndex((per)=>per.idperiodo==idperiodo);
        let indextr = arrperiodos[index].medicass.findIndex((medic)=>medic.idtr==idtr);
        let valor = parseInt(this.value);

        
        if(arrperiodos[index].medicass[indextr].excluido==0){
            if(valor>0){
                arrperiodos[index].medicass[indextr].qtd = valor;
            }else{
                let valorantigo = arrperiodos[index].medicass[indextr].qtd;
                inserirMensagemTela('<li class="mensagem-aviso"> O valor informado não é aceitado, o último valor válido inserido anteriormente ('+valorantigo+') foi retornado no campo Quantidade. </li>')
                this.value = arrperiodos[index].medicass[indextr].qtd;
            }
        }
    })

    datainicio.focusout(function(){
        let index = arrperiodos.findIndex((per)=>per.idperiodo==idperiodo);
        let indextr = arrperiodos[index].medicass.findIndex((medic)=>medic.idtr==idtr);
        let valor = this.value;

        if(arrperiodos[index].medicass[indextr].excluido==0){
            if(valor!=''){
                arrperiodos[index].medicass[indextr].datainicio = valor;
            }else{
                let valorantigo = retornaDadosDataHora(arrperiodos[index].medicass[indextr].datainicio,2);
                inserirMensagemTela('<li class="mensagem-aviso"> O valor informado não é aceitado, o último valor válido inserido anteriormente ('+valorantigo+') foi retornado no campo Data Início. </li>')

                this.value = arrperiodos[index].medicass[indextr].datainicio;
            }
        }
    })

    datatermino.focusout(function(){
        let index = arrperiodos.findIndex((per)=>per.idperiodo==idperiodo);
        let indextr = arrperiodos[index].medicass.findIndex((medic)=>medic.idtr==idtr);
        let valor = this.value;

        if(arrperiodos[index].medicass[indextr].excluido==0){
            arrperiodos[index].medicass[indextr].datatermino = valor;
            if(valor==''){
                // inserirMensagemTela('<li class="mensagem-aviso"> O valor informado não é aceitado, o campo Data Término foi limpo automaticamente. </li>')
                this.value = '';
            }
        }
    })
    
    //Inserir botão de remover 
    inserirBotaoExcluirMedicAssistidos(idperiodo,idtr);
}

$('#btninserirpopmedass').click(()=>{
    let idmedic = $('#selectmedicpopmedass').val();
    let idperiodo = $('input[name=periodopopmedass]:checked').val();

    if(idpresopopmedass>0){
        if(idmedic>0 && idperiodo>0){
            let index = arrperiodos.findIndex((per)=>per.idperiodo==idperiodo);
            let indexmedic = arrperiodos[index].medicass.findIndex((medic)=>medic.idmedic==idmedic);

            if(indexmedic==-1){
                inserirAssistidoMedicAssistidos(idmedic,idperiodo);
            }else{
                inserirMensagemTela('<li class="mensagem-aviso"> Este medicamento já está inserido para o período informado. </li>')
            }
        }else{
            inserirMensagemTela('<li class="mensagem-aviso"> Selecione um medicamento. </li>')
        }
        $('#searchmedicpopmedass').focus();
    }else{
        inserirMensagemTela('<li class="mensagem-aviso"> Selecione um preso. </li>')
        $('#searchpresopopmedass').focus();
    }
})

function inserirBotaoExcluirMedicAssistidos(idperiodo,idtr){
    let tr = $('#'+idtr);
    let tdbotoes = tr.find('.tdbotoes');

    tdbotoes.append('<button class="btnAcaoRegistro btnexclmedicass" title="Excluir medicamento"><img src="imagens/lixeira.png" class="imgBtnAcao"></button>');
    tdbotoes.find('.btnexclmedicass').click(()=>{
        let index = arrperiodos.findIndex((per)=>per.idperiodo==idperiodo);
        let indextr = arrperiodos[index].medicass.findIndex((medic)=>medic.idtr==idtr);

        if(arrperiodos[index].medicass[indextr].idbanco>0){
            arrperiodos[index].medicass[indextr].excluido = 1;
            tr.removeClass('cor-fundo-comum-tr').addClass('cor-excluido').find('.btnexclmedicass').remove();
            tr.find('input').attr('disabled',true);
            inserirBotaoCancelarExcluirMedicAssistidos(idperiodo,idtr);
        }else{
            arrperiodos[index].medicass = arrperiodos[index].medicass.filter((medic)=>medic.idtr!=idtr);
            tr.remove();
        }
    })
}

function inserirBotaoCancelarExcluirMedicAssistidos(idperiodo,idtr){
    let tr = $('#'+idtr);
    let tdbotoes = tr.find('.tdbotoes');

    tdbotoes.append('<button class="btnAcaoRegistro btnrefmedicass" title="Cancelar exclusão de medicamento"><img src="imagens/refazer.png" class="imgBtnAcao"></button>');
    tdbotoes.find('.btnrefmedicass').click(()=>{
        let index = arrperiodos.findIndex((per)=>per.idperiodo==idperiodo);
        let indextr = arrperiodos[index].medicass.findIndex((medic)=>medic.idtr==idtr);

        arrperiodos[index].medicass[indextr].excluido = 0;
        tr.removeClass('cor-excluido').addClass('cor-fundo-comum-tr').find('.btnrefmedicass').remove();
        tr.find('input').removeAttr('disabled');
        inserirBotaoExcluirMedicAssistidos(idperiodo,idtr);
    })
}

$('#cancelarpopmedass').click(()=>{
    if(idpresopopmedass>0){
        limparCamposPopMedicAssistidos();
        $('#searchpresopopmedass').val('').trigger('change').focus();
        idpresopopmedass = 0;
    }else{
        fecharPopMedicAssistidos();
    }
})

$('#salvarpopmedass').click(function(){
    if(verificaSalvarPopMedicAssistidos()==true){
        salvarPopMedicAssistidos();
    }
})

function verificaSalvarPopMedicAssistidos(){
    let mensagem = '';

    if(idpresopopmedass>0){
        let bln = false;
        arrperiodos.forEach(per => {
            per.medicass.forEach(medic => {
                bln = true;

                if(medic.datatermino!=''){
                    let diferenca = retornaDiferencaDeDataEHora(medic.datainicio,medic.datatermino,1);
                    if(diferenca<0){
                        if(mensagem==''){
                            $('#'+medic.idtr).find('.datatermino').focus();
                        }
                        mensagem = ("<li class = 'mensagem-aviso'> A Data Término do medicamento <b>"+medic.nomemedicamento+"</b> do período da <b>"+per.nomeperiodo+"</b> é menor que a Data Início! </li>")
                        inserirMensagemTela(mensagem);    
                    }
                }
            });
        });
        if(!bln){
            $('#searchmedicpopmedass').focus();
            mensagem = ("<li class = 'mensagem-aviso'> Nenhum medicamento foi adicionado! </li>")
            inserirMensagemTela(mensagem);    
        }
    }else{
        $('#searchpresopopmedass').focus();
        mensagem = ("<li class = 'mensagem-aviso'> Nenhum preso foi selecionado! </li>")
        inserirMensagemTela(mensagem);
    }

    if(mensagem!=''){
        return false;
    }else{
        return true;
    }
}

function salvarPopMedicAssistidos(){
    
    let dados = {
        tipo: 6,
        idpreso: idpresopopmedass,
        arrperiodos: arrperiodos,
        confirmacao: confirmacaopopmedass
    };

    // console.log(dados)

    $.ajax({
        url: 'ajax/inserir_alterar/saude_gerenciar.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            if(result.CONFIR){
                if(confirm(result.MSGCONFIR)==true){
                    confirmacaopopmedass = result.CONFIR;
                    idpresopopmedass = result.IDMOV;
                };
                if(confirmacaopopmedass>0){
                    salvarPopMedicAssistidos();
                }
            }else{
                inserirMensagemTela(result.OK);
                if($('#table-assistidos-gerenciar').length>0){
                    atualizaListaGerenciarAssistidos();
                }

                if(idpresoalterarpopmedass>0){
                    fecharPopMedicAssistidos();
                }else{
                    $('#cancelarpopmedass').click();
                }
            }
        }
    });
}