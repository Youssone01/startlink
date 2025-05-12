<?php
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/certifC.php';

$certifC = new CertifC();

if (isset($_GET['id'])) {
    $id_certification = $_GET['id'];
    $certif = $certifC->recupererCertification($id_certification);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_user = $_POST['id_user'];
        $id_formation = $_POST['id_formation'];
        $date_obtention = $_POST['date_obtention'];
        $score_quiz = $_POST['score_quiz'];

        $certifC->modifierCertification($id_certification, $id_user, $id_formation, $date_obtention, $score_quiz,);
        header('Location: listcertif.php');
        exit;
    }
} else {
    echo "ID de certification non fourni.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Certification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            padding: 30px 40px;
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.6s ease-in-out;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #2d5b98;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        label {
            width: 100%;
            text-align: left;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4a4a4a;
        }

        input {
            width: 90%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #2d5b98;
            box-shadow: 0px 0px 8px rgba(45, 91, 152, 0.3);
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            width: 100%;
            gap: 10px;
        }

        button {
            flex: 1;
            padding: 12px;
            background-color: #2d5b98;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #1c4567;
            transform: translateY(-2px);
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        }

        button:active {
            transform: translateY(0);
            box-shadow: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier la Certification</h2>
        <form method="POST">
            <!-- Champs masqués -->
            <input type="hidden" name="id_user" value="<?= $certif['id_user']; ?>">
            <input type="hidden" name="id_formation" value="<?= $certif['id_formation']; ?>">

            <label for="date_obtention">Date d'Obtention:</label>
            <input type="date" id="date_obtention" name="date_obtention" value="<?= $certif['date_obtention']; ?>" required>

            <label for="score_quiz">Score Quiz:</label>
            <input type="number" id="score_quiz" name="score_quiz" value="<?= $certif['score_quiz']; ?>" required>

            
            <div class="button-group">
                <button type="submit">Mettre à jour</button>
                <button type="button" onclick="window.location.href='listcertif.php';">Retour</button>
            </div>
        </form>
    </div>
</body>
</html>
