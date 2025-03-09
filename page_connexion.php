<?php
session_start();
include('config.php'); // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_message = "Veuillez remplir tous les champs.";
    } else {
        $tables = ['admin', 'client', 'employe'];
        foreach ($tables as $table) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $table;
                header('Location: accueil_' . $table . '.php');
                exit();
            }
        }
        $error_message = "Identifiants incorrects. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter à votre espace</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #D3B89E, #8B5A2B); /* Dégradé beige vers marron */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: #F8F1E5; /* Fond beige clair */
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #6c4f3d; /* Marron foncé pour le titre */
        }

        input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #B69A6E; /* Bordure marron clair */
            border-radius: 5px;
            background-color: #FFF; /* Fond blanc pour les champs */
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #8B5A2B; /* Marron moyen pour le bouton */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #6C4F3D; /* Marron foncé au survol */
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Se connecter à votre espace</h2>

        <?php if (isset($error_message)): ?>
            <div class="error-message"> <?php echo $error_message; ?> </div>
        <?php endif; ?>

        <form action="page_connexion.php" method="POST">
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" class="btn-login">Accéder</button>
        </form>
    </div>
</body>
</html>
