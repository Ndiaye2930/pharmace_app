<?php
require_once '../../config/database.php';
require_once 'vendor/autoload.php'; // charger Tesseract via Composer
require_once '../../includes/header.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

$clients = $pdo->query("SELECT * FROM clients ORDER BY nom")->fetchAll();
$suggestions = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_client = $_POST['id_client'];
    $fichier = $_FILES['fichier'];

    $dossier = "../../uploads/ordonnances/";
    if (!is_dir($dossier)) mkdir($dossier, 0777, true);

    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    $nom_fichier = uniqid('ord_') . '.' . $extension;
    $chemin = $dossier . $nom_fichier;

    if (move_uploaded_file($fichier['tmp_name'], $chemin)) {
        // OCR sur image
        $ocr = new TesseractOCR($chemin);
        $ocr->executable('C:\Program Files\Tesseract-OCR\tesseract.exe'); // adapte si autre chemin

        $texte = $ocr->run();

        // Extraction basique des noms de médicaments (à améliorer avec regex)
        $mots = preg_split('/[\s,.\n]+/', strtolower($texte));
        $stmt = $pdo->query("SELECT nom FROM produits");
        $produits = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($produits as $produit) {
            if (in_array(strtolower($produit), $mots)) {
                $suggestions[] = $produit;
            }
        }

        // Enregistrement de l'ordonnance
        $stmt = $pdo->prepare("INSERT INTO ordonnances (id_client, fichier) VALUES (?, ?)");
        $stmt->execute([$id_client, $nom_fichier]);
        // Redirection vers la page de liste des ordonnances
header("Location: liste.php");
exit;
    }
}
?>

<div class="container mt-5">
    <h2>Ajouter une ordonnance</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="id_client">Client</label>
            <select name="id_client" class="form-control" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($clients as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['nom'] . ' ' . $c['prenom'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="fichier">Fichier ordonnance (image/PDF)</label>
            <input type="file" name="fichier" class="form-control" accept="image/*,application/pdf" required>
        </div>
        <button type="submit" class="btn btn-primary">Analyser l'ordonnance</button>
    </form>

    <?php if (!empty($suggestions)) : ?>
        <div class="mt-4 alert alert-info">
            <h5>Médicaments suggérés :</h5>
            <ul>
                <?php foreach ($suggestions as $medoc): ?>
                    <li>
                        <?= htmlspecialchars($medoc) ?>
                        <?php
                            $stmt = $pdo->prepare("SELECT quantite FROM produits WHERE nom = ?");
                            $stmt->execute([$medoc]);
                            $quantite = $stmt->fetchColumn();
                            if ($quantite > 0) {
                                echo " ✅ En stock ($quantite)";
                            } else {
                                echo " ❌ Rupture";
                            }
                        ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>
</div>

<?php require_once '../../includes/footer.php'; ?>
