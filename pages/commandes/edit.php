<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: liste.php");
    exit();
}

$id = $_GET['id'];
$commande = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
$commande->execute([$id]);
$data = $commande->fetch();

$fournisseurs = $pdo->query("SELECT * FROM fournisseurs")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_fournisseur = $_POST['id_fournisseur'];
    $date_commande = $_POST['date_commande'];
    $etat = $_POST['etat'];

    $update = $pdo->prepare("UPDATE commandes SET id_fournisseur=?, date_commande=?, etat=? WHERE id=?");
    $update->execute([$id_fournisseur, $date_commande, $etat, $id]);

    header("Location: liste.php");
    exit();
}
?>

<div class="container mt-5">
    <h3>Modifier une commande</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Fournisseur</label>
            <select name="id_fournisseur" class="form-select" required>
                <?php foreach ($fournisseurs as $f): ?>
                    <option value="<?= $f['id'] ?>" <?= ($f['id'] == $data['id_fournisseur']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Date de commande</label>
            <input type="date" name="date_commande" class="form-control" value="<?= $data['date_commande'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">État</label>
            <select name="etat" class="form-select" required>
                <option value="en attente" <?= $data['etat'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
                <option value="livrée" <?= $data['etat'] === 'livrée' ? 'selected' : '' ?>>Livrée</option>
                <option value="annulée" <?= $data['etat'] === 'annulée' ? 'selected' : '' ?>>Annulée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
        <a href="liste.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
