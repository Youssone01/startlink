<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=startlink", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$isInvestor = false;
$fullname = '';

if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'investisseur') {
    $isInvestor = true;
    $fullname = $_SESSION['user']['fullname'];
}

if (isset($_POST['confirm_investor'])) {
    $email = trim($_POST['email']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT fullname FROM users WHERE email = ? AND role = 'investisseur'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $isInvestor = true;
            $fullname = $user['fullname'];
        } else {
            $error = "❌ Cet email n’est pas reconnu comme investisseur.";
        }
    } else {
        $error = "❌ Adresse email invalide.";
    }
}
?>
<?php if (isset($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>StartLink | Publier une Offre</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #cceeff, #e6f7ff);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 60px auto;
            padding: 40px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
            position: relative;
        }

        h2 {
            color: #0a1b89;
            text-align: center;
            margin-bottom: 30px;
            font-size: 26px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="email"],
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #b0c4de;
            font-size: 15px;
            background-color: #f7fbff;
        }

        button {
            background: linear-gradient(to right, #0a1b89, #1e3fc8);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0a3fb1;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: #0a1b89;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .tag {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
            color: #0a1b89;
            font-weight: bold;
        }

        .logo-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #0a1b89;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: bold;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo-circle">S</div>

    <?php if (!$isInvestor): ?>
        <h2>❓ Es-tu investisseur ?</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Entrez votre email pour continuer" required>
            <button type="submit" name="confirm_investor">✅ Continuer</button>
            <a class="back-link" href="index.php">⬅ Retour</a>


        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    <?php else: ?>
        <h2>📝 Nouvelle offre d’emploi</h2>
        <div class="tag"><?= htmlspecialchars($fullname) ?>@investisseur.com</div>
        <form action="index.php?action=saveOffer" method="post">
            <input type="text" name="titre" placeholder="Titre du poste" required>
            <textarea name="description" rows="4" placeholder="Description du poste" required></textarea>
            <input type="text" name="lieu" placeholder="Lieu" required>
            <select name="type_contrat" required>
                <option value="">-- Type de contrat --</option>
                <option value="CDI">CDI</option>
                <option value="CDD">CDD</option>
                <option value="Stage">Stage</option>
                <option value="Freelance">Freelance</option>
            </select>
            <input type="number" name="salaire" placeholder="Salaire (€)" min="0" required>
            <input type="date" name="date_publication" id="date_publication" required readonly>
            <input type="text" name="entreprise" placeholder="Nom de l'entreprise" required>
            <button type="submit">✅ Publier l’offre</button>
        </form>
        <a class="back-link" href="index.php">⬅ Retour</a>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.getElementById("date_publication").value = new Date().toISOString().split('T')[0];
            });
        </script>
    <?php endif; ?>
</div>


</body>
</html>
