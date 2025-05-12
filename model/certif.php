<?php
// Inclure la configuration pour établir la connexion à la base de données

class Certif {
    private $conn;

    public function __construct() {
        // Récupérer la connexion via la classe config
        $this->conn = config::getConnexion();
    }

    // Récupérer toutes les certifications
    public function getAll() {
        $query = "SELECT * FROM certifications";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);  // Correction ici : fetchAll() avec PDO
    }

    public function ajouterCertification($id_user, $id_formation, $date_obtention, $score_quiz, ) {
        $sql = "INSERT INTO certifications (id_user, id_formation, date_obtention, score_quiz, )
                VALUES (:id_user, :id_formation, :date_obtention, :score_quiz, )";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
    
            // Lier les valeurs
            $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);
            $query->bindValue(':id_formation', $id_formation, PDO::PARAM_INT);
            $query->bindValue(':date_obtention', $date_obtention, PDO::PARAM_STR);
            $query->bindValue(':score_quiz', $score_quiz, PDO::PARAM_INT);
    
            // Exécuter la requête
            $query->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    
    public function supprimerCertification($id_certification)
{
    $sql = "DELETE FROM certifications WHERE id_certification = :id_certification";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id_certification', $id_certification, PDO::PARAM_INT);
        $query->execute();
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}


public function modifierCertification($id, $user_id, $formation_id, $date) {
    $db = config::getConnexion();

    // Requête SQL mise à jour
    $sql = "UPDATE certifications SET user_id = :user_id, formation_id = :formation_id, date = :date WHERE id_certification = :id_certification";

    // Préparation de la requête
    $stmt = $db->prepare($sql);

    // Liaison des paramètres
    $stmt->bindValue(':id_certification', $id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':formation_id', $formation_id, PDO::PARAM_INT);
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);

    // Exécution de la requête
    try {
        $stmt->execute();
        $affectedRows = $stmt->rowCount(); // Nombre de lignes affectées
        if ($affectedRows > 0) {
            // Rediriger après la mise à jour réussie
            header('Location: listcertif.php');
            exit;
        } else {
            // Si aucune ligne n'est affectée, afficher un message
            echo "Aucune modification n'a été effectuée.";
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}





}
?>
