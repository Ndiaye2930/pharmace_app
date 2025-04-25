<?php
require_once '../config/database.php';

$roles = ['Admin', 'Pharmacien', 'Caissier', 'Livreur'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Upload photo
    $photo = "";
    if (!empty($_FILES['photo']['name'])) {
        $target = "../uploads/" . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photo = $target;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $password, $role, $photo]);

    $message = "Compte créé avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card p-4 shadow" style="min-width: 500px;">
    <h4 class="mb-3 text-center">Créer un compte utilisateur</h4>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Prénom</label>
            <input type="text" name="prenom" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Rôle</label>
            <select name="role" class="form-select">
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r ?>"><?= $r ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Mot de passe</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Photo de profil</label>
            <input type="file" name="photo" class="form-control">
        </div>
        <button class="btn btn-success w-100">Créer</button>
    </form>
    <p class="text-center mt-3"><a href="login.php">Déjà inscrit ? Se connecter</a></p>
</div>
</body>
</html>
