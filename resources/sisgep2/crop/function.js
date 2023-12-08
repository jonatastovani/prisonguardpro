$(document).ready(function(){

    $('#img').change(function(){
        readUrl(this);
    })

    function readUrl(input){
        if(input.files && input.files[0]){

            var reader = new FileReader();
            reader.onload = function(e){
                $('#cropbox').attr("src", e.target.result);
                var size;
                $('#cropbox').Jcrop({
                    aspectRatio: 1,
                    onSelect: function(c){
                        size = {x:c.x,y:c.y,w:c.w,h:c.h};
                        // $("#crop").css("visibility", "visible");     
                    }
                });
                
                $('#upload').on('submit', (function(e){
                    e.preventDefault();
                    $.ajax({
                        url:'upload.php?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&img='+img,
                        type:'POST',
                        cache:false,
                        processData:false,
                        contentType:false,
                        data: new FormData(this),
                        success: function(data){
                            $('#mensagens').html(data);
                        },
                        error: function(){
                            $('#mensagens').html('Erro ao salvar a imagem!')
                        }
                    });
                }))
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

}); 