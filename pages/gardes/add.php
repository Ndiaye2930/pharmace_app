<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$pharmaciens = $pdo->query("SELECT * FROM users WHERE role = 'Pharmacien'")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date_garde'];
    $pharmacien = $_POST['id_pharmacien'];
    $nuit = isset($_POST['is_nuit']) ? 1 : 0;
    $commentaire = $_POST['commentaire'];

    $stmt = $conn->prepare("INSERT INTO gardes (date_garde, id_pharmacien, is_nuit, commentaire)
                            VALUES (?, ?, ?, ?)");
    $stmt->execute([$date, $pharmacien, $nuit, $commentaire]);

    header("Location: liste.php");
}
?>

<div class="container mt-5">
    <h2>Nouvelle garde</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="date_garde" class="form-label">Date de garde</label>
            <input type="date" name="date_garde" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_pharmacien" class="form-label">Pharmacien</label>
            <select name="id_pharmacien" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($pharmaciens as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= $p['nom'] . ' ' . $p['prenom'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_nuit" class="form-check-input" id="nuit">
            <label for="nuit" class="form-check-label">Garde de nuit</label>
        </div>
        <div class="mb-3">
            <label for="commentaire" class="form-label">Commentaire</label>
            <textarea name="commentaire" class="form-control"></textarea>
        </div>
        <button class="btn btn-success" type="submit">Enregistrer</button>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
