<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php'; // sidebar ou navbar commun
$users = $pdo->query("SELECT * FROM users ORDER BY date_creation DESC")->fetchAll();
?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <?php //require_once '../../includes/sidebar.php'; ?>

        <!-- Contenu principal -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
            <h3 class="mb-4">👤 Liste des utilisateurs</h3>
            <a href="add.php" class="btn btn-primary mb-3">Ajouter un utilisateur</a>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Nom & Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td>
                                    <?php if ($user['photo']): ?>
                                        <img src="<?= $user['photo'] ?>" alt="Photo" width="40" height="40" class="rounded-circle">
                                    <?php else: ?>
                                        <span class="text-muted">Aucune</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td><span class="badge bg-<?= $user['statut'] == 'actif' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($user['statut']) ?></span></td>
                                <td>
                                    <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                    <a href="edit.php?id=<?= $cd['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
