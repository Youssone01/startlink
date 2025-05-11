<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../controller/EventC.php';
require_once __DIR__ . '/include/header.php';

$eventC = new EventC();
$events = $eventC->getEventsForCalendar();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier des Événements</title>
    
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    
    <style>
        .calendar-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 30px;
        }
        
        .fc-toolbar-title {
            color: #13a7cd;
            font-weight: 600;
        }
        
        .fc-button {
            background-color: #13a7cd !important;
            border: none !important;
        }
        
        .fc-event {
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .fc-event:hover {
            transform: scale(1.02);
        }
        
        .switch-view {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include('include/spinner.php') ?>
    <?php include('include/navbar.php') ?>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Événements</h6>
                <h1 class="mb-5">Calendrier des Événements</h1>
            </div>

            <div class="switch-view">
                <a href="afficherEvent.php" class="btn btn-outline-primary me-2">
                    <i class="fas fa-list"></i> Vue Liste
                </a>
                <a href="ajouterEvent.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un événement
                </a>
            </div>

            <div class="calendar-container wow fadeInUp" data-wow-delay="0.3s">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php') ?>
    
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.min.js'></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },
                events: <?php echo json_encode($events); ?>,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    
                    // Créer un modal avec les détails
                    const modalHtml = `
                        <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">${info.event.title}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Date:</strong> ${info.event.start.toLocaleString()}</p>
                                        <div>${info.event.extendedProps.description}</div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="modifierEvent.php?id=${info.event.id}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="supprimerEvent.php?id=${info.event.id}" 
                                           class="btn btn-danger"
                                           onclick="return confirm('Confirmer la suppression?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Ajouter le modal au DOM
                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                    
                    // Afficher le modal
                    var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    modal.show();
                    
                    // Nettoyer après fermeture
                    document.getElementById('eventModal').addEventListener('hidden.bs.modal', function() {
                        this.remove();
                    });
                },
                eventContent: function(arg) {
                    // Personnalisation du rendu des événements
                    return {
                        html: `<div class="fc-event-title">${arg.event.title}</div>`
                    };
                }
            });
            
            calendar.render();
        });
    </script>
</body>
</html>