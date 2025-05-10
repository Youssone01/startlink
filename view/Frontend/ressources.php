<!-- ressources.php -->
<?php include('include/header.php'); ?>
<?php include('include/navbar.php'); ?>

<!-- CSS Animations -->
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
        transform: scale(1.05);
        transition: 0.5s;
    }
</style>

<div class="container py-5">

    <!-- Hero Section -->
    <div class="text-center fade-in mb-5">
        <h1 style="color: rgb(12, 170, 205); font-weight: bold; font-family: 'Poppins', sans-serif;">Boostez vos Compétences avec nos Ressources</h1>
        <p class="mt-3" style="color: #555; font-size: 18px;">Des outils puissants, des supports exclusifs et des guides pratiques pour concrétiser vos ambitions.</p>
    </div>

    <!-- Grille Ressources -->
    <div class="row g-5">
        <div class="col-lg-6 fade-in">
            <div class="bg-light rounded-4 shadow-sm hover-scale p-4 d-flex flex-column h-100 justify-content-between">
                <div>
                    <h3 style="color: #0a1b89; font-weight: bold;">E-Books Stratégiques 📚</h3>
                    <p style="color: #555;">Accédez à des guides clairs et actionnables pour structurer vos projets, négocier, financer et innover efficacement.</p>
                </div>
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-primary rounded-pill px-4" style="background-color: #13a7cd;">Télécharger</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 fade-in">
            <div class="bg-light rounded-4 shadow-sm hover-scale p-4 d-flex flex-column h-100 justify-content-between">
                <div>
                    <h3 style="color: #0a1b89; font-weight: bold;">Ateliers Vidéos 🎥</h3>
                    <p style="color: #555;">Des vidéos pédagogiques animées par nos experts pour booster votre savoir-faire en marketing, pitch, finance, etc.</p>
                </div>
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-primary rounded-pill px-4" style="background-color: #13a7cd;">Voir les vidéos</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12 fade-in">
            <div class="bg-light rounded-4 shadow-sm hover-scale p-4 d-flex flex-column h-100 justify-content-between">
                <div>
                    <h3 style="color: #0a1b89; font-weight: bold;">Templates & Modèles Business 📄</h3>
                    <p style="color: #555;">Téléchargez des modèles prêts à l'emploi pour vos business plans, prévisions financières, pitch decks et stratégies digitales.</p>
                </div>
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-primary rounded-pill px-4" style="background-color: #13a7cd;">Télécharger les modèles</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Témoignage -->
    <div class="row align-items-center mt-5 fade-in">
        <div class="col-lg-6">
            <blockquote class="blockquote p-4 rounded-4 shadow bg-white">
                <p style="font-style: italic; font-size: 18px;">"Grâce aux ressources StartLink, j'ai pu professionnaliser mon projet rapidement et convaincre mes premiers investisseurs."</p>
                <footer class="blockquote-footer mt-3">Youssef Ben Salah, Fondateur de GreenTech</footer>
            </blockquote>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0 text-center">
            <img src="assets/img/testimonial-2.jpg" alt="Témoignage" class="img-fluid rounded-circle hover-scale shadow" style="width: 250px; height: 250px; object-fit: cover;">
        </div>
    </div>

    <!-- Appel à Action + Bouton Retour -->
    <div class="text-center fade-in mt-5 d-flex flex-column align-items-center gap-3">
        <h2 style="color: #0a1b89; font-weight: bold;">Débloquez tout votre potentiel avec StartLink 🚀</h2>

        <a href="contact.php" class="btn btn-primary py-3 px-5 rounded-pill hover-scale" 
           style="background-color: rgb(19, 153, 190); font-size: 18px;">
            Nous Contacter
        </a>

        <a href="index.php" class="btn btn-outline-primary py-3 px-5 rounded-pill hover-scale" 
           style="border: 2px solid rgb(19, 153, 190); color: rgb(19, 153, 190); font-size: 18px;">
            <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil
        </a>
    </div>

</div>

<!-- Script Animation -->
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
