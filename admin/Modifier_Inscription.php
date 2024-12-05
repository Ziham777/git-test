<?php  
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $id = htmlspecialchars(trim($_POST['Numero'])); // ID de l'utilisateur à modifier
    $nom = htmlspecialchars(trim($_POST['Nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $motpasse = htmlspecialchars(trim($_POST['motpasse']));

    // Vérifications de base
    if (empty($id) || empty($nom) || empty($email)) {
        echo "Tous les champs (y compris l'ID) sont requis.";
        exit;
    }

    // Hachage du mot de passe si fourni
    $motpasse_hache = !empty($motpasse) ? password_hash($motpasse, PASSWORD_BCRYPT) : null;

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demande_emploi";

    try {
        // Établir la connexion avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête de mise à jour
        if ($motpasse_hache) {
            // Mise à jour avec mot de passe
            $stmt = $conn->prepare("
                UPDATE inscription 
                SET Nom = :nom, Email = :email,motdepasse = :motpasse
                WHERE Numinscrit = :id
            ");
            $stmt->bindParam(':motpasse', $motpasse_hache);
        } else {
            // Mise à jour sans modifier le mot de passe
            $stmt = $conn->prepare("
                UPDATE inscription 
                SET Nom = :nom, Email = :email
                WHERE Numinscrit= :id
            ");
        }

        // Lier les paramètres communs
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Inscription modifiée avec succès !";
        } else {
            echo "Une erreur est survenue lors de la modification.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
