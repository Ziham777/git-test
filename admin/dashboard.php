<?php
// Inclure la configuration
include 'config.php';

// Récupérer les statistiques
try {
    // Nombre total d'utilisateurs
    $queryUsers = $pdo->query("SELECT COUNT(*) AS total_users FROM candidat");
    $totalUsers = $queryUsers->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Nombre total d'offres publiées
    $queryOffres = $pdo->query("SELECT COUNT(*) AS total_offres FROM offre");
    $totalOffres = $queryOffres->fetch(PDO::FETCH_ASSOC)['total_offres'];

    // Nombre total d'entreprises partenaires
    $queryEntreprises = $pdo->query("SELECT COUNT(*) AS total_entreprises FROM entreprise");
    $totalEntreprises = $queryEntreprises->fetch(PDO::FETCH_ASSOC)['total_entreprises'];

    // Nombre de demandes en attente
    /*$queryDemandes = $pdo->query("SELECT COUNT(*) AS total_demandes FROM candidature WHERE statut = 'en attente'");
    $totalDemandes = $queryDemandes->fetch(PDO::FETCH_ASSOC)['total_demandes'];*/
} catch (PDOException $e) {
    die("Erreur lors de la récupération des statistiques : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="bord2.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile">
            <img src="icon_profil.svg" alt="Avatar">
            <h3>Directeur</h3>
        </div>
        <ul class="menu">
            <li>
                <a href="accueiladmin.html">Accueil</a>
            </li>
            <li>
                <a href="dashboard.php">Tableau de bord</a>
            </li>
            <li>
                <a href="utilisateur.html">Gestion d'utilisateur</a>
            </li>
            <li>
                <a href="#">Déconnexion</a>
            </li>
        </ul>
        </div>
        <main class="main-content">
            <section id="dashboard" class="section">
                <h1>Tableau de bord</h1>
                <div class="dashboard-cards">
                    <div class="card">
                        <h3>Utilisateurs inscrits</h3>
                        <p><?php echo $totalUsers; ?></p>
                    </div>
                    <div class="card">
                        <h3>Offres publiées</h3>
                        <p><?php echo $totalOffres; ?></p>
                    </div>
                    <div class="card">
                        <h3>Entreprises partenaires</h3>
                        <p><?php echo $totalEntreprises; ?></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
