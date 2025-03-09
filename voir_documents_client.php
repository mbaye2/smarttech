<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est un client
if (!isset($_SESSION['user_id']) || $_SESSION['table'] !== 'client') {
    header('Location: page_connexion.php'); // Si l'utilisateur n'est pas un client ou non connecté, rediriger vers login
    exit();
}

include('config.php');

// Requête pour récupérer les documents
$stmt = $pdo->query("SELECT * FROM documents");
$documents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents disponibles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2e6d5; /* Beige clair pour le fond */
        }
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff8e1; /* Beige doux pour la zone principale */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre douce */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #6f4f1f; /* Marron foncé pour le titre */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #d7ccc8; /* Bordures beige/marron très claires */
        }
        th, td {
            padding: 12px;
            text-align: left;
            background-color: #f9f4e6; /* Beige très clair pour les lignes du tableau */
        }
        th {
            background-color: #6f4f1f; /* Marron foncé pour les en-têtes */
            color: white; /* Texte blanc pour les en-têtes */
        }
        td {
            color: #4e342e; /* Marron moyen pour le texte dans le tableau */
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #6f4f1f; /* Marron foncé pour le bouton */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #3e2723; /* Marron plus foncé au survol */
        }
        .btn-download {
            background-color: #8d6e63; /* Marron clair pour le bouton de téléchargement */
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-download:hover {
            background-color: #5d4037; /* Marron plus foncé au survol */
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Documents Disponibles</h2>

        <table>
            <thead>
                <tr>
                    <th>Nom du Document</th>
                    <th>Télécharger</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $document): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($document['nom_document']); ?></td>
                        <td>
                            <a href="uploads/<?php echo htmlspecialchars($document['chemin_document']); ?>" class="btn-download" download>Télécharger</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard_client.php" class="back-btn">Retour au Dashboard</a>
    </div>

</body>
</html>
