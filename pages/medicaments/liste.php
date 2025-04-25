<?php
require_once '../../config/database.php';

$meds = $pdo->query("SELECT * FROM medicaments ORDER BY date_expiration ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Médicaments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Liste des Médicaments</h3>
    <a href="add.php" class="btn btn-success mb-3">Ajouter un médicament</a>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Code barre</th>
            <th>Stock</th>
            <th>Stock_min</th>
            <th>Emplacement</th>
            <th>Date d'expiration</th>
            <th>Prix de vente</th>
            <th>Prix d'achat</th>
            <th>Date d'ajout</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($meds as $med): ?>
            <tr>
                <td><?= $med['id'] ?></td>
                <td><?= $med['nom'] ?></td>
                <td><?= $med['description'] ?></td>
                <td><?= $med['code_barre'] ?></td>
                <td><?= $med['stock'] ?> </td>
                <td><?= $med['stock_min'] ?> </td>
                <td><?= $med['emplacement'] ?> </td>
                <td><?= $med['date_expiration'] ?></td>
                <td><?= $med['prix_vente'] ?> </td>
                <td><?= $med['prix_achat'] ?> </td>
                <td><?= $med['date_ajout'] ?> </td>


                <td>
                    <a href="edit.php?id=<?= $med['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
