<html>
<head>
<title>How to Crop Image using jQuery</title>
<link rel="stylesheet" href="jquery.Jcrop.min.css" type="text/css" />
<script src="jquery-3.6.0.min.js"></script>
<script src="jquery.Jcrop.min.js"></script>
<script src="function.js"></script>

</head>
<body>

    <form id="upload">
        <div>
            <input type="file" id="img" name="img" accept="image/jpeg" required>
        </div>
        <br>

        <div>
            <img id="cropbox" class="img"/><br/>
        </div>
        <br>

        <div>
            <button type="submit" id="crop">Enviar Imagem</button>
        </div>
<!-- 
        <div>
            Canvas
            <br>
            <div>
                <canvas id="imagem"></canvas>
            </div> -->
        </div>
    </form>
    <div id="mensagens"></div>
</body>
</html>