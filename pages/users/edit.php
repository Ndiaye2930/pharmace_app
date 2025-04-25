<?php
require_once '../../config/database.php';

$id = $_GET['id'];
$user = $pdo->query("SELECT * FROM users WHERE id = $id")->fetch();
$roles = ['Admin', 'Pharmacien', 'Caissier', 'Livreur'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $statut = $_POST['statut'];

    $photo = $user['photo'];
    if (!empty($_FILES['photo']['name'])) {
        $target = "../../uploads/" . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        $photo = $target;
    }

    $stmt = $pdo->prepare("UPDATE users SET nom=?, prenom=?, email=?, role=?, statut=?, photo=? WHERE id=?");
    $stmt->execute([$nom, $prenom, $email, $role, $statut, $photo, $id]);

    header("Location: liste.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Modifier un utilisateur</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col">
                <label>Nom</label>
                <input type="text" name="nom" required value="<?= $user['nom'] ?>" class="form-control">
            </div>
            <div class="col">
                <label>Prénom</label>
                <input type="text" name="prenom" required value="<?= $user['prenom'] ?>" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required value="<?= $user['email'] ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>Rôle</label>
            <select name="role" class="form-select">
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r ?>" <?= $user['role'] == $r ? 'selected' : '' ?>><?= $r ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Statut</label>
            <select name="statut" class="form-select">
                <option value="actif" <?= $user['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                <option value="inactif" <?= $user['statut'] == 'inactif' ? 'selected' : '' ?>>Inactif</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Photo actuelle</label><br>
            <?php if ($user['photo']): ?>
                <img src="<?= $user['photo'] ?>" width="80" height="80" class="rounded-circle mb-2">
            <?php endif; ?>
            <input type="file" name="photo" class="form-control">
        </div>
        <button class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
</body>
</html>
