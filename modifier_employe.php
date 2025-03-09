<?php
session_start();

// Connexion à la base de données
include('config.php');

// Vérifier si un identifiant d'employé est passé dans l'URL
if (!isset($_GET['id'])) {
    die("Identifiant de l'employé manquant.");
}

$employe_id = $_GET['id'];

// Récupérer les informations actuelles de l'employé
$stmt = $pdo->prepare("SELECT * FROM employe WHERE id = ?");
$stmt->execute([$employe_id]);
$employe = $stmt->fetch();

if (!$employe) {
    die("Employé non trouvé.");
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nettoyage et validation des données
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    
    // Validation basique de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'email n'est pas valide.";
    } else {
        // Mettre à jour les informations dans la base de données
        $stmt = $pdo->prepare("UPDATE employe SET prenom = ?, nom = ?, email = ? WHERE id = ?");
        $stmt->execute([$prenom, $nom, $email, $employe_id]);

        // Message de succès
        $success_message = "Les informations de l'employé ont été mises à jour avec succès.";

        // Rediriger après la mise à jour
        header('Location: gerer_employes.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Employé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f1e1; /* Beige clair */
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff5e1; /* Beige pâle */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #4e2a1c; /* Marron foncé */
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #4e2a1c; /* Marron foncé */
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #b49e83; /* Marron clair */
            border-radius: 4px;
            background-color: #f9f1e3; /* Beige très clair */
            color: #4e2a1c; /* Marron foncé */
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #7f4b35; /* Marron moyen */
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #4e2a1c; /* Marron foncé au survol */
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Modifier l'Employé</h2>

        <!-- Message d'erreur -->
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Message de succès -->
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Formulaire de modification -->
        <form action="modifier_employe.php?id=<?php echo $employe['id']; ?>" method="POST">
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($employe['prenom']); ?>" required>

            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($employe['nom']); ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($employe['email']); ?>" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>

</body>
</html>
