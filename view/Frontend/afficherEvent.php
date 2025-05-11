<?php
require_once __DIR__ . '/../../controller/EventC.php';
require_once __DIR__ . '/../../Controllers/config.php';


try {
    $eventController = new EventC();
    $events = $eventController->afficherEvent();
    $eventsByDate = $eventController->getEventsForCustomCalendar();
    
    // Calendar parameters
    $currentYear = date('Y');
    $currentMonth = date('n');
    $year = $_GET['year'] ?? $currentYear;
    $month = $_GET['month'] ?? $currentMonth;
    
    // Calendar calculations
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $firstDay = date('N', strtotime("$year-$month-01"));
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>

<?php include('include/header.php') ?>

<body>
    <?php include('include/spinner.php') ?>
    <?php include('include/navbar.php') ?>

    <style>
        /* Main container */
        .events-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Event cards */
        .event-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 25px;
            position: relative;
            min-height: 450px;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .event-image-container {
            height: 200px;
            overflow: hidden;
        }

        .event-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .event-card:hover .event-image {
            transform: scale(1.05);
        }

        .event-content {
            padding: 20px;
        }

        .event-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .event-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .event-price {
            font-weight: bold;
            color: #27ae60;
        }

        .event-description {
            color: #34495e;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .event-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        /* Calendar styles */
        .custom-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            max-width: 900px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .calendar-header {
            grid-column: span 7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #13a7cd;
            color: white;
            font-size: 1.2rem;
        }

        .day-header {
            background: #f0fafc;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #ddd;
        }

        .day-cell {
            min-height: 100px;
            border: 1px solid #eee;
            padding: 8px;
            background: white;
            position: relative;
            transition: all 0.3s ease;
        }

        .day-cell:hover {
            background: #f9f9f9;
        }

        .day-number {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .calendar-event {
            background: #FF5733;
            color: white;
            padding: 3px 6px;
            margin: 3px 0;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .other-month {
            opacity: 0.4;
            background: #f9f9f9;
        }

        /* Buttons and actions */
        .btn-event-action {
            border-radius: 6px;
            padding: 8px 15px;
            margin: 5px;
            font-size: 14px;
            border: 1px solid rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .btn-participate {
            background-color: #28a745;
            color: white;
        }

        .btn-participate:hover {
            background-color: #218838;
        }

        /* Search box styles */
        .search-container {
            margin: 20px auto;
            max-width: 800px;
            text-align: center;
        }

        .search-box {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-box input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .search-box button {
            padding: 10px 20px;
            background-color: #13a7cd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .custom-calendar {
                grid-template-columns: repeat(7, 1fr);
                gap: 5px;
            }
            
            .day-cell {
                min-height: 60px;
                padding: 3px;
                font-size: 0.8rem;
            }
            
            .calendar-event {
                font-size: 0.6rem;
                padding: 2px 3px;
            }

            .search-box {
                flex-direction: column;
                align-items: center;
            }

            .search-box input[type="text"] {
                width: 100%;
            }
        }
    </style>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Événements</h6>
                <h1 class="mb-5">Gestion des Événements StartLink</h1>
            </div>

            <div class="text-center mb-5">
                <a href="ajouterEvent.php" class="btn btn-primary py-md-3 px-md-5 me-3">
                    <i class="fas fa-plus me-2"></i>Ajouter un événement
                </a>
                <button id="toggleView" class="btn btn-secondary py-md-3 px-md-5">
                    <i class="fas fa-exchange-alt me-2"></i>Vue Cartes
                </button>
            </div>

            <!-- Search container -->
            <div class="search-container wow fadeInUp" data-wow-delay="0.15s">
                <div class="search-box">
                    <input type="text" id="eventSearch" placeholder="Rechercher un événement par nom...">
                    <button id="searchButton"><i class="fas fa-search me-2"></i>Rechercher</button>
                </div>
            </div>

            <!-- Calendar container -->
            <div id="calendar-container">
                <div class="custom-calendar wow fadeInUp" data-wow-delay="0.2s">
                    <div class="calendar-header">
                        <div class="navigation">
                            <a href="?month=<?= $month-1 < 1 ? 12 : $month-1 ?>&year=<?= $month-1 < 1 ? $year-1 : $year ?>" 
                               class="btn btn-sm btn-light">
                                &lt; Mois précédent
                            </a>
                        </div>
                        <h3><?= strftime('%B %Y', strtotime("$year-$month-01")) ?></h3>
                        <div class="navigation">
                            <a href="?month=<?= $month+1 > 12 ? 1 : $month+1 ?>&year=<?= $month+1 > 12 ? $year+1 : $year ?>" 
                               class="btn btn-sm btn-light">
                                Mois suivant &gt;
                            </a>
                        </div>
                    </div>

                    <?php 
                    // Day headers
                    $jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                    foreach ($jours as $jour) {
                        echo "<div class='day-header'>$jour</div>";
                    }

                    // Empty cells before first day
                    for ($i = 1; $i < $firstDay; $i++) {
                        echo "<div class='day-cell other-month'></div>";
                    }

                    // Month days
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $currentDate = date('Y-m-d', strtotime("$year-$month-$day"));
                        $hasEvents = isset($eventsByDate[$currentDate]);
                        
                        echo "<div class='day-cell'>";
                        echo "<div class='day-number'>$day</div>";
                        
                        if ($hasEvents) {
                            foreach ($eventsByDate[$currentDate] as $event) {
                                echo "<div class='calendar-event' title='{$event['title']}'>";
                                echo substr($event['title'], 0, 15) . (strlen($event['title']) > 15 ? '...' : '');
                                echo "</div>";
                            }
                        }
                        
                        echo "</div>";
                    }

                    // Empty cells after last day
                    $lastDay = date('N', strtotime("$year-$month-$daysInMonth"));
                    $remaining = 7 - $lastDay;
                    for ($i = 0; $i < $remaining; $i++) {
                        echo "<div class='day-cell other-month'></div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Cards container (hidden by default) -->
            <div id="cards-container" style="display: none;">
                <div class="row g-4">
                    <?php if (empty($events)): ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x mb-3 text-muted"></i>
                                <h3>Aucun événement disponible</h3>
                                <p>Créez votre premier événement en cliquant sur le bouton ci-dessus</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($events as $event): 
                            $imageBg = '';
                            if (!empty($event['has_image'])) {
                                $imageData = $eventController->getEventImage($event['idEvenement']);
                                if ($imageData) {
                                    $imageBg = 'data:image/jpeg;base64,' . base64_encode($imageData);
                                }
                            }
                            
                            $prixFormate = isset($event['prix']) && $event['prix'] !== null ? 
                                number_format($event['prix'], 2) . ' dt' : 'Gratuit';
                        ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp">
                            <div class="event-card">
                                <?php if ($imageBg): ?>
                                <div class="event-image-container">
                                    <img src="<?= $imageBg ?>" class="event-image" alt="<?= htmlspecialchars($event['titre']) ?>">
                                </div>
                                <?php endif; ?>
                                
                                <div class="event-content">
                                    <h3 class="event-title"><?= htmlspecialchars($event['titre']) ?></h3>
                                    <div class="event-meta">
                                        <span><i class="far fa-calendar-alt me-2"></i><?= date('d/m/Y', strtotime($event['dateEven'])) ?></span>
                                        <span class="event-price"><?= $prixFormate ?></span>
                                    </div>
                                    <p class="event-organizer">
                                        <i class="fas fa-user-tie me-2"></i>
                                        Organisateur : <?= htmlspecialchars($event['organisateur'] ?? 'Non spécifié') ?>
                                    </p>
                                    <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
                                    
                                    <div class="event-actions">
                                        <a href="ajouterParticipant.php?event_id=<?= $event['idEvenement'] ?>" 
                                           class="btn btn-participate btn-event-action">
                                           <i class="fas fa-users me-1"></i>Participer
                                        </a>
                                        <div>
                                            <a href="modifierEvent.php?id=<?= $event['idEvenement'] ?>" 
                                               class="btn btn-warning btn-event-action">
                                               <i class="fas fa-edit me-1"></i>Modifier
                                            </a>
                                            <a href="supprimerEvent.php?id=<?= $event['idEvenement'] ?>" 
                                               class="btn btn-danger btn-event-action"
                                               onclick="return confirm('Confirmer la suppression ?')">
                                               <i class="fas fa-trash me-1"></i>Supprimer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php') ?>
    <?php include('include/js.php') ?>

    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle between calendar and card views
            document.getElementById('toggleView').addEventListener('click', function() {
                const calendarContainer = document.getElementById('calendar-container');
                const cardsContainer = document.getElementById('cards-container');
                
                if (calendarContainer.style.display === 'none') {
                    calendarContainer.style.display = 'block';
                    cardsContainer.style.display = 'none';
                    this.innerHTML = '<i class="fas fa-exchange-alt me-2"></i>Vue Cartes';
                } else {
                    calendarContainer.style.display = 'none';
                    cardsContainer.style.display = 'block';
                    this.innerHTML = '<i class="fas fa-exchange-alt me-2"></i>Vue Calendrier';
                }
            });

            // Search functionality
            document.getElementById('searchButton').addEventListener('click', function() {
                const searchTerm = document.getElementById('eventSearch').value.toLowerCase();
                const eventCards = document.querySelectorAll('#cards-container .event-card');
                
                eventCards.forEach(card => {
                    const title = card.querySelector('.event-title').textContent.toLowerCase();
                    if (title.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Allow search on Enter key
            document.getElementById('eventSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('searchButton').click();
                }
            });
        });
    </script>
    <!-- Ajoutez ces lignes avant la fermeture du body -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>     
</body>
</html>