<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$fournisseurs = $pdo->query("SELECT * FROM fournisseurs")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_fournisseur = $_POST['id_fournisseur'];
    $date_commande = $_POST['date_commande'];
    $etat = $_POST['etat'];

    $stmt = $pdo->prepare("INSERT INTO commandes (id_fournisseur, date_commande, etat) VALUES (?, ?, ?)");
    $stmt->execute([$id_fournisseur, $date_commande, $etat]);

    header("Location: liste.php");
    exit();
}
?>

<div class="container mt-5">
    <h3>Nouvelle commande</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="id_fournisseur" class="form-label">Fournisseur</label>
            <select name="id_fournisseur" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($fournisseurs as $f): ?>
                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="date_commande" class="form-label">Date de commande</label>
            <input type="date" name="date_commande" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="etat" class="form-label">État</label>
            <select name="etat" class="form-select" required>
                <option value="en attente">En attente</option>
                <option value="livrée">Livrée</option>
                <option value="annulée">Annulée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Valider</button>
        <a href="liste.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
