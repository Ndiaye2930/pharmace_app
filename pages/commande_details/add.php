<?php
require_once '../../includes/db.php';
require_once '../../includes/header.php';

$commandes = $pdo->query("SELECT id FROM commandes")->fetchAll();
$medicaments = $pdo->query("SELECT id, nom FROM medicaments")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_commande = $_POST['id_commande'];
    $id_medicament = $_POST['id_medicament'];
    $quantite = $_POST['quantite'];
    $prix_achat = $_POST['prix_achat'];

    $stmt = $pdo->prepare("INSERT INTO commande_details (id_commande, id_medicament, quantite, prix_achat) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_commande, $id_medicament, $quantite, $prix_achat]);

    header("Location: liste.php");
    exit();
}
?>

<div class="container mt-5">
    <h3>Ajouter un détail de commande</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Commande</label>
            <select name="id_commande" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($commandes as $c): ?>
                    <option value="<?= $c['id'] ?>">Commande #<?= $c['id'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Médicament</label>
            <select name="id_medicament" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($medicaments as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantité</label>
            <input type="number" name="quantite" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prix d'achat</label>
            <input type="number" name="prix_achat" class="form-control" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="liste.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
