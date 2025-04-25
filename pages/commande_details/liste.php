<?php
require_once '../../includes/db.php';
require_once '../../includes/header.php';

$stmt = $pdo->query("SELECT cd.*, m.nom AS medicament_nom, c.id AS commande_id 
                     FROM commande_details cd
                     JOIN medicaments m ON cd.id_medicament = m.id
                     JOIN commandes c ON cd.id_commande = c.id
                     ORDER BY cd.id DESC");
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Détails des commandes</h3>
        <a href="add.php" class="btn btn-success">+ Ajouter un détail</a>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Commande</th>
                <th>Médicament</th>
                <th>Quantité</th>
                <th>Prix Achat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cd = $stmt->fetch()): ?>
                <tr>
                    <td><?= $cd['id'] ?></td>
                    <td>#<?= $cd['id_commande'] ?></td>
                    <td><?= htmlspecialchars($cd['medicament_nom']) ?></td>
                    <td><?= $cd['quantite'] ?></td>
                    <td><?= number_format($cd['prix_achat'], 2) ?> FCFA</td>
                    <td>
                        <a href="edit.php?id=<?= $cd['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="edit.php?id=<?= $cd['id'] ?>" class="btn btn-danger btn-sm">supprimer</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
