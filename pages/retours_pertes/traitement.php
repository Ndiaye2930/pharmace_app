<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_medicament = $_POST['id_medicament'];
    $type = $_POST['type'];
    $quantite = $_POST['quantite'];
    $commentaire = $_POST['commentaire'] ?? null;

    $stmt = $pdo->prepare("INSERT INTO retours_pertes (id_medicament, type, quantite, commentaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_medicament, $type, $quantite, $commentaire]);

    if ($type !== 'retour_client') {
        $pdo->prepare("UPDATE medicaments SET stock = stock - ? WHERE id = ?")
             ->execute([$quantite, $id_medicament]);
    }

    header("Location: liste.php?success=1");
}
