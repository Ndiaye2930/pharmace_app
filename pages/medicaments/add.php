<?php
require_once '../../config/database.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $code_barre = $_POST['code_barre'];
    $stock = $_POST['stock_min'];
    $stock_min = $_POST['stock_min'];
    $emplacement = $_POST['emplacement'];
    $date_expiration = $_POST['date_expiration'];
    $prix_vente = $_POST['prix_vente'];
    $prix_achat = $_POST['prix_achat']; 
    $date_ajout = $_POST['date_ajout'];   
    



    $stmt = $pdo->prepare("INSERT INTO medicaments (nom, description, code_barre, stock, stock_min, emplacement, date_expiration, prix_vente, prix_achat, date_ajout) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $description, $code_barre, $stock, $stock_min, $emplacement, $date_expiration, $prix_vente,$prix_achat,$date_ajout]);

    $message = "Médicament ajouté avec succès.";
    // Redirection vers la page de liste des ordonnances
header("Location: liste.php");
exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Médicament</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Ajouter un Médicament</h3>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <input type="text" name="description" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Code barre</label>
            <input type="varchar" name="code_barre" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Stock_min</label>
            <input type="number" name="stock_min" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Emplacement</label>
            <input type="text" name="emplacement" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Date d’expiration</label>
            <input type="date" name="date_expiration" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Prix de vente</label>
            <input type="number" name="prix_vente" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Prix d’achat</label>
            <input type="number" name="prix_achat" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Date d'ajout</label>
            <input type="date" name="date_ajout" required class="form-control">
        </div>
        <button class="btn btn-success">Ajouter</button>
    </form>
</div>
</body>
</html>
