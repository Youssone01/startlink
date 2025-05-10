<?php 
class Formation {
    private $idFormation;
    private $nomFormation;
    private $description;
    private $duree;
    private $niveau;
    private $date_debut;
    private $date_fin;
    private $places_disponibles; // Ajout du champ placesDisponibles

    // Constructeur
    public function __construct($nomFormation, $description, $duree, $niveau, $date_debut, $date_fin, $places_disponibles, $idFormation = null) {
        $this->nomFormation = $nomFormation;
        $this->description = $description;
        $this->duree = $duree;
        $this->niveau = $niveau;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->places_disponibles = $places_disponibles; // Initialisation du champ placesDisponibles
        $this->idFormation = $idFormation; // Par défaut null si non fourni
    }

    // Getter et Setter pour idFormation
    public function getIdFormation() {
        return $this->idFormation;
    }

    public function setIdFormation($idFormation) {
        $this->idFormation = $idFormation;
    }

    // Getter et Setter pour nomFormation
    public function getNomFormation() {
        return $this->nomFormation;
    }

    public function setNomFormation($nomFormation) {
        $this->nomFormation = $nomFormation;
    }

    // Getter et Setter pour description
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    // Getter et Setter pour duree
    public function getDuree() {
        return $this->duree;
    }

    public function setDuree($duree) {
        $this->duree = $duree;
    }

    // Getter et Setter pour niveau
    public function getNiveau() {
        return $this->niveau;
    }

    public function setNiveau($niveau) {
        $this->niveau = $niveau;
    }

    // Getter et Setter pour dateDebut
    public function getDateDebut() {
        return $this->date_debut;
    }

    public function setDateDebut($date_debut) {
        $this->date_debut = $date_debut;
    }

    // Getter et Setter pour dateFin
    public function getDateFin() {
        return $this->date_fin;
    }

    public function setDateFin($date_fin) {
        $this->date_fin = $date_fin;
    }

    // Getter et Setter pour placesDisponibles
    public function getPlacesDisponibles() {
        return $this->places_disponibles;
    }

    public function setPlacesDisponibles($places_disponibles) {
        $this->places_disponibles = $places_disponibles;
    }
}
?>
