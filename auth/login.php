<?php
session_start();
require_once '../config/database.php';

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND statut = 'actif'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'role' => $user['role'],
            'photo' => $user['photo']
        ];
        header("Location: ../dashboard.php");
    } else {
        $erreur = "Email ou mot de passe incorrect ou compte inactif.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card p-4 shadow" style="min-width: 400px;">
    <h4 class="mb-3 text-center">Connexion à la pharmacie</h4>
    <?php if ($erreur): ?>
        <div class="alert alert-danger"><?= $erreur ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Mot de passe</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <button class="btn btn-primary w-100">Connexion</button>
    </form>
    <p class="text-center mt-3"><a href="register.php">Créer un compte</a></p>
</div>
</body>
</html>
