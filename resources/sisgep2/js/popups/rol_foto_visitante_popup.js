
let strCPFfoto = 0;
let idvisitantepopfotovisitante = 0;

$("#openpopfotovisitante").on("click",function(){
    abrirPopFotoVisitante();
});

function abrirPopFotoVisitante(){
    limparCamposPopFotoVisitante();
    if(idvisitantepopfotovisitante>0){
        buscaDadosPopFotoVisitante();
        $("#pop-popfotovisitante").addClass("active");
        $("#pop-popfotovisitante").find(".popup").addClass("active");
        $('#cpfpopfotovisitante').focus();
    }
}
//Fechar pop-up Artigo
$("#pop-popfotovisitante").find(".close-btn").on("click",function(){
    fecharPopFotoVisitante();
})
function fecharPopFotoVisitante(){
    $("#pop-popfotovisitante").removeClass("active");
    $("#pop-popfotovisitante").find(".popup").removeClass("active");
    idvisitantepopfotovisitante = 0;
    strCPFfoto = 0;
}

$('#cancelarpopfotovisitante').click(()=>{
    fecharPopFotoVisitante();
})

function limparCamposPopFotoVisitante(){
    $('.divscanvas').attr('hidden','hidden');
    $('.htmltemppopfotovisitante').html('');
    $('.btnsacaopopfotovisitante').attr('hidden','hidden').off('click');
    $('#uploader').val('');
}

function buscaDadosPopFotoVisitante(){
    let result = consultaBanco('rol_busca_gerenciar',{tipo: 4, idvisitante: idvisitantepopfotovisitante});
    if(result.MENSAGEM){
        inserirMensagemTela(result.MENSAGEM);
    }else{
        if(result.length){
            $('#datacadastropopfotovisitante').html(retornaDadosDataHora(result[0].DATACADASTRO,12));
            $('#nomevisitantepopfotovisitante').html(result[0].NOME);
            strCPFfoto = result[0].CPF;
            atualizaFotoPopFotoVisitante()
        }
    }
}

function atualizaFotoPopFotoVisitante(){
    $('#fotovisitapopfotovisitante').attr('src', 'imagens/sem-foto.png');
    $.ajax({
        url: 'ajax/consultas/baixa_foto_servidor.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {tipo:2, cpfvisitante: strCPFfoto},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        // console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            let timestamp = '?t=' + new Date().getTime();
            $('#fotovisitapopfotovisitante').attr('src', result.OK + timestamp);
        }
    }); 
}

const preview = $('#preview')[0];
preview.width = 340;
preview.height = 480;

const reader = new FileReader();
const img = new Image();

const carregarImagem = (e)=>{
    reader.onload = ()=>{
        img.onload = ()=>{
            $('.btnsacaopopfotovisitante').attr('hidden','hidden').off('click');
            $('#divcanvas').html('<canvas id="canvas"></canvas>');
            const canvas = $('#canvas')[0];
            const ctx = canvas.getContext('2d');
            $('#divimgoriginal').removeAttr('hidden');

            //Limpar preview
            let ctxpreview = preview.getContext('2d');
            ctxpreview.clearRect(0, 0, preview.width, preview.height);

            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img,0,0);
            $('#divimgpreview').attr('hidden','hidden');

            $('#canvas').Jcrop({
                onChange: updatePreview,
                onSelect: updatePreview,
                allowSelect: true,
                allowMove: true,
                allowResize: true,
                aspectRatio: 3/4
            });
            
        };
        img.src = reader.result;
    };
    reader.readAsDataURL(e.target.files[0]);
};

const imageLoader = $('#uploader')[0];
imageLoader.addEventListener('change',carregarImagem);

function downloadFotoVisitante(){
    const image = preview.toDataURL();
    const link = document.createElement('a');
    link.href = image;
    link.download = strCPFfoto+'.jpg';
    link.click();
}

function updatePreview(c) {
    if (parseInt(c.w) > 0) {
        // Show image preview
        var imageObj = $("#canvas")[0];
        var canvas = $("#preview")[0];
        var context = canvas.getContext("2d");
        
        // console.log(c)
        if (imageObj != null && c.w != 0 && c.h != 0) {
            context.drawImage(imageObj, Math.floor(c.x), Math.floor(c.y), Math.floor(c.w), Math.floor(c.h), 0, 0, canvas.width, canvas.height);
        }

        inserirCPFFoto(canvas);

        if($('#divimgpreview').attr('hidden')=='hidden'){
            $('#divimgpreview').removeAttr('hidden');
        }

        if($('#baixar').attr('hidden')=='hidden'){
            $('#baixar').removeAttr('hidden');
            $('#baixar').click(()=>{
                downloadFotoVisitante();
            });
        }

        if($('#salvarfotovisitante').attr('hidden')=='hidden'){
            $('#salvarfotovisitante').removeAttr('hidden');
            
            $('#salvarfotovisitante').click(()=>{
                salvarPopFotoVisisante();
            });
        }
    }
}

function inserirCPFFoto(canvas) {
    var fontSizeCPF = 30;
    var color = 'white';
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
    ctx.fillRect(canvas.width / 2 - i / 2,canvas.height - 30 - (fontSizeCPF * 1.5) / 2, i, (fontSizeCPF * 1.5) );
    ctx.font = fontSizeCPF.toString() + "px monospace";
    ctx.fillStyle = color;
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";

    ctx.fillText(strCPFfoto, canvas.width / 2, canvas.height - 30);
}

function salvarPopFotoVisisante(){

    var fotos = [];
    // var canvas = document.querySelectorAll('.foto');
    // for(var i=0;i<canvas.length;i++){
    //     fotos.push({
    //         nome: retornaSomenteNumeros("429.712.118-27"),
    //         imagem: canvas[i].toDataURL()
    //     })
    // }

    var canvas = $('#preview')[0];
    fotos.push({
        nome: 1,
        imagem: canvas.toDataURL()
    })

    // console.log(fotos);

    $.ajax({
        url: 'ajax/inserir_alterar/salvar_foto_servidor.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {tipo: 2, cpfvisitante: strCPFfoto, fotos: fotos},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json',
        async: false
    }).done(function(result){
        //console.log(result)

        if(result.MENSAGEM){
            inserirMensagemTela(result.MENSAGEM);
        }else{
            inserirMensagemTela(result.OK);
            if($('#fotovisita1').length>0){
                atualizaFotoVisitante();
            }
            fecharPopFotoVisitante();
        }
    });
}