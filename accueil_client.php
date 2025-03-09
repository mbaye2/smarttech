<?php
session_start();

// Vérifier si l'utilisateur est connecté et si son rôle est client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header('Location: page_connexion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Client</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #D8B9A5, #C7A28C); /* Dégradé beige/marron */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .dashboard-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #6A4E23; /* Marron foncé pour le titre */
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 12px;
            background: #8C5A3C; /* Marron doux pour les boutons */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #6E3B2B; /* Marron plus foncé au survol */
        }

        .logout-btn {
            background: #f44336; /* Rouge pour déconnexion */
        }

        .logout-btn:hover {
            background: #e53935; /* Rouge plus foncé pour déconnexion */
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Bienvenue, Client</h2>

        <div class="button-container">
            <a href="voir_documents_client.php" class="btn">Consulter les documents</a>
            <a href="page_connexion.php" class="btn logout-btn">Déconnexion</a>
        </div>
    </div>
</body>
</html>
