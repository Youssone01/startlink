<?php
require_once __DIR__ . '/../Controllers/config.php';
require_once __DIR__ . '/../model/Event.php';

class EventC {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    // Ajouter un événement
    public function addEvent(Event $event, $imageFile = null) {
        $imageData = $this->processImage($imageFile);

        $sql = "INSERT INTO evenement (titre, dateEven, description, organisateur, prix, image) 
                VALUES (:titre, :dateEven, :description, :organisateur, :prix, :image)";
        
        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'titre' => $event->getTitre(),
                'dateEven' => $event->getDateEvent(),
                'description' => $event->getDescription(),
                'organisateur' => $event->getOrganisateur(),
                'prix' => $event->getPrix(),
                'image' => $imageData
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erreur d'ajout: " . $e->getMessage());
        }
    }

    // Afficher les événements
    public function afficherEvent($limit = null) {
        $sql = "SELECT idEvenement, titre, dateEven, description, organisateur, prix,
                CASE WHEN image IS NOT NULL THEN 1 ELSE 0 END as has_image 
                FROM evenement ORDER BY dateEven DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }

        try {
            $query = $this->db->prepare($sql);
            if ($limit) {
                $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            }
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur d'affichage: " . $e->getMessage());
        }
    }

    // Récupérer les événements pour le calendrier personnalisé
    public function getEventsForCustomCalendar() {
        $sql = "SELECT 
                idEvenement as id,
                titre as title, 
                DATE(dateEven) as date,
                '#FF5733' as color,
                description,
                organisateur
                FROM evenement";
        
        try {
            $query = $this->db->query($sql);
            $events = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Structurer par date [date => [events]]
            $organized = [];
            foreach ($events as $event) {
                $organized[$event['date']][] = $event;
            }
            return $organized;
        } catch (PDOException $e) {
            throw new Exception("Erreur de récupération: " . $e->getMessage());
        }
    }

    // Récupérer l'image d'un événement
    public function getEventImage($idEvent) {
        $sql = "SELECT image FROM evenement WHERE idEvenement = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $idEvent, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['image'] : null;
        } catch (PDOException $e) {
            throw new Exception("Erreur de récupération d'image: " . $e->getMessage());
        }
    }

    // Mettre à jour un événement
    public function updateEvent(Event $event, $imageFile = 'keep') {
        $sql = "UPDATE evenement SET 
                titre = :titre, 
                dateEven = :dateEven, 
                description = :description,
                organisateur = :organisateur,
                prix = :prix";
        
        if ($imageFile !== 'keep') {
            $sql .= ", image = :image";
        }
        
        $sql .= " WHERE idEvenement = :id";
        
        try {
            $query = $this->db->prepare($sql);
            
            $params = [
                'titre' => $event->getTitre(),
                'dateEven' => $event->getDateEvent(),
                'description' => $event->getDescription(),
                'organisateur' => $event->getOrganisateur(),
                'prix' => $event->getPrix(),
                'id' => $event->getIdEvent()
            ];
            
            if ($imageFile !== 'keep') {
                $params['image'] = $imageFile === null ? null : file_get_contents($imageFile['tmp_name']);
            }
            
            return $query->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Erreur de modification: " . $e->getMessage());
        }
    }

    // Supprimer un événement
    public function deleteEvent($idEvent) {
        try {
            $this->db->beginTransaction();
            
            // D'abord supprimer les participations
            $sql1 = "DELETE FROM participationEvenement WHERE evenement_id = :id";
            $query1 = $this->db->prepare($sql1);
            $query1->bindValue(':id', $idEvent, PDO::PARAM_INT);
            $query1->execute();
            
            // Puis supprimer l'événement
            $sql2 = "DELETE FROM evenement WHERE idEvenement = :id";
            $query2 = $this->db->prepare($sql2);
            $query2->bindValue(':id', $idEvent, PDO::PARAM_INT);
            $query2->execute();
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Erreur de suppression: " . $e->getMessage());
        }
    }

    // Récupérer un événement par son ID
    public function getEventById($idEvent) {
        $sql = "SELECT idEvenement, titre, dateEven, description, organisateur, prix,
                CASE WHEN image IS NOT NULL THEN 1 ELSE 0 END as has_image 
                FROM evenement WHERE idEvenement = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $idEvent, PDO::PARAM_INT);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
            
            if (!$data) {
                return null;
            }
            
            return new Event(
                $data['idEvenement'],
                $data['titre'] ?? '',
                $data['dateEven'] ?? '',
                $data['description'] ?? '',
                $data['organisateur'] ?? '',
                $data['prix'] ?? null
            );
        } catch (PDOException $e) {
            throw new Exception("Erreur de récupération: " . $e->getMessage());
        }
    }

    // Rechercher des événements
    public function searchEvents($searchTerm) {
        $sql = "SELECT idEvenement, titre, dateEven, description, organisateur, prix,
                CASE WHEN image IS NOT NULL THEN 1 ELSE 0 END as has_image 
                FROM evenement 
                WHERE titre LIKE :search 
                OR description LIKE :search
                OR organisateur LIKE :search
                ORDER BY dateEven DESC";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':search', '%'.$searchTerm.'%');
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur de recherche: " . $e->getMessage());
        }
    }

    // Compter les participants
    public function countParticipants($idEvent) {
        $sql = "SELECT COUNT(*) as count FROM participationEvenement WHERE evenement_id = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $idEvent, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['count'] : 0;
        } catch (PDOException $e) {
            throw new Exception("Erreur de comptage: " . $e->getMessage());
        }
    }

    // Traiter l'image uploadée
    private function processImage($imageFile) {
        if (!$imageFile || $imageFile['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageFile['type'], $allowedTypes)) {
            throw new Exception("Type de fichier non supporté");
        }

        if ($imageFile['size'] > 2097152) {
            throw new Exception("La taille de l'image ne doit pas dépasser 2MB");
        }

        return file_get_contents($imageFile['tmp_name']);
    }
}