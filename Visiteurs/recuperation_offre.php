<?php
header('Content-Type: application/json');

// Connexion à la base de données
$host = 'localhost';
$dbname = 'demande_emploi';
$user = 'root'; // utilisateur par défaut de WampServer
$password = ''; // mot de passe par défaut
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
    exit;
}

// Récupération des trois dernières offres
$query = "SELECT titre, entreprise, ville, datedebut
          FROM offre
          ORDER BY datedebut DESC 
          LIMIT 3";
$stmt = $pdo->prepare($query);
$stmt->execute();
$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renvoi des résultats en JSON
echo json_encode($offers);
?>