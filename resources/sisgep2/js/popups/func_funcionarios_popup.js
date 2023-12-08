// Confirmação do retorno para salvamento das informações
let confirmacaopopfunc = 0;
let idfuncionariopopfunc = 0;
let divpermissoespopfunc = $("#permissoespopfunc");
let arrpermissoespopfunc = [];
let permtemporaria = 0;
const tabtemporarias = $('#table-permtermporarias').find('tbody');

$("#openpopfuncionario").on("click",function(){
    abrirPopPopFuncionario();
});

function abrirPopPopFuncionario(){
    atualizaListagemComum('buscas_comuns',{tipo: 30},0,$('#selectturnopopfunc'),false,false,'',false,false);
    atualizaListagemComum('buscas_comuns',{tipo: 31},0,$('#selectescalapopfunc'),false,false,'',false,true,'Nenhuma escala');
    buscaPermissoesGerenciaveisPopFuncionario();
    let blnencontrado = false;

    if(idfuncionariopopfunc>0){
        blnencontrado = buscaDadosPopFuncionario();
        if(permtemporaria==0){
            $("#titulopopfunc").html('Alterar Funcionário')
            $("#camposdados").removeAttr('hidden');
            $("#campotemporario").attr('hidden','hidden');
        }else{
            $("#titulopopfunc").html('Adicionar Permissões Temporárias')
            $("#camposdados").attr('hidden','hidden');
            $("#campotemporario").removeAttr('hidden');
            $("#datainiciopopfunc").val(retornaDadosDataHora(new Date(),1)).focus();
            $("#dataterminopopfunc").val(retornaDadosDataHora(new Date(),1));
        }
    }else{
        $("#titulopopfunc").html('Novo Funcionário')
        $("#camposdados").removeAttr('hidden');
        $("#campotemporario").attr('hidden','hidden');
        blnencontrado=true;
    }

    if(blnencontrado==true){
        $("#pop-popfuncionario").addClass("active");
        $("#pop-popfuncionario").find(".popup").addClass("active");
    }else{
        limparCamposPopPopFuncionario();
    }
}
//Fechar pop-up Artigo
$("#pop-popfuncionario").find(".close-btn").on("click",function(){
    fecharPopPopFuncionario();
})
function fecharPopPopFuncionario(){
    $("#pop-popfuncionario").removeClass("active");
    $("#pop-popfuncionario").find(".popup").removeClass("active");
    $('#table-pop-popfuncionario').find('tbody').html('')
    limparCamposPopPopFuncionario();
}

function limparCamposPopPopFuncionario(){
    $('.temppopfunc').val('');
    $('#selectstatuspopfunc').val(1);
    $('#selectbloqueadopopfunc').val(0);
    $('#nomepopfunc').focus();
    $('#exibirpermtemp').attr('hidden','hidden');
    tabtemporarias.html('');
    divpermissoespopfunc.html('');
    confirmacaopopfunc = 0;
    idfuncionariopopfunc=0;
    permtemporaria=0;
}

function buscaPermissoesGerenciaveisPopFuncionario(){
    divpermissoespopfunc.html('');
    arrpermissoespopfunc = [];

    $.ajax({
        url: 'ajax/consultas/busca_funcionarios.php',
        method: 'POST',
        data: {tipo: 3},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{

            let idgrupo = 0;
            let divgrupo = '';
            let novoID = '';

            result.forEach(linha => {
                let espacoesq = 'margin-espaco-esq';
                let ckbanterior = 0;
                let title = '';

                if(idgrupo!=linha.IDGRUPO){
                    let novoIDGrupo = gerarID('.grpermfunc');
                    idgrupo = linha.IDGRUPO;

                    divpermissoespopfunc.append('<div class="grupo"><h4 class="titulo-grupo">'+linha.NOMEGRUPO+'</h4><div id="grpermfunc'+novoIDGrupo+'" class="flex grpermfunc" style="flex-wrap: wrap;"></div></div><br>')
                    divgrupo = $('#grpermfunc'+novoIDGrupo);
                    espacoesq = '';

                }else{
                    if(linha.INDIVIDUAIS==0){
                        //Se o grupo não for de permissões individuais, então se define o botão interior
                        ckbanterior = $('#'+novoID);
                    }
                }

                novoID = 'permfunc'+gerarID('.permfunc');

                title = linha.DESCRICAO;
                divgrupo.append('<div class="'+espacoesq+'"><input type="checkbox" id="'+novoID+'" class="permfunc" title="'+title+'"><label for="'+novoID+'" title="'+title+'"> '+linha.NOME+'</label></div>');

                arrpermissoespopfunc.push({idpermissao: linha.IDPERMISSAO, nomepermissao: linha.NOME, ckb:novoID, valor:0, diretor:linha.DIRETOR, substituto:0});

                let ckb = $('#'+novoID);

                adicionaEventoCheck(ckb,ckbanterior);

                if(linha.INDIVIDUAIS==1){
                    if(linha.DIRETOR==1){
                        adicionaEventoSubstituto(ckb)
                    }
                }
                
            });
        }
    });
}

function adicionaEventoSubstituto(ckb){
    let divperm = ckb.parent();
    let title = '';
    
    ckb.change(()=>{
        let index = arrpermissoespopfunc.findIndex(linha => linha.ckb == ckb.attr('id'));
        
        if(ckb.prop('checked')==true){
            title = 'Clique para atribuir ao cargo de '+arrpermissoespopfunc[index].nomepermissao+' substituto.';
            divperm.append('<div class="divsubstituto"><input type="checkbox" id="subst'+arrpermissoespopfunc[index].ckb+'" class="ckbsubstituto" title="'+title+'"><label for="subst'+arrpermissoespopfunc[index].ckb+'" title="'+title+'"> Substituto</label></div>');

            divperm.addClass('permissao-diretor');
            
            let ckbsubst = $('#subst'+arrpermissoespopfunc[index].ckb);

            ckbsubst.change(()=>{
                if(ckbsubst.prop('checked')==true){
                    arrpermissoespopfunc[index].substituto = 1;
                }else{
                    arrpermissoespopfunc[index].substituto = 0;
                }
            })
        }else{
            divperm.find('.divsubstituto').remove();
            divperm.removeClass('permissao-diretor');
            arrpermissoespopfunc[index].substituto = 0;
        }
    })
}

function adicionaEventoCheck(ckb,ckbanterior){
    ckb.change(()=>{
        let index = arrpermissoespopfunc.findIndex(linha => linha.ckb == ckb.attr('id'));
        
        if(ckb.prop('checked')==true){
            arrpermissoespopfunc[index].valor = 1;
            if(ckbanterior!=0){
                ckbanterior.prop('checked',true).trigger('change');
            }
        }else{
            arrpermissoespopfunc[index].valor = 0;
        }
        // console.log(arrpermissoespopfunc[index]);
    })
    
    if(ckbanterior!=0){
        ckbanterior.change(()=>{
            if(ckbanterior.prop('checked')==false){
                ckb.prop('checked',false).trigger('change');
            }
        })
    }
}

function buscaDadosPopFuncionario(){
    let blnencontrado = false;

    $.ajax({
        url: 'ajax/consultas/busca_funcionarios.php',
        method: 'POST',
        data: {tipo: 4, idfuncionario: idfuncionariopopfunc},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            $('#nomepopfunc').val(result[0].NOME);
            $('#usuariopopfunc').val(result[0].USUARIO);
            let rs = '';
            if(result[0].RSUSUARIO!=null){
                rs=result[0].RSUSUARIO;
            }
            $('#rspopfunc').val(rs);
            $('#cpfpopfunc').val(result[0].CPF).trigger('change');
            let rg = '';
            if(result[0].RG!=null){
                rg=result[0].RG;
            }
            $('#rgpopfunc').val(rg);
            $('#selectturnopopfunc').val(result[0].IDTURNO);
            let escala = 0;
            if(result[0].IDESCALA!=null){
                escala=result[0].IDESCALA;
            }
            $('#selectescalapopfunc').val(escala);
            $('#selectstatuspopfunc').val(result[0].STATUS);
            $('#selectbloqueadopopfunc').val(result[0].CONTABLOQUEADA);
            
            if(permtemporaria==0){
                buscaPermissoesPopFuncionario();
            }
            buscaPermissoesTemporariaPopFuncionario()

            blnencontrado = true;
        }
    });
    return blnencontrado;
}

function buscaPermissoesPopFuncionario(){

    $.ajax({
        url: 'ajax/consultas/busca_funcionarios.php',
        method: 'POST',
        data: {tipo: 10, idfuncionario: idfuncionariopopfunc},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(permissao => {
                let index = arrpermissoespopfunc.findIndex(linha => linha.idpermissao == permissao.IDPERMISSAO)
                if(index!=-1){
                    let ckb = $('#'+arrpermissoespopfunc[index].ckb);
                    ckb.prop('checked',true).trigger('change');
                }
                if(permissao.SUBSTITUTO==1 && permissao.DIRETOR==1){
                    let ckbsubst = $('#subst'+arrpermissoespopfunc[index].ckb);
                    ckbsubst.prop('checked',true).trigger('change');
                }
            });
        }
    });
}

function buscaPermissoesTemporariaPopFuncionario(){
    tabtemporarias.html('');

    $.ajax({
        url: 'ajax/consultas/busca_funcionarios.php',
        method: 'POST',
        data: {tipo: 10, idfuncionario: idfuncionariopopfunc, temporaria: 1},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            if(result.length>0){
                let blnHidden = false;
                if($('#divpermtermporarias').attr('hidden')!='hidden'){
                    blnHidden = true;
                }

                $('#exibirpermtemp').off('click');
                $('#exibirpermtemp').removeAttr('hidden');
                adicionaEventoExpandirDiv($('#popfuncionario'),$('#exibirpermtemp'),$('#divpermtermporarias'),blnHidden,'Exibir permissões temporárias ('+result.length+')','Ocultar permissões temporárias')

                result.forEach(permissao => {
                    let idbanco = permissao.ID;
                    let nomepermissao = permissao.NOMEPERMISSAO;
                    let descpermissao = permissao.DESCRICAOPERMISSAO;
                    let datainicio = retornaDadosDataHora(permissao.DATAINICIO,2);
                    let datatermino = retornaDadosDataHora(permissao.DATATERMINO,2);

                    let novoID = 'trpermtemp'+ gerarID('.trpermtemp')
                    tabtemporarias.append('<tr id="'+novoID+'" class="cor-fundo-comum-tr trpermtemp"><td class="tdbotoes"></td><td class="nowrap">'+nomepermissao+'</td><td class="nowrap">'+datainicio+'</td><td class="nowrap">'+datatermino+'</td><td>'+descpermissao+'</td></tr>');
                    adicionaEventoExcluirPermissaoTemporaria($('#'+novoID).find('.tdbotoes'),idbanco,'Excluir permissão temporária '+nomepermissao);
                });
    
            }else{
                $('#exibirpermtemp').attr('hidden','hidden').off('click');
            }

        }
    });
}

function adicionaEventoExcluirPermissaoTemporaria(tdbotoes,idbanco,title='Excluir permissão temporária'){
    tdbotoes.append('<button class="btnAcaoRegistro btnexcpermtemp" title="'+title+'"><img src="imagens/lixeira.png" class="imgBtnAcao"></button>');

    tdbotoes.find('.btnexcpermtemp').click(()=>{
        let confirmacao = confirm('Confirma a exclusão desta permissão temporária?\r\rEsta ação não poderá ser desfeita.');
        if(confirmacao==true){
            let dados = {
                tipo: 4,
                idbanco: idbanco
            }
            // console.log(dados)
            $.ajax({
                url: 'ajax/inserir_alterar/funcionarios_gerenciar.php',
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
                    inserirMensagemTela(result.OK);
                    buscaPermissoesTemporariaPopFuncionario();
                }
            });
        }
    })
}

$('#cpfpopfunc').focusout(()=>{
    let elementoVerificar = $('#cpfpopfunc')
    if(elementoVerificar.val().length>0){
        var retorno = validaCPF(elementoVerificar.val());
        if(retorno!==true){
            // retorno = alert('O CPF informado não é um número válido, por isso não será possível continuar')
            if(retorno !== true){
                mensagem = ("<li class = 'mensagem-aviso'> CPF inválido. </li>")
                inserirMensagemTela(mensagem)
            }
        }
    }
})

$('#salvarpopfunc').click(function(){
    if(verificaSalvarPopPopFuncionario()==true){
        salvarPopPopFuncionario();
    }
})

function verificaSalvarPopPopFuncionario(){
    let mensagem = '';
    let elementoVerificar = 0;

    if(permtemporaria==0){
        elementoVerificar = $('#nomepopfunc')
        if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            elementoVerificar.focus()
            mensagem = ("<li class = 'mensagem-aviso'> Nome do(a) funcionário(a) inválido! </li>")
            inserirMensagemTela(mensagem)
        }
    
        elementoVerificar = $('#usuariopopfunc')
        if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            if(mensagem==''){
                elementoVerificar.focus()
            }
            mensagem = ("<li class = 'mensagem-aviso'> Nome de usuário inválido! </li>")
            inserirMensagemTela(mensagem)
        }
    
        elementoVerificar = $('#cpfpopfunc')
        var retorno = validaCPF(elementoVerificar.val());
        if(retorno!==true){
            retorno = alert('O CPF informado não é um número válido, por isso não será possível continuar')
            if(retorno !== true){
                mensagem = ("<li class = 'mensagem-aviso'> CPF inválido. </li>")
                inserirMensagemTela(mensagem)
            }
        }
    
        elementoVerificar = $('#selectturnopopfunc')
        if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            if(mensagem==''){
                elementoVerificar.focus()
            }
            mensagem = ("<li class = 'mensagem-aviso'> Turno inválido! </li>")
            inserirMensagemTela(mensagem)
        }
    
        elementoVerificar = $('#selectstatuspopfunc')
        if(elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            if(mensagem==''){
                elementoVerificar.focus()
            }
            mensagem = ("<li class = 'mensagem-aviso'> Status inválido! </li>")
            inserirMensagemTela(mensagem)
        }
    
        elementoVerificar = $('#selectbloqueadopopfunc')
        if(elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            if(mensagem==''){
                elementoVerificar.focus()
            }
            mensagem = ("<li class = 'mensagem-aviso'> Situação inválida! </li>")
            inserirMensagemTela(mensagem)
        }
    }else{
        elementoVerificar = $('#datainiciopopfunc')
        if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            elementoVerificar.focus()
            mensagem = ("<li class = 'mensagem-aviso'> Data início da permissão inválida! </li>")
            inserirMensagemTela(mensagem)
        }
        elementoVerificar = $('#dataterminopopfunc')
        if(elementoVerificar.val()==0 || elementoVerificar.val()==null || elementoVerificar.val()==NaN){
            if(mensagem==''){
                elementoVerificar.focus()
            }
            mensagem = ("<li class = 'mensagem-aviso'> Data término da permissão inválida! </li>")
            inserirMensagemTela(mensagem)
        }

        //Verifica as datas do intervalo
        if(mensagem==""){
            if(retornaDiferencaDeDataEHora(retornaDadosDataHora(new Date(),1),$('#datainiciopopfunc').val(),1)<0){
                $('#datainiciopopfunc').focus()
                mensagem = ("<li class = 'mensagem-aviso'> Data início é menor que a data de hoje. Não é possível inserir permissões temporárias com data retroativa! </li>")
                inserirMensagemTela(mensagem)
            }else{
                if(retornaDiferencaDeDataEHora($('#datainiciopopfunc').val(),$('#dataterminopopfunc').val(),1)<0){
                    $('#datainiciopopfunc').focus()
                    mensagem = ("<li class = 'mensagem-aviso'> Data início é maior que a data término! </li>")
                    inserirMensagemTela(mensagem)
                }    
            }
        }

        //Verifica se tem permissões selecionadas
        let blnperm = arrpermissoespopfunc.findIndex((perm)=>perm.valor==1);
        if(blnperm==-1){
            mensagem = ("<li class = 'mensagem-aviso'> Nenhuma permissão foi selecionada </li>")
            inserirMensagemTela(mensagem)
        }
    }
    
    if(mensagem!=''){
        return false;
    }else{
        return true;
    }

}

function salvarPopPopFuncionario(){
    
    let nome = $("#nomepopfunc").val();
    let usuario = $("#usuariopopfunc").val();
    let rs = $("#rspopfunc").val();
    let cpf = $("#cpfpopfunc").val();
    let rg = $("#rgpopfunc").val();
    let idturno = $("#selectturnopopfunc").val();
    let idescala = $("#selectescalapopfunc").val();
    let idstatus = $("#selectstatuspopfunc").val();
    let idsituacao = $("#selectbloqueadopopfunc").val();
    let datainicio = $("#datainiciopopfunc").val();
    let datatermino = $("#dataterminopopfunc").val();

    let arrenviar = [];

    if(permtemporaria==1){
        arrpermissoespopfunc.forEach(perm => {
            if(perm.valor==1){
                arrenviar.push({idpermissao: perm.idpermissao, valor:perm.valor, diretor:perm.diretor, substituto:perm.substituto});
            }
        });
    }else{
        arrenviar = arrpermissoespopfunc;
    }

    let dados = {
        tipo: 1,
        idfuncionario: idfuncionariopopfunc,
        nome: nome,
        usuario: usuario,
        rs: rs,
        cpf: cpf,
        rg: rg,
        idturno: idturno,
        idescala: idescala,
        idstatus: idstatus,
        idsituacao: idsituacao,
        temporaria: permtemporaria,
        datainicio: datainicio,
        datatermino: datatermino,
        arrpermissoes: arrenviar
    }

    //console.log(dados);

    $.ajax({
        url: 'ajax/inserir_alterar/funcionarios_gerenciar.php',
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
            if(result.MSGCONFIR){
                if(confirm(result.MSGCONFIR)==true){
                    confirmacaopopfunc = result.CONFIR;
                    idfuncionariopopfunc = result.IDMOV;
                };
                confirmacaopopfunc = 0;
                idfuncionariopopfunc = 0;
            }else{
                inserirMensagemTela(result.OK);
                if(idfuncionariopopfunc!=0){
                    fecharPopPopFuncionario();
                }else{
                    limparCamposPopPopFuncionario();
                }
                if($('#table-todosfuncionarios').length>0){
                    atualizaListaFuncionarios();
                }
            }
        }
    });
}

//Máscara especial para o RS do Funcionário, com a quantidade de dígitos delimitada
$('.num-rsusuario').mask('99999990');
