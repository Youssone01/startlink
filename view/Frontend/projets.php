<!-- projets.php -->
<?php include('include/header.php'); ?>
<?php include('include/navbar.php'); ?>

<!-- Ajout du CSS pour animation -->
<style>
    .fade-up {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s ease-out;
    }
    .fade-up.show {
        opacity: 1;
        transform: translateY(0);
    }
    .fade-left {
        opacity: 0;
        transform: translateX(-50px);
        transition: all 0.8s ease-out;
    }
    .fade-left.show {
        opacity: 1;
        transform: translateX(0);
    }
    .fade-right {
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.8s ease-out;
    }
    .fade-right.show {
        opacity: 1;
        transform: translateX(0);
    }
    .hover-zoom:hover {
        transform: scale(1.03);
        transition: 0.5s;
    }
</style>

<div class="container py-5">
    <h1 class="text-center mb-5 fade-up" style="color: rgb(12, 170, 205); font-weight: bold; font-family: 'Poppins', sans-serif;">Nos Projets Innovants</h1>

    <!-- Projet 1 -->
    <div class="row align-items-center mb-5 fade-left">
        <div class="col-lg-6">
            <img src="assets/img/Green.PNG" class="img-fluid rounded-4 shadow hover-zoom" alt="GreenHub" style="object-fit: cover; width: 100%; height: 350px;">
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <h2 style="color: #0a1b89; font-weight: bold;">GreenHub 🌱</h2>
            <p style="color: #555; font-size: 17px;">Un projet d'agriculture urbaine connectée pour transformer les toits de la ville en fermes écologiques intelligentes.</p>
            <a href="#" class="btn btn-primary rounded-pill px-4 py-2 mt-3 hover-zoom" style="background-color: #13a7cd; border: none;">Découvrir</a>
        </div>
    </div>

    <!-- Projet 2 -->
    <div class="row align-items-center mb-5 flex-lg-row-reverse fade-right">
        <div class="col-lg-6">
            <img src="assets/img/edu.jpg" class="img-fluid rounded-4 shadow hover-zoom" alt="EduSmart" style="object-fit: cover; width: 100%; height: 350px;">
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <h2 style="color: #0a1b89; font-weight: bold;">EduSmart 🎓</h2>
            <p style="color: #555; font-size: 17px;">Une plateforme éducative interactive qui utilise l'IA pour personnaliser les parcours d'apprentissage des étudiants.</p>
            <a href="#" class="btn btn-primary rounded-pill px-4 py-2 mt-3 hover-zoom" style="background-color: #13a7cd; border: none;">Découvrir</a>
        </div>
    </div>

    <!-- Projet 3 -->
    <div class="row align-items-center mb-5 fade-left">
        <div class="col-lg-6">
            <img src="assets/img/hop.PNG" class="img-fluid rounded-4 shadow hover-zoom" alt="HealthBridge" style="object-fit: cover; width: 100%; height: 350px;">
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <h2 style="color: #0a1b89; font-weight: bold;">HealthBridge 🏥</h2>
            <p style="color: #555; font-size: 17px;">HealthBridge connecte patients et médecins pour des soins de santé rapides et sécurisés via une application mobile.</p>
            <a href="#" class="btn btn-primary rounded-pill px-4 py-2 mt-3 hover-zoom" style="background-color: #13a7cd; border: none;">Découvrir</a>
        </div>
    </div>

    <!-- Boutons Action -->
    <div class="text-center mt-5 fade-up d-flex flex-column align-items-center gap-3">
        <a href="contact.php" class="btn btn-primary py-3 px-5 rounded-pill hover-zoom" style="background-color: rgb(19, 153, 190); border: none; font-weight: bold; font-size: 18px;">
            <i class="fas fa-lightbulb me-2"></i> Proposer un Projet
        </a>
        <a href="index.php" class="btn btn-outline-primary py-3 px-5 rounded-pill hover-zoom" style="border: 2px solid rgb(19, 153, 190); color: rgb(19, 153, 190); font-weight: bold; font-size: 18px;">
            <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil
        </a>
    </div>
</div>

<!-- Ajout du JS pour déclencher les animations -->
<script>
    function reveal() {
        var reveals = document.querySelectorAll(".fade-up, .fade-left, .fade-right");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 100;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("show");
            }
        }
    }

    window.addEventListener("scroll", reveal);
    window.addEventListener("load", reveal); // pour afficher si déjà visible au chargement
</script>

<?php include('include/footer.php'); ?>
