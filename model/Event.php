<?php

class Event {
    private ?int $idEvent = null;
    private string $titre;
    private string $dateEvent;
    private string $description;
    private string $organisateur;
    private ?float $prix = null;
    private ?string $imageData = null;       // Données binaires de l'image
    private ?string $imageType = null;       // Type MIME de l'image
    private ?string $tempImagePath = null;   // Chemin temporaire pour l'upload

    // Constructeur
    public function __construct(
        ?int $idEvent = null, 
        string $titre = "", 
        string $dateEvent = "", 
        string $description = "", 
        string $organisateur = "",
        ?float $prix = null,
        ?string $imageData = null,
        ?string $imageType = null
    ) {
        $this->idEvent = $idEvent;
        $this->titre = $titre;
        $this->dateEvent = $dateEvent;
        $this->description = $description;
        $this->organisateur = $organisateur;
        $this->prix = $prix;
        $this->imageData = $imageData;
        $this->imageType = $imageType;
    }

    // Getters
    public function getIdEvent(): ?int {
        return $this->idEvent;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getDateEvent(): string {
        return $this->dateEvent;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getOrganisateur(): string {
        return $this->organisateur;
    }

    public function getImageData(): ?string {
        return $this->imageData;
    }

    public function getImageType(): ?string {
        return $this->imageType;
    }

    public function getTempImagePath(): ?string {
        return $this->tempImagePath;
    }

    // Setters
    public function setIdEvent(?int $idEvent): void {
        $this->idEvent = $idEvent;
    }

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function setDateEvent(string $dateEvent): void {
        $this->dateEvent = $dateEvent;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setOrganisateur(string $organisateur): void {
        $this->organisateur = $organisateur;
    }
    public function setPrix(?float $prix): self {
        $this->prix = $prix;
        return $this;
    }
    public function setImageData(?string $imageData): void {
        $this->imageData = $imageData;
    }

    public function setImageType(?string $imageType): void {
        $this->imageType = $imageType;
    }

    public function setTempImagePath(?string $path): void {
        $this->tempImagePath = $path;
    }
    public function getPrix(): ?float {
        return $this->prix;
    }

    public function getFormattedPrix(): string {
        return $this->prix !== null ? number_format($this->prix, 2) . 'dt' : 'Gratuit';
    }
    // Méthodes pour l'image
    public function hasImage(): bool {
        return $this->imageData !== null;
    }

    public function getImageBase64(): string {
        if ($this->hasImage()) {
            return 'data:' . $this->imageType . ';base64,' . base64_encode($this->imageData);
        }
        return '';
    }

    /**
     * Charge une image à partir d'un fichier uploadé
     * @param array $file Le fichier uploadé ($_FILES['nom_du_champ'])
     * @throws Exception Si l'upload échoue
     */
    public function loadImageFromUpload(array $file): void {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors de l'upload de l'image: " . $file['error']);
        }

        // Vérification du type MIME
        $mimeTypesAutorises = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $mimeTypesAutorises)) {
            throw new Exception("Type de fichier non autorisé. Seuls JPEG, PNG et GIF sont acceptés.");
        }

        // Limite de taille (2MB max)
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new Exception("L'image ne doit pas dépasser 2MB");
        }

        $this->tempImagePath = $file['tmp_name'];
        $this->imageData = file_get_contents($file['tmp_name']);
        $this->imageType = $mime;
    }

    /**
     * Supprime l'image de l'événement
     */
    public function removeImage(): void {
        $this->imageData = null;
        $this->imageType = null;
        $this->tempImagePath = null;
    }
}