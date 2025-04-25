<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Récupération des clients
$stmt = $pdo->query("SELECT * FROM clients ORDER BY id DESC");
$clients = $stmt->fetchAll();
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-success">Liste des clients</h3>
        <a href="add.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Ajouter un client</a>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">Client ajouté avec succès.</div>
    <?php elseif (isset($_GET['updated'])): ?>
        <div class="alert alert-info">Client modifié avec succès.</div>
    <?php endif; ?>

    <?php if (count($clients) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom complet</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Fidélité</th>
                        <th>Date d'ajout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $index => $c): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
                            <td><?= htmlspecialchars($c['telephone']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?: '<em>-</em>' ?></td>
                            <td><?= $c['programme_fidelite'] ? 'Oui' : 'Non' ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($c['date_creation'] ?? $c['id'])) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Aucun client trouvé.</div>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>
