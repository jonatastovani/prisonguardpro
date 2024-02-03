let idPhoto = 0;
let arrHeaderData = [];
let idImgUpdate = '';
let pathFolder = '';

// $(document).ready(function(){

    function openPopPhoto(arrInfoPopPhoto){

        arrHeaderData = arrInfoPopPhoto[0].arrHeaderData;
        idPhoto = arrInfoPopPhoto[0].idPhoto;
        idImgUpdate = arrInfoPopPhoto[0].idImgUpdate;
        pathFolder = arrInfoPopPhoto[0].pathFolder;

        clearPopPhoto();
        if(idPhoto>0){
            fillHeaderDataPopPhoto();
            $("#pop-popPhoto").addClass("active");
            $("#pop-popPhoto").find(".popup").addClass("active");
        }
    }
    //Fechar pop-up Artigo
    $("#pop-popPhoto").find(".close-btn").on("click",function(){
        closePopPhoto();
    })
    function closePopPhoto(){
        $("#pop-popPhoto").removeClass("active");
        $("#pop-popPhoto").find(".popup").removeClass("active");
        arrHeaderData = [];
        idPhoto = 0;
        idImgUpdate = '';
        pathFolder = '';
    }

    $('#cancelPopPhoto').click(()=>{
        closePopPhoto();
    })

    function clearPopPhoto(){
        $('.divscanvasPopPhoto').attr('hidden','hidden');
        $('.htmlTempPopPhoto').html('');
        $('.btnsActionPopPhoto').attr('hidden','hidden').off('click');
        $('#uploaderPopPhoto').val('');
    }

    function fillHeaderDataPopPhoto(){
        let title = arrHeaderData[0].title?arrHeaderData[0].title:'Seleção de Foto';

        let htmlHeader = '<div class="row"><div class="col-12"><div class="card"><div class="card-body">';

        arrHeaderData[0].fields.forEach(element => {
            htmlHeader += '<p><b>'+element.label+':</b> '+element.info+'</p>';
        });

        htmlHeader += '</div></div></div></div>';

        $('#titlePopPhoto').html(title);
        $('#headerData').html(htmlHeader);
        
        buscaFoto(`${pathFolder}/${idPhoto}`,'#photoPopPhoto');
    }

    const preview = $('#preview')[0];
    preview.width = 340;
    preview.height = 480;

    const reader = new FileReader();
    const img = new Image();

    const loadImage = (e)=>{
        reader.onload = ()=>{
            img.onload = ()=>{
                $('.btnsActionPopPhoto').attr('hidden','hidden').off('click');
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

    const imageLoader = $('#uploaderPopPhoto')[0];
    imageLoader.addEventListener('change',loadImage);

    function downloadPopPhoto(){
        const image = preview.toDataURL();
        const link = document.createElement('a');
        link.href = image;
        link.download = idPhoto+'.jpg';
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

            if($('#divimgpreview').attr('hidden')=='hidden'){
                $('#divimgpreview').removeAttr('hidden');
            }

            if($('#downloadPhoto').attr('hidden')=='hidden'){
                $('#downloadPhoto').removeAttr('hidden');
                $('#downloadPhoto').click(()=>{
                    downloadPopPhoto();
                });
            }

            if($('#savePhoto').attr('hidden')=='hidden'){
                $('#savePhoto').removeAttr('hidden');
                
                $('#savePhoto').click(()=>{
                    savePopPhoto();
                });
            }
        }
    }

    document.onkeydown = function(e) {
        if(e.key === 'Escape') {
            if($('#pop-popPhoto').find(".popup.active").length==1){
                $("#pop-popPhoto").find(".close-btn").trigger('click');
            }
        }
    }

    function savePopPhoto(){

        var photo = [];
        var canvas = $('#preview')[0];
        photo.push({
            name: idPhoto,
            image: canvas.toDataURL()
        })

        $.ajax({
            url: "../api/savePopPhotos.php",
            method:"POST",
            data: {action: 'insert', pathFolder: pathFolder, photos: photo},
            dataType: "json",
            success:function(data) {

                let close = false;

                if(data.success==1 || data.success==2){
                    if (data.success==1) {
                        alert('Foto(s) enviada(s) com sucesso!');
                        close = true;
                        buscaFoto(`${pathFolder}/${idPhoto}`,idImgUpdate);
                    } else {
                        let message = 'Um ou mais fotos não obtveram sucesso no salvamento:\n' + data.message;
                        alert(message);
                        console.log(message);
                    }

                    // if (data.paths.length) {
                    //     let index = data.paths.findIndex((element)=>element.name==idPhoto);

                    //     data.paths.forEach(path => {
                    //         if (path.name == idPhoto && path.pathFile!='') {
                    //             let timestamp = '?t=' + new Date().getTime();
                    //             idImgUpdate!=''?$(idImgUpdate).attr('src',`${path.pathFile}${timestamp}`):'';
                    //         }
                    //     });

                    // }

                    close==true?closePopPhoto():'';

                } else {
                    let message = 'Uma ou mais fotos não obtveram sucesso no salvamento:\n' + data.message;
                    alert(message);
                    console.log(message);
                    
                }
            },
            error: function (error) {
                console.log(error)
                alert('Erro no salvamento. Tente novamente mais tarde, se o problema persistir, consulte o desenvolvedor.');
            }
        });
    }

    function deletePopPhoto(arrData){

        let pathFolder = arrData[0].pathFolder;
        let arrIdsPhotos = arrData[0].idsPhotos;
        let idImg = arrData[0].idImg;

        $.ajax({
            url: "../api/savePopPhotos.php",
            method:"POST",
            data: {action: 'delete', pathFolder: pathFolder, arrIdsPhotos: arrIdsPhotos},
            dataType: "json",
            success:function(data) {

                if (data.success==1) {
                    alert(data.message);
                    buscaFoto(`${pathFolder}/${arrIdsPhotos[0]}`,idImg);
                } else if (data.success==2) {
                    alert(data.message);
                    console.log(data.message);
                }

            },
            error: function (error) {
                console.log(error)
                alert('Erro no salvamento. Tente novamente mais tarde, se o problema persistir, consulte o desenvolvedor.');
            }
        });
    }

