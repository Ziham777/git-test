<?php
// Activer les erreurs pour le debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "demande_emploi"); // Remplacez "nom_de_la_base" par le nom de votre base de données
if ($conn->connect_error) {
    die(json_encode(['error' => 'Échec de connexion : ' . $conn->connect_error]));
}

// Récupérer les données nécessaires
$offres = $conn->query("SELECT COUNT(*) AS total FROM offre")->fetch_assoc()['total'] ?? 0;
$candidats = $conn->query("SELECT COUNT(*) AS total FROM candidat")->fetch_assoc()['total'] ?? 0;
$entreprises = $conn->query("SELECT COUNT(*) AS total FROM entreprise")->fetch_assoc()['total'] ?? 0;

// Retourner les données au format JSON
header('Content-Type: application/json');
echo json_encode([
    'offres' => $offres,
    'candidats' => $candidats,
    'entreprises' => $entreprises
]);

$conn->close();
?>