<?php
session_start();

// Vérifier si l'utilisateur est connecté et si son rôle est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: page_connexion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5dc; /* Beige pour toute la page */
            margin: 0;
        }

        .dashboard-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 500px;
            margin: 50px auto;
            text-align: center;
        }

        h2 {
            color: #6f4f28; /* Titre en marron */
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 12px;
            background: #6f4f28; /* Marron */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #4e3629; /* Marron foncé au survol */
        }

        .logout-btn {
            background: #8b4513; /* Couleur marron clair pour la déconnexion */
        }

        .logout-btn:hover {
            background: #6e3a1e; /* Marron foncé au survol */
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Espace Administrateur</h2>

        <div class="button-container">
            <a href="register.php" class="btn">Ajouter un utilisateur</a>
            <a href="gerer_employes.php" class="btn">Gérer les employés</a>
            <a href="gerer_clients.php" class="btn">Gérer les clients</a>
            <a href="gerer_documents.php" class="btn">Gérer les documents</a>
            <a href="page_connexion.php" class="btn logout-btn">Déconnexion</a>
        </div>
    </div>
</body>
</html>
