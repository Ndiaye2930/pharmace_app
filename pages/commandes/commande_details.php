<?php
// Exemple de récupération (à adapter selon votre logique)
require_once '../../config/database.php';
$id_commande = $_GET['id'] ?? 0;

// Requête pour les infos commande + fournisseur
$commande = $pdo->query("
    SELECT c.*, f.nom AS fournisseur_nom
    FROM commandes c
    JOIN fournisseurs f ON f.id = c.id_fournisseur
    WHERE c.id = $id_commande
")->fetch(PDO::FETCH_ASSOC);

// Requête pour les médicaments commandés
$details = $pdo->query("
    SELECT m.nom, cd.quantite, cd.prix_achat
    FROM commande_details cd
    JOIN medicaments m ON m.id = cd.id_medicament
    WHERE cd.id_commande = $id_commande
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détails commande</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Bon de commande #<?= $commande['id'] ?></h4>
    </div>
    <div class="card-body">
      <p><strong>Fournisseur :</strong> <span id="fournisseurs"><?= htmlspecialchars($commande['fournisseurs']) ?></span></p>
      <p><strong>Date de commande :</strong> <span id="date_commande"><?= $commande['date_commande'] ?></span></p>

      <table class="table table-bordered mt-4" id="table_commandes">
        <thead class="table-secondary">
          <tr>
            <th>Médicament</th>
            <th>Quantité</th>
            <th>Prix d'achat (FCFA)</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($details as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['nom']) ?></td>
            <td><?= $d['quantite'] ?></td>
            <td><?= number_format($d['prix_achat'], 2, ',', ' ') ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

      <button class="btn btn-success mt-3" onclick="generatePDF()">📄 Télécharger le bon de commande</button>
    </div>
  </div>
</div>

<!-- Script jsPDF -->
<script>
  async function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const fournisseur = document.querySelector('#fournisseur_nom').textContent;
    const dateCommande = document.querySelector('#date_commande').textContent;

    doc.setFontSize(14);
    doc.text("Bon de commande", 80, 20);
    doc.setFontSize(12);
    doc.text(`Fournisseur : ${fournisseur}`, 10, 30);
    doc.text(`Date : ${dateCommande}`, 10, 38);

    let startY = 50;
    doc.setFontSize(12);
    doc.text("Détails :", 10, startY);
    startY += 10;

    const rows = document.querySelectorAll('#table_commandes tbody tr');
    doc.setFontSize(10);
    rows.forEach((row, i) => {
      const cells = row.querySelectorAll('td');
      let ligne = `${i + 1}. ${cells[0].textContent} | Qté: ${cells[1].textContent} | Prix: ${cells[2].textContent} FCFA`;
      doc.text(ligne, 10, startY);
      startY += 8;
    });

    doc.save(`bon_commande_<?= $commande['id'] ?>.pdf`);
  }
</script>

</body>
</html>
