<?php
session_start();

include('config.php');

// Requête pour récupérer les employés
$stmt = $pdo->query("SELECT * FROM employe");

if ($stmt) {
    $employes = $stmt->fetchAll();
} else {
    $employes = [];
    $error_message = "Une erreur est survenue lors de la récupération des employés.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Employés</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7; /* Beige clair pour l'arrière-plan */
            display: flex;
            height: 100vh; /* Pleine hauteur */
        }

        /* Sidebar */
        .sidebar {
            background-color: #6c4f3d; /* Marron foncé pour la sidebar */
            color: white;
            width: 250px; /* Largeur fixe de la sidebar */
            height: 100vh; /* La sidebar prend toute la hauteur de la page */
            padding: 20px;
            position: fixed; /* Sidebar fixe à gauche */
            top: 0;
            left: 0;
            z-index: 1000; /* Assure que la sidebar soit au-dessus du contenu */
            box-sizing: border-box; /* Assure que les padding ne perturbent pas la largeur */
        }

        .sidebar h2 {
            color: #F8F1E5; /* Couleur beige clair pour le texte dans la sidebar */
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #F8F1E5; /* Couleur beige clair pour les liens */
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #8B5A2B; /* Marron moyen sur hover */
        }

        /* Main content */
        .main-content {
            margin-left: 250px; /* Décalage pour laisser de la place à la sidebar */
            padding: 20px;
            width: calc(100% - 250px); /* Utilise la largeur restante */
            overflow-y: auto; /* Assure que le contenu se défile s'il est trop grand */
            box-sizing: border-box;
        }

        h2 {
            color: #6c4f3d; /* Marron foncé pour le titre */
            text-align: center;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #8B5A2B; /* Marron moyen pour le bouton */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: 200px;
            margin: 20px auto;
        }

        .back-btn:hover {
            background-color: #6C4F3D; /* Marron foncé au survol */
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Menu</h2>
        <a href="dashboard_employe.php">Tableau de bord</a>
        <a href="voir_employes.php">Voir les employés</a>
        <a href="voir_clients.php">Voir les clients</a>
        <a href="gerer_documents.php">Gérer les documents</a>
        <a href="page_connexion.php">Déconnexion</a>
    </div>

    <div class="main-content">
        <h2>Liste des Employés</h2>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (empty($employes)): ?>
            <p>Aucun employé trouvé.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employes as $employe): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employe['id']); ?></td>
                            <td><?php echo htmlspecialchars($employe['nom']); ?></td>
                            <td><?php echo htmlspecialchars($employe['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($employe['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="dashboard_employe.php" class="back-btn">Retour au Dashboard</a>
    </div>

</body>
</html>
