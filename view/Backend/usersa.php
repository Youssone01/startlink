<?php
include '../../controller/user.php'; 
$user = new user(); 
// Vérifier si un terme de recherche a été soumis
$search = isset($_GET['search']) ? $_GET['search'] : '';
// Si un terme de recherche est fourni, filtrez la liste des utilisateurs
if ($search) {
    $list = $userc->searchUsers($search);  // Fonction à implémenter dans votre contrôleur
} else {
    $list = $userc->listen();  // Fonction pour obtenir tous les utilisateurs
}

// Récupérer les statistiques des rôles
$roleStats = $userc->getUserRoleStats();

// Convertir les résultats en JSON pour l'envoyer au frontend
$roleStatsJson = json_encode($roleStats);

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>user </title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport">
   <meta content="" name="keywords">
   <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap"
        rel="stylesheet">

      

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
      <!-- Inclure Chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <!-- Inclure le plugin Chart.js DataLabels -->
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <style>
        .search-form {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-form input {
            padding: 10px;
            width: 50%;
            font-size: 16px;
        }
    </style>

</head>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
      <section class="flex">
         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div> 
         </div>
      </section>
      <a href="usersa.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
          <img src="img/logo.jpg" alt="ES-Hub Logo" class="me-2" style="height: 70px; width: 70PX;">
          <h2 class="m-0 text-primary">ES-Hub</h2>
      </a>
      <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
          <div class="navbar-nav ms-auto p-4 p-lg-0">
              <a href="usersa.php" class="nav-item nav-link">Home</a>
              <a href="courses.html" class="nav-item nav-link">Courses</a>
              <div class="nav-item dropdown">
                  <a href="training.html" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Training</a>
                  <div class="dropdown-menu fade-down m-0">
                      <div class="dropdown">
                          <a href="#" class="dropdown-item dropdown-toggle">Categories</a>
                          <div class="dropdown-menu submenu fade-down m-0">
                              <a href="web-development.html" class="dropdown-item">Web Development</a>
                              <a href="data science.html" class="dropdown-item">Data Science</a>
                              <a href="design.html" class="dropdown-item">Graphic Design</a>
                          </div>
                      </div>
                      <a href="purchase.html" class="dropdown-item">Purchase</a>
                  </div>
              </div>
              <a href="forum.html" class="nav-item nav-link">Forum</a>
              <a href="contact.html" class="nav-item nav-link">Contact</a>
              <div class="icons">
                  <a href="#" id="user-btn" class="fas fa-user" data-bs-toggle="modal"
                      data-bs-target="#authModal"></a>
              </div>
          </div>
      </div>
  </nav>
  <!-- Navbar End -->


   <div class="side-bar">
      <div id="close-btn">
         <i class="fas fa-times"></i>
      </div>
      <nav class="navbar">
         <a href="usersa.php"><i class="fa-solid fa-user"></i><span>users</span></a>
         <a href="courses.html"><i class="fa-solid fa-book"></i><span>courses</span></a>
         <a href="training.html"><i class="fa-solid fa-pen-to-square"></i><span>training</span></a>
         <a href="forum.html"><i class="fa-regular fa-comment"></i><span>forum</span></a>
         <a href="complaints.html"><i class="fa-solid fa-exclamation"></i><span>complaints</span></a>
      </nav>

   </div>
     <!-- Barre de recherche -->
     <div class="search-form">
        <input type="text" id="searchInput" placeholder="Rechercher par nom" value="<?= htmlspecialchars($search) ?>" onkeyup="searchUser()" />
    </div>

   <section class="admin-user">
      <table>
         <thead>
         <tr>
            <th style="width: 50px;">ID</th>
            <th style="width: 150px;">Name</th>
            <th style="width: 100px;">Date of Birth</th>
            <th style="width: 100px;">Photo</th>
            <th style="width: 300px;">Bio</th>
            <th style="width: 200px;">Email</th>
            <th style="width: 200px;">role</th>
            <th style="width: 200px;">Delete</th>
            <th style="width: 200px;">changerole</th>
         </tr>
         </thead>
         <tbody>
         <?php
        foreach ($list as $user) {
        ?>
            <tr class="userRow">
                     <td><?= $user['id']; ?></td>
                     <td><?= htmlspecialchars($user['name']); ?></td>
                     <td><?= htmlspecialchars($user['date_n']); ?></td>
                     <td> 
                        <?php
                              $image_data=base64_encode($user['photo']);
                              $image_mine ='image/jpeg';
                              $image_src="data:{$image_mine};base64,{$image_data}";
                              ?>
                              <img style="width: 50px; height: 50px;" src="<?php echo $image_src;?>"alt="image" >
                     </td>
                     
                     <td><?= htmlspecialchars($user['bio']); ?></td>
                     <td><?= htmlspecialchars($user['email']); ?></td>
                     <td><?= htmlspecialchars($user['role']); ?></td>
                     <td>
                           <form action="deleteuser.php" method="GET" ">
                              <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                              <button type="submit" class="delete-btn">
                                    <i class="fa-solid fa-trash"></i>
                              </button>
                           </form>
                     </td>
                     <td>
                           <form action="changerole.php" method="GET" ">
                              <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                              <button type="submit">
                                    <i>changerole</i>
                              </button>
                           </form>
                     </td>
               </tr>
            <?php
            }
            ?>
         </tbody>
      </table>
   </section>
   <div style="width: 50%; margin: auto;">
     <canvas id="roleChart"></canvas>
   </div>

   <script>
        // Fonction pour rechercher et filtrer les utilisateurs côté client
        function searchUser() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.userRow');
            
            rows.forEach(row => {
                const nameCell = row.querySelector('td:nth-child(2)'); // Sélectionner la cellule du nom
                const name = nameCell.textContent || nameCell.innerText;

                if (name.toLowerCase().indexOf(input) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
   <script>
   // Récupérer les données PHP dans JavaScript
   const roleStats = <?php echo $roleStatsJson; ?>;

   // Préparer les données pour le graphique
   const labels = roleStats.map(stat => stat.role); // Récupérer les noms des rôles
   const data = roleStats.map(stat => stat.count); // Récupérer le nombre d'utilisateurs par rôle

   // Créer le graphique circulaire
   const ctx = document.getElementById('roleChart').getContext('2d');
   const roleChart = new Chart(ctx, {
       type: 'pie', // Type de graphique (camembert)
       data: {
           labels: labels, // Labels des rôles
           datasets: [{
               data: data, // Données de chaque rôle
               backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FF9F40'], // Couleurs des segments
               hoverBackgroundColor: ['#FF6F91', '#4DA6D1', '#FFD54F', '#66D6D6', '#FFB14D']
           }]
       },
       options: {
           responsive: true,
           plugins: {
               legend: {
                   position: 'top'
               },
               tooltip: {
                   callbacks: {
                       label: function(tooltipItem) {
                           // Afficher le nombre d'utilisateurs et le pourcentage dans le tooltip
                           const percentage = (tooltipItem.raw / data.reduce((a, b) => a + b, 0)) * 100;
                           return tooltipItem.label + ': ' + tooltipItem.raw + ' utilisateurs (' + percentage.toFixed(2) + '%)';
                       }
                   }
               },
               datalabels: {
                   formatter: (value, context) => {
                       // Calculer le pourcentage
                       const total = context.chart._metasets[0].data.reduce((a, b) => a + b, 0);
                       const percentage = ((value / total) * 100).toFixed(2); // Calcul du pourcentage
                       return percentage + '%'; // Afficher le pourcentage
                   },
                   color: '#fff',
                   font: {
                       weight: 'bold',
                       size: 14
                   },
                   anchor: 'center', // Centrer le pourcentage dans le segment
                   align: 'center' // Alignement centré du texte
               }
           }
       }
   });
</script>
   <!-- custom js file link  -->
   <script src="js/script.js"></script>
</body>

</html>