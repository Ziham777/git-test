<?php 
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $titre = htmlspecialchars(trim($_POST['titre']));
    $description = htmlspecialchars(trim($_POST['description']));
    $salaire = htmlspecialchars(trim($_POST['salaire']));
    $entreprise = htmlspecialchars(trim($_POST['entreprise']));
    $secteur = htmlspecialchars(trim($_POST['secteur']));
    $ville = htmlspecialchars(trim($_POST['ville']));
    $adress = htmlspecialchars(trim($_POST['adress'])); // Champ pour l'adresse
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $datedebut = htmlspecialchars(trim($_POST['datedebut']));
    $datefin = htmlspecialchars(trim($_POST['datefin']));

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demande_emploi";

    try {
        // Établir la connexion avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insérer les données dans la table "offre"
        $stmt = $conn->prepare("
            INSERT INTO offre (titre, description, salaire, entreprise, secteur, ville, adresse, telephone, datedebut, datefin)
            VALUES (:titre, :description, :salaire, :entreprise, :secteur, :ville, :adress, :telephone, :datedebut, :datefin)
        ");

        // Lier les paramètres
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':salaire', $salaire);
        $stmt->bindParam(':entreprise', $entreprise);
        $stmt->bindParam(':secteur', $secteur);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':adress', $adress); // Correction ici pour l'adresse
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':datedebut', $datedebut);
        $stmt->bindParam(':datefin', $datefin);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Offre d'emploi ajoutée avec succès !";
        } else {
            echo "Une erreur est survenue lors de l'ajout de l'offre.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
