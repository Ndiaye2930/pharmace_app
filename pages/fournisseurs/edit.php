<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: liste.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM fournisseurs WHERE id = ?");
$stmt->execute([$id]);
$fournisseur = $stmt->fetch();

if (!$fournisseur) {
    header("Location: liste.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];

    $stmt = $pdo->prepare("UPDATE fournisseurs SET nom=?, telephone=?, email=?, adresse=? WHERE id=?");
    $stmt->execute([$nom, $telephone, $email, $adresse, $id]);
    header("Location: liste.php");
    exit();
}
?>

<div class="container mt-5">
    <h3>Modifier un fournisseur</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" value="<?= $fournisseur['nom'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" value="<?= $fournisseur['telephone'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?= $fournisseur['email'] ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Adresse</label>
            <textarea name="adresse" class="form-control"><?= $fournisseur['adresse'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
        <a href="liste.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
