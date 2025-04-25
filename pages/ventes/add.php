<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$medicaments = $pdo->query("SELECT * FROM medicaments WHERE stock > 0 ORDER BY nom ASC")->fetchAll();
?>

<div class="container mt-5">
    <h2>Nouvelle Vente</h2>

    <form method="POST" action="traitement_vente.php">
        <div id="vente-items">
            <div class="row mb-3 vente-item">
                <div class="col-md-6">
                    <label>Médicament</label>
                    <select name="medicaments[]" class="form-control" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($medicaments as $med): ?>
                            <option value="<?= $med['id'] ?>" data-prix="<?= $med['prix_vente'] ?>">
                                <?= $med['nom'] ?> (<?= $med['stock'] ?> en stock)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Quantité</label>
                    <input type="number" name="quantites[]" class="form-control" min="1" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-item">Supprimer</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary" id="add-item">Ajouter un médicament</button>
        <br><br>
        <button type="submit" class="btn btn-success">Valider la vente</button>
    </form>
</div>

<script>
document.getElementById('add-item').addEventListener('click', function () {
    let clone = document.querySelector('.vente-item').cloneNode(true);
    clone.querySelectorAll('input').forEach(el => el.value = '');
    document.getElementById('vente-items').appendChild(clone);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        let items = document.querySelectorAll('.vente-item');
        if (items.length > 1) {
            e.target.closest('.vente-item').remove();
        }
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>
