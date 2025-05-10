<!-- accomp.php -->
<?php include('include/header.php'); ?>
<?php include('include/navbar.php'); ?>

<!-- Ajout du CSS pour animations -->
<style>
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }
    .fade-in.show {
        opacity: 1;
        transform: translateY(0);
    }
    .hover-scale:hover {
        transform: scale(1.03);
        transition: 0.5s;
    }
</style>

<div class="container py-5">
    <h1 class="text-center mb-5 fade-in" style="color: rgb(12, 170, 205); font-weight: bold; font-family: 'Poppins', sans-serif;">
        Notre Accompagnement en Ligne
    </h1>

    <!-- Section Hero -->
    <div class="row align-items-center mb-5 fade-in">
        <div class="col-lg-6">
            <img src="assets/img/coach.PNG" alt="Coaching Personnalisé" class="img-fluid rounded-4 shadow hover-scale" style="object-fit: cover; height: 400px;">
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <h2 style="color: #0a1b89; font-weight: bold;">Coaching 100% Personnalisé 👨‍💻</h2>
            <p style="color: #555; font-size: 18px;">Profitez de séances individuelles avec des experts qui vous aident à structurer vos idées, clarifier vos objectifs et accélérer vos projets. L'accompagnement s'adapte à votre rythme et vos besoins.</p>
            <a href="contact.php" class="btn btn-primary rounded-pill px-5 py-2 mt-3 hover-scale" style="background-color: #13a7cd; border: none;">Réserver une séance</a>
        </div>
    </div>

    <!-- Section Avantages sous forme de Grille -->
    <div class="text-center mb-5">
        <h2 class="fade-in" style="color: #0a1b89; font-weight: bold;">Pourquoi Choisir StartLink ?</h2>
        <div class="row g-4 mt-4">
            <div class="col-md-4 fade-in">
                <div class="bg-light rounded-4 shadow-sm p-4 hover-scale" style="height: 100%;">
                    <i class="fas fa-laptop-code fa-3x mb-3" style="color: #0a1b89;"></i>
                    <h5 style="font-weight: bold;">Accès en ligne 24/7</h5>
                    <p>Suivez vos formations, participez à vos coachings où et quand vous voulez.</p>
                </div>
            </div>
            <div class="col-md-4 fade-in">
                <div class="bg-light rounded-4 shadow-sm p-4 hover-scale" style="height: 100%;">
                    <i class="fas fa-users fa-3x mb-3" style="color: #0a1b89;"></i>
                    <h5 style="font-weight: bold;">Réseau d'experts</h5>
                    <p>Bénéficiez de conseils d'entrepreneurs expérimentés et de coachs certifiés.</p>
                </div>
            </div>
            <div class="col-md-4 fade-in">
                <div class="bg-light rounded-4 shadow-sm p-4 hover-scale" style="height: 100%;">
                    <i class="fas fa-rocket fa-3x mb-3" style="color: #0a1b89;"></i>
                    <h5 style="font-weight: bold;">Boostez votre projet</h5>
                    <p>Accélérez votre passage de l'idée à l'action avec un accompagnement pragmatique.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Témoignage -->
    <div class="row align-items-center mb-5 fade-in">
        <div class="col-lg-6">
            <blockquote class="blockquote p-4 rounded-4 shadow bg-light">
                <p style="font-style: italic; font-size: 18px;">"Grâce à l'accompagnement StartLink, j'ai pu structurer mon business plan et trouver mes premiers financements. Le suivi est professionnel et bienveillant."</p>
                <footer class="blockquote-footer mt-3">Nour Ben Salem, Fondatrice de BioCosmétique</footer>
            </blockquote>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <img src="assets/img/testimonial-1.jpg" alt="Témoignage Client" class="img-fluid rounded-circle hover-scale shadow" style="width: 250px; height: 250px; object-fit: cover;">
        </div>
    </div>

    <!-- Appel à action + Bouton Retour à l'accueil -->
    <div class="text-center fade-in mt-5 d-flex flex-column align-items-center gap-3">
        <h2 style="color: #0a1b89; font-weight: bold;">Prêt à transformer vos idées en succès ? 🚀</h2>

        <a href="contact.php" class="btn btn-primary rounded-pill px-5 py-3 hover-scale" 
           style="background-color: #13a7cd; border: none; font-size: 20px;">
            Contactez un Coach StartLink
        </a>

        <a href="index.php" class="btn btn-outline-primary py-3 px-4 rounded-pill hover-scale"
           style="border: 2px solid rgb(19, 153, 190); color: rgb(19, 153, 190); font-weight: bold; font-size: 18px;">
            <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil
        </a>
    </div>

</div>

<!-- Animation script -->
<script>
    function reveal() {
        var reveals = document.querySelectorAll(".fade-in");
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
    window.addEventListener("load", reveal);
</script>

<?php include('include/footer.php'); ?>
