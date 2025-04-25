<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $fidelite = isset($_POST['programme_fidelite']) ? 1 : 0;

    // Vérification des champs obligatoires
    if (empty($nom) || empty($prenom) || empty($telephone)) {
        $errors[] = "Les champs nom, prénom et téléphone sont obligatoires.";
    } else {
        // Vérifier si le téléphone existe déjà
        $check = $pdo->prepare("SELECT id FROM clients WHERE telephone = ?");
        $check->execute([$telephone]);

        if ($check->rowCount() > 0) {
            $errors[] = "Ce numéro de téléphone est déjà utilisé.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, telephone, email, programme_fidelite) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $telephone, $email, $fidelite]);

        header('Location: liste.php?success=1');
        exit;
    }
}
?>

<div class="container mt-5">
    <h3 class="text-primary mb-4">Ajouter un nouveau client</h3>

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
            <label for="nom" class="form-label">Nom *</label>
            <input type="text" class="form-control" name="nom" required>
        </div>
        <div class="col-md-6">
            <label for="prenom" class="form-label">Prénom *</label>
            <input type="text" class="form-control" name="prenom" required>
        </div>
        <div class="col-md-6">
            <label for="telephone" class="form-label">Téléphone *</label>
            <input type="text" class="form-control" name="telephone" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email (optionnel)</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="col-12 form-check">
            <input class="form-check-input" type="checkbox" name="programme_fidelite" id="fidelite">
            <label class="form-check-label" for="fidelite">
                Inscrire au programme de fidélité
            </label>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Enregistrer</button>
            <a href="liste.php" class="btn btn-secondary">Retour</a>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
