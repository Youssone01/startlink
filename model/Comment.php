<?php
require_once __DIR__ . '/../Config/Database.php';

class Comment {
    private $conn;
    private $table = 'comments';
    
    // Comment properties based on the database schema
    public $comment_id;
    public $post_id;
    public $author;
    public $content;
    public $created_at;
    public $status; // enum('approved','pending','spam','blocked')
    
    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }
    
    // Get all comments for a post
    public function getCommentsByPost() {
        try {
            $query = "SELECT * FROM {$this->table} WHERE post_id = :post_id ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':post_id', $this->post_id);
            $stmt->execute();
            
            // Log the number of comments found
            $count = $stmt->rowCount();
            error_log("Found {$count} comments for post ID: {$this->post_id}");
            
            return $stmt;
        } catch (PDOException $e) {
            // Log any errors
            error_log("Error in getCommentsByPost for post ID {$this->post_id}: " . $e->getMessage());
            return false;
        }
    }
    
    // Get all comments from all posts
    public function getAllComments($includeBlocked = false) {
        $query = "SELECT c.*, p.title as post_title FROM {$this->table} c 
                 LEFT JOIN posts p ON c.post_id = p.post_id";
                 
        if (!$includeBlocked) {
            $query .= " WHERE c.status != 'blocked'";
        }
        
        $query .= " ORDER BY c.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Get single comment
    public function getSingleComment() {
        $query = "SELECT * FROM {$this->table} WHERE comment_id = :comment_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comment_id', $this->comment_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->post_id = $row['post_id'];
            $this->author = $row['author'];
            $this->content = $row['content'];
            $this->created_at = $row['created_at'];
            $this->status = $row['status'];
            return true;
        }
        
        return false;
    }
    
    // Create comment
    public function createComment() {
        try {
            // Create SQL query with explicit column names (excluding comment_id which is auto-increment)
            $query = "INSERT INTO {$this->table} 
                      (post_id, author, content, status, created_at) 
                      VALUES (:post_id, :author, :content, :status, NOW())";
            
            $stmt = $this->conn->prepare($query);
            
            // Clean data
            $this->post_id = htmlspecialchars(strip_tags($this->post_id));
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->content = htmlspecialchars(strip_tags($this->content));
            $this->status = htmlspecialchars(strip_tags($this->status));
            
            // Bind data
            $stmt->bindParam(':post_id', $this->post_id);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':content', $this->content);
            $stmt->bindParam(':status', $this->status);
            
            // Execute query
            if($stmt->execute()) {
                // Get the ID of the newly created comment
                $this->comment_id = $this->conn->lastInsertId();
                return true;
            }
            
            return false;
        } catch(PDOException $e) {
            // Log the error for debugging
            error_log("Database Error in createComment: " . $e->getMessage());
            return false;
        }
    }
    
    // Update comment
    public function updateComment() {
        $query = "UPDATE {$this->table} 
                  SET 
                    author = :author, 
                    content = :content, 
                    status = :status 
                  WHERE 
                    comment_id = :comment_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->comment_id = htmlspecialchars(strip_tags($this->comment_id));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Bind data
        $stmt->bindParam(':comment_id', $this->comment_id);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':status', $this->status);
        
        if($stmt->execute()) {
            return true;
        }
        
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
    
    // Update comment status
    public function updateStatus() {
        $query = "UPDATE {$this->table} SET status = :status WHERE comment_id = :comment_id";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->comment_id = htmlspecialchars(strip_tags($this->comment_id));
        
        // Bind data
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':comment_id', $this->comment_id);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Block/Unblock comment
    public function toggleBlockStatus() {
        // First get current status
        $query = "SELECT status FROM {$this->table} WHERE comment_id = :comment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comment_id', $this->comment_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $currentStatus = $row['status'];
        
        // Determine new status
        $newStatus = ($currentStatus == 'blocked') ? 'approved' : 'blocked';
        
        // Update status
        $query = "UPDATE {$this->table} SET status = :status WHERE comment_id = :comment_id";
        $stmt = $this->conn->prepare($query);
        
        // Bind data
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':comment_id', $this->comment_id);
        
        // Execute query
        if($stmt->execute()) {
            return $newStatus;
        }
        
        return false;
    }
    
    // Get all blocked comments
    public function getBlockedComments() {
        $query = "SELECT c.*, p.title as post_title FROM {$this->table} c 
                 LEFT JOIN posts p ON c.post_id = p.post_id 
                 WHERE c.status = 'blocked' 
                 ORDER BY c.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Delete comment
    public function deleteComment() {
        $query = "DELETE FROM {$this->table} WHERE comment_id = :comment_id";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->comment_id = htmlspecialchars(strip_tags($this->comment_id));
        
        // Bind data
        $stmt->bindParam(':comment_id', $this->comment_id);
        
        if($stmt->execute()) {
            return true;
        }
        
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
    
    // Get comment statistics
    public function getCommentStatistics() {
        $stats = [];
        
        // Total number of comments
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Comments by status
        $query = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['by_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Most recent comment
        $query = "SELECT c.comment_id, c.author, c.content, c.created_at, p.title as post_title, p.post_id 
                 FROM {$this->table} c
                 JOIN posts p ON c.post_id = p.post_id
                 ORDER BY c.created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['most_recent'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Comments per post (top 5 posts)
        $query = "SELECT p.post_id, p.title, COUNT(c.comment_id) as comment_count 
                 FROM {$this->table} c
                 JOIN posts p ON c.post_id = p.post_id
                 GROUP BY p.post_id
                 ORDER BY comment_count DESC
                 LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['by_post'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

}
?>
