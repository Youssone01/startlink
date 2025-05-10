<?php include('include/header.php') ?>

 <body>
     <?php include('include/spinner.php') ?>


     <?php include('include/navbar.php') ?>

<style>
     .service-item {
    background-color: #f0fafc;
    transition: background-color 0.3s, color 0.3s;
    cursor: pointer;
}

.service-item:hover,
.service-item:active {
    background-color: #c9eef7; /* Bleu plus vif au survol et au clic */
}

.service-item:hover h5,
.service-item:hover p,
.service-item:hover i,
.service-item:active h5,
.service-item:active p,
.service-item:active i {
    color:rgb(32, 142, 197); /* Texte et icône en bleu foncé StartLink */
}

</style>
     <!-- Carousel Start -->
     <div class="container-fluid p-0 mb-5">
         <div class="owl-carousel header-carousel position-relative">
             <div class="owl-carousel-item position-relative">
             <img class="img-fluid" src="assets\img\cat -2.jpg" alt="cat-2">
             <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(24, 29, 56, .7);">
                     <div class="container">
                         <div class="row justify-content-start">
                             <div class="col-sm-10 col-lg-8">
                                 <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Projets Innovants</h5>
                                 <h1 class="display-3 text-white animated slideInDown">La Meilleure Plateforme d'Innovation</h1>
                                 <p class="fs-5 text-white mb-4 pb-2">Chez StartLink, chaque idée peut devenir une solution concrète à fort impact économique, social ou technologique.</p>
                                 <a href="reservation.php" class="btn btn-light py-md-3 px-md-5 animated slideInRight">Réserver maintenant</a>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="owl-carousel-item position-relative">
                 <img class="img-fluid" src="assets\img\cat-5.jpg" alt="cat-3">
                 <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(24, 29, 56, .7);">
                     <div class="container">
                         <div class="row justify-content-start">
                             <div class="col-sm-10 col-lg-8">
                                 <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Apprendre et Innover</h5>
                                 <h1 class="display-3 text-white animated slideInDown"> Épanouissez Votre Esprit, Construisez Votre Avenir</h1>
                                 <p class="fs-5 text-white mb-4 pb-2">StartLink vous accompagne pour transformer vos ambitions en projets concrets, durables et à fort potentiel.</p>

                                 <a href="reservation.php" class="btn btn-light py-md-3 px-md-5 animated slideInRight">Réserver maintenant</a>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- Carousel End -->

     <div class="container py-5"> <!-- Ajout d'un container et padding -->
    <div class="row g-4">
    
        <div class="col-lg-3 col-sm-6">
            <a href="formationsQ.php" style="text-decoration: none; color: inherit;">
                <div class="service-item rounded pt-3 text-center" style="background-color: #f0fafc; padding: 30px;">
                    <div class="p-4">
                        <i class="fas fa-graduation-cap fa-3x mb-3" style="color: #00bcd4;"></i>
                        <h5 class="mb-3">Formations Qualifiées</h5>
                        <p>Des experts reconnus pour vous guider à chaque étape de votre parcours entrepreneurial.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-sm-6">
            <a href="projets.php" style="text-decoration: none; color: inherit;">
                <div class="service-item rounded pt-3 text-center" style="background-color: #f0fafc; padding: 30px;">
                    <div class="p-4">
                        <i class="fas fa-globe fa-3x mb-3" style="color: #00bcd4;"></i>
                        <h5 class="mb-3">Projets en Ligne</h5>
                        <p>Participez à des projets collaboratifs, innovants et accessibles à distance via notre plateforme.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-sm-6">
            <a href="accom.php" style="text-decoration: none; color: inherit;">
                <div class="service-item rounded pt-3 text-center" style="background-color: #f0fafc; padding: 30px;">
                    <div class="p-4">
                        <i class="fas fa-home fa-3x mb-3" style="color: #00bcd4;"></i>
                        <h5 class="mb-3">Accompagnement à Domicile</h5>
                        <p>Des ressources et outils personnalisés pour apprendre et innover depuis chez vous.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-sm-6">
            <a href="ressources.php" style="text-decoration: none; color: inherit;">
                <div class="service-item rounded pt-3 text-center" style="background-color: #f0fafc; padding: 30px;">
                    <div class="p-4">
                        <i class="fas fa-book-open fa-3x mb-3" style="color: #00bcd4;"></i>
                        <h5 class="mb-3">Ressources StartLink</h5>
                        <p>Accédez à des supports pratiques, e-books, vidéos et guides pour booster vos projets.</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>


     <!-- About Start -->
     <div class="container-xxl py-5">
         <div class="container">
             <div class="row g-5">
                 <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                     <div class="position-relative h-100">
                         <img class="img-fluid position-absolute w-100 h-100" src="assets\img\cat-1.jpg" alt="cat-1" style="object-fit: cover;">
                     </div>
                 </div>
                 <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                     <h6 class="section-title bg-white text-start text-primary pe-3">À propos de nous</h6>
                     <h1 class="mb-4">Bienvenue chez StartLink</h1>
                     <p class="mb-4">C’est dans le silence des débuts incertains que naissent les plus grandes idées. Chaque défi cache une opportunité, chaque hésitation appelle à l’audace. L’innovation grandit là où la vision rencontre l’action.</p>
                     <p class="mb-4">StartLink est une plateforme dédiée à l’innovation, à la formation et à la mise en relation entre jeunes entrepreneurs et investisseurs. Nous croyons en une croissance inclusive portée par la technologie, l'audace et l'engagement.</p>
                     <div class="row gy-2 gx-4 mb-4">
                         <div class="col-sm-6">
                             <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Formateurs expérimentés</p>
                         </div>
                         <div class="col-sm-6">
                             <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Événements en ligne</p>
                         </div>
                         <div class="col-sm-6">
                             <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Certificats reconnus</p>
                         </div>
                         <div class="col-sm-6">
                             <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Formations interactives</p>
                         </div>
                         <div class="col-sm-6">
                             <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Accès à une communauté d'entrepreneurs</p>
                         </div>
                         <div class="col-sm-6">
                             <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Opportunités de financement</p>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- About End -->


     <!-- Categories Start -->
     <div class="container-xxl py-5 category">
         <div class="container">
             <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                 <h6 class="section-title bg-white text-center text-primary px-3">Catégories</h6>
                 <h1 class="mb-5">Classifications des Événements</h1>
             </div>
             <div class="row g-3">
                 <div class="col-lg-7 col-md-6">
                     <div class="row g-3">
                         <div class="col-lg-12 col-md-12 wow zoomIn" data-wow-delay="0.1s">
                             <a class="position-relative d-block overflow-hidden" href="">
                                 <img class="img-fluid" src="assets/img/cat-1.jpg" alt="">
                                 <div class="bg-white text-center position-absolute bottom-0 end-0 py-2 px-3" style="margin: 1px;">
                                     <h5 class="m-0">UI/UX Design</h5>
                                     <small class="text-primary">23 Nos Événements</small>
                                 </div>
                             </a>
                         </div>
                         <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.3s">
                             <a class="position-relative d-block overflow-hidden" href="">
                                 <img class="img-fluid" src="assets/img/cat-2.jpg" alt="">
                                 <div class="bg-white text-center position-absolute bottom-0 end-0 py-2 px-3" style="margin: 1px;">
                                     <h5 class="m-0">Studio de Design</h5>
                                     <small class="text-primary">42 Nos Événements </small>
                                 </div>
                             </a>
                         </div>
                         <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.5s">
                             <a class="position-relative d-block overflow-hidden" href="">
                                 <img class="img-fluid" src="assets/img/cat-3.jpg" alt="">
                                 <div class="bg-white text-center position-absolute bottom-0 end-0 py-2 px-3" style="margin: 1px;">
                                     <h5 class="m-0">Atelier de Montage Vidéo</h5>
                                     <small class="text-primary">35 Nos Événements</small>
                                 </div>
                             </a>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-5 col-md-6 wow zoomIn" data-wow-delay="0.7s" style="min-height: 350px;">
                     <a class="position-relative d-block h-100 overflow-hidden" href="">
                         <img class="img-fluid position-absolute w-100 h-100" src="assets/img/cat-4.jpg" alt="" style="object-fit: cover;">
                         <div class="bg-white text-center position-absolute bottom-0 end-0 py-2 px-3" style="margin:  1px;">
                             <h5 class="m-0">Marketing digital</h5>
                             <small class="text-primary">40 Nos Événements</small>
                         </div>
                     </a>
                 </div>
             </div>
         </div>
     </div>
     <!-- Categories Start -->

<!-- Courses Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Événements à Venir</h6>
            <h1 class="mb-5">Formations StartLink du Mois</h1>
        </div>

        <!-- ➔ C'est ici qu'on ajoute ça : -->
        <div class="row g-4">
            <!-- Formation 1 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="course-item bg-light rounded overflow-hidden shadow-sm hover-scale">
                    <div class="position-relative overflow-hidden">
                        <img class="img-fluid" src="assets/img/formation1.PNG" alt="Formation 1">
                    </div>
                    <div class="p-4">
                        <h5 class="mb-3" style="color: #0a1b89; font-weight: bold;">Développement Web Moderne</h5>
                        <p>Apprenez à créer des sites dynamiques en HTML, CSS, JavaScript et frameworks modernes.</p>
                        <a class="btn btn-primary rounded-pill mt-3" style="background-color: #13a7cd; border: none;" href="#">S'inscrire</a>
                    </div>
                </div>
            </div>

            <!-- Formation 2 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="course-item bg-light rounded overflow-hidden shadow-sm hover-scale">
                    <div class="position-relative overflow-hidden">
                        <img class="img-fluid" src="assets/img/formation2.PNG" alt="Formation 2">
                    </div>
                    <div class="p-4">
                        <h5 class="mb-3" style="color: #0a1b89; font-weight: bold;">Entrepreneuriat & Business Plan</h5>
                        <p>De l'idée à l'investissement : construisez un projet solide et préparez votre levée de fonds.</p>
                        <a class="btn btn-primary rounded-pill mt-3" style="background-color: #13a7cd; border: none;" href="#">S'inscrire</a>
                    </div>
                </div>
            </div>

            <!-- Formation 3 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="course-item bg-light rounded overflow-hidden shadow-sm hover-scale">
                    <div class="position-relative overflow-hidden">
                        <img class="img-fluid" src="assets/img/formation3.PNG" alt="Formation 3">
                    </div>
                    <div class="p-4">
                        <h5 class="mb-3" style="color: #0a1b89; font-weight: bold;">Marketing Digital Avancé</h5>
                        <p>Boostez votre visibilité en ligne avec SEO, publicité, stratégie réseaux sociaux et e-mailing.</p>
                        <a class="btn btn-primary rounded-pill mt-3" style="background-color: #13a7cd; border: none;" href="#">S'inscrire</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ➔ Fin ajout -->
    </div>
</div>
<!-- Courses End -->



<!-- Équipe StartLink -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Notre Équipe</h6>
            <h1 class="mb-5">Les Experts Derrière StartLink</h1>
        </div>
        <div class="row g-4">

            <!-- Membre 1 -->
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item bg-light">
                    <div class="overflow-hidden">
                        <img class="img-fluid" src="assets/img/team-1.jpg" alt="Rana Ben Atig">
                    </div>
                    <div class="position-relative d-flex justify-content-center" style="margin-top: -23px;">
                        <div class="bg-light d-flex justify-content-center pt-2 px-1">
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="text-center p-4">
                        <h5 class="mb-0">Rana Ben Atig</h5>
                        <small>Responsable Communication</small>
                    </div>
                </div>
            </div>

            <!-- Membre 2 -->
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="team-item bg-light">
                    <div class="overflow-hidden">
                    <img class="img-fluid" src="assets\img\ines.jpg" alt="Ines Hmani">
                    </div>
                    <div class="position-relative d-flex justify-content-center" style="margin-top: -23px;">
                        <div class="bg-light d-flex justify-content-center pt-2 px-1">
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="text-center p-4">
                        <h5 class="mb-0">Ines Hmani</h5>
                        <small>Coordinatrice Projet</small>
                    </div>
                </div>
            </div>

            <!-- Membre 3 -->
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="team-item bg-light">
                    <div class="overflow-hidden">
                        <img class="img-fluid" src="assets/img/team-3.jpg" alt="Khansa Oueslati">
                    </div>
                    <div class="position-relative d-flex justify-content-center" style="margin-top: -23px;">
                        <div class="bg-light d-flex justify-content-center pt-2 px-1">
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="text-center p-4">
                        <h5 class="mb-0">Khansa Oueslati</h5>
                        <small>Chargée des Partenariats</small>
                    </div>
                </div>
            </div>

            <!-- Membre 4 -->
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                <div class="team-item bg-light">
                    <div class="overflow-hidden">
                        <img class="img-fluid" src="assets/img/team-4.jpg" alt="Hamza Gassoumi">
                    </div>
                    <div class="position-relative d-flex justify-content-center" style="margin-top: -23px;">
                        <div class="bg-light d-flex justify-content-center pt-2 px-1">
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="text-center p-4">
                        <h5 class="mb-0">Hamza Gassoumi</h5>
                        <small>Développeur Web</small>
                    </div>
                </div>
            </div>

            <!-- Membre 5 -->
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item bg-light">
                    <div class="overflow-hidden">
                        <img class="img-fluid" src="assets/img/team-1.jpg" alt="Yassine Belhout">
                    </div>
                    <div class="position-relative d-flex justify-content-center" style="margin-top: -23px;">
                        <div class="bg-light d-flex justify-content-center pt-2 px-1">
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="text-center p-4">
                        <h5 class="mb-0">Yassine Belhout</h5>
                        <small>Responsable UX/UI</small>
                    </div>
                </div>
            </div>

            <!-- Membre 6 -->
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="team-item bg-light">
                    <div class="overflow-hidden">
                        <img class="img-fluid" src="assets/img/team-2.jpg" alt="Omar Ben Dhieb">
                    </div>
                    <div class="position-relative d-flex justify-content-center" style="margin-top: -23px;">
                        <div class="bg-light d-flex justify-content-center pt-2 px-1">
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-sm-square btn-primary mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="text-center p-4">
                        <h5 class="mb-0">Omar Ben Dhieb</h5>
                        <small>Support Technique</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Fin Équipe -->


     <!-- Testimonial Start -->
     <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
         <div class="container">
             <div class="text-center">
                 <h6 class="section-title bg-white text-center text-primary px-3">Testimonial</h6>
                 <h1 class="mb-5">Ce qu'ils disent de StartLink!</h1>
             </div>
             <div class="owl-carousel testimonial-carousel position-relative">
                 <div class="testimonial-item text-center">
                     <img class="border rounded-circle p-2 mx-auto mb-3" src="assets/img/testimonial-1.jpg" style="width: 80px; height: 80px;">
                     <h5 class="mb-0">Nour Ben Salem</h5>
                     <p>Entrepreneuse</p>
                     <div class="testimonial-text bg-light text-center p-4">
                         <p class="mb-0">Grâce à StartLink, j'ai trouvé un investisseur pour lancer ma marque de cosmétiques bio. Une plateforme innovante et humaine !</p>
                     </div>
                 </div>
                 <div class="testimonial-item text-center">
                     <img class="border rounded-circle p-2 mx-auto mb-3" src="assets/img/testimonial-2.jpg" style="width: 80px; height: 80px;">
                     <h5 class="mb-0">Youssef Hachicha</h5>
                     <p>Investisseur</p>
                     <div class="testimonial-text bg-light text-center p-4">
                         <p class="mb-0">J’ai participé à un bootcamp organisé par StartLink. J’ai appris à pitcher et structurer mon projet. Merci à toute l’équipe !</p>
                     </div>
                 </div>
                 <div class="testimonial-item text-center">
                     <img class="border rounded-circle p-2 mx-auto mb-3" src="assets/img/testimonial-3.jpg" style="width: 80px; height: 80px;">
                     <h5 class="mb-0">Karim Jaziri</h5>
                     <p>Étudiant entrepreneur</p>
                     <div class="testimonial-text bg-light text-center p-4">
                         <p class="mb-0">StartLink m’a permis d’investir dans des startups prometteuses en Tunisie. Interface simple, communauté ambitieuse.</p>
                     </div>
                 </div>
                 <div class="testimonial-item text-center">
                     <img class="border rounded-circle p-2 mx-auto mb-3" src="assets/img/testimonial-4.jpg" style="width: 80px; height: 80px;">
                     <h5 class="mb-0">Ons Trabelsi</h5>
                     <p>Mentor & Coach</p>
                     <div class="testimonial-text bg-light text-center p-4">
                         <p class="mb-0">StartLink connecte des idées brillantes avec des opportunités concrètes. C’est un accélérateur de talents tunisien 🔥</p>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- Testimonial End -->


     <?php include('include/footer.php') ?>

     


     <!-- Back to Top -->
     <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>




     <?php include('include/js.php') ?>