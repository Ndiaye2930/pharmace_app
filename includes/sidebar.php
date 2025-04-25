<?php

require_once './config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ./auth/login.php");
    exit();
}

$user = is_array($_SESSION['user']) ? $_SESSION['user'] : json_decode($_SESSION['user'], true);

// Récupérer les 5 dernières notifications non lues
$notifs = $pdo->prepare("SELECT * FROM notifications WHERE id_user = ? AND vue = 0 ORDER BY date_notif DESC LIMIT 5");
$notifs->execute([$user['id']]);
$notifications = $notifs->fetchAll();
$countNotifs = count($notifications);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-end text-white" id="sidebar-wrapper" style="min-height: 100vh;">
        <div class="sidebar-heading p-3 border-bottom text-center">
            <strong>Pharmacie</strong>
        </div>
        <div class="list-group list-group-flush">
            <a href="./dashboard.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-home me-2"></i>Tableau de bord</a>
            <a href="./pages/users/liste.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-users me-2"></i>Utilisateurs</a>
            <a href="./pages/clients/liste.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-user-friends me-2"></i>Clients</a>
            <a href="./pages/medicaments/liste.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-pills me-2"></i>Médicaments</a>
            <a href="./pages/ventes/liste.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-cash-register me-2"></i>Ventes</a>
            <a href="./pages/ordonnances/liste.php" class="list-group-item list-group-item-action bg-dark text-white"> <i class="fas fa-file-medical"></i> Ordonnances</a>
            <a href="./pages/retours_pertes/liste.php" class="list-group-item list-group-item-action bg-dark text-white"> <i class="fas fa-undo"></i> Retours et Pertes</a>
            <a href="./pages/commandes/liste.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-boxes me-2"></i>Commandes</a>
            <a href="./pages/fournisseurs/liste.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-truck me-2"></i>Fournisseurs</a>
            <a href="./pages/gardes/liste.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-moon me-2"></i>Gardes</a>
            <a href="./auth/logout.php" class="list-group-item list-group-item-action bg-danger text-white"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a>
        </div>
    </div>

    <!-- Page Content Wrapper -->
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-dark" id="menu-toggle"><i class="fas fa-bars"></i></button>

                <div class="ms-auto d-flex align-items-center">
                    <!-- Notifications -->
                    <div class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle position-relative text-dark" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <?php if ($countNotifs > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $countNotifs ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($countNotifs === 0): ?>
                                <li><span class="dropdown-item text-muted">Aucune notification</span></li>
                            <?php else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <li class="dropdown-item small">
                                        <strong><?= ucfirst(str_replace('_', ' ', $notif['type'])) ?></strong><br>
                                        <?= htmlspecialchars($notif['message']) ?><br>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($notif['date_notif'])) ?></small>
                                    </li>
                                <?php endforeach; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="./pages/notifications/liste.php">Voir toutes</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Nom utilisateur -->
                    <span class="fw-bold"><?= htmlspecialchars($user['prenom'] . " " . $user['nom']); ?></span>
                </div>
            </div>
        </nav>
