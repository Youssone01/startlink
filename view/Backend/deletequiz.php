<?php
require_once __DIR__ . '/../../Controllers/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_quiz'])) {
    try {
        $pdo = Config::getConnexion();
        $id_quiz = $_POST['id_quiz'];

        $query = "DELETE FROM quiz WHERE id_quiz = :id_quiz";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_quiz', $id_quiz, PDO::PARAM_INT);

        $stmt->execute();
    } catch (PDOException $e) {
        // Vous pouvez enregistrer l'erreur dans un fichier de log si nécessaire
        error_log('Erreur lors de la suppression du quiz : ' . $e->getMessage());
    }
}

// Rediriger vers une autre page ou revenir à la liste des quiz
header('Location: listquiz.php'); // Remplacez par la bonne URL
exit;
?>
