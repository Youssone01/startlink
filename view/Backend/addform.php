<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Formation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fb;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #0a1b89;
            font-size: 2em;
        }
        label {
            font-size: 1.1em;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }
        input[type="text"], input[type="number"], input[type="date"], select, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }
        textarea {
            resize: vertical;
        }
        button {
            background-color: #0a1b89;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1.2em;
        }
        button:hover {
            background-color: #0a167a;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 1.1em;
        }
        .success {
            background-color: #4CAF50;
            color: white;
        }
        .error {
            background-color: #f44336;
            color: white;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0a1b89;
        }
        a:hover {
            color: #0a167a;
        }
        .error-input {
            border-color: red;
        }
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: -10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Ajouter une Formation</h2>

    <?php if ($message): ?>
        <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="addform.php" id="formationForm">
        <label for="nomFormation">Nom de la formation</label>
        <input type="text" name="nomFormation" id="nomFormation">
        <div class="error-message" id="error-nomFormation"></div>

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"></textarea>
        <div class="error-message" id="error-description"></div>

        <label for="duree">Durée (heures)</label>
        <input type="number" name="duree" id="duree">
        <div class="error-message" id="error-duree"></div>

        <label for="niveau">Niveau</label>
        <select name="niveau" id="niveau">
            <option value="débutant">Débutant</option>
            <option value="intermédiaire">Intermédiaire</option>
            <option value="avancé">Avancé</option>
        </select>
        <div class="error-message" id="error-niveau"></div>

        <label for="date_debut">Date de début</label>
        <input type="date" name="date_debut" id="date_debut">
        <div class="error-message" id="error-date_debut"></div>

        <label for="date_fin">Date de fin</label>
        <input type="date" name="date_fin" id="date_fin">
        <div class="error-message" id="error-date_fin"></div>

        <label for="places_disponibles">Places disponibles</label>
        <input type="number" name="places_disponibles" id="places_disponibles">
        <div class="error-message" id="error-places_disponibles"></div>

        <button type="submit">Ajouter la formation</button>
    </form>

    <a href="formation_bo.php">← Retour à la liste des formations</a>
</div>

<script>
document.getElementById("formationForm").addEventListener("submit", function(e) {
    let nom = document.getElementById("nomFormation").value.trim();
    let desc = document.getElementById("description").value.trim();
    let niveau = document.getElementById("niveau").value;
    let dateDebut = document.getElementById("date_debut").value;
    let dateFin = document.getElementById("date_fin").value;
    let places = document.getElementById("places_disponibles").value;

    let erreurs = [];
    let valid = true;

    // Clear previous error messages and styles
    document.querySelectorAll(".error-input").forEach(input => input.classList.remove("error-input"));
    document.querySelectorAll(".error-message").forEach(error => error.textContent = "");

    // Validation for each field
    if (nom === "") {
        erreurs.push("Le nom de la formation est requis.");
        document.getElementById("error-nomFormation").textContent = "Le nom de la formation est requis.";
        document.getElementById("nomFormation").classList.add("error-input");
        valid = false;
    }
    if (desc === "") {
        erreurs.push("La description est requise.");
        document.getElementById("error-description").textContent = "La description est requise.";
        document.getElementById("description").classList.add("error-input");
        valid = false;
    }
    
    if (!["débutant", "intermédiaire", "avancé"].includes(niveau)) {
        erreurs.push("Le niveau est invalide.");
        document.getElementById("error-niveau").textContent = "Le niveau est invalide.";
        document.getElementById("niveau").classList.add("error-input");
        valid = false;
    }
    if (!dateDebut || !dateFin || new Date(dateDebut) >= new Date(dateFin)) {
        erreurs.push("Les dates sont invalides ou incohérentes.");
        document.getElementById("error-date_debut").textContent = "Les dates sont invalides ou incohérentes.";
        document.getElementById("date_debut").classList.add("error-input");
        document.getElementById("error-date_fin").textContent = "Les dates sont invalides ou incohérentes.";
        document.getElementById("date_fin").classList.add("error-input");
        valid = false;
    }
    if (places === "" || isNaN(places) || places <= 0) {
        erreurs.push("Le nombre de places doit être supérieur à 0.");
        document.getElementById("error-places_disponibles").textContent = "Le nombre de places doit être supérieur à 0.";
        document.getElementById("places_disponibles").classList.add("error-input");
        valid = false;
    }

    // Prevent form submission if there are errors
    if (!valid) {
        e.preventDefault();
    }
});
</script>

</body>
</html>
