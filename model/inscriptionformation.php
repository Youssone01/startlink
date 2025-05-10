<?php

class InscriptionFormation {
    // Attributs
    private $idInscription;
    private $idFormation;
    private $nomParticipant;
    private $emailParticipant;
    private $dateInscription;

    // Constructeur
    public function __construct($idInscription = null, $idFormation, $nomParticipant, $emailParticipant, $dateInscription) {
        $this->idInscription = $idInscription;
        $this->idFormation = $idFormation;
        $this->nomParticipant = $nomParticipant;
        $this->emailParticipant = $emailParticipant;
        $this->dateInscription = $dateInscription;
    }

    // Getter et Setter pour chaque attribut
    public function getIdInscription() {
        return $this->idInscription;
    }

    public function setIdInscription($idInscription) {
        $this->idInscription = $idInscription;
    }

    public function getIdFormation() {
        return $this->idFormation;
    }

    public function setIdFormation($idFormation) {
        $this->idFormation = $idFormation;
    }

    public function getNomParticipant() {
        return $this->nomParticipant;
    }

    public function setNomParticipant($nomParticipant) {
        $this->nomParticipant = $nomParticipant;
    }

    public function getEmailParticipant() {
        return $this->emailParticipant;
    }

    public function setEmailParticipant($emailParticipant) {
        $this->emailParticipant = $emailParticipant;
    }

    public function getDateInscription() {
        return $this->dateInscription;
    }

    public function setDateInscription($dateInscription) {
        $this->dateInscription = $dateInscription;
    }

    // Méthode pour enregistrer une inscription (exemple avec PDO)
    public function save() {
        global $pdo; // Connexion à la base de données via PDO

        $query = "INSERT INTO inscriptionformation (idFormation, nomParticipant, emailParticipant, dateInscription)
                  VALUES (:idFormation, :nomParticipant, :emailParticipant, :dateInscription)";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idFormation', $this->idFormation);
        $stmt->bindParam(':nomParticipant', $this->nomParticipant);
        $stmt->bindParam(':emailParticipant', $this->emailParticipant);
        $stmt->bindParam(':dateInscription', $this->dateInscription);

        return $stmt->execute();
    }

    // Méthode pour supprimer une inscription
    public function delete($idInscription) {
        global $pdo;

        $query = "DELETE FROM inscriptionformation WHERE idInscription = :idInscription";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idInscription', $idInscription);

        return $stmt->execute();
    }

    // Méthode pour récupérer toutes les inscriptions
    public static function getAllInscriptions() {
        global $pdo;

        $query = "SELECT * FROM inscriptionformation";
        $stmt = $pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer une inscription par ID
    public static function getInscriptionById($idInscription) {
        global $pdo;

        $query = "SELECT * FROM inscriptionformation WHERE idInscription = :idInscription";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idInscription', $idInscription);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
