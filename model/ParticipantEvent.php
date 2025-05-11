<?php

class ParticipationEvenement {
    private ?int $idParti = null;
    private int $utilisateur_id;
    private int $evenement_id;
    private string $dateInscription ;

    // Constructeur
    public function __construct(?int $idParti = null, int $utilisateur_id = 0, int $evenement_id = 0, string $dateInscription) {
        $this->idParti = $idParti;
        $this->utilisateur_id = $utilisateur_id;
        $this->evenement_id = $evenement_id;
        $this->dateInscription = $dateInscription;
    }

    // Getters
    public function getIdParti(): ?int {
        return $this->idParti;
    }

    public function getUtilisateurId(): int {
        return $this->utilisateur_id;
    }

    public function getEvenementId(): int {
        return $this->evenement_id;
    }

    public function isdateInscription(): bool {
        return $this->dateInscription;
    }

    // Setters
    public function setIdParti(?int $idParti): void {
        $this->idParti = $idParti;
    }

    public function setUtilisateurId(int $utilisateur_id): void {
        $this->utilisateur_id = $utilisateur_id;
    }

    public function setEvenementId(int $evenement_id): void {
        $this->evenement_id = $evenement_id;
    }

    public function setdateInscription(bool $dateInscription): void {
        $this->dateInscription = $dateInscription;
    }
}
