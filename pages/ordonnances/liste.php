<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Récupérer toutes les ordonnances
$sql = "SELECT o.*, c.nom, c.prenom FROM ordonnances o
        JOIN clients c ON c.id = o.id_client
        ORDER BY o.date_upload DESC";
$stmt = $pdo->query($sql);
$ordonnances = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h3 class="mb-4">📑 Liste des Ordonnances</h3>
    <a href="add.php" class="btn btn-success mb-3">➕ Ajouter une ordonnance</a>

    <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Fichier</th>
                <th>Date Upload</th>
                <th>Action</th>
                <th>Médicaments</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($ordonnances) > 0): ?>
            <?php foreach ($ordonnances as $index => $ord): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($ord['prenom'] . ' ' . $ord['nom']) ?></td>
                    <td><?= htmlspecialchars(basename($ord['fichier'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($ord['date_upload'])) ?></td>
                    <td>
                        <a href="../../uploads/ordonnances/<?= urlencode($ord['fichier']) ?>" target="_blank" class="btn btn-primary btn-sm">
                            📂 Voir
                        </a>
                        <a href="../../uploads/ordonnances/<?= urlencode($ord['fichier']) ?>" download class="btn btn-success btn-sm">
                            ⬇ Télécharger
                        </a>
                    </td>
                    <td>
                        <a href="details.php?id=<?= $ord['id'] ?>" class="btn btn-info btn-sm">🧠 Voir</a>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center text-muted">Aucune ordonnance enregistrée.</td></tr>
        <?php endif ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
