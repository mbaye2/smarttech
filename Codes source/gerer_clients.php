<?php
session_start();

// Connexion à la base de données
include('config.php');

// Traitement de la suppression d'un client
if (isset($_GET['supprimer_id'])) {
    $client_id = $_GET['supprimer_id'];
    
    // Requête pour supprimer le client
    $stmt = $pdo->prepare("DELETE FROM client WHERE id = ?");
    $stmt->execute([$client_id]);

    // Redirection après suppression
    header('Location: gerer_clients.php');
    exit();
}

// Récupérer la liste des clients
$stmt = $pdo->prepare("SELECT * FROM client");
$stmt->execute();
$clients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <style>
        /* Style général de la page */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        /* Conteneur principal */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Titre principal */
        h2 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 40px;
        }

        /* Tableaux */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #6f4f28; /* Marron */
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        /* Liens d'actions */
        td a {
            color: #6f4f28; /* Marron */
            text-decoration: none;
            font-weight: bold;
        }

        td a:hover {
            text-decoration: underline;
        }

        /* Styles des boutons */
        .action-btn {
            background-color: #6f4f28; /* Marron */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin: 5px;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #5a3d1b; /* Marron foncé */
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Styles pour la barre latérale */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            height: 100%;
            background-color: #6f4f28; /* Marron */
            color: white;
            padding-top: 30px;
            text-align: center;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #5a3d1b; /* Marron foncé */
        }

        /* Espacement autour du contenu principal */
        .content {
            margin-left: 220px;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Barre latérale -->
    <div class="sidebar">
        <h3>Admin Panel</h3>
        <a href="gerer_employes.php">Gestion des employés</a>
        <a href="register.php">Ajouter des utilisateurs</a>
        <a href="gerer_documents.php">Gestion des clients</a>
        <a href="page_connexion.php">Se Déconnecter</a>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <div class="container">
            <h2>Clients Enregistrés</h2>

            <!-- Liste des clients -->
            <table>
                <thead>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($client['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($client['nom']); ?></td>
                            <td><?php echo htmlspecialchars($client['email']); ?></td>
                            <td>
                                <!-- Bouton Modifier -->
                                <a href="modifier_client.php?id=<?php echo $client['id']; ?>" class="action-btn">Modifier</a>
                                 
                                <!-- Bouton Supprimer -->
                                <a href="gerer_clients.php?supprimer_id=<?php echo $client['id']; ?>" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
