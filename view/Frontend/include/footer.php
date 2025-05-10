  <!-- Footer Start -->
  <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Liens rapides</h4>
                    <a class="btn btn-link" href="">À propos</a>
                    <a class="btn btn-link" href="">Contact</a>
                    <a class="btn btn-link" href="">Politique de confidentialité</a>
                    <a class="btn btn-link" href="">Conditions d'utilisation</a>
                    <a class="btn btn-link" href="">FAQs & Aide</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Centre StartLink, Tunis, Tunisie</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>contact@startlink.tn</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Galerie</h4>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="assets/img/course-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="assets/img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="assets/img/course-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="assets/img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="assets/img/course-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="assets/img/course-1.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Newsletter</h4>
                    <p>Inscrivez-vous pour recevoir les dernières nouveautés et opportunités de StartLink.</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">S'inscrire</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">StartLink</a>, Tous droits réservés.

                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                        Conçu avec ❤️ par  <a class="border-bottom" href="https://htmlcodex.com">Esprit Student</a>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Accueil</a>
                            <a href="">Cookies</a>
                            <a href="">Aide</a>
                            <a href="">FQAs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- 1. Votre configuration initiale du chatbot -->
<script>
  // Fonction basique de détection de langue (français vs anglais)
  function detectLang(text) {
    return /[éèàùçœ]/i.test(text) ? 'fr' : 'en';
  }

  // Configuration du widget Chatbase
  window.embeddedChatbotConfig = {
    chatbotId: "ZHhrn8z3J_f4b66hccSqY",
    domain: "www.chatbase.co",
    model: "gpt-4",
    onUserMessage: (msg) => {
      // On enregistre la langue détectée dans les métadonnées (optionnel)
      msg.metadata = { language: detectLang(msg.text) };
    },
    onBotResponse: (resp) => {
      // Vous pouvez aussi agir sur la réponse ici si besoin
    }
  };

  // 2. Lorsque le widget est chargé,  "StartLink"
  document.addEventListener('cb-widget-loaded', () => {
    const titleEl = document.querySelector('.cb-chat-header .cb-title');
    if (titleEl) {
      titleEl.textContent = 'StartLink';
    }
  });
</script>

<!-- 3. Chargement du SDK Chatbase -->
<script src="https://www.chatbase.co/embed.min.js" defer></script>