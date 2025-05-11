<?php
require_once __DIR__ . '/../model/Offre.php';
require_once __DIR__ . '/../Controllers/config.php';  // Utilisation de __DIR__ pour obtenir le chemin absolu


class OffreController {
    private $db;

    public function __construct() {
        // Démarrage de la session pour récupérer l'ID de l'utilisateur
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Connexion à la base de données
        $this->db = config::getConnexion();
    }

    public function createOffre(Offre $offre) {
        try {
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Vous devez être connecté pour ajouter une offre.");
            }
    
            // Récupérer l'ID de l'utilisateur connecté
            $userId = $_SESSION['user_id'];
    
            // Vérification que toutes les informations nécessaires sont présentes
            if (empty($offre->getTitre()) || empty($offre->getDescription()) || empty($offre->getCompetencesRequises()) || 
                empty($offre->getTypeContrat()) || empty($offre->getSalaire()) || empty($offre->getLieu())) {
                throw new Exception("Tous les champs doivent être remplis.");
            }
    
            // Préparer la requête d'insertion
            $query = $this->db->prepare("INSERT INTO offres (titre, description, competences_requises, type_contrat, salaire, lieu, user_id) 
                                        VALUES (:titre, :description, :competences, :type, :salaire, :lieu, :user_id)");
    
            // Assainir les données avant l'insertion (sécurisation)
            $titre = htmlspecialchars($offre->getTitre());
            $description = htmlspecialchars($offre->getDescription());
            $competences = htmlspecialchars($offre->getCompetencesRequises());
            $typeContrat = htmlspecialchars($offre->getTypeContrat());
            $salaire = floatval($offre->getSalaire()); // Assurer que le salaire est bien un nombre
            $lieu = htmlspecialchars($offre->getLieu());
    
            // Exécuter la requête avec les données du formulaire et l'ID de l'utilisateur
            $result = $query->execute([
                'titre' => $titre,
                'description' => $description,
                'competences' => $competences,
                'type' => $typeContrat,
                'salaire' => $salaire,
                'lieu' => $lieu,
                'user_id' => $userId
            ]);
    
            // Vérifier si l'insertion a réussi
            if ($result) {
                return "L'offre a été ajoutée avec succès!";
            } else {
                throw new Exception("Une erreur est survenue lors de l'ajout de l'offre.");
            }
        } catch (Exception $e) {
            return "Erreur: " . $e->getMessage(); // Retourner l'erreur au lieu d'utiliser echo
        }
    }
        public function getOffres() {
        try {
            $query = $this->db->query("SELECT * FROM offres ORDER BY date_publication DESC");
            return $query->fetchAll();
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    public function getOffresByUser($user_id) {
        try {
            $query = $this->db->prepare("SELECT * FROM offres WHERE user_id = :user_id ORDER BY date_publication DESC");
            $query->execute(['user_id' => $user_id]);
            return $query->fetchAll();
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    public function getOffreById($id) {
        try {
            $query = $this->db->prepare("SELECT o.*, u.fullname as auteur FROM offres o JOIN users u ON o.user_id = u.id WHERE o.id = :id");
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    public function updateOffre(Offre $offre) {
        try {
            $query = $this->db->prepare("UPDATE offres SET titre = :titre, description = :description, competences_requises = :competences, 
                                    type_contrat = :type, salaire = :salaire, lieu = :lieu WHERE id = :id");
            return $query->execute([
                'id' => $offre->getId(),
                'titre' => $offre->getTitre(),
                'description' => $offre->getDescription(),
                'competences' => $offre->getCompetencesRequises(),
                'type' => $offre->getTypeContrat(),
                'salaire' => $offre->getSalaire(),
                'lieu' => $offre->getLieu()
            ]);
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    public function deleteOffre($id) {
        try {
            $query = $this->db->prepare("DELETE FROM offres WHERE id = :id");
            return $query->execute(['id' => $id]);
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }
}
?>
