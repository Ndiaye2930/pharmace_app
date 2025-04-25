<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$stmt = $pdo->query("SELECT c.*, f.nom AS fournisseur_nom 
                     FROM commandes c 
                     JOIN fournisseurs f ON c.id_fournisseur = f.id
                     ORDER BY c.date_commande DESC");
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Liste des commandes</h3>
        <a href="add.php" class="btn btn-success">+ Nouvelle commande</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Fournisseur</th>
                <th>Date</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($c = $stmt->fetch()): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['fournisseur_nom']) ?></td>
                    <td><?= $c['date_commande'] ?></td>
                    <td><span class="badge bg-info"><?= ucfirst($c['etat']) ?></span></td>
                    <td>
                        <a href="edit.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
