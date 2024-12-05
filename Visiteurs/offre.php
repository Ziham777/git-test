<?php
$serveur = "localhost";
$utilisateur = "root";
$mot = "";
$base = "demande_emploi";

// Connexion à la base de données
$connect = mysqli_connect($serveur, $utilisateur, $mot, $base);

// Vérification de la connexion
if (!$connect) {
    die("Connexion échouée : " . mysqli_connect_error());
}

// Récupérer les critères de recherche s'ils existent
$keyword = isset($_GET['keyword']) ? '%' . mysqli_real_escape_string($connect, $_GET['keyword']) . '%' : '%';
$location = isset($_GET['location']) ? '%' . mysqli_real_escape_string($connect, $_GET['location']) . '%' : '%';

// Requête SQL avec filtres
$sql = "
    SELECT Numoffre, titre, description, salaire, entreprise, secteur, ville, adresse, telephone, 
           datedebut, datefin 
    FROM offre 
    WHERE (titre LIKE '$keyword' OR description LIKE '$keyword' OR secteur LIKE '$keyword') 
      AND (ville LIKE '$location')
";

// Exécution de la requête avec filtres
$res = mysqli_query($connect, $sql);

// Vérification des résultats
if ($res && mysqli_num_rows($res) > 0) {
    // Résultats trouvés : utilisation des résultats filtrés
    $offres = mysqli_fetch_all($res, MYSQLI_ASSOC);
} else {
    // Aucun résultat : charger toutes les offres
    $res = mysqli_query($connect, "SELECT Numoffre, titre, description, salaire, entreprise, secteur, ville, adresse, telephone, datedebut, datefin FROM offre");
    $offres = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

// Ajout du style CSS intégré
echo "
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 20px;
        color: #333;
    }
    h1 {
        text-align: center;
        color: #4CAF50;
    }
    .container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: flex-start;
        margin-top: 20px;
    }
    .card {
        background-color: #ffffff;
        width: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid #ddd;
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: scale(1.05);
    }
    .card-header {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }
    .card-body {
        padding: 15px;
        line-height: 1.6;
    }
    .card-body p {
        margin: 5px 0;
    }
    .label {
        font-weight: bold;
        color: #555;
    }
    button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }
    /* Style pour l'en-tête */
    .header {
        background-color: #fff;
        display: flex;
        justify-content: space-between;
         align-items: center;
         padding: 1rem 2rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-top:-25px;
        
        
    }
    .header-logo img {
      max-width: 120px;
      
    }
    .header-nav a {
        margin: 0 15px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
    }
   
    .header-nav a:hover {
        color: #4CAF50;
    }
</style>
";

// Intégration de l'en-tête (header)
echo "
<div class='header'>
    <div class='header-logo'>
       <a href='Accueil2.html'><img src='logo.png' alt='Logo'></a>  
    </div>
    <div class='header-nav'>
        <a href='A propos.html'>A Propos</a>
        <a href='offres.php'>Offres</a>
        <a href='connexion.php'>Connexion</a>
        <a href='contact.php'>Contact</a>
        <a href='publier.php'>Publier</a>
    </div>
</div>
";

// Titre de la page
echo "<h1>Liste des Offres</h1>";

// Début du conteneur
echo "<div class='container'>";

// Parcourir les résultats de la requête et afficher chaque offre sous forme de carte
foreach ($offres as $offre) {
    $id_offre = htmlspecialchars($offre['Numoffre']);
    $titre = htmlspecialchars($offre['titre']);
    $description = htmlspecialchars($offre['description']);
    $salaire = htmlspecialchars($offre['salaire']);
    $entreprise = htmlspecialchars($offre['entreprise']);
    $secteur = htmlspecialchars($offre['secteur']);
    $ville = htmlspecialchars($offre['ville']);
    $adrs = htmlspecialchars($offre['adresse']);
    $tel = htmlspecialchars($offre['telephone']);
    $dateDebut = htmlspecialchars($offre['datedebut']);
    $dateFin = htmlspecialchars($offre['datefin']);

    echo "
    <div class='card'>
        <div class='card-header'>$titre</div>
        <div class='card-body'>
            <p><span class='label'>Description:</span> $description</p>
            <p><span class='label'>Salaire:</span> $salaire</p>
            <p><span class='label'>Entreprise:</span> $entreprise</p>
            <p><span class='label'>Secteur:</span> $secteur</p>
            <p><span class='label'>Ville:</span> $ville</p>
            <p><span class='label'>Adresse:</span> $adrs</p>
            <p><span class='label'>Téléphone:</span> $tel</p>
            <p><span class='label'>Date de début:</span> $dateDebut</p>
            <p><span class='label'>Date de fin:</span> $dateFin</p>
            <!-- Bouton pour postuler -->
            <form action='candidature.php' method='post'>
                <input type='hidden' name='id_offre' value='$id_offre'>
                <button type='submit'>Postuler</button>
            </form>
        </div>
    </div>
    ";
}

echo "</div>";

// Fermeture de la connexion
mysqli_close($connect);
?>