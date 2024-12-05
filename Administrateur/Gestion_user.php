<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demande_emploi";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Traitement des actions (ajouter, modifier, supprimer)
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'ajouter') {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $mot_de_passe = password_hash($_POST['motdepasse'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO utilisateurs (nom, email, motdepasse) VALUES ('$nom', '$email', '$mot_de_passe')";
        if ($conn->query($sql) === TRUE) {
            echo "Utilisateur ajouté avec succès.";
        } else {
            echo "Erreur: " . $conn->error;
        }
    } elseif ($action == 'modifier') {
        $id = $_POST['numero'];
        $nom = $_POST['nom'];
        $email = $_POST['email'];

        $sql = "UPDATE inscription SET nom='$nom', email='$email' WHERE numero='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Utilisateur modifié avec succès.";
        } else {
            echo "Erreur: " . $conn->error;
        }
    } elseif ($action == 'supprimer') {
        $id = $_POST['numero'];

        $sql = "DELETE FROM inscription WHERE numero='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Utilisateur supprimé avec succès.";
        } else {
            echo "Erreur: " . $conn->error;
        }
    }
}

// Récupération de la liste des utilisateurs
$sql = "SELECT * FROM inscription";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="bord2.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <img src="user-icon.png" alt="Directeur" class="profile-img">
                <h2>Administrateur</h2>
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="#accueil">Accueil</a></li>
                    <li><a href="#ajouter">Ajouter</a></li>
                    <li><a href="#modifier">Modifier</a></li>
                    <li><a href="#supprimer">Supprimer</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <h1>Gestion des Utilisateurs</h1>
            <form action="gestion_utilisateurs.php" method="post">
                <h2>Ajouter un Utilisateur</h2>
                <input type="hidden" name="action" value="ajouter">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="mot_de_passe">Mot de passe:</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                <button type="submit">Ajouter</button>
            </form>

            <h2>Liste des Utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nom']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <form action="gestion_utilisateurs.php" method="post" style="display: inline-block;">
                                        <input type="hidden" name="action" value="modifier">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit">Modifier</button>
                                    </form>
                                    <form action="gestion_utilisateurs.php" method="post" style="display: inline-block;">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
