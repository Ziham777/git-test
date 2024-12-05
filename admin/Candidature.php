<?php

$host = 'localhost'; 
$dbname = 'demande_emploi'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire avec validation
    $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '';
    $nom_offre = isset($_POST['nom_offre']) ? htmlspecialchars($_POST['nom_offre']) : '';
    $adress = isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $telephone = isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '';
    $diplome = isset($_POST['diplome']) ? htmlspecialchars($_POST['diplome']) : '';
    $lettre = isset($_POST['lettre']) ? htmlspecialchars($_POST['lettre']) : '';
    $date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '';

    // Vérification si l'email est valide
    if (!$email) {
        echo "L'email saisi est invalide.<br>";
        exit;
    }

    // Vérification de l'existence de l'email dans la table inscription
    try {
        $checkEmailQuery = "SELECT COUNT(*) FROM candidat WHERE email = :email";
        $stmt = $pdo->prepare($checkEmailQuery);
        $stmt->execute([':email' => $email]);
        $emailExists = $stmt->fetchColumn();

        if (!$emailExists) {
            echo "L'email n'est pas inscrit dans notre base de données. La candidature ne peut pas être soumise.<br>";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la vérification de l'email : " . $e->getMessage();
        exit;
    }

    // Dossier pour les fichiers téléchargés
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Traitement du fichier CV
    $cv = $_FILES['cv'];
    $cvPath = $uploadDir . basename($cv['name']);
    $cvType = strtolower(pathinfo($cvPath, PATHINFO_EXTENSION));

    // Validation des fichiers (taille et type)
    $allowedTypes = ['pdf', 'doc', 'docx'];
    if (in_array($cvType, $allowedTypes) && $cv['size'] <= 2 * 1024 * 1024) { // 2 Mo max
        if (move_uploaded_file($cv['tmp_name'], $cvPath)) {
            echo "CV téléchargé avec succès.<br>";
        } else {
            echo "Erreur lors du téléchargement du CV.<br>";
            exit;
        }
    } else {
        echo "Le CV doit être au format PDF, DOC ou DOCX et ne pas dépasser 2 Mo.<br>";
        exit;
    }

    // Insertion des données dans la base de données (table Candidature)
    try {
        $query = "INSERT INTO Candidature (Nom, prenom, nom_offre, adresse, email, telephone, diplome, lettremotivation, datecandidature, cv)
                  VALUES (:nom, :prenom, :nom_offre, :adresse, :email, :telephone, :diplome, :lettre, :date_candidature, :cv_path)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':nom_offre' => $nom_offre,
            ':adresse' => $adress,
            ':email' => $email,
            ':telephone' => $telephone,
            ':diplome' => $diplome,
            ':lettre' => $lettre,
            ':date_candidature' => $date,
            ':cv_path' => $cvPath
        ]);
        echo "<h2>Votre candidature a été soumise et enregistrée avec succès ! <a href='http://localhost/Gestion_projet2/admin/accueiladmin.html'>Cliquez ici</a></h2>";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion des données : " . $e->getMessage();
    }
} else {
    echo "Aucun formulaire soumis.";
}
?>
