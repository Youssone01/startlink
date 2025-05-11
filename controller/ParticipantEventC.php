<?php
class ParticipationC {
    public function ajouterParticipant($utilisateur_id, $evenement_id) {
        $db = config::getConnexion();  // Connexion à la base de données
        // On ajoute la date d'inscription dans la requête SQL
        $dateInscription = date("Y-m-d");
    
        $sql = "INSERT INTO participationEvenement (utilisateur_id, evenement_id, dateInscription) 
                VALUES (:utilisateur_id, :evenement_id, :dateInscription)";
        
        try {
            $query = $db->prepare($sql);
            // Bind des paramètres
            $query->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
            $query->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
            $query->bindParam(':dateInscription', $dateInscription, PDO::PARAM_STR);
            
            // Exécution de la requête
            $query->execute();
        } catch (PDOException $e) {
            die('Erreur lors de l\'ajout du participant : ' . $e->getMessage());
        }
    }

    public function getEventById($id) {
        $db = config::getConnexion();  // Connexion à la base de données
        $sql = "SELECT * FROM evenement WHERE idEvenement = :id";
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            die('Erreur lors de la récupération de l\'événement : ' . $e->getMessage());
        }
    }
    
    public function modifierParticipant($id, $nom, $prenom, $email) {
        $db = config::getConnexion();  // Connexion à la base de données
        try {
            $sql = "UPDATE participants SET nom = :nom, prenom = :prenom, email = :email WHERE idParticipant = :id";
            $query = $db->prepare($sql);
            $query->bindParam(':nom', $nom);
            $query->bindParam(':prenom', $prenom);
            $query->bindParam(':email', $email);
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (PDOException $e) {
            die('Erreur lors de la modification du participant : ' . $e->getMessage());
        }
    }

    public function getParticipantById($id) {
        $db = config::getConnexion();  // Connexion à la base de données
        try {
            $sql = "SELECT * FROM participants WHERE idParticipant = :id";
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Erreur lors de la récupération du participant : ' . $e->getMessage());
        }
    }

    public function supprimerParticipant($id) {
        $db = config::getConnexion();  // Connexion à la base de données
        try {
            $sql = "DELETE FROM participants WHERE idParticipant = :id";
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (PDOException $e) {
            die('Erreur lors de la suppression du participant : ' . $e->getMessage());
        }
    }

    // Fonction corrigée pour récupérer le nombre total de participants
    public function getTotalParticipantsByEvent($event_id) {
        $db = config::getConnexion();  // Connexion à la base de données
        // Requête SQL pour compter le nombre de participants pour un événement donné
        $query = "SELECT COUNT(*) FROM participationevenement WHERE evenement_id = :event_id";
        
        // Préparer la requête
        $stmt = $db->prepare($query);
        
        // Lier la valeur de l'ID de l'événement à la requête
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        
        // Exécuter la requête
        $stmt->execute();
        
        // Retourner le nombre de participants
        return $stmt->fetchColumn();
    }
}

