<?php
// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Connexion à la base de données
    $servername = "localhost";
    $db_username = "root"; // Par défaut pour MySQL
    $db_password = ""; // Mettez votre mot de passe MySQL ici
    $dbname = "demande_emploi"; // Changez ce nom en fonction de votre base de données

    // Créer une connexion
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Préparer la requête SQL pour vérifier l'utilisateur
    $sql = "SELECT * FROM utilisateurs WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // Liaison du paramètre
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // L'utilisateur existe, vérifier le mot de passe
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Connexion réussie
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Si vous gérez des rôles
            echo "Connexion réussie ! Bienvenue, " . $user['username'] . "!";
            // Redirigez vers la page d'accueil ou un tableau de bord
            header("Location: dashboard.php");
            exit;
        } else {
            // Mot de passe incorrect
            echo "Mot de passe incorrect.";
        }
    } else {
        // Aucun utilisateur trouvé
        echo "Nom d'utilisateur incorrect ou inexistant.";
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
