<?php
    header('Content-Type: application/json');

    $name = $_POST['name'];
    $contato = $_POST['contato'];

    $pdo = new PDO('mysql:host=10.14.239.102; dbname=sistemaphp;', 'jonatas', 'jon123');

    $stmt = $pdo->prepare('INSERT INTO teste (nome, contato) VALUES (:na, :co)');
    $stmt->bindValue(':na', $name);
    $stmt->bindValue(':co', $contato);
    $stmt->execute();

    if ($stmt->rowCount() >= 1) {
        echo json_encode('Comentário Salvo com Sucesso');
    } else {
        echo json_encode('Falha ao salvar comentário');
    }