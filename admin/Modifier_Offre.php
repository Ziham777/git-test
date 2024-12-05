<?php  
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $id = htmlspecialchars(trim($_POST['Numero'])); // Récupérer l'ID de l'offre à modifier
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

        // Mettre à jour les données dans la table "offre"
        $stmt = $conn->prepare("
            UPDATE offre 
            SET 
                titre = :titre, 
                description = :description, 
                salaire = :salaire, 
                entreprise = :entreprise, 
                secteur = :secteur, 
                ville = :ville, 
                adresse = :adress, 
                telephone = :telephone, 
                datedebut = :datedebut, 
                datefin = :datefin
            WHERE Numoffre = :id
        ");

        // Lier les paramètres
        $stmt->bindParam(':id', $id); // L'identifiant de l'offre
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
            echo "Offre d'emploi modifiée avec succès !";
        } else {
            echo "Une erreur est survenue lors de la modification de l'offre.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
