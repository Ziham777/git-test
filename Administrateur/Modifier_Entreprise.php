<?php   
// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $numero = htmlspecialchars(trim($_POST['Numero']));  // Vérifiez que 'Numero' est correct
    $nom = htmlspecialchars(trim($_POST['Nom']));
    $email = htmlspecialchars(trim($_POST['Email']));
    $adress = htmlspecialchars(trim($_POST['Adresse']));  // Vérifiez que 'Adresse' est correct
    $secteur = htmlspecialchars(trim($_POST['Secteur']));
    
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
        $stmt = $conn->prepare("SELECT * FROM entreprise WHERE Numentrep = :numero");  // Assurez-vous que Numentrep est correct
        $stmt->bindParam(':numero', $numero);
        $stmt->execute();
        
        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($entreprise) {
            // L'entreprise existe, procéder à la mise à jour
            $updateStmt = $conn->prepare("
                UPDATE entreprise 
                SET Nom_entreprise = :nom, Email = :email, adresse = :adress, secteur = :secteur 
                WHERE Numentrep = :numero
            ");
            // Lier les paramètres
            $updateStmt->bindParam(':numero', $numero);
            $updateStmt->bindParam(':nom', $nom);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':adress', $adress);
            $updateStmt->bindParam(':secteur', $secteur);
            
            // Exécuter la mise à jour
            $updateStmt->execute();
            
            echo "Les informations de l'entreprise ont été mises à jour avec succès.";
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
