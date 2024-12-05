
<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'demande_emploi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Démarrer la session
session_start();

// Vérifier si l'entreprise est connectée
if (!isset($_SESSION['Numentrep'])) {
    die("Accès refusé. Veuillez vous connecter en tant qu'entreprise.");
}

$id_entreprise = $_SESSION['Numentrep'];

// Récupérer les candidatures pour les offres de l'entreprise connectée
$query = "
    SELECT c.Nom, c.prenom, c.Nom_offre, c.adresse, c.Email, c.telephone, c.diplome, c.cv, c.lettremotivation, c.datecandidature 
    FROM candidature c
    INNER JOIN offre o ON c.Numoffre = o.Numoffre
    WHERE o.Numentrep = :id_entreprise
";
$stmt = $pdo->prepare($query);
$stmt->execute(['id_entreprise' => $id_entreprise]);
$candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Candidatures</title>
    <style>
        /* Styles CSS */
        .container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            margin: 20px;
        }
        .card {
            width: 300px; /* Taille augmentée */
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Alignement à gauche des éléments */
        }
        .card h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
            text-align: left;
            width: 100%;
        }
        .card p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
            text-align: left;
            word-wrap: break-word; /* Retour à la ligne si le texte est trop long */
            width: 100%;
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        .card a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Liste des Candidatures</h1>
    <div class="container">
        <?php if (count($candidatures) > 0): ?>
            <?php foreach ($candidatures as $candidature): ?>
                <div class="card">
                    <h2><?php echo htmlspecialchars($candidature['Nom']) . ' ' . htmlspecialchars($candidature['prenom']); ?></h2>
                    <p><strong>Nom de l'offre :</strong> <?php echo htmlspecialchars($candidature['Nom_offre']); ?></p>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($candidature['adresse']); ?></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($candidature['Email']); ?></p>
                    <p><strong>telephone portable :</strong> <?php echo htmlspecialchars($candidature['telephone']); ?></p>
                    <p><strong>Diplôme :</strong> <?php echo htmlspecialchars($candidature['diplome']); ?></p>
                    <p><strong>Lettre de Motivation :</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($candidature['lettremotivation'])); ?></p>
                    <p><strong>Date :</strong> <?php echo htmlspecialchars($candidature['datecandidature']); ?></p>
                    <a href="uploads/<?php echo htmlspecialchars($candidature['cv']); ?>" download>Télécharger le CV</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune candidature trouvée.</p>
        <?php endif; ?>
    </div>
</body>
</html>

