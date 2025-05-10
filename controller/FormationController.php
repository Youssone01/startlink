<?php
declare(strict_types=1);

require_once __DIR__ . '/../Controllers/config.php';

class FormationController
{
    /** @var \PDO */
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    public function getFormationStats(): array
    {
        $sql = "
            SELECT
                (SELECT COUNT(*) FROM formations) AS total_formations,
                (SELECT COUNT(*) FROM formations WHERE niveau = 'Débutant') AS formations_debutant,
                (SELECT COUNT(*) FROM formations WHERE niveau = 'Intermédiaire') AS formations_intermediaire,
                (SELECT COUNT(*) FROM formations WHERE niveau = 'Avancé') AS formations_avance
            FROM formations
            LIMIT 1
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function searchFormations(string $search, int $start, int $limit): array
    {
        $searchTerm = "%$search%";
        $sql = "
            SELECT * FROM formations
            WHERE nomFormation LIKE :search
               OR description LIKE :search
            LIMIT :start, :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Correction de la méthode addFormation
    public function addFormation(Formation $formation): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO formations (nomFormation, description, duree, niveau, date_debut, date_fin, places_disponibles) 
                                     VALUES (:nomFormation, :description, :duree, :niveau, :dateDebut, :dateFin, :placesDisponibles)");

        return $stmt->execute([ 
            ':nomFormation' => $formation->getNomFormation(),
            ':description' => $formation->getDescription(),
            ':duree' => $formation->getDuree(),
            ':niveau' => $formation->getNiveau(),
            ':dateDebut' => $formation->getDateDebut(),
            ':dateFin' => $formation->getDateFin(),
            ':placesDisponibles' => $formation->getPlacesDisponibles()
        ]);
    }

    public function deleteFormation(int $id): bool|string
    {
        $stmt = $this->pdo->prepare("DELETE FROM formations WHERE idFormation = :id");
        try {
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return "❌ Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    public function updateFormation(Formation $formation): bool|string
    {
        $sql = "
            UPDATE formations SET
                nomFormation = :nomFormation,
                description = :description,
                duree = :duree,
                niveau = :niveau,
                date_debut = :date_debut,
                date_fin = :date_fin,
                places_disponibles = :places_disponibles
            WHERE idFormation = :idFormation
        ";
        $stmt = $this->pdo->prepare($sql);
        try {
            return $stmt->execute([
                ':nomFormation' => $formation->getNomFormation(),
                ':description' => $formation->getDescription(),
                ':duree' => $formation->getDuree(),
                ':niveau' => $formation->getNiveau(),
                ':date_debut' => $formation->getDateDebut(),
                ':date_fin' => $formation->getDateFin(),
                ':places_disponibles' => $formation->getPlacesDisponibles(),
                ':idFormation' => $formation->getIdFormation(),
            ]);
        } catch (PDOException $e) {
            return "❌ Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }

    public function getAllFormations(): array
    {
        try {
            // Récupère toutes les formations
            $stmt = $this->pdo->query("SELECT * FROM formations ORDER BY date_debut ASC");
            $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Ajoute les participants pour chaque formation
            foreach ($formations as &$formation) {
                $formation['participants'] = $this->getParticipantsByFormation($formation['idFormation']);
            }

            return $formations;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getPaginatedFormations(int $page, int $limit): array
    {
        $start = max(0, ($page - 1) * $limit);
        $sql = "SELECT * FROM formations LIMIT :start, :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalFormations(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM formations");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

    public function getFormationById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM formations WHERE idFormation = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode corrigée pour récupérer les participants par ID de formation
    public function getInscriptionsByFormationId(int $idFormation): array
    {
        $sql = "
            SELECT
                i.idInscription, i.nomParticipant, i.emailParticipant, f.nomFormation, i.dateInscription
            FROM inscriptionformation i
            JOIN formations f ON i.idFormation = f.idFormation
            WHERE i.idFormation = :idFormation
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idFormation' => $idFormation]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addParticipantToFormation($idFormation, $nom, $email) {
        try {
            // Récupérer la date et l'heure actuelles
            $dateInscription = date('Y-m-d H:i:s');
    
            // Préparer la requête pour insérer un participant
            $query = "INSERT INTO inscriptionformation (idFormation, nomParticipant, emailParticipant, dateInscription) 
                      VALUES (:idFormation, :nom, :email, :dateInscription)";
            
            // Préparer la déclaration SQL
            $stmt = $this->pdo->prepare($query);
            
            // Lier les paramètres à la requête
            $stmt->bindParam(':idFormation', $idFormation, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':dateInscription', $dateInscription, PDO::PARAM_STR);
            
            // Exécuter la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            // Gérer l'erreur
            echo "Erreur lors de l'ajout du participant : " . $e->getMessage();
            return false;
        }
    }

    public function updateInscriptionFormation(int $id, string $nom, string $email, string $dateInscription): bool
{
    $stmt = $this->pdo->prepare("UPDATE inscriptionformation
        SET nomParticipant = :nom,
            emailParticipant = :email,
            dateInscription = :dateInscription
        WHERE idInscription = :id
    ");
    
    return $stmt->execute([ 
        ':nom' => $nom,
        ':email' => $email,
        ':dateInscription' => $dateInscription,
        ':id' => $id
    ]);
}


    // Méthode pour supprimer un participant
    public function deleteInscriptionFormation($id) {
        $query = "DELETE FROM inscriptionformation WHERE idInscription = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getParticipantsByFormation($formationId) {
        $sql = "SELECT * FROM inscriptionformation WHERE idFormation = :formationId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['formationId' => $formationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour gérer l'inscription à une formation et décrémenter les places disponibles
    public function registerForFormation($idFormation, $userId): bool 
    {
        try {
            $this->pdo->beginTransaction();
    
            // Vérifier si déjà inscrit
            $stmt = $this->pdo->prepare("SELECT * FROM inscriptionformation WHERE idFormation = :idFormation AND idUser = :idUser");
            $stmt->execute(['idFormation' => $idFormation, 'idUser' => $userId]);
            if ($stmt->fetch()) {
                $this->pdo->rollBack();
                return false;
            }
    
            // Vérifier les places disponibles (avec verrouillage)
            $stmt = $this->pdo->prepare("SELECT places_disponibles FROM formations WHERE idFormation = :idFormation FOR UPDATE");
            $stmt->execute(['idFormation' => $idFormation]);
            $formation = $stmt->fetch();
    
            if (!$formation || $formation['places_disponibles'] <= 0) {
                $this->pdo->rollBack();
                return false;
            }
    
            // Insérer l'inscription
            $stmt = $this->pdo->prepare("INSERT INTO inscriptionformation (idFormation, idUser) VALUES (:idFormation, :idUser)");
            $stmt->execute(['idFormation' => $idFormation, 'idUser' => $userId]);
    
            // Décrémenter les places disponibles
            $stmt = $this->pdo->prepare("UPDATE formations SET places_disponibles = places_disponibles  WHERE idFormation = :idFormation");
            $stmt->execute(['idFormation' => $idFormation]);
    
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    public function unregisterFromFormation($idFormation, $userId): bool 
{
        try {
            $this->pdo->beginTransaction();

        // Vérifier si l'utilisateur est bien inscrit
            $stmt = $this->pdo->prepare("SELECT * FROM inscriptionformation WHERE idFormation = :idFormation AND idUser = :idUser");
            $stmt->execute(['idFormation' => $idFormation, 'idUser' => $userId]);
            if (!$stmt->fetch()) {
                $this->pdo->rollBack();
            return false; // Pas d'inscription à annuler
        }

        // Supprimer l'inscription
        $stmt = $this->pdo->prepare("DELETE FROM inscriptionformation WHERE idFormation = :idFormation AND idUser = :idUser");
        $stmt->execute(['idFormation' => $idFormation, 'idUser' => $userId]);

        // Réincrémenter les places disponibles
        $stmt = $this->pdo->prepare("UPDATE formations SET places_disponibles = places_disponibles + 1 WHERE idFormation = :idFormation");
        $stmt->execute(['idFormation' => $idFormation]);

        $this->pdo->commit();
        return true;
    } catch (PDOException $e) {
        $this->pdo->rollBack();
        return false;
    }
}

}
