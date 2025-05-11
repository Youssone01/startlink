<?php

// Affiche toutes les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../Controllers/config.php';
require_once __DIR__ . '/../model/user.php';

class StartlinkUserController {
    private $conn;

    public function __construct() {
        $this->conn = config::getConnexion();
    }

    // ✅ Ajout d'utilisateur
    public function addUser($user) {
        try {
            $sql = "INSERT INTO users (fullname, date_n, adresse, bio, password, email, role)  
                    VALUES (:fullname, :date_n, :adresse, :bio, :password, :email, :role)";
            $query = $this->conn->prepare($sql);
            $query->execute([
                'fullname' => $user->getFullname(),
                'date_n' => $user->getDateN(),
                'adresse' => $user->getAdresse(),
                'bio' => $user->getBio(),
                'password' => $user->getPassword(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ]);
            return true; 
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    // ✅ Mise à jour utilisateur
    public function updateUser($id, User $user) {
        try {
            $sql = "UPDATE users 
                    SET fullname = :fullname, 
                        date_n = :date_n, 
                        adresse = :adresse, 
                        bio = :bio, 
                        password = :password, 
                        email = :email, 
                        role = :role
                    WHERE id = :id";
            $query = $this->conn->prepare($sql);
            $query->execute([
                'id' => $id,
                'fullname' => $user->getFullname(),
                'date_n' => $user->getDateN(),
                'adresse' => $user->getAdresse(),
                'bio' => $user->getBio(),
                'password' => $user->getPassword(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // ✅ Supprimer un utilisateur
    public function deleteUser($id) {
        try {
            $query = $this->conn->prepare("DELETE FROM users WHERE id = :id");
            $query->bindValue(':id', $id);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // ✅ Récupérer tous les utilisateurs
    public function getAllUsers() {
        try {
            $query = $this->conn->query("SELECT * FROM users");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // ✅ Récupérer un utilisateur par ID
    public function getUserById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return null;
        }
    }

    // ✅ Login (optionnel si pas encore ajouté)
    public function login($email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (md5($password) === $user['password']) {
                    return $user;
                }
            }
            return false;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
}
?>
