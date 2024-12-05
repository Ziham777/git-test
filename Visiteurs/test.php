
<?php
// Connexion à la base de données
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

// Récupération des données du formulaire
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($email) && !empty($password)) {
    try {
        // Vérification dans la table administrateur
        $query = $pdo->prepare("SELECT * FROM administrateur WHERE Email = :email");
        $query->execute(['email' => $email]);
        $admin = $query->fetch();

        if ($admin && password_verify($password, $admin['motdepasse'])) {
            // Redirection vers la page administrateur
            header("Location: http://localhost/Gestion_projet2/Administrateur/acceuil%20admist.html");
            exit;
        }

        // Vérification dans la table entreprise
        $query = $pdo->prepare("SELECT Numentrep, Email, motdepasse FROM entreprise WHERE Email = :email");
        $query->execute(['email' => $email]);
        $entreprise = $query->fetch();

        if ($entreprise && password_verify($password, $entreprise['motdepasse'])) {
            // Démarrer une session pour l'entreprise
            session_start();
            $_SESSION['Numentrep'] = $entreprise['Numentrep'];
            $_SESSION['Email'] = $entreprise['Email'];

            // Redirection vers la page listecandidature
            header("Location: http://localhost/GP21/Visiteurs/listecandidature.php");
            exit;
        }

        // Vérification dans la table inscription (candidats)
        $query = $pdo->prepare("SELECT * FROM inscription WHERE Email = :email");
        $query->execute(['email' => $email]);
        $candidat = $query->fetch();

        if ($candidat && password_verify($password, $candidat['motdepasse'])) {
            // Démarrer une session pour le candidat
            session_start();
            $_SESSION['IdCandidat'] = $candidat['IdCandidat'];
            $_SESSION['Email'] = $candidat['Email'];

            // Redirection vers la page offre
            header("Location: offre.php");
            exit;
        }

        // Si aucune correspondance trouvée
        echo "<script>alert('Adresse e-mail ou mot de passe incorrect.'); window.location.href = 'connexion.html';</script>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "<script>alert('Veuillez remplir tous les champs.'); window.location.href = 'connexion.html';</script>";
}
?>

