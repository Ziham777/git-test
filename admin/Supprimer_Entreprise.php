<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $numero = htmlspecialchars(trim($_POST['numero']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $motpasse = htmlspecialchars(trim($_POST['motpasse']));

    // Vérifications de base
    if (empty($numero) || empty($nom) || empty($email) || empty($motpasse)) {
        echo "Tous les champs sont requis.";
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

        // Vérifier si l'entreprise existe avec les informations fournies
        $stmt = $conn->prepare("
            SELECT * FROM entreprise 
            WHERE Numentrep = :numero AND Nom = :nom AND Email = :email
        ");
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($entreprise) {
            // Vérifier le mot de passe
            if (password_verify($motpasse, $entreprise['motdepasse'])) {
                // Supprimer l'entreprise
                $deleteStmt = $conn->prepare("DELETE FROM entreprise WHERE Numentrep = :numero");
                $deleteStmt->bindParam(':numero', $numero);
                $deleteStmt->execute();

                echo "L'entreprise avec le numéro $numero a été supprimée avec succès.";
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Aucune entreprise correspondante trouvée.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
