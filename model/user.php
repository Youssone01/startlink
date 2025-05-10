<?php

class User {
    private $fullname;
    private $date_n;
    private $adresse;
    private $bio;
    private $password;
    private $email;
    private $role;

    public function __construct($fullname, $date_n, $adresse, $bio, $password, $email, $role) {
        $this->fullname = $fullname;
        $this->date_n = $date_n;
        $this->adresse = $adresse;
        $this->bio = $bio;
        $this->password = $password;
        $this->email = $email;
        $this->role = $role;
    }

    public function getFullname() { return $this->fullname; }
    public function getDateN() { return $this->date_n; }
    public function getAdresse() { return $this->adresse; }
    public function getBio() { return $this->bio; }
    public function getPassword() { return $this->password; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }
}
?>