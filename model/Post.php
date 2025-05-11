<?php
require_once __DIR__ . '/../Config/Database.php';

class Post {
    private $conn;
    private $table = 'posts';
    
    // Post properties based on the database schema
    public $post_id;
    public $title;
    public $content;
    public $author;
    public $created_at;
    public $updated_at;
    public $status; // enum('published','draft','archived','blocked')
    
    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }
    
    // Get all posts
    public function getAllPosts($includeBlocked = false, $statusFilter = null) {
        $query = "SELECT * FROM {$this->table}";
        
        if ($statusFilter) {
            $query .= " WHERE status = :status";
        } else if (!$includeBlocked) {
            $query .= " WHERE status != 'blocked'";
        }
        
        $query .= " ORDER BY post_id DESC";
        $stmt = $this->conn->prepare($query);
        
        if ($statusFilter) {
            $stmt->bindParam(':status', $statusFilter);
        }
        
        $stmt->execute();
        
        return $stmt;
    }
    
    // Get single post
    public function getSinglePost() {
        try {
            $query = "SELECT * FROM {$this->table} WHERE post_id = :post_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':post_id', $this->post_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($row) {
                $this->title = $row['title'];
                $this->content = $row['content'];
                $this->author = $row['author'];
                $this->created_at = $row['created_at'];
                $this->updated_at = $row['updated_at'];
                $this->status = $row['status'];
                
                // Log successful post retrieval
                error_log("Successfully loaded post ID: {$this->post_id}, Title: {$this->title}, Status: {$this->status}");
                
                return true;
            }
            
            // Log failed post retrieval
            error_log("Failed to load post ID: {$this->post_id} - No data found");
            return false;
        } catch (PDOException $e) {
            // Log any errors
            error_log("Error in getSinglePost for ID {$this->post_id}: " . $e->getMessage());
            return false;
        }
    }
    
    // Create post
    public function createPost() {
        try {
            // First, let's check the table structure
            $tableCheckQuery = "SHOW COLUMNS FROM {$this->table}";
            $tableCheck = $this->conn->query($tableCheckQuery);
            $columns = $tableCheck->fetchAll(PDO::FETCH_COLUMN);
            
            // Determine which columns actually exist in the table
            $hasCreatedAt = in_array('created_at', $columns);
            $hasUpdatedAt = in_array('updated_at', $columns);
            
            // Build query based on existing columns
            $query = "INSERT INTO {$this->table} (title, content, author, status";
            $values = "VALUES (:title, :content, :author, :status";
            
            if ($hasCreatedAt) {
                $query .= ", created_at";
                $values .= ", NOW()";
            }
            
            if ($hasUpdatedAt) {
                $query .= ", updated_at";
                $values .= ", NOW()";
            }
            
            $query .= ") ";
            $values .= ")";
            
            $fullQuery = $query . $values;
            
            // For debugging
            error_log("SQL Query: " . $fullQuery);
            error_log("Table columns: " . implode(", ", $columns));
            
            $stmt = $this->conn->prepare($fullQuery);
            
            // Clean data
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->content = htmlspecialchars(strip_tags($this->content));
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->status = htmlspecialchars(strip_tags($this->status));
            
            // Bind data
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':content', $this->content);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':status', $this->status);
            
            // Execute query
            if($stmt->execute()) {
                // Get the ID of the newly created post
                $this->post_id = $this->conn->lastInsertId();
                return true;
            }
            
            // If execution failed, get error info
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error: " . implode(", ", $errorInfo));
            return false;
        } catch(PDOException $e) {
            // Log the error for debugging
            error_log("Database Error in createPost: " . $e->getMessage());
            return false;
        }
    }
    
    // Update post
    public function updatePost() {
        $query = "UPDATE {$this->table} 
                  SET 
                    title = :title, 
                    content = :content, 
                    author = :author, 
                    updated_at = CURRENT_TIMESTAMP, 
                    status = :status 
                  WHERE 
                    post_id = :post_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->post_id = htmlspecialchars(strip_tags($this->post_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Bind data
        $stmt->bindParam(':post_id', $this->post_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':status', $this->status);
        
        if($stmt->execute()) {
            return true;
        }
        
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
    
    // Update post status
    public function updateStatus() {
        $query = "UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->post_id = htmlspecialchars(strip_tags($this->post_id));
        
        // Bind data
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':post_id', $this->post_id);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Block/Unblock post
    public function toggleBlockStatus() {
        // First get current status
        $query = "SELECT status FROM {$this->table} WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':post_id', $this->post_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $currentStatus = $row['status'];
        
        // Determine new status
        $newStatus = ($currentStatus == 'blocked') ? 'published' : 'blocked';
        
        // Update status
        $query = "UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($query);
        
        // Bind data
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':post_id', $this->post_id);
        
        // Execute query
        if($stmt->execute()) {
            return $newStatus;
        }
        
        return false;
    }
    
    // Get all blocked posts
    public function getBlockedPosts() {
        $query = "SELECT * FROM {$this->table} WHERE status = 'blocked' ORDER BY updated_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Delete post
    public function deletePost() {
        $query = "DELETE FROM {$this->table} WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->post_id = htmlspecialchars(strip_tags($this->post_id));
        
        // Bind data
        $stmt->bindParam(':post_id', $this->post_id);
        
        if($stmt->execute()) {
            return true;
        }
        
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
    
    // Get post statistics
    public function getPostStatistics() {
        $stats = [];
        
        // Total number of posts
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Posts by status
        $query = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['by_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Most recent post
        $query = "SELECT post_id, title, created_at FROM {$this->table} ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['most_recent'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Posts per month (last 6 months)
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                 FROM {$this->table} 
                 WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) 
                 GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                 ORDER BY month";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['by_month'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

    
    // Get filtered posts with pagination
    public function getFilteredPosts($search = '', $sort = 'newest', $limit = 10, $offset = 0) {
        $query = "SELECT * FROM {$this->table} WHERE status != 'blocked' AND status != 'pending_deletion'";
        
        // Add search condition if provided
        if (!empty($search)) {
            $query .= " AND (title LIKE :search OR content LIKE :search OR author LIKE :search)";
        }
        
        // Add sorting
        switch ($sort) {
            case 'oldest':
                $query .= " ORDER BY created_at ASC";
                break;
            case 'comments':
                $query .= " ORDER BY (SELECT COUNT(*) FROM comments WHERE comments.post_id = {$this->table}.post_id AND comments.status = 'approved') DESC, created_at DESC";
                break;
            case 'newest':
            default:
                $query .= " ORDER BY created_at DESC";
                break;
        }
        
        // Add pagination
        $query .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind search parameter if provided
        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        // Bind pagination parameters
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt;
    }
    
    // Count filtered posts
    public function countFilteredPosts($search = '') {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE status != 'blocked' AND status != 'pending_deletion'";
        
        // Add search condition if provided
        if (!empty($search)) {
            $query .= " AND (title LIKE :search OR content LIKE :search OR author LIKE :search)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind search parameter if provided
        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
}
?>
