<?php include('include/header.php'); ?>  
<?php include('include/navbar.php'); ?>

<style>
    .hover-zoom {
        background-color: #f0fafc;
        transition: all 0.4s ease;
        cursor: pointer;
    }

    .hover-zoom:hover,
    .hover-zoom:active {
        transform: scale(1.05); /* Grandissement léger */
        background-color: #c9eef7; /* Fond bleu clair au hover */
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* Ombre plus marquée */
    }

    .hover-zoom:hover h5,
    .hover-zoom:hover p,
    .hover-zoom:hover i,
    .hover-zoom:active h5,
    .hover-zoom:active p,
    .hover-zoom:active i {
        color: #0a1b89; /* Texte et icône bleu StartLink au hover/clic */
    }

    /* Animation de l'apparition du coach virtuel */
    #coach-virtuel {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }
</style>

<div class="container py-5">
    <h1 class="text-center mb-4" style="color:rgb(12, 170, 205); font-weight: bold; font-family: 'Poppins', sans-serif;">Nos Formations Qualifiées</h1>
    <p class="text-center mb-5" style="font-size: 18px; color: #666; font-family: 'Poppins', sans-serif;">
        Découvrez nos programmes conçus pour vous aider à exceller dans l'entrepreneuriat et l'innovation. 🚀
    </p>

    <div class="row g-5">
        <!-- Formation Business Plan -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-zoom" style="transition: all 0.3s;">
                <img src="assets/img/FQ1.PNG" class="card-img-top rounded-top-4" alt="Formation Business Plan" style="height: 250px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #0a1b89; font-weight: bold; font-family: 'Poppins', sans-serif;">Formation Business Plan</h5>
                    <p class="card-text" style="color: #555; font-family: 'Poppins', sans-serif;">Apprenez à construire un business plan impactant pour convaincre vos partenaires et investisseurs.</p>
                </div>
            </div>
        </div>

        <!-- Formation Marketing Digital -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-zoom" style="transition: all 0.3s;">
                <img src="assets/img/MG.PNG" class="card-img-top rounded-top-4" alt="Formation Marketing Digital" style="height: 250px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #0a1b89; font-weight: bold; font-family: 'Poppins', sans-serif;">Formation Marketing Digital</h5>
                    <p class="card-text" style="color: #555; font-family: 'Poppins', sans-serif;">Maîtrisez les nouvelles techniques de marketing pour développer votre présence en ligne et booster votre activité.</p>
                </div>
            </div>
        </div>

        <!-- Formation Introduction Informatique & Développement -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-zoom" style="transition: all 0.3s;">
                <img src="assets/img/IT.PNG" class="card-img-top rounded-top-4" alt="Formation Informatique" style="height: 250px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #0a1b89; font-weight: bold; font-family: 'Poppins', sans-serif;">Introduction à l'Informatique & Développement</h5>
                    <p class="card-text" style="color: #555; font-family: 'Poppins', sans-serif;">Découvrez les bases de l'informatique, de la programmation, et initiez-vous au monde du développement web et logiciel.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 d-flex flex-column align-items-center gap-3">
        <!-- Bouton Contact -->
        <a href="contact.php" class="btn btn-primary py-3 px-4 rounded-pill" style="background-color:rgb(19, 153, 190); border: none; font-weight: bold; font-family: 'Poppins', sans-serif; font-size: 18px;">
            <i class="fas fa-envelope me-2"></i>Contactez-nous pour plus d'informations
        </a>

        <!-- Bouton Retour Accueil -->
        <a href="index.php" class="btn btn-outline-primary py-3 px-4 rounded-pill" style="border: 2px solid rgb(19, 153, 190); color: rgb(19, 153, 190); font-weight: bold; font-family: 'Poppins', sans-serif; font-size: 18px;">
            <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
        </a>
    </div>
</div>

<?php
session_start(); // Assure-toi que la session est démarrée

// Connexion et récupération des formations
try {
    $pdo = new PDO("mysql:host=localhost;dbname=startlink", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT idFormation, nomFormation, description, places_disponibles FROM formations");
    $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    $formations = [];
}
?>

<?php if (!empty($formations)): ?>
<!-- Coach Virtuel -->
<div id="coach-virtuel" class="position-fixed bottom-0 end-0 m-4" style="z-index: 9999; max-width: 370px;">
    <div class="card shadow-lg rounded-4 border-0" style="background-color: #e6f8fc;">
        <div class="card-header bg-white rounded-top-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0" style="color: #1399be; font-weight: bold;">👨‍🏫 Coach Virtuel</h5>
            <button id="closeCoach" class="btn btn-sm text-danger">&times;</button>
        </div>
        <div class="card-body" style="font-family: 'Poppins', sans-serif;">
            <p style="color: #333;">Choisissez une formation selon vos besoins :</p>
            <select id="coach-select" class="form-select mb-3">
                <option value="">-- Sélectionnez une formation --</option>
                <?php foreach ($formations as $formation): ?>
                    <option 
                        value="<?= $formation['idFormation'] ?>" 
                        data-desc="<?= htmlspecialchars($formation['description']) ?>" 
                        data-places="<?= $formation['places_disponibles'] ?>">
                        <?= htmlspecialchars($formation['nomFormation']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div id="coach-reponse" class="text-primary fw-bold mb-3" style="min-height: 70px;"></div>

            <a id="inscription-link" href="#" class="btn btn-primary w-100 d-none" style="background-color: #1399be; border: none;">
                Je veux m'inscrire 🚀
            </a>
        </div>
    </div>
</div>

<script>
document.getElementById('closeCoach').addEventListener('click', function () {
    document.getElementById('coach-virtuel').style.display = 'none';
});

document.getElementById('coach-select').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const desc = selectedOption.getAttribute('data-desc');
    const places = selectedOption.getAttribute('data-places');
    const idFormation = selectedOption.value;

    const reponseDiv = document.getElementById('coach-reponse');
    const link = document.getElementById('inscription-link');

    if (idFormation) {
        let encouragement = places > 0 
            ? `🎯 Il reste <strong>${places}</strong> places. Saisissez votre chance !` 
            : `<span class="text-danger">❌ Plus de places disponibles actuellement.</span>`;
        reponseDiv.innerHTML = `<strong>${selectedOption.text}</strong><br>${desc}<br><br>${encouragement}`;
        
        if (places > 0) {
            link.href = `inscription.php?idFormation=${idFormation}`;
            link.classList.remove('d-none');
        } else {
            link.classList.add('d-none');
        }
    } else {
        reponseDiv.innerHTML = "";
        link.classList.add('d-none');
    }
});

// Vérification si utilisateur non connecté
document.getElementById('inscription-link').addEventListener('click', function (e) {
    const isConnected = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    if (!isConnected) {
        e.preventDefault(); // Empêche la redirection immédiate
        window.location.href = 'login.php'; // Redirige vers login
    }
});
</script>
<?php endif; ?>

<?php include('include/footer.php'); ?>
