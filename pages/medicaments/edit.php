<?php
require_once '../../config/database.php';

$id = $_GET['id'];
$med = $pdo->query("SELECT * FROM medicaments WHERE id = $id")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $categorie = $_POST['categorie'];
    $quantite = $_POST['quantite'];
    $prix = $_POST['prix'];
    $date_expiration = $_POST['date_expiration'];

    $stmt = $pdo->prepare("UPDATE medicaments SET nom=?, categorie=?, quantite=?, prix=?, date_expiration=? WHERE id=?");
    $stmt->execute([$nom, $categorie, $quantite, $prix, $date_expiration, $id]);

    header("Location: liste.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Médicament</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Modifier un Médicament</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" value="<?= $med['nom'] ?>" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Catégorie</label>
            <input type="text" name="categorie" value="<?= $med['categorie'] ?>" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Quantité</label>
            <input type="number" name="quantite" value="<?= $med['quantite'] ?>" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Prix unitaire (FCFA)</label>
            <input type="number" name="prix" value="<?= $med['prix'] ?>" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Date d’expiration</label>
            <input type="date" name="date_expiration" value="<?= $med['date_expiration'] ?>" required class="form-control">
        </div>
        <button class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
</body>
</html>
