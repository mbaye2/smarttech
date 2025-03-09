<?php
session_start();
include('config.php'); // Inclure la connexion à la base de données

// Variables pour les messages d'erreur et de succès
$error_message = '';
$success_message = '';

// Vérifier si un fichier a été téléchargé
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document'])) {
    $file_name = $_FILES['document']['name'];
    $file_tmp = $_FILES['document']['tmp_name'];
    $file_error = $_FILES['document']['error'];
    $file_size = $_FILES['document']['size'];

    // Vérifier s'il y a une erreur lors de l'upload
    if ($file_error === UPLOAD_ERR_OK) {
        // Vérifier l'extension du fichier (exemples : pdf, docx)
        $allowed_extensions = ['pdf', 'docx', 'txt', 'jpg', 'png'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        if (!in_array($file_extension, $allowed_extensions)) {
            $error_message = 'Seuls les fichiers PDF, DOCX, TXT, JPG et PNG sont autorisés.';
        } elseif ($file_size > 5000000) { // Limite de taille de fichier (5MB)
            $error_message = 'Le fichier est trop volumineux. La taille maximale autorisée est de 5MB.';
        } else {
            // Dossier où seront stockés les fichiers téléchargés
            $upload_directory = 'uploads/';
            $file_path = $upload_directory . basename($file_name);

            // Déplacer le fichier téléchargé vers le répertoire de destination
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Enregistrer les informations dans la base de données (nom et chemin du document)
                $stmt = $pdo->prepare("INSERT INTO documents (nom_document, chemin_document) VALUES (?, ?)");
                $stmt->execute([$file_name, $file_path]);

                $success_message = 'Le document a été téléchargé avec succès!';
            } else {
                $error_message = 'Une erreur est survenue lors du téléchargement du fichier.';
            }
        }
    } else {
        $error_message = 'Veuillez sélectionner un fichier valide.';
    }
}

// Récupérer tous les documents depuis la base de données
$stmt = $pdo->prepare("SELECT * FROM documents");
$stmt->execute();
$documents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Documents</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f1e5; /* Fond beige clair */
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Style de la barre latérale */
        .sidebar {
            width: 250px;
            background-color: #6c4f3d; /* Marron foncé */
            padding-top: 20px;
            position: fixed;
            height: 100vh;
            color: white;
            padding-left: 20px;
            flex-shrink: 0; /* Empêche le redimensionnement de la barre latérale */
        }

        .sidebar h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #fff; /* Blanc pour les titres de la barre latérale */
        }

        .sidebar a {
            display: block;
            color: #fff; /* Liens en blanc */
            text-decoration: none;
            padding: 12px 16px;
            font-size: 18px;
            margin: 5px 0;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #8e6e54; /* Marron plus clair au survol */
        }

        /* Conteneur principal */
        .container {
            margin-left: 250px; /* Décalage de la largeur de la barre latérale */
            width: calc(100% - 250px); /* Le reste de l'espace */
            background-color: #fff; /* Fond blanc pour le contenu */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            flex-grow: 1; /* Permet au conteneur de prendre tout l'espace restant */
        }

        h2 {
            text-align: center;
            color: #6c4f3d; /* Marron foncé pour les titres */
            font-size: 28px;
            margin-bottom: 30px;
        }

        .form-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-container input[type="file"] {
            border: 1px solid #ddd;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .form-container button {
            background-color: #6c4f3d; /* Marron pour le bouton */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #8e6e54; /* Marron clair au survol */
        }

        /* Messages de succès et erreur */
        .success, .error {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background-color: #28a745; /* Vert pour succès */
            color: white;
        }

        .error {
            background-color: #dc3545; /* Rouge pour erreur */
            color: white;
        }

        .document-list {
            margin-top: 30px;
        }

        .document-list table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .document-list table, th, td {
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #6c4f3d; /* Marron foncé pour les en-têtes */
            color: white;
        }

        td {
            background-color: #f9f9f9; /* Fond clair pour les cellules */
        }

        td a {
            color: #6c4f3d; /* Liens marron foncé */
            text-decoration: none;
            font-weight: bold;
        }

        td a:hover {
            text-decoration: underline; /* Soulignement des liens au survol */
        }

        /* Bouton Supprimer */
        .delete-btn {
            background-color: #dc3545; /* Rouge pour le bouton supprimer */
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #c82333; /* Rouge foncé au survol */
        }
    </style>
</head>
<body>

    <!-- Barre latérale -->
    <div class="sidebar">
        <h3>Admin Panel</h3>
        <a href="gerer_clients.php">Gestion des clients</a>
        <a href="gerer_documents.php">Gestion des documents</a>
        <a href="register.php">Ajouter des utilisateurs</a>
        <a href="page_connexion.php">Se Déconnecter</a>
    </div>

    <!-- Contenu principal -->
    <div class="container">
        <h2>Gestion des Documents</h2>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Formulaire de téléchargement de document -->
        <div class="form-container">
            <form action="gerer_documents.php" method="POST" enctype="multipart/form-data">
                <label for="document">Choisissez un document à télécharger :</label><br>
                <input type="file" name="document" id="document" required><br><br>
                <button type="submit">Télécharger le document</button>
            </form>
        </div>

        <!-- Liste des documents téléchargés -->
        <div class="document-list">
            <h3>Documents téléchargés</h3>
            <?php if (count($documents) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom du Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><a href="<?php echo $document['chemin_document']; ?>" target="_blank"><?php echo $document['nom_document']; ?></a></td>
                                <td><a href="supprimer_document.php?id=<?php echo $document['id']; ?>" class="delete-btn">Supprimer</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun document trouvé.</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
