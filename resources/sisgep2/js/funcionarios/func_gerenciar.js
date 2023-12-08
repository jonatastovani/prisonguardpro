const tabela = $('#table-todosfuncionarios').find('tbody');

atualizaListagemComum('busca_funcionarios',{tipo: 2},0,$('#selectturno'),false,false,false,false,true,'Todos os turnos');

function atualizaListaFuncionarios(){

    tabela.html('');
    let idturno = $('#selectturno').val();
    let status = 'X';
    if($('#rbbativos').prop('checked')==true){
        status = 1;
    }else if($('#rbbinativos').prop('checked')==true){
        status = 0;
    }
    let textobusca = $('#textobusca').val();

    let dados = {
        tipo: 1,
        idturno: idturno,
        idescala: 1,
        status: status,
        textobusca: textobusca
    }
   //console.log(dados);

    $.ajax({
        url: 'ajax/consultas/busca_funcionarios.php',
        method: 'POST',
        data: dados,
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        // console.log(result);

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM)
        }else{
            result.forEach(dados => {
                let usuario = dados.USUARIO;
                let rsusuario = dados.RSUSUARIO;
                let nome = dados.NOME;
                let apelido = '';
                if(dados.APELIDO!=null){
                    apelido = dados.APELIDO;
                }
                let rg = '';
                if(dados.RG!=null){
                    rg = dados.RG;
                }
                let cpf = retornaCPFFormatado(dados.CPF);
                let turno = dados.TURNO;
                let escala = '';
                if(dados.ESCALA!=null){
                    escala = dados.ESCALA;
                }
                let bloqueada = dados.BLOQUEADA;
                let status = dados.STATUS;

                let cor = 'cor-fundo-comum-tr';
                if(dados.IDSTATUS==0){
                    cor = 'cor-inativo';
                }
                let novoID = gerarID('.funcionario');

                let linha = '<tr id="tr'+novoID+'" class="funcionario '+cor+'"><td class="centralizado tdbotoes" style="min-width: 55px;"></td><td class="min-width-350">'+nome+'</td><td class="centralizado min-width-100">'+turno+'</td><td class="centralizado min-width-250">'+escala+'</td><td class="centralizado">'+bloqueada+'</td><td class="centralizado">'+status+'</td></tr>';
                tabela.append(linha);

                inserirBotaoAlterarFuncionario($('#tr'+novoID).find('.tdbotoes'),dados.IDUSUARIO)
                inserirBotaoPermissaoTemporaria($('#tr'+novoID).find('.tdbotoes'),dados.IDUSUARIO)
            });
        }
    });
}

function adicionaEventoPesquisaFuncionarios(){
    let seletores = [];
    seletores.push(['#rbtodos','click']);
    seletores.push(['#rbbativos','click']);
    seletores.push(['#rbbinativos','click']);
    seletores.push(['#pesquisar','click']);
    seletores.push(['#selectturno','change'])
    seletores.push(['#textobusca','enter'])

    seletores.forEach(linha => {
        if(linha[1]=='change'){
            $(linha[0]).on(linha[1], (e)=>{
                atualizaListaFuncionarios();
            })
        }else if(linha[1]=='enter'){
            $(linha[0]).keydown(linha[1], (e)=>{
                let key = e.which || e.keyCode;
                if(key==13){
                    atualizaListaFuncionarios();
                }
            })
        }else if(linha[1]=='click'){
            $(linha[0]).click(()=>{
                atualizaListaFuncionarios();
            })
        }
    });
}

adicionaEventoPesquisaFuncionarios();
atualizaListaFuncionarios();
