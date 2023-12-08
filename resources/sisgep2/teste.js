let nome = $('#nomeunidade').html();
// nome = 'TESTE';
let nomecss = '';

for(let i=0;i<nome.length;i++){
    let letra = nome.substr(i,1);
    if(letra==' '){
        letra = '&nbsp';
    }
    nomecss += '<li style="--i-nomeunidade:'+(i+1)+'">'+letra+'</li>';
}
console.log(nomecss);
$('#nomeunidade').html('');
$('#ulnomeunidade').html(nomecss);

