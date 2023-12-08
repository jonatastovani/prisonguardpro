<?php

$img_r = imagecreatefromjpeg($_FILES['img']['tmp_name']);
$dst_r = ImageCreateTrueColor( $_GET['w'], $_GET['h'] );
$img = "imagens/".$_FILES['img']['name'];

imagecopyresampled($dst_r, $img_r, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);


if(!imagejpeg($dst_r, $img,100)){
    die("Erro ao salvar sua imagem");
}else{
    echo "Sua imagem foi salva com sucesso";
}