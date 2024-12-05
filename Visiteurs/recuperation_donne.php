<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'demande_emploi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

// Récupérer l'ID de l'offre depuis le formulaire
$id_offre = $_POST['id_offre']; // Ou depuis l'URL si nécessaire

// Requête SQL pour récupérer les détails de l'offre et de l'entreprise
$sql = "SELECT offre.titre, entreprise.Email
        FROM offres
        INNER JOIN entreprises ON offre.Numentrep = entreprises.Numentrep
        WHERE offre.Numoffre = :Numoffre";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_offre', $id_offre, PDO::PARAM_INT);
$stmt->execute();

// Récupérer les détails de l'offre et de l'entreprise
$offre = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si des résultats ont été trouvés
if ($offre) {
    $titre_offre = $offre['titre_offre'];
    $email_entreprise = $offre['email_entreprise'];
} else {
    echo "L'offre n'existe pas ou il y a eu une erreur.";
    exit;
}
?>