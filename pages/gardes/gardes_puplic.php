<?php
require_once '../../config/database.php';

$gardes = $pdo->query("
    SELECT g.*, u.nom, u.prenom 
    FROM gardes g 
    JOIN users u ON g.id_pharmacien = u.id 
    WHERE g.date_garde >= CURDATE()
    ORDER BY g.date_garde ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pharmacies de Garde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">📅 Pharmacies de Garde à venir</h2>

    <table class="table table-bordered" id="gardes-table">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Pharmacien</th>
                <th>Type</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($gardes as $garde): ?>
            <tr>
                <td><?= htmlspecialchars($garde['date_garde']) ?></td>
                <td><?= htmlspecialchars($garde['nom']) . ' ' . htmlspecialchars($garde['prenom']) ?></td>
                <td><?= $garde['is_nuit'] ? 'Nuit' : 'Jour' ?></td>
                <td><?= htmlspecialchars($garde['commentaire']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <button class="btn btn-success" onclick="exportPDF()">📄 Télécharger PDF</button>
</div>

<script>
function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Liste des gardes à venir", 10, 10);
    let y = 20;
    const rows = document.querySelectorAll('#gardes-table tbody tr');
    rows.forEach((row, index) => {
        const cols = row.querySelectorAll('td');
        const text = [...cols].map(col => col.innerText).join(" | ");
        doc.text(text, 10, y);
        y += 10;
    });
    doc.save("gardes.pdf");
}
</script>
</body>
</html>
