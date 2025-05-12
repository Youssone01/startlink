<?php  
// Connexion à la base de données
$host = 'localhost';
$db   = 'gestion'; 
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les statistiques des formations par niveau
$query = "SELECT niveau, COUNT(*) as total FROM formations GROUP BY niveau";
$stmt = $pdo->prepare($query);
$stmt->execute();
$levelsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données
$labels = [];
$data = [];
$colors = [];

foreach ($levelsData as $level) {
    $niveau = strtolower($level['niveau']);
    $labels[] = ucfirst($niveau);
    $data[] = (int) $level['total'];

    // Couleurs selon le niveau
    if ($niveau == 'débutant' || $niveau == 'debutant') {
        $colors[] = '#e74c3c'; // rouge
    } elseif ($niveau == 'intermédiaire' || $niveau == 'intermediaire') {
        $colors[] = '#3498db'; // bleu
    } elseif ($niveau == 'avancé' || $niveau == 'avance') {
        $colors[] = '#2ecc71'; // vert
    } else {
        $colors[] = '#95a5a6'; // gris par défaut
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques des utilisateurs</title>
    <title>Statistiques Utilisateurs</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Startlink Dashboard</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
    <link
        rel="icon"
        href="assets/img/kaiadmin/favicon.ico"
        type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">

</head>  <!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="dashboard.php" class="logo">
                <img src="assets/img/kaiadmin/logo_dark.png" alt="StartLink Logo" class="navbar-brand" height="30" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <li class="nav-item">
                    <a href="dashboard.php" target="_blank">
                        <i class="fas fa-home"></i>
                        <p>Menu</p>
                    </a>
                </li>
<li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Participation</h4>
                </li>

                <li class="nav-item">
                    <a href="dashboard.php" target="_blank">
                        <i class="fas fa-users"></i>
                        <p>Liste Des Utilisateurs</p>
                    </a>
                    <li class="nav-item">
    <a href="statistiques.php">
        <i class="fas fa-chart-pie"></i>
        <p>Statistiques Utilisateurs</p>
    </a>
</li>
<li class="nav-item">
    <a href="user_tools.php">
        <i class="fas fa-chart-pie"></i>
        <p> Utilisateurs+</p>
    </a>
</li>

                </li>

                <li class="nav-item">
                    <a href="gestion_offres.php" target="_blank">
                        <i class="fas fa-briefcase"></i>
                        <p>Offres d'Emplois</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="blogs.php" target="_blank">
                        <i class="fas fa-comments"></i>
                        <p>Blogs & Forums</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="evenements.php" target="_blank">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Evènements</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="formation_bo.php" target="_blank">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Formations</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="statform.php" target="_blank">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Statistiques Formations</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="quizz.php" target="_blank">
                        <i class="fas fa-question-circle"></i>
                        <p>Quizz</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="certifications.php" target="_blank">
                        <i class="fas fa-certificate"></i>
                        <p>Certifications</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        .container {
            text-align: center;
            padding: 40px;
            margin: 50px auto;
            max-width: 700px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 30px;
            color: #333;
        }
        canvas {
            max-width: 100%;
        }
        .btn {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-retour {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 10px 16px;
            margin-top: 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }
        .btn-retour:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container" id="stat-container">
    <h1>Statistiques des Formations par Niveau</h1>
    <canvas id="myChart" width="400" height="200"></canvas>
    <button class="btn" onclick="exportPDF()">Exporter en PDF</button>
</div>

<script>
// Données PHP vers JavaScript
const labels = <?php echo json_encode($labels); ?>;
const data = <?php echo json_encode($data); ?>;
const colors = <?php echo json_encode($colors); ?>;

// Création du graphique
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Nombre de Formations',
            data: data,
            backgroundColor: colors,
            borderColor: colors,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

function exportPDF() {
    const button = document.querySelector(".btn");
    button.style.display = "none"; // Masquer le bouton avant capture

    html2canvas(document.querySelector("#stat-container")).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        const imgWidth = 190;
        const pageHeight = 295;
        const imgHeight = canvas.height * imgWidth / canvas.width;
        let heightLeft = imgHeight;
        let position = 10;

        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        pdf.save("statistiques_formations.pdf");
        button.style.display = "inline-block"; // Réafficher le bouton après
    });
}
</script>
</body>
</html>
