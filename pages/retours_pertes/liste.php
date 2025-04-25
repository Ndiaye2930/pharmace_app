<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$retours = $pdo->query("
    SELECT rp.*, m.nom AS medicament 
    FROM retours_pertes rp 
    JOIN medicaments m ON rp.id_medicament = m.id 
    ORDER BY rp.date_enregistrement DESC
")->fetchAll();
?>

<div class="container mt-5">
    <h2>Liste des Retours et Pertes</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Enregistrement réussi.</div>
    <?php endif; ?>
    <a href="add.php" class="btn btn-primary mb-3">Ajouter un médicament</a>

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Médicament</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Date</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($retours as $r): ?>
                <tr>
                    <td><?= $r['medicament'] ?></td>
                    <td><?= ucfirst(str_replace('_', ' ', $r['type'])) ?></td>
                    <td><?= $r['quantite'] ?></td>
                    <td><?= $r['date_enregistrement'] ?></td>
                    <td><?= $r['commentaire'] ?: '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
