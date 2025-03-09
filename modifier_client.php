<?php
session_start();



// Connexion à la base de données
include('config.php');

// Vérifier si un identifiant de client est passé dans l'URL
if (!isset($_GET['id'])) {
    die("Identifiant du client manquant.");
}

$client_id = $_GET['id'];

// Récupérer les informations actuelles du client
$stmt = $pdo->prepare("SELECT * FROM client WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if (!$client) {
    die("Client non trouvé.");
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validation des données entrées
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);

    // Validation basique du format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("L'email n'est pas valide.");
    }

    // Mettre à jour les informations dans la base de données
    $stmt = $pdo->prepare("UPDATE client SET prenom = ?, nom = ?, email = ? WHERE id = ?");
    $stmt->execute([$prenom, $nom, $email, $client_id]);

    // Message de succès
    $success_message = "Les informations du client ont été mises à jour avec succès.";

    // Rediriger après la mise à jour
    header('Location: gerer_clients.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client</title>
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

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Modifier le Client</h2>

        <!-- Message de succès -->
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Formulaire de modification -->
        <form action="modifier_client.php?id=<?php echo $client['id']; ?>" method="POST">
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($client['prenom']); ?>" required>

            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($client['nom']); ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>

</body>
</html>
