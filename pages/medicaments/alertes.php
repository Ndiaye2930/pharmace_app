<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

$stmtStock =  $pdo->query("SELECT * FROM medicaments WHERE stock <= stock_min");
$stmtExpire =  $pdo->query("SELECT * FROM medicaments WHERE date_expiration BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 15 DAY)");
?>

<div class="container mt-5">
  <h2 class="mb-4">🚨 Alertes Stock & Expiration</h2>

  <div class="card mb-4">
    <div class="card-header bg-danger text-white">⚠️ Stock critique</div>
    <div class="card-body">
      <?php if ($stmtStock->rowCount() > 0): ?>
        <ul class="list-group">
          <?php while($row = $stmtStock->fetch(PDO::FETCH_ASSOC)): ?>
            <li class="list-group-item d-flex justify-content-between">
              <?= $row['nom'] ?> 
              <span class="badge bg-danger"><?= $row['stock'] ?> unités</span>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p>Aucun stock critique.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-warning">📅 Médicaments expirant bientôt</div>
    <div class="card-body">
      <?php if ($stmtExpire->rowCount() > 0): ?>
        <ul class="list-group">
          <?php while($row = $stmtExpire->fetch(PDO::FETCH_ASSOC)): ?>
            <li class="list-group-item d-flex justify-content-between">
              <?= $row['nom'] ?> 
              <span class="badge bg-warning"><?= $row['date_expiration'] ?></span>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p>Aucune expiration proche.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
