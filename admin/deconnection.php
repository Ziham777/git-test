<?php
session_start();
session_unset(); // Supprimer toutes les variables de session
session_destroy(); // Détruire la session
header("Location: http://localhost/Gestion_projet2/Visiteurs/Accueil2.html"); // Rediriger vers la page de connexion
exit();
?>
