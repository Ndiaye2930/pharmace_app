<?php
require_once '../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meds = $_POST['medicaments'];
    $qtes = $_POST['quantites'];

    $total = 0;
    $vente_id = null;
    $id_caissier = $_SESSION['user']['id'];

    $pdo->beginTransaction();

    try {
        $pdo->prepare("INSERT INTO ventes (id_caissier, total) VALUES (?, 0)")->execute([$id_caissier]);
        $vente_id = $pdo->lastInsertId();

        for ($i = 0; $i < count($meds); $i++) {
            $id_med = $meds[$i];
            $qte = $qtes[$i];

            $stmt = $pdo->prepare("SELECT prix_vente, stock FROM medicaments WHERE id = ?");
            $stmt->execute([$id_med]);
            $med = $stmt->fetch();

            if ($med && $med['stock'] >= $qte) {
                $prix = $med['prix_vente'];
                $total += $prix * $qte;

                $pdo->prepare("INSERT INTO vente_details (id_vente, id_medicament, quantite, prix_unitaire) VALUES (?, ?, ?, ?)")
                     ->execute([$vente_id, $id_med, $qte, $prix]);

                $pdo->prepare("UPDATE medicaments SET stock = stock - ? WHERE id = ?")
                     ->execute([$qte, $id_med]);
            }
        }

        $pdo->prepare("UPDATE ventes SET total = ? WHERE id = ?")->execute([$total, $vente_id]);
        $pdo->commit();
        header('Location: liste.php?success=1');
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
}
