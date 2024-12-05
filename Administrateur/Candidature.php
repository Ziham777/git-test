<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    
    $nom = htmlspecialchars(trim($_POST['Nom']));
    $prenom = htmlspecialchars(trim($_POST['Prenom']));
    $adresse = htmlspecialchars(trim($_POST['Adress']));
    $email = htmlspecialchars(trim($_POST['email']));
    $diplome = htmlspecialchars(trim($_POST['diplome']));
    // Connexion à la base de données (modifiez les informations selon votre configuration)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demande_emploi";

    // Créer une connexion avec PDO (plus sécurisé que mysqli pour la gestion des requêtes)
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Configurer PDO pour afficher les exceptions
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête SQL avec des paramètres pour éviter les injections SQL
        $stmt = $conn->prepare("
            INSERT INTO candidature ( Nom, prenom, adresse, Email, diplome)
            VALUES ( :nom, :prenom, :adresse, :email, :diplome)
        ");

        // Associer les valeurs aux paramètres

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':diplome', $diplome);

        // Exécuter la requête
        $stmt->execute();

        echo "Nouvelle candidature ajoutée avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
