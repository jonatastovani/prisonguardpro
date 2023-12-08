const videoElement = document.getElementById('cam1');
const matric = $('#matric').val();
const idpreso = $('#idpreso').val();

function startVideoFromCamera(){
    const specs = {video:{
        width: 340,
        height:460
    }}
    
    navigator.mediaDevices.getUserMedia(specs).then(stream=>{
        videoElement.srcObject = stream
        $('#tirarfoto').focus();

    }).catch(error=>{
        alert('A câmera não foi iniciada. Erro: '+error)
        $('#salvar').remove();
        $('#tirarfoto').remove();
    })
}

$('#tirarfoto').click(()=>{
    var id = retornaIDFoto();
    var canvas = document.getElementById(id);
    var divlink = $('#'+canvas.id).parent().find('.linkfoto');
    canvas.height = videoElement.videoHeight;
    canvas.width = videoElement.videoWidth;
    var context = canvas.getContext('2d')
    context.drawImage(videoElement, 0, 0);

    //Escrever matrícula na imagem
    inserirMatriculaFoto(canvas);
    adicionaEventoExcluirFoto(id);
    /*
    context.font = "30px Arial";
    context.fillStyle = "black";
    context.textAlign = "center";
    context.fillText("Hello World", canvas.width/2, canvas.height-20);
*/
    //Download
    var link = document.createElement('a');
    link.download = id+'.jpg';
    link.href = canvas.toDataURL();
    link.textContent = 'Clique para baixar a imagem';
    divlink.html(link);
    //document.body.appendChild(link)

})

function retornaIDFoto(){
    if($('#frontal').prop('checked')==true){
        return criarCampoFoto('foto1');
    }
    else if($('#perfildir').prop('checked')==true){
        return criarCampoFoto('foto2');
    }
    else if($('#perfilesq').prop('checked')==true){
        return criarCampoFoto('foto3');
    }
    else if($('#adicionais').prop('checked')==true){
        return criarCampoFoto('adicionais');
    }
}

function criarCampoFoto(id){
    
    if(id!='adicionais'){
        $('#'+id).parent().removeAttr('hidden')
        return id;
    }else{
        var novoID = gerarID('.fotosadicionais');
        $('#fotostiradas').append('<div class="grupo relative"><h4 class="titulo-grupo">Adicional '+novoID+'</h4><canvas id="adicional'+novoID+'" class="fotosadicionais"></canvas><div class="linkfoto"></div><button class="fechar-absolute">&times;</button></div>')
        return 'adicional'+novoID;
    }
}

// function gerarIDFoto(){
//     var fotos = $('.fotosadicionais')
//     var id = 1;
//     if(fotos.length!=0){
//         id = parseInt(retornaSomenteNumeros($('#'+fotos[fotos.length-1].id).attr('id')))+1;
//     }
//     return id;
// }

function inserirMatriculaFoto(canvas) {
    var stringmatricula = midMatricula(matric,3);
    var fontSizematricula = 40;
    var color = 'white';
    var stringdados = 'ID Preso: '+idpreso+' Data: '+retornaDadosDataHora(new Date(),12)+' ';
    var fontSizedados = 10;
    var ctx = canvas.getContext('2d')

    var i = canvas.width;
    //Colocar faixa de fundo somente atás das letras
    /*var i = string.length;
    i = i*fontSize*0.62;
    if (i > canvas.width) {
      i = canvas.width;
    }*/

    ctx.fillStyle = "RGBA(0, 0, 0, 0.8)"; // Fundo preto
    //ctx.fillStyle = "RGBA(255, 255, 255, 0.8)"; // Fundo branco
    //ctx.fillRect(canvas.width / 2 - i / 2,canvas.height / 2 - (fontSize * 1.5) / 2, i, (fontSize * 1.5) ); // Centralização da faixa
    ctx.fillRect(canvas.width / 2 - i / 2,canvas.height - 40 - (fontSizematricula * 1.5) / 2, i, (fontSizematricula * 1.5) );
    ctx.font = fontSizematricula.toString() + "px monospace";
    ctx.fillStyle = color;
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";

    ctx.fillText(stringmatricula, canvas.width / 2, canvas.height - 40);

    ctx.font = fontSizedados.toString() + "px monospace";
    ctx.textAlign = "right";
    
    ctx.fillText(stringdados, canvas.width, canvas.height - 15);
}

function adicionaEventoExcluirFoto(id){
    let pai = $('#'+id).parent();
    pai.find('.fechar-absolute').on('click',()=>{
        pai.remove();
    })
}

$('#salvar').click(()=>{

    var fotos = [];
    var canvas = document.querySelectorAll('.foto');
    for(var i=0;i<canvas.length;i++){
        fotos.push({
            nome: retornaSomenteNumeros(canvas[i].id),
            imagem: canvas[i].toDataURL()
        })
    }

    var canvas = document.querySelectorAll('.fotosadicionais');
    for(var i=0;i<canvas.length;i++){
        fotos.push({
            nome: 'adicionais'+(i+1),
            imagem: canvas[i].toDataURL()
        })
    }
    console.log(fotos);

    $.ajax({
        url: 'ajax/inserir_alterar/salvar_foto_servidor.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {matric: matric, idpreso: idpreso, tipo: 1, fotos: fotos},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            inserirMensagemTela(result.OK);
            setTimeout(() => {
                window.close()
            }, 1000);
        }
    });
})

window.addEventListener('DOMContentLoaded', startVideoFromCamera());
