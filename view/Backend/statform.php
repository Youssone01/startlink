<?php  
// Connexion à la base de données
$host = 'localhost';
$db   = 'startlink'; 
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
    <title>Statistiques des Formations</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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
    <a href="dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
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
