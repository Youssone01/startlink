<?php
class Offre {
    private $id;
    private $titre;
    private $description;
    private $competences_requises;
    private $type_contrat;
    private $salaire;
    private $lieu;
    private $date_publication;
    private $user_id;

    // Getters et Setters
    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getCompetencesRequises() { return $this->competences_requises; }
    public function getTypeContrat() { return $this->type_contrat; }
    public function getSalaire() { return $this->salaire; }
    public function getLieu() { return $this->lieu; }
    public function getDatePublication() { return $this->date_publication; }
    public function getUserId() { return $this->user_id; }

    public function setId($id) { $this->id = $id; }
    public function setTitre($titre) { $this->titre = $titre; }
    public function setDescription($description) { $this->description = $description; }
    public function setCompetencesRequises($competences) { $this->competences_requises = $competences; }
    public function setTypeContrat($type) { $this->type_contrat = $type; }
    public function setSalaire($salaire) { $this->salaire = $salaire; }
    public function setLieu($lieu) { $this->lieu = $lieu; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function __construct($titre = null, $description = null, $competences = null, $type = null, $salaire = null, $lieu = null, $user_id = null) {
        if ($titre) $this->titre = $titre;
        if ($description) $this->description = $description;
        if ($competences) $this->competences_requises = $competences;
        if ($type) $this->type_contrat = $type;
        if ($salaire) $this->salaire = $salaire;
        if ($lieu) $this->lieu = $lieu;
        if ($user_id) $this->user_id = $user_id;
    }
}
?>
