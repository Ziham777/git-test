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
$email = $_POST['email'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_BCRYPT);


if (!empty($email) && !empty($password)) {
    try {
        // Vérification dans la table administrateur
        $query = $pdo->prepare("SELECT * FROM administrateur WHERE Email = :email AND motdepasse = :password");
        $query->execute(['email' => $email, 'password' => $password]);
        $admin = $query->fetch();

        if ($admin) {
            // Redirection vers la page administrateur
            header("Location: http://localhost/Gestion_projet2/admin/accueiladmin.html");
            exit;
        }

        // Vérification dans la table entreprise
        $query = $pdo->prepare("SELECT * FROM entreprise WHERE Email = :email AND motdepasse = :password");
        $query->execute(['email' => $email, 'password' => $password]);
        $entreprise = $query->fetch();

        if ($entreprise) {
            // Redirection vers la page publier
            header("Location: publier.html");
            exit;
        }

        // Vérification dans la table inscription (candidats)
        $query = $pdo->prepare("SELECT * FROM candidat WHERE Email = :email AND motdepasse = :password");
        $query->execute(['email' => $email, 'password' => $password]);
        $candidat = $query->fetch();

        if ($candidat) {
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
