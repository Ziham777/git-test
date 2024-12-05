<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $motpasse = htmlspecialchars(trim($_POST['motpasse']));
    // Valider les champs requis
    if (empty($nom) || empty($email)  || empty($motpasse)) {
        die("Tous les champs sont obligatoires.");
    }
    $motpasse_hache = !empty($motpasse) ? password_hash($motpasse, PASSWORD_BCRYPT) : null;

    // Connexion à la base de données (modifiez les informations selon votre configuration)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demande_emploi";

    try {
        // Créer une connexion avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête SQL avec des paramètres
        if ($motpasse_hache) {
            $stmt = $conn->prepare("
            INSERT INTO entreprise (Nom, Email,motdepasse)
            VALUES (:nom, :email ,:motpasse)
        ");
        $stmt->bindParam(':motpasse', $motpasse_hache);
    }  else {
        $stmt = $conn->prepare ("
            INSERT INTO entreprise (Nom, Email)
            VALUES (:nom, :email)
        ");
    }
        // Lier les valeurs aux paramètres
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);

        // Exécuter la requête
        $stmt->execute();

        echo "Nouvelle entreprise ajoutée avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
