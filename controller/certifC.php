<?php
require_once __DIR__ . '/../model/certif.php';

class CertifC {
    private $model;

    public function __construct() {
        $this->model = new Certif();
    }

    public function afficherCertifications() {
        return $this->model->getAll();
    }

    public function ajouterCertification($id_user, $id_formation, $date_obtention, $score_quiz, $statut) {
        $certif = new Certif();
        $certif->ajouterCertification($id_user, $id_formation, $date_obtention, $score_quiz, $statut);
    }
    

    public function supprimerCertification($id_certif) {
        return $this->model->supprimerCertification($id_certif);
    }

    public function modifierCertification($id_certification, $id_user, $id_formation, $date_obtention, $score_quiz) {
        $pdo = config::getConnexion();
        $sql = "UPDATE certifications SET id_user = :id_user, id_formation = :id_formation, date_obtention = :date_obtention, score_quiz = :score_quiz WHERE id_certification = :id_certification";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_certification', $id_certification, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_formation', $id_formation, PDO::PARAM_INT);
        $stmt->bindParam(':date_obtention', $date_obtention);
        $stmt->bindParam(':score_quiz', $score_quiz, PDO::PARAM_INT);

        return $stmt->execute(); // Retourne true si la mise à jour a réussi, sinon false
    }

    
    public function recupererCertification($id_certification)
{
    $sql = "SELECT * FROM certifications WHERE id_certification = :id_certification";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id_certification', $id_certification);
        $query->execute();
        return $query->fetch(); // Retourner une seule ligne
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
public function rechercherCertificationParId($id) {
    $sql = "SELECT * FROM certifications WHERE id_certification = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        return $query->fetch();
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

}
