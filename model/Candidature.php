<?php
class Candidature {
    private $id;
    private $id_offre;
    private $nom;
    private $email;
    private $lettre_motivation;
    private $date_candidature;

    // Getters et Setters
    public function getId() { return $this->id; }
    public function getIdOffre() { return $this->id_offre; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getLettreMotivation() { return $this->lettre_motivation; }
    public function getDateCandidature() { return $this->date_candidature; }

    public function setId($id) { $this->id = $id; }
    public function setIdOffre($id_offre) { $this->id_offre = $id_offre; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setEmail($email) { $this->email = $email; }
    public function setLettreMotivation($lettre_motivation) { $this->lettre_motivation = $lettre_motivation; }
    public function setDateCandidature($date_candidature) { $this->date_candidature = $date_candidature; }

    public function __construct($id_offre, $nom, $email, $lettre_motivation) {
        $this->id_offre = $id_offre;
        $this->nom = $nom;
        $this->email = $email;
        $this->lettre_motivation = $lettre_motivation;
        $this->date_candidature = date('Y-m-d H:i:s'); // Date actuelle
    }
}
?>
