<?php
session_start();

// Vérifier si l'utilisateur est connecté et si son rôle est employé
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employe') {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur depuis la base de données
include('config.php');
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM employe WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: page_connexion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Employé</title>
    <style>
        /* Réinitialisation des marges et du padding pour prendre toute la page */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7; /* Fond gris clair */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Prendre toute la hauteur de l'écran */
        }

        .dashboard-container {
            background: #F8F1E5; /* Fond beige clair */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Limiter la largeur pour garder une taille raisonnable */
            text-align: center;
            height: 100%; /* Prendre toute la hauteur disponible */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            color: #6c4f3d; /* Marron foncé pour le titre */
            margin-bottom: 20px;
        }

        p {
            color: #6c4f3d; /* Marron foncé pour le texte */
            font-size: 16px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 12px;
            background: #8B5A2B; /* Marron moyen pour les boutons */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #6C4F3D; /* Marron foncé au survol */
        }

        .logout-btn {
            background: #F44336; /* Rouge pour déconnexion */
        }

        .logout-btn:hover {
            background: #E53935; /* Rouge plus foncé pour déconnexion */
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Bienvenue, <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h2>
        <p>Vous êtes connecté en tant qu'employé.</p>

        <div class="button-container">
            <a href="voir_employes.php" class="btn">Voir les employés</a>
            <a href="voir_clients.php" class="btn">Voir les clients</a>
            <a href="gerer_documents.php" class="btn">Voir et télécharger les documents</a>
            <a href="page_connexion.php" class="btn logout-btn">Déconnexion</a>
        </div>
    </div>
</body>
</html>
