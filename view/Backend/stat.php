<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques Certifications</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        body {
            background-color: #e6f2fb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            margin-bottom: 20px;
        }
        h2 {
            color: #0a1b89;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        canvas {
            margin: 20px auto;
            max-width: 600px;
            height: auto;
        }
        .btn {
            background-color: #0a66c2;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container" id="chartContainer">
        <h2>📊 Statistiques des Certifications</h2>
        <canvas id="niveauChart"></canvas>
        <canvas id="scoreChart"></canvas>
        <button onclick="exportChartsAsPDF()" class="btn">📄 Exporter en PDF</button>
        <button onclick="goBack()" class="btn">🔙 Retour</button>
    </div>

    <script>
        // Données pour les certifications par niveau
        const niveaux = ["Débutant", "Intermédiaire", "Avancé"];
        const dataNiveaux = [10, 20, 15]; // Exemple : remplacer par les données dynamiques

        // Données pour les certifications par score
        const scores = ["0-50%", "51-75%", "76-100%"];
        const dataScores = [5, 15, 25]; // Exemple : remplacer par les données dynamiques

        // Graphique des niveaux
        const niveauCtx = document.getElementById('niveauChart').getContext('2d');
        new Chart(niveauCtx, {
            type: 'bar',
            data: {
                labels: niveaux,
                datasets: [{
                    label: 'Certifications par Niveau',
                    data: dataNiveaux,
                    backgroundColor: ['#3498db', '#2ecc71', '#e74c3c'],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Répartition des Certifications par Niveau'
                    }
                }
            }
        });

        // Graphique des scores
        const scoreCtx = document.getElementById('scoreChart').getContext('2d');
        new Chart(scoreCtx, {
            type: 'pie',
            data: {
                labels: scores,
                datasets: [{
                    label: 'Certifications par Score',
                    data: dataScores,
                    backgroundColor: ['#8e44ad', '#f1c40f', '#27ae60'],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'Répartition des Certifications par Score'
                    }
                }
            }
        });

        // Fonction pour exporter les graphiques en PDF
        function exportChartsAsPDF() {
            const container = document.getElementById('chartContainer');
            const opt = {
                margin: 10,
                filename: 'statistiques_certifications.pdf',
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().from(container).set(opt).save();
        }

        // Fonction pour revenir en arrière
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
