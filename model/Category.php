<?php
class Category {
    // Connection
    private $conn;
    private $table = 'categories';

    // Properties
    public $category_id;
    public $name;
    public $description;
    public $created_at;
    public $updated_at;

    // Constructor with DB
    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Get All Categories
    public function getAllCategories() {
        // Create query
        $query = "SELECT * FROM {$this->table} ORDER BY name ASC";
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Execute query
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Single Category
    public function getSingleCategory() {
        // Create query
        $query = "SELECT * FROM {$this->table} WHERE category_id = :category_id LIMIT 0,1";
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Bind ID
        $stmt->bindParam(':category_id', $this->category_id);
        
        // Execute query
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Set properties
        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'] ?? '';
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        
        return false;
    }

    // Create Category
    public function createCategory() {
        // Create query
        $query = "INSERT INTO {$this->table} (name, description) VALUES (:name, :description)";
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        
        return false;
    }

    // Update Category
    public function updateCategory() {
        // Create query
        $query = "UPDATE {$this->table} SET name = :name, description = :description, updated_at = NOW() WHERE category_id = :category_id";
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        
        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        
        return false;
    }

    // Delete Category
    public function deleteCategory() {
        // Create query
        $query = "DELETE FROM {$this->table} WHERE category_id = :category_id";
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        
        // Bind data
        $stmt->bindParam(':category_id', $this->category_id);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        
        return false;
    }
}
?>
