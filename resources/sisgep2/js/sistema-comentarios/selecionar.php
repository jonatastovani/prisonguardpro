<?php
    header('Content-Type: application/json');

    $pdo = new PDO('mysql:host=10.14.239.102; dbname=sistemaphp;', 'jonatas', 'jon123');

    $stmt = $pdo->prepare('SELECT * FROM teste');
    $stmt->execute();

    if ($stmt->rowCount() >= 1) {
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        echo json_encode('Nenhum comentário encontrado');
    }