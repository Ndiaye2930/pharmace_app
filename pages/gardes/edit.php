<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: liste.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM gardes WHERE id = ?");
$stmt->execute([$id]);
$garde = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$garde) {
    echo "Garde introuvable.";
    exit;
}

$pharmaciens = $conn->query("SELECT * FROM users WHERE role = 'Pharmacien'")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date_garde'];
    $pharmacien = $_POST['id_pharmacien'];
    $nuit = isset($_POST['is_nuit']) ? 1 : 0;
    $commentaire = $_POST['commentaire'];

    $stmt = $conn->prepare("UPDATE gardes SET date_garde=?, id_pharmacien=?, is_nuit=?, commentaire=? WHERE id=?");
    $stmt->execute([$date, $pharmacien, $nuit, $commentaire, $id]);

    header("Location: liste.php");
}
?>

<div class="container mt-5">
    <h2>Modifier la garde</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="date_garde" class="form-label">Date de garde</label>
            <input type="date" name="date_garde" required class="form-control" value="<?= $garde['date_garde'] ?>">
        </div>
        <div class="mb-3">
            <label for="id_pharmacien" class="form-label">Pharmacien</label>
            <select name="id_pharmacien" class="form-select" required>
                <?php foreach ($pharmaciens as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $garde['id_pharmacien'] ? 'selected' : '' ?>>
                        <?= $p['nom'] . ' ' . $p['prenom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_nuit" class="form-check-input" id="nuit" <?= $garde['is_nuit'] ? 'checked' : '' ?>>
            <label for="nuit" class="form-check-label">Garde de nuit</label>
        </div>
        <div class="mb-3">
            <label for="commentaire" class="form-label">Commentaire</label>
            <textarea name="commentaire" class="form-control"><?= $garde['commentaire'] ?></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Mettre à jour</button>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
