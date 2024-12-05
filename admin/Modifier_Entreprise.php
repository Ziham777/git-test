<?php 
// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $numero = htmlspecialchars(trim($_POST['Numero'])); 
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $motpasse = htmlspecialchars(trim($_POST['motpasse']));  // Nouveau mot de passe

    // Vérifications de base
    if (empty($numero) || empty($nom) || empty($email) || empty($motpasse)) {
        echo "Tous les champs (numéro, nom, email et mot de passe) sont requis.";
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
        // Création de la connexion avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérification si l'entreprise existe avec le numéro fourni
        $stmt = $conn->prepare("SELECT * FROM entreprise WHERE Numentrep = :numero");
        $stmt->bindParam(':numero', $numero);
        $stmt->execute();

        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($entreprise) {
            // L'entreprise existe, mise à jour du mot de passe et autres informations
            $updateStmt = $conn->prepare("
                UPDATE entreprise 
                SET Nom = :nom, Email = :email, motdepasse = :motpasse
                WHERE Numentrep = :numero
            ");
            
            // Lier les paramètres
            $updateStmt->bindParam(':numero', $numero);
            $updateStmt->bindParam(':nom', $nom);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':motpasse', $motpasse_hache);

            // Exécuter la mise à jour
            $updateStmt->execute();

            echo "Les informations ont été mises à jour avec succès.";
        } else {
            echo "Aucune entreprise trouvée avec ce numéro.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
