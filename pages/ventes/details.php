<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$id = $_GET['id'] ?? null;
$vente = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT v.*, u.nom, u.prenom FROM ventes v JOIN users u ON v.id_caissier = u.id WHERE v.id = ?");
    $stmt->execute([$id]);
    $vente = $stmt->fetch();

    $details = $pdo->prepare("SELECT vd.*, m.nom FROM vente_details vd JOIN medicaments m ON vd.id_medicament = m.id WHERE vd.id_vente = ?");
    $details->execute([$id]);
    $items = $details->fetchAll();
}
?>

<div class="container mt-5">
    <h2>Détails de la Vente #<?= $vente['id'] ?? '' ?></h2>
    <p><strong>Caissier : </strong><?= $vente['prenom'] . ' ' . $vente['nom'] ?></p>
    <p><strong>Date : </strong><?= $vente['date_vente'] ?></p>
    <p><strong>Total : </strong><?= $vente['total'] ?> FCFA</p>

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Médicament</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item['nom'] ?></td>
                    <td><?= $item['quantite'] ?></td>
                    <td><?= $item['prix_unitaire'] ?> FCFA</td>
                    <td><?= $item['quantite'] * $item['prix_unitaire'] ?> FCFA</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
