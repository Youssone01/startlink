<?php
require_once __DIR__ . '/../Model/Candidature.php';
require_once __DIR__ . '/../Controllers/config.php';

class CandidatureController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    // Méthode pour ajouter une candidature
    public function ajouterCandidature($userId, $offreId, $message, $cv) {
        try {
            // Vérification que le fichier a été téléchargé sans erreur
            if ($cv['error'] === 0) {
                // Définir les extensions autorisées pour le CV
                $allowedExtensions = ['pdf', 'doc', 'docx'];
                $fileExtension = strtolower(pathinfo($cv['name'], PATHINFO_EXTENSION));

                // Vérifier si l'extension du fichier est autorisée
                if (!in_array($fileExtension, $allowedExtensions)) {
                    throw new Exception("Le fichier doit être un PDF ou un document Word.");
                }

                // Vérifier la taille du fichier (maximum 5MB)
                if ($cv['size'] > 5000000) { // 5MB
                    throw new Exception("Le fichier est trop volumineux. Taille maximale autorisée : 5MB.");
                }

                // Définir le répertoire de destination pour le fichier CV
                $targetDir = "uploads/cvs/";
                if (!is_dir($targetDir)) {
                    if (!mkdir($targetDir, 0777, true)) {
                        throw new Exception("Impossible de créer le répertoire de téléchargement.");
                    }
                }

                // Définir le chemin complet du fichier
                $targetFile = $targetDir . basename($cv["name"]);

                // Vérifier si le fichier existe déjà
                if (file_exists($targetFile)) {
                    throw new Exception("Le fichier existe déjà.");
                }

                // Déplacer le fichier téléchargé vers le répertoire cible
                if (!move_uploaded_file($cv["tmp_name"], $targetFile)) {
                    throw new Exception("Erreur lors de l'upload du fichier.");
                }
            } else {
                throw new Exception("Aucun fichier CV n'a été téléchargé.");
            }

            // Préparer la requête d'insertion dans la table candidatures
            $query = $this->db->prepare("INSERT INTO candidatures (id_offre, user_id, lettre_motivation, cv, date_candidature) 
                                         VALUES (:offre_id, :user_id, :message, :cv, NOW())");
            
            // Exécuter la requête avec les données
            $query->execute([
                'offre_id' => $offreId,
                'user_id' => $userId,
                'message' => $message,
                'cv' => $targetFile
            ]);
    
            return true;
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo "Erreur de base de données : " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            // Gestion des autres erreurs (upload, validation)
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    // Méthode pour récupérer les candidatures d'un utilisateur spécifique
    public function getCandidaturesByUser($userId) {
        try {
            // Requête pour récupérer les candidatures de l'utilisateur connecté
            $query = $this->db->prepare("SELECT c.*, o.titre AS offre_titre
                                          FROM candidatures c
                                          INNER JOIN offres o ON c.id_offre = o.id
                                          WHERE c.user_id = :user_id");
            $query->execute(['user_id' => $userId]);
    
            // Retourner les candidatures sous forme de tableau
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo "Erreur de base de données : " . $e->getMessage();
            return [];
        }
    }

    // Fonction pour récupérer les candidatures soumises à une offre par les entrepreneurs
    public function getCandidaturesParOffre($offreId) {
    try {
        // Jointure entre candidatures et users pour récupérer les infos du candidat
        $query = "SELECT c.*, u.fullname, u.email 
                  FROM candidatures c
                  JOIN users u ON c.user_id = u.id
                  WHERE c.id_offre = :offre_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':offre_id', $offreId, PDO::PARAM_INT);
        $stmt->execute();

        $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $candidatures;
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des candidatures : " . $e->getMessage();
        return [];
    }
}

    // Méthode pour mettre à jour le statut d'une candidature
    public function mettreAJourStatutCandidature($candidatureId, $statut) {
    try {
        // Vérifier que l'ID de la candidature et le statut sont valides
        if (empty($candidatureId) || empty($statut)) {
            throw new Exception('ID de la candidature ou statut manquant.');
        }

        // Requête SQL pour mettre à jour le statut de la candidature
        $query = "UPDATE candidatures SET statut = :statut WHERE id = :id";

        // Préparer la requête
        $stmt = $this->db->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);  // Assurez-vous que le statut est bien une chaîne
        $stmt->bindParam(':id', $candidatureId, PDO::PARAM_INT); // Lier l'ID de la candidature comme entier

        // Exécuter la requête
        $stmt->execute();

        // Vérifier si une ligne a été affectée (mise à jour réussie)
        if ($stmt->rowCount() > 0) {
            echo "Le statut a été mis à jour avec succès.";
        } else {
            // Si aucune ligne n'est affectée, cela signifie que l'ID n'a pas été trouvé
            echo "Aucune mise à jour effectuée. Vérifiez que l'ID de la candidature est correct.";
        }
    } catch (PDOException $e) {
        // Gérer les erreurs PDO
        echo "Erreur lors de la mise à jour du statut de la candidature : " . $e->getMessage();
    } catch (Exception $e) {
        // Gérer les erreurs génériques
        echo "Erreur : " . $e->getMessage();
    }
}
}
?>