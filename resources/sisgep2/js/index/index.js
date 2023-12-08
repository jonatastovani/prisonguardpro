
$('#form1').submit(function(e){
    //No evento de submit do botão, não permitirá recarregar a página
    e.preventDefault();

    var login_usuario = $('#usuario').val();
    var login_senha = $('#senha').val();
    $("#erros").html('');

    //console.log(login_usuario, login_senha)
    $.ajax({
        url: 'ajax/consultas/verifica_usuario_senha.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {usuario: login_usuario, senha: login_senha},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //console.log(result);
        if(result.ERRO){
            $("#erros").html(result.ERRO);
        }else{
            window.location.assign('principal.php');
        }
    });
});

/*
function getComments() {
    $.ajax({
        url: 'selecionar.php',
        method: 'GET',
        dataType: 'json'
    }).done(function(result){
        console.log(result);

        for (var i = 0; i < result.length; i++) {
            $('.box_comment').prepend('<div class="b_comm"><h4>' + result[i].name + '</h4><p>' + result[i].comment + '</p></div>');
        }
    });
}

getComments();*/