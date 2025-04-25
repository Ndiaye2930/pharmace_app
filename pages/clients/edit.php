<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: liste.php');
    exit;
}

$errors = [];

// Récupération du client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if (!$client) {
    echo "<div class='alert alert-danger mt-4'>Client introuvable.</div>";
    require_once '../../includes/footer.php';
    exit;
}

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $fidelite = isset($_POST['programme_fidelite']) ? 1 : 0;

    if (empty($nom) || empty($prenom) || empty($telephone)) {
        $errors[] = "Les champs nom, prénom et téléphone sont obligatoires.";
    } else {
        // Vérifie si un autre client a le même téléphone
        $check = $pdo->prepare("SELECT id FROM clients WHERE telephone = ? AND id != ?");
        $check->execute([$telephone, $id]);

        if ($check->rowCount() > 0) {
            $errors[] = "Ce numéro de téléphone est déjà utilisé par un autre client.";
        }
    }

    if (empty($errors)) {
        $update = $pdo->prepare("UPDATE clients SET nom = ?, prenom = ?, telephone = ?, email = ?, programme_fidelite = ? WHERE id = ?");
        $update->execute([$nom, $prenom, $telephone, $email, $fidelite, $id]);
        header("Location: liste.php?updated=1");
        exit;
    }
}
?>

<div class="container mt-5">
    <h3 class="text-warning mb-4">Modifier le client</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nom *</label>
            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Prénom *</label>
            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Téléphone *</label>
            <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($client['telephone']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email (optionnel)</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($client['email']) ?>">
        </div>
        <div class="col-12 form-check">
            <input class="form-check-input" type="checkbox" name="programme_fidelite" id="fidelite" <?= $client['programme_fidelite'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="fidelite">
                Programme de fidélité
            </label>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Modifier</button>
            <a href="liste.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
