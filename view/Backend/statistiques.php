
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
        <i class="fas fa-users-cog"></i>
        <p>Utilisateurs+</p>
    </a>
</li>


                </li>

                <li class="nav-item">
                    <a href="../listeoffres.php" target="_blank">
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
                    <a href="formations.php" target="_blank">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Formations</p>
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
            background-color: #e6f2fb;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 720px;
            width: 100%;
        }
        h2 {
            color: #0a1b89;
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        h2 i {
            color: #0a1b89;
            font-size: 24px;
        }
        select {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 200px;
        }
        canvas {
    display: block;
    margin: 20px auto;
    max-width: 500px;      /* Taille plus adaptée */
    height: auto;
}


        .btn-export,
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 10px 0 0;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-export {
            background-color: #0a66c2;
            color: white;
        }
        .btn-back {
            background: #e74c3c;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            display: block;
            margin: 20px auto 0;
            transition: background 0.3s ease;
        }
        .btn-back:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container" id="chartContainer">
        <h2>📊 Statistiques des utilisateurs</h2>
        <form method="GET" onchange="this.submit()">
            <label for="filter">Filtrer par :</label>
            <select name="filter" id="filter">
                <option value="role" selected>Rôle</option>
                <option value="age" >Âge</option>
                <option value="adresse" >Adresse</option>
            </select>
        </form>

        <canvas id="statsChart"></canvas>
        <div style="text-align: center;">
        <button onclick="exportChartAsPDF()" class="btn-export">📄 Exporter en PDF</button>
        <button onclick="window.location.href='dashboard.php'" class="btn-back">⬅ Retour au tableau de bord</button>
        </div>


    <script>
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["entrepreneur","investisseur","admin"],
                datasets: [{
                    data: [9,1,1],
                    backgroundColor: [
                        '#0a1b89', '#f7941d', '#8c52ff', '#e74c3c', '#3498db', '#2ecc71', '#9b59b6', '#1abc9c'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Répartition des utilisateurs par role',
                        color: '#0a1b89',
                        font: {
                            size: 18,
                            weight: 'bold'
                        }
                    }
                }
            }
        });
    </script>
<script>
function exportChartAsPDF() {
    const canvas = document.getElementById('statsChart');

    // Attendre que le canvas soit rendu
    setTimeout(() => {
        const canvasImage = canvas.toDataURL('image/png', 1.0);

        // Créer un conteneur temporaire pour le contenu PDF
        const pdfContent = document.createElement('div');
        pdfContent.style.padding = '20px';
        pdfContent.style.fontFamily = 'Segoe UI, sans-serif';
        pdfContent.style.textAlign = 'center';

        // Logo StartLink
        const logo = document.createElement('img');
        logo.src = '../../assets/img/logo.PNG'; // Change le chemin si nécessaire
        logo.style.width = '100px';
        logo.style.margin = '0 auto 20px';
        pdfContent.appendChild(logo);

        // Titre
        const title = document.createElement('h2');
        title.innerText = '📊 Statistiques des utilisateurs de Startlink';
        title.style.color = '#0a1b89';
        title.style.marginBottom = '10px';
        pdfContent.appendChild(title);

        // Légende (ex: Rôle, Âge, etc.)
        const subtitle = document.createElement('p');
        subtitle.innerText = document.querySelector('.chart-title')?.innerText || '';
        subtitle.style.fontWeight = 'bold';
        subtitle.style.color = '#0a1b89';
        pdfContent.appendChild(subtitle);

        // Graphique sous forme d'image
        const img = document.createElement('img');
        img.src = canvasImage;
        img.style.maxWidth = '90%';
        img.style.margin = '20px auto';
        pdfContent.appendChild(img);

        // Export en PDF
        const opt = {
            margin: 10,
            filename: 'statistiques_startlink.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().from(pdfContent).set(opt).save();
    }, 300); // petit délai pour s'assurer que le canvas est prêt
}
</script>





</body>
</html>