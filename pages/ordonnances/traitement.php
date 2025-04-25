<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_client = $_POST['id_client'];
    $fichier = $_FILES['fichier'];

    // Dossier de destination
    $dossier = "../../uploads/ordonnances/";
    if (!is_dir($dossier)) mkdir($dossier, 0777, true);

    // Nom de fichier unique
    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    $nom_fichier = uniqid('ord_') . '.' . $extension;
    $chemin = $dossier . $nom_fichier;

    // Déplacer le fichier
    if (move_uploaded_file($fichier['tmp_name'], $chemin)) {
        $stmt = $pdo->prepare("INSERT INTO ordonnances (id_client, fichier) VALUES (?, ?)");
        $stmt->execute([$id_client, $nom_fichier]);

        header("Location: liste.php?success=1");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'upload du fichier. Vérifiez les permissions du dossier.</div>";
    }
}
?>
