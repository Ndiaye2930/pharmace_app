<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">Liste des Ventes</h2>
    <a href="add.php" class="btn btn-primary mb-3">Ajouter un utilisateur</a>


    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Caissier</th>
                <th>Date</th>
                <th>Total</th>
                <th>Détails</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $stmt = $pdo->query("SELECT v.*, u.nom, u.prenom 
                              FROM ventes v 
                              JOIN users u ON v.id_caissier = u.id 
                              ORDER BY v.date_vente DESC");
        while ($row = $stmt->fetch()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['prenom']} {$row['nom']}</td>
                    <td>{$row['date_vente']}</td>
                    <td>{$row['total']} FCFA</td>
                    <td><a href='details.php?id={$row['id']}' class='btn btn-sm btn-info'>Voir</a></td>
                </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
