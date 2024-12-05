

<?php
$serveur = "localhost";
$utilisateur = "root";
$mot = "";
$base = "demande_emploi";
$connect = mysqli_connect($serveur, $utilisateur, $mot, $base);

if (!$connect) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Récupérer les données du formulaire
$titre = $_POST["titre"];
$description = $_POST["description"];
$salaire = $_POST["salaire"];
$entreprise = $_POST["entreprise"];
$secteur = $_POST["secteur"];
$ville = $_POST["ville"];
$adresse = $_POST["adresse"];
$tel = $_POST["tel"];
$date = $_POST["date"];
$dates = $_POST["dates"];

// Vérifier si l'entreprise existe dans la table entreprise
$query_entreprise = "SELECT Numentrep FROM entreprise WHERE Nom = '$entreprise'";
$result_entreprise = mysqli_query($connect, $query_entreprise);

if (mysqli_num_rows($result_entreprise) > 0) {
    // Récupérer l'id de l'entreprise
    $row = mysqli_fetch_assoc($result_entreprise);
    $entreprise_id = $row["Numentrep"];

    // Insérer l'offre avec l'id de l'entreprise
    $query_offre = "INSERT INTO offre (titre, description, salaire, entreprise, secteur, ville, adresse, telephone, datedebut, datefin,Numentrep) 
                    VALUES ('$titre', '$description', '$salaire', '$entreprise', '$secteur', '$ville', '$adresse', '$tel', '$date', '$dates','$entreprise_id')";

    $res = mysqli_query($connect, $query_offre);

    if ($res == true) {
        echo "L'offre a été ajoutée avec succès.";
    } else {
        echo "Erreur: L'offre n'a pas été ajoutée. " . mysqli_error($connect);
    }
} else {
    echo "Erreur : L'entreprise spécifiée n'existe pas dans la base de données.";
}

mysqli_close($connect);
?>







