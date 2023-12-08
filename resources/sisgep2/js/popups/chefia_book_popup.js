// Confirmação do retorno para salvamento das informações
let idraiopopbook = 0;
let celapopbook = 0;
let divpopocultarpopbook = 0;

const containerbook = $('#presosbook');

$("#openbook").on("click",function(){
    abrirPopBook();
});

function abrirPopBook(){
    divpopocultarpopbook.removeClass('visibilidadepop')
    $("#pop-book").addClass("active");
    $("#pop-book").find(".popup").addClass("active");
    atualizaBookPresos();
}

//Fechar pop-up Artigo
$("#pop-book").find(".close-btn").on("click",function(){
    fecharPopBook();
})
function fecharPopBook(){
    idraiopopbook=0;
    celapopbook=0;
    divpopocultarpopbook.addClass('visibilidadepop')

    $("#pop-book").removeClass("active");
    $("#pop-book").find(".popup").removeClass("active");
    $('#table-pop-book').find('tbody').html('')
    containerbook.html('');
}

function atualizaBookPresos(){
    $('.temppopbook').html('');

    if(idraiopopbook>0 && celapopbook>0){

        let dados = {
            tipo: 8,
            idraio: idraiopopbook,
            cela: celapopbook
        }
        // console.log(dados);

        $.ajax({
            url: 'ajax/consultas/chefia_busca_gerenciar.php',
            method: 'POST',
            data: dados,
            //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
            dataType: 'json'
        }).done(function(result){
            console.log(result);

            if(result.MENSAGEM){
                inserirMensagemTela(result.MENSAGEM);
                fecharPopBook();
            }else{
                $('#book-titulo').html('Book Presos do Raio '+result[0].RAIO+' Cela '+result[0].CELA);
                $('#qtd-book').html(result.length+' preso(s) listado(s)');

                result.forEach(linha => {
                    let idpreso = linha.IDPRESO;
                    let nome = linha.NOME;
                    let matricula = linha.MATRICULA!=null?midMatricula(linha.MATRICULA,3):'Não Atribuída';
                    let pai = linha.PAI!=null?linha.PAI:'Não informado';
                    let mae = linha.MAE!=null?linha.MAE:'Não informado';
                    let cela = linha.RAIO + '/' + linha.CELA;
                    let entradacela = retornaDadosDataHora(linha.DATACADASTRO,12);
                    let dataentrada = linha.DATAENTRADA!='0000-00-00 00:00:00'?retornaDadosDataHora(linha.DATAENTRADA,2):'***';
                    let origem = linha.ORIGEM?linha.ORIGEM:'Preso não inserido';
                    let foto = linha.FOTO;

                    let novoID = gerarID('.presobook');
                    containerbook.append('<div id="presobook'+novoID+'" class="presobook grupo-block largura-total flex" data-idpreso="'+idpreso+'"><div class="divfotopreso"><img src="'+foto+'"></div><div class="grupo largura-restante" style="margin: 0;">Nome: <b>'+nome+'</b>;<br>Matrícula: <b>'+matricula+'</b>;<br>Cela: <b>'+cela+'</b>; Entrada na Cela: <b>'+entradacela+'</b>;<br>Data da Inclusão: <b>'+dataentrada+'</b>;<br>Origem: <b>'+origem+'</b>;<br>Pai: <b>'+pai+'</b>;<br>Mãe: <b>'+mae+'</b>;</div><div class="grupo centralizado" style="margin: 0;"><button id="novamudanca'+novoID+'" class="centralizado" title="Solicitar Mudança de Cela para o preso '+nome+'">Solic. Mudança Cela</button><br><button class="centralizado" id="novoatend'+novoID+'" title="Solicitar Atendimento para o preso '+nome+'">Solic. Atendimento</button></div></div>');
            
                    adicionaEventosBotoesPopBook(novoID,idpreso);
                });
            }
        });
    }
}

function adicionaEventosBotoesPopBook(idelemento,idpreso){
    
    $('#novamudanca'+idelemento).click(()=>{
        abrirPopNovaMudanca(idpreso);
    })

    $('#novoatend'+idelemento).click(()=>{
        abrirPopNovoAtend(idpreso);
    })
   
};
