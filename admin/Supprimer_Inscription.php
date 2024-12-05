<?php   
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser l'ID de l'utilisateur à supprimer
    $id = htmlspecialchars(trim($_POST['Numero']));
    $nom = htmlspecialchars(trim($_POST['Nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $motpasse = htmlspecialchars(trim($_POST['motpasse']));

    // Vérification de base
   // Vérifications de base
   if (empty($id) || empty($nom) || empty($email)) {
    echo "Tous les champs (y compris l'ID) sont requis.";
    exit;
}

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demande_emploi";

    try {
        // Établir la connexion avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête de suppression
        $stmt = $conn->prepare("
            DELETE FROM inscription 
            WHERE Numinscrit = :id
        ");

        // Lier l'ID de l'utilisateur
        $stmt->bindParam(':id', $id);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Inscription supprimée avec succès !";
        } else {
            echo "Une erreur est survenue lors de la suppression.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
