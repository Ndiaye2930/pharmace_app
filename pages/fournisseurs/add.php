<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];

    $stmt = $pdo->prepare("INSERT INTO fournisseurs (nom, telephone, email, adresse) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $telephone, $email, $adresse]);
    header("Location: liste.php");
    exit();
}
?>

<div class="container mt-5">
    <h3>Ajouter un fournisseur</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" name="telephone" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <textarea name="adresse" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="liste.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
