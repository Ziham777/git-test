<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $numero = htmlspecialchars(trim($_POST['numero']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    $email = htmlspecialchars(trim($_POST['email']));
    $diplome = htmlspecialchars(trim($_POST['diplome']));

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demande_emploi"; // Nom de la base de données

    try {
        // Créer une connexion avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Configurer PDO pour afficher les exceptions
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête SQL avec des paramètres pour éviter les injections SQL
        $stmt = $conn->prepare("
            UPDATE candidature
            SET nom = :nom, prenom = :prenom, adresse = :adresse, email = :email, diplome = :diplome
            WHERE numcandidat = :numero
        ");

        // Associer les valeurs aux paramètres
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':diplome', $diplome);
        $stmt->bindParam(':numero', $numero);

        // Exécuter la requête
        $stmt->execute();

        echo "Candidature mise à jour avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
