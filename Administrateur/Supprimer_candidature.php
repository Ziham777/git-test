<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $numero = htmlspecialchars(trim($_POST['numero']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['Prenom']));
    $adresse = htmlspecialchars(trim($_POST['adress']));
    $email = htmlspecialchars(trim($_POST['email']));
    $diplome = htmlspecialchars(trim($_POST['diplome']));

    // Connexion à la base de données (modifiez les informations selon votre configuration)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demande_emploi";

    try {
        // Créer une connexion avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Configurer PDO pour afficher les exceptions
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête SQL pour supprimer la candidature
        $stmt = $conn->prepare("DELETE FROM candidature WHERE Numcandidat = :numero");
        
        // Associer la valeur à la variable :numero
        $stmt->bindParam(':numero', $numero, PDO::PARAM_INT);
        
        // Exécuter la requête
        $stmt->execute();

        echo "Candidature supprimée avec succès.";
    } catch (PDOException $e) {
        // Gérer les erreurs de connexion ou d'exécution
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
