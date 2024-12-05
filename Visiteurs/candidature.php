<?php
// Connexion à la base de données
$connect = mysqli_connect("localhost", "root", "", "demande_emploi");

if (!$connect) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Récupérer l'ID de l'offre
$id_offre = $_POST['id_offre'];

// Requête pour récupérer l'ID de l'entreprise associée à l'offre
$sql = "SELECT Numentrep, titre FROM offre WHERE Numoffre = $id_offre";
$result = mysqli_query($connect, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $offre = mysqli_fetch_assoc($result);
    $id_entreprise = $offre['Numentrep']; // ID de l'entreprise
    $titre_offre = $offre['titre'];
} else {
    die("Offre introuvable.");
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Candidature</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="file"], textarea, input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h1>Postuler pour l'offre : <?php echo htmlspecialchars($titre_offre); ?></h1>

    <form action="env_candidature.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_entreprise" value="<?php echo $id_entreprise; ?>">
        <input type="hidden" name="id_offre" value="<?php echo $id_offre; ?>">

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="Nom_offre">Nom_offre :</label>
        <input type="text" id="offre" name="Nom_offre" required>

        <label for="adresse">Adresse :</label>
        <input type="text" id="adresse" name="adresse" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="Telephone">Telephone portable:</label>
        <input type="tel" id="tel" name="tel" required>

        <label for="diplome">Diplôme :</label>
        <select name="diplome">
            <option value="bac"selected>Bac</option>
            <option value="Licence">Licence</option>
            <option value="master">Master</option>
            <option value="doctora">Doctora</option>
        </select>

        <label for="cv">Télécharger votre CV :</label>
        <input type="file" id="cv" name="cv" accept=".pdf, .doc, .docx" required>

        <label for="lettre">Lettre de motivation :</label>
        <textarea name="lettre" id="lettre" rows="10" cols="50"></textarea>

        <label for="date">Date de candidature :</label>
        <input type="date" id="date" name="date" required>

        <button type="submit">Soumettre</button>
    </form>

</body>
</html>