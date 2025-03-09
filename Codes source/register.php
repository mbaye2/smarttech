<?php
session_start();
include('config.php'); // Inclure la connexion à la base de données

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire et sécuriser les entrées
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $role = $_POST['role']; // Récupérer le rôle choisi

    // Vérifier que tous les champs sont remplis
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($role)) {
        $error_message = "Tous les champs doivent être remplis.";
    } else {
        // Vérifier si l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "L'email n'est pas valide.";
        } else {
            // Vérifier si l'email est déjà utilisé
            $stmt = $pdo->prepare("SELECT * FROM $role WHERE email = ?");
            $stmt->execute([$email]);
            $existing_user = $stmt->fetch();

            if ($existing_user) {
                $error_message = "L'email est déjà utilisé.";
            } else {
                // Hacher le mot de passe
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insérer les données dans la table correspondante en fonction du rôle
                $insert_query = "INSERT INTO $role (nom, prenom, email, password) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($insert_query);
                if ($stmt->execute([$nom, $prenom, $email, $hashed_password])) {
                    // Message de succès
                    $success_message = "Utilisateur ajouté avec succès.";
                    // Rediriger l'utilisateur vers la page de connexion
                    header('Location: login.php');
                    exit();
                } else {
                    $error_message = "Une erreur est survenue lors de l'ajout de l'utilisateur.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5dc; /* Fond beige pour la page */
            margin: 0;
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
            background-color: #5a3e22; /* Couleur au survol de la sidebar */
        }

        /* Conteneur principal */
        .container {
            margin-left: 270px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #6f4f28; /* Bouton marron */
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #5a3e22; /* Couleur au survol du bouton */
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
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
        <h2>Ajout d'autres utilisateurs</h2>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" required>

            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Rôle</label>
            <select name="role" id="role" required>
                <option value="admin">Administrateur</option>
                <option value="client">Client</option>
                <option value="employe">Employé</option>
            </select>

            <button type="submit">Ajouter</button>
        </form>
    </div>

</body>
</html>
