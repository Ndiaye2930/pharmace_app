<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php'; 
$roles = ['Admin', 'Pharmacien', 'Caissier', 'Livreur'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $statut = $_POST['statut'];

    $photo = "";
    if (!empty($_FILES['photo']['name'])) {
        $target = "../../uploads/" . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        $photo = $target;
    }

    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role, photo, statut) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $password, $role, $photo, $statut]);
    $message = "Utilisateur ajouté avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require_once '../../includes/sidebar.php'; ?>

        <!-- Contenu principal -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3 class="mb-4"> 👤 Ajouter un utilisateur</h3>
            <?php if ($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                        <label>Nom</label>
                        <input type="text" name="nom" required class="form-control">
                    </div>
                    <div class="col">
                        <label>Prénom</label>
                        <input type="text" name="prenom" required class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="password" required class="form-control">
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
                    <label>Statut</label>
                    <select name="statut" class="form-select">
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Photo</label>
                    <input type="file" name="photo" class="form-control">
                </div>
                <button class="btn btn-success">Ajouter</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
