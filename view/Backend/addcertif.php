<?php
// Inclure le fichier de configuration et le contrôleur CertifC
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/certifC.php';

$certifC = new CertifC();
?>

<!-- CSS pour améliorer le design -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #D1E8FF;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    h2 {
        font-size: 1.8rem;
        color: #004b87;
        font-weight: 600;
        margin-bottom: 30px;
        text-align: center;
        margin-top: 20px;
    }

    .form-container {
        background-color: #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 30px;
        width: 100%;
        max-width: 500px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-size: 1.1rem;
        color: #333;
        font-weight: 500;
        display: block;
    }

    input, select {
        width: 100%;
        padding: 12px;
        font-size: 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #ecf0f1;
    }

    input:focus, select:focus {
        border-color: #2980b9;
        outline: none;
        background-color: #fff;
    }

    button {
        width: 100%;
        padding: 14px;
        background-color: #2980b9;
        color: #fff;
        font-size: 1.2rem;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    button:hover {
        background-color: #1e3d8d;
        transform: scale(1.05);
    }

    .message-box {
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 8px;
        color: #fff;
        background-color: #e74c3c;
        font-size: 1rem;
        font-weight: 500;
        text-align: center;
        display: none;
    }
</style>

<!-- Contenu Principal -->
<div class="content">
    <h2>Ajouter une certification</h2>

    <div class="form-container">
        <form id="certifForm" action="insertcertif.php" method="POST">
            <div class="message-box" id="messageBox"></div>
            <div class="form-group">
                <label for="id_user">ID Utilisateur</label>
                <input type="text" id="id_user" name="id_user">
            </div>
            <div class="form-group">
                <label for="id_formation">ID Formation</label>
                <input type="text" id="id_formation" name="id_formation">
            </div>
            <div class="form-group">
                <label for="date_obtention">Date d'Obtention</label>
                <input type="date" id="date_obtention" name="date_obtention">
            </div>
            <div class="form-group">
                <label for="score_quiz">Score Quiz</label>
                <input type="number" id="score_quiz" name="score_quiz" min="0" max="100">
            </div>
            <div class="form-group">
                <label for="statut">Statut</label>
                <select id="statut" name="statut">
                    <option value="">Choisir un statut</option>
                    <option value="Validée">Validée</option>
                    <option value="Non Validée">Non Validée</option>
                </select>
            </div>
            <button type="submit">Ajouter</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('certifForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let messageBox = document.getElementById('messageBox');
        let message = '';
        let isValid = true;

        const id_user = document.getElementById('id_user').value.trim();
        const id_formation = document.getElementById('id_formation').value.trim();
        const date_obtention = document.getElementById('date_obtention').value.trim();
        const score_quiz = document.getElementById('score_quiz').value.trim();
        const statut = document.getElementById('statut').value.trim();

        // Vérification des champs
        if (!id_user && !id_formation && !date_obtention && !score_quiz && !statut) {
            message = 'Veuillez renseigner les champs.';
            isValid = false;
        } else if (!id_user) {
            message = 'Veuillez renseigner le champ ID Utilisateur.';
            isValid = false;
        } else if (!id_formation) {
            message = 'Veuillez renseigner le champ ID Formation.';
            isValid = false;
        } else if (!date_obtention) {
            message = 'Veuillez renseigner le champ Date d\'Obtention.';
            isValid = false;
        } else {
            const date = new Date(date_obtention);
            const now = new Date();
            const oneYearAgo = new Date();
            oneYearAgo.setFullYear(now.getFullYear() - 1);
            if (date < oneYearAgo) {
                message = 'La date d\'obtention dépasse un an.';
                isValid = false;
            }
        }

        if (!score_quiz) {
            message = 'Veuillez renseigner le champ Score Quiz.';
            isValid = false;
        } else if (!statut) {
            message = 'Veuillez sélectionner un statut.';
            isValid = false;
        }

        // Affichage du message ou soumission du formulaire
        if (!isValid) {
            messageBox.textContent = message;
            messageBox.style.display = 'block';
        } else {
            messageBox.style.display = 'none';
            this.submit();
        }
    });
</script>
