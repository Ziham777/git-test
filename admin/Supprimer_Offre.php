<?php   
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser l'ID de l'offre à supprimer
    $id = htmlspecialchars(trim($_POST['Numero']));
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

    // Vérification de base
    if (empty($id)) {
        echo "L'ID de l'offre est requis pour la suppression.";
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
            DELETE FROM offre 
            WHERE Numoffre = :id
        ");

        // Lier l'ID de l'offre
        $stmt->bindParam(':id', $id);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Offre d'emploi supprimée avec succès !";
        } else {
            echo "Une erreur est survenue lors de la suppression de l'offre.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>
