<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$medicaments = $pdo->query("SELECT * FROM medicaments ORDER BY nom ASC")->fetchAll();
?>

<div class="container mt-5">
    <h2>Ajouter un retour ou une perte</h2>

    <form method="POST" action="traitement.php">
        <div class="mb-3">
            <label for="id_medicament" class="form-label">Médicament</label>
            <select name="id_medicament" id="id_medicament" class="form-control" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($medicaments as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= $m['nom'] ?> (<?= $m['stock'] ?> en stock)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">-- Choisir --</option>
                <option value="retour_client">Retour Client</option>
                <option value="perte">Perte</option>
                <option value="expiration">Expiration</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" name="quantite" id="quantite" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label for="commentaire" class="form-label">Commentaire (facultatif)</label>
            <textarea name="commentaire" id="commentaire" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
