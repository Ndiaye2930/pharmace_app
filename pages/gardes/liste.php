<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$sql = "SELECT g.*, u.nom, u.prenom FROM gardes g
        JOIN users u ON g.id_pharmacien = u.id
        ORDER BY date_garde DESC";
$gardes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="mb-4">Liste des gardes</h2>
    <a href="add.php" class="btn btn-primary mb-3">Nouvelle garde</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Pharmacien</th>
                <th>Garde de nuit ?</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($gardes as $g): ?>
                <tr>
                    <td><?= htmlspecialchars($g['date_garde']) ?></td>
                    <td><?= htmlspecialchars($g['nom'] . ' ' . $g['prenom']) ?></td>
                    <td><?= $g['is_nuit'] ? 'Oui' : 'Non' ?></td>
                    <td><?= htmlspecialchars($g['commentaire']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
