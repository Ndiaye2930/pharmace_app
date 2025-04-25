<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Liste des fournisseurs</h3>
        <a href="add.php" class="btn btn-success">+ Ajouter un fournisseur</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM fournisseurs");
            while ($f = $stmt->fetch()) {
                echo "<tr>
                        <td>{$f['id']}</td>
                        <td>{$f['nom']}</td>
                        <td>{$f['telephone']}</td>
                        <td>{$f['email']}</td>
                        <td>{$f['adresse']}</td>
                        <td>
                            <a href='edit.php?id={$f['id']}' class='btn btn-warning btn-sm'>Modifier</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
