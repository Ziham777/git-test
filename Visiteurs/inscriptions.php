

<?php
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = ""; // Mot de passe de connexion MySQL
$base = "demande_emploi";

// Connexion à la base de données
$connect = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base);
if (!$connect) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Récupération et sécurisation des données du formulaire
$nom = mysqli_real_escape_string($connect, $_POST["nom"]);
$email = mysqli_real_escape_string($connect, $_POST["email"]);
$mdp = $_POST["mot"]; // Pas besoin d'échapper ici, le mot de passe sera haché

// Validation de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Erreur : L'adresse email saisie n'est pas valide.");
}

// Hachage du mot de passe
$mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

// Exécution de la requête
$res = mysqli_query($connect, "INSERT INTO entreprise(Nom, Email, motdepasse) VALUES ('$nom', '$email', '$mdp_hash')");
if ($res == true) {
    echo "Vous êtes bien inscrit.";
} else {
    echo "Erreur : " . mysqli_error($connect);
}
?>



