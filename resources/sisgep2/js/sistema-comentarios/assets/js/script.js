
$('#form1').submit(function(e){
    //No evento de submit do botão, não permitirá recarregar a página
    e.preventDefault();

    var u_name = $('#name').val();
    var u_comment = $('#comment').val();

    //console.log(u_name, u_comment);
    $.ajax({
        url: 'inserir.php',
        method: 'POST',
        //Insere os dados no data. key: value. Pode fazer o data fora daqui e depois inserir a variável para atribuir no data.
        data: {name: u_name, contato:u_comment},
        //json é uma linguagem que ambos se entendem. Tanto javascript quanto PHP.
        dataType: 'json'
    }).done(function(result){
        //Quando retornar os dados já limpa os campos de nome e comentário
        $('#name').val('');
        $('#comment').val('');
        console.log(result);
        getComments();
    });
});

function getComments() {
    $.ajax({
        url: 'selecionar.php',
        method: 'GET',
        dataType: 'json'
    }).done(function(result){
        console.log(result);

        for (var i = 0; i < result.length; i++) {
            $('.box_comment').prepend('<div class="b_comm"><h4>' + result[i].nome + '</h4><p>' + result[i].contato + '</p></div>');
        }
    });
}

getComments();