<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/sidebar.php'; // Sidebar ici
require_once 'config/database.php';

// Statistiques générales
$ventes = $pdo->query("SELECT COUNT(*) AS total_ventes FROM ventes")->fetch();
$ca = $pdo->query("SELECT SUM(total) AS chiffre_affaires FROM ventes")->fetch();
$ruptures = $pdo->query("SELECT COUNT(*) AS rupture FROM medicaments WHERE stock <= stock_min")->fetch();
$perimes = $pdo->query("SELECT COUNT(*) AS perimes FROM medicaments WHERE date_expiration <= CURDATE()")->fetch();

// Récupérer les ventes des 7 derniers jours
$ventes_par_jour = $pdo->query("
    SELECT DATE(date_vente) AS jour, COUNT(*) AS total
    FROM ventes
    WHERE date_vente >= CURDATE() - INTERVAL 6 DAY
    GROUP BY jour
    ORDER BY jour
")->fetchAll();

// Préparation des tableaux pour Chart.js
$labels = [];
$datas = [];

$jours_map = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = $jours_map[date('w', strtotime($date))];
    $datas[$date] = 0;
}

foreach ($ventes_par_jour as $row) {
    $datas[$row['jour']] = (int) $row['total'];
}
?>

<div class="container mt-5">
  <h2 class="mb-4"><i class="fas fa-chart-line"></i> Tableau de Bord</h2>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-gradient-primary text-success">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="mb-1">💰 Chiffre d'affaires</h6>
              <h4><?= number_format($ca['chiffre_affaires'], 2, ',', ' ') ?> FCFA</h4>
            </div>
            <i class="fas fa-coins fa-2x"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-success text-white">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="mb-1">🧾 Total Ventes</h6>
              <h4><?= $ventes['total_ventes'] ?></h4>
            </div>
            <i class="fas fa-shopping-cart fa-2x"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-warning text-dark">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="mb-1">⚠️ Ruptures</h6>
              <h4><?= $ruptures['rupture'] ?></h4>
            </div>
            <i class="fas fa-box-open fa-2x"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-danger text-white">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="mb-1">⏳ Périmés</h6>
              <h4><?= $perimes['perimes'] ?></h4>
            </div>
            <i class="fas fa-skull-crossbones fa-2x"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Graphique -->
  <div class="card shadow-sm mt-5">
    <div class="card-header bg-white fw-bold">
      <i class="fas fa-chart-area me-2"></i> Ventes par jour (7 derniers jours)
    </div>
    <div class="card-body">
      <canvas id="ventesChart" height="100"></canvas>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ventesChart').getContext('2d');
const ventesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Ventes',
            data: <?= json_encode(array_values($datas)) ?>,
            fill: true,
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            borderColor: '#007bff',
            borderWidth: 2,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
