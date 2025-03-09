<?php
session_start();

// Connexion à la base de données
include('config.php');

// Traitement de la suppression d'un employé
if (isset($_GET['supprimer_id'])) {
    $employe_id = $_GET['supprimer_id'];
    
    // Requête pour supprimer l'employé
    $stmt = $pdo->prepare("DELETE FROM employe WHERE id = ?");
    $stmt->execute([$employe_id]);

    // Redirection après suppression
    header('Location: gerer_employes.php');
    exit();
}

// Récupérer la liste des employés
$stmt = $pdo->prepare("SELECT * FROM employe");
$stmt->execute();
$employes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Employés</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5dc; /* Fond beige pour la page */
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Style de la barre latérale */
        .sidebar {
            width: 250px;
            background-color: #6f4f28; /* Sidebar marron */
            padding-top: 20px;
            position: fixed;
            height: 100vh;
            color: white;
            padding-left: 20px;
        }

        .sidebar h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #ecf0f1;
        }

        .sidebar a {
            display: block;
            color: #ecf0f1;
            text-decoration: none;
            padding: 12px 16px;
            font-size: 18px;
            margin: 5px 0;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #4e3629; /* Légèrement plus foncé au survol */
        }

        .container {
            margin-left: 270px;
            width: 80%;
            max-width: 1200px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #6f4f28; /* Titre en marron */
            font-size: 32px;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #6f4f28; /* En-têtes en marron */
            color: white;
        }

        td {
            background-color: #f4f4f4;
        }

        td a {
            color: #6f4f28; /* Liens en marron */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        td a:hover {
            color: #4e3629; /* Légèrement plus foncé au survol */
            text-decoration: underline;
        }

        .delete-btn {
            background-color: #8b4513; /* Bouton de suppression en marron clair */
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #6e3a1e; /* Légèrement plus foncé au survol */
        }

        .action-btns {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>

    <!-- Barre latérale -->
    <div class="sidebar">
        <h3>Admin Panel</h3>
        <a href="gerer_clients.php">Gestion des clients</a>
        <a href="register.php">Ajouter des utilisateurs</a>
        <a href="page_connexion.php">Se Déconnecter</a>
    </div>

    <div class="container">
        <h2>Liste des Employés</h2>

        <!-- Liste des employés -->
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
                <?php foreach ($employes as $employe): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employe['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($employe['nom']); ?></td>
                        <td><?php echo htmlspecialchars($employe['email']); ?></td>
                        <td class="action-btns">
                            <!-- Bouton Modifier -->
                            <a href="modifier_employe.php?id=<?php echo $employe['id']; ?>" class="edit-btn">Modifier</a>
                            <!-- Bouton Supprimer -->
                            <a href="gerer_employes.php?supprimer_id=<?php echo $employe['id']; ?>" class="delete-btn">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
