<?php 
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $nom = htmlspecialchars(trim($_POST['Nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $motpasse = htmlspecialchars(trim($_POST['motpasse']));

    // Vérifications de base
    if (empty($nom) || empty($email) || empty($motpasse)) {
        echo "Tous les champs sont requis.";
        exit;
    }

    // Hachage du mot de passe
    $motpasse_hache = password_hash($motpasse, PASSWORD_BCRYPT);

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demande_emploi";

    try {
        // Établir la connexion avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si l'email existe déjà
        $stmt = $conn->prepare("SELECT * FROM candidat WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Cet email est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            // Insérer l'entreprise dans la base de données
            $insertStmt = $conn->prepare("
                INSERT INTO candidat (Nom, Email, MotdePasse)
                VALUES (:nom, :email, :motpasse)
            ");
            $insertStmt->bindParam(':nom', $nom);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':motpasse', $motpasse);

            if ($insertStmt->execute()) {
                echo "Inscription réussie !";
            } else {
                echo "Une erreur est survenue lors de l'inscription.";
            }
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
