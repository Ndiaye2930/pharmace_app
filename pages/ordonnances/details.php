<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once __DIR__ . '/vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Ordonnance non trouvée.</div>";
    exit;
}

$id_ordonnance = intval($_GET['id']);

// Récupérer infos ordonnance + fichier
$stmt = $pdo->prepare("SELECT o.*, c.nom, c.prenom FROM ordonnances o JOIN clients c ON c.id = o.id_client WHERE o.id = ?");
$stmt->execute([$id_ordonnance]);
$ordonnance = $stmt->fetch();

if (!$ordonnance) {
    echo "<div class='alert alert-warning'>Aucune ordonnance trouvée.</div>";
    exit;
}

$fichier = '../../uploads/ordonnances/' . $ordonnance['fichier'];

// Vérifier si déjà analysé
$stmt2 = $pdo->prepare("SELECT * FROM ordonnance_medicaments WHERE id_ordonnance = ?");
$stmt2->execute([$id_ordonnance]);
$deja_traite = $stmt2->rowCount();

if ($deja_traite === 0) {
    // 🔎 Analyse OCR de l’image
    $ocr = new TesseractOCR($fichier);
    $ocr->executable('C:\Program Files\Tesseract-OCR\tesseract.exe'); // adapte si autre chemin
    $texte = $ocr->run();

    // 📌 Extraction de médicaments depuis texte
    $produitsDetectes = [];
    $allProduits = $pdo->query("SELECT id, nom FROM produits")->fetchAll();

    foreach ($allProduits as $produit) {
        if (stripos($texte, $produit['nom']) !== false) {
            $produitsDetectes[] = $produit;
        }
    }

    // 📥 Insertion dans ordonnance_medicaments
    foreach ($produitsDetectes as $p) {
        $insert = $pdo->prepare("INSERT INTO ordonnance_medicaments (id_ordonnance, id_produit, quantite) VALUES (?, ?, ?)");
        $insert->execute([$id_ordonnance, $p['id'], 1]); // par défaut quantité 1 (ou détecter si tu veux aller plus loin)
    }

    // Recharge la page
    header("Location: details.php?id=$id_ordonnance");
    exit;
}

// Récupérer les produits déjà enregistrés
$stmt3 = $pdo->prepare("SELECT om.*, p.nom AS nom_produit, p.quantite AS stock FROM ordonnance_medicaments om
                        JOIN produits p ON p.id = om.id_produit
                        WHERE om.id_ordonnance = ?");
$stmt3->execute([$id_ordonnance]);
$medicaments = $stmt3->fetchAll();
?>

<div class="container mt-5">
    <h3 class="mb-4">💊 Médicaments détectés - Ordonnance de <?= htmlspecialchars($ordonnance['prenom'] . ' ' . $ordonnance['nom']) ?></h3>

    <a href="../../uploads/ordonnances/<?= urlencode($ordonnance['fichier']) ?>" target="_blank" class="btn btn-secondary mb-3">📂 Voir le fichier</a>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Médicament</th>
                <th>Quantité</th>
                <th>Stock</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($medicaments as $med): ?>
            <tr>
                <td><?= htmlspecialchars($med['nom_produit']) ?></td>
                <td><?= $med['quantite'] ?></td>
                <td><?= $med['stock'] ?></td>
                <td><?= ($med['stock'] >= $med['quantite']) ? '✅ Disponible' : '❌ Rupture' ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <a href="liste.php" class="btn btn-outline-primary mt-3">⬅ Retour</a>
</div>

<?php require_once '../../includes/footer.php'; ?>
