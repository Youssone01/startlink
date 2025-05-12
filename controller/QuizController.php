<?php
require_once __DIR__ . '/../model/Quiz.php';

class QuizController
{
    private $db;

    public function __construct()
    {
        // Connexion à la base de données
        $host = 'localhost';
        $dbname = 'gestion'; // Assurez-vous que cette base de données existe
        $username = 'root';
        $password = '';

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
 // Fonction pour générer des questions automatiquement pour le quiz
 public function generateQuizQuestions($domain)
 {
     $questions = [];

     // Exemple pour le domaine Marketing
     if ($domain == 'Marketing') {
         $questions = [
             ['texte_question' => 'Qu\'est-ce que le marketing digital ?', 'reponse_correcte' => 'Search Engine Optimization', 'reponse_incorrecte1' => 'Social Engine Optimization', 'reponse_incorrecte2' => 'Search Evaluation Online'],
             ['texte_question' => 'Que signifie ROI ?', 'reponse_correcte' => 'Return on Investment', 'reponse_incorrecte1' => 'Rate of Investment', 'reponse_incorrecte2' => 'Return of Interest'],
             ['texte_question' => 'Qu’est-ce que le marketing de contenu ?', 'reponse_correcte' => 'Accroître la notoriété de la marque', 'reponse_incorrecte1' => 'Vendre des produits directement', 'reponse_incorrecte2' => 'Promouvoir le service client'],
             // Ajouter des questions supplémentaires pour atteindre 10
         ];
     }

     // Assurez-vous d'avoir 10 questions
     while (count($questions) < 10) {
         $questions[] = ['texte_question' => 'Exemple de question', 'reponse_correcte' => 'Réponse correcte', 'reponse_incorrecte1' => 'Réponse incorrecte 1', 'reponse_incorrecte2' => 'Réponse incorrecte 2'];
     }

     return $questions;
 }

 public function createQuiz(Quiz $quiz, array $questions): bool {
    $db = config::getConnexion();
    try {
        // Démarrer une transaction
        $db->beginTransaction();

        // Insérer le quiz
        $stmt = $db->prepare("INSERT INTO quiz (id_formation, titre, description, duree) VALUES (:id_formation, :titre, :description, :duree)");
        $stmt->execute([
            'id_formation' => $quiz->getIdFormation(),
            'titre' => $quiz->getTitre(),
            'description' => $quiz->getDescription(),
            'duree' => $quiz->getDuree()
        ]);

        // Obtenir l'ID du quiz inséré
        $idQuiz = $db->lastInsertId();

        // Insérer les questions
        $stmtQuestion = $db->prepare("INSERT INTO questions (id_quiz, text, correct, incorrect1, incorrect2) VALUES (:id_quiz, :text, :correct, :incorrect1, :incorrect2)");
        foreach ($questions as $question) {
            $stmtQuestion->execute([
                'id_quiz' => $idQuiz,
                'text' => $question['text'],
                'correct' => $question['correct'],
                'incorrect1' => $question['incorrect1'],
                'incorrect2' => $question['incorrect2']
            ]);
        }

        // Valider la transaction
        $db->commit();
        return true;
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $db->rollBack();
        throw $e;
    }
}


 // Fonction pour ajouter une question à la table `questions`
 public function addQuestion($quiz_id, $texte_question, $reponse_correcte, $reponse_incorrecte1, $reponse_incorrecte2)
 {
     try {
         $query = "INSERT INTO questions (id_quiz, texte_question, reponse_correcte, reponse_incorrecte1, reponse_incorrecte2)
                   VALUES (:id_quiz, :texte_question, :reponse_correcte, :reponse_incorrecte1, :reponse_incorrecte2)";
         $stmt = $this->db->prepare($query);
         $stmt->execute([
             ':id_quiz' => $quiz_id,
             ':texte_question' => $texte_question,
             ':reponse_correcte' => $reponse_correcte,
             ':reponse_incorrecte1' => $reponse_incorrecte1,
             ':reponse_incorrecte2' => $reponse_incorrecte2
         ]);
     } catch (PDOException $e) {
         throw new Exception("Erreur lors de l'ajout de la question : " . $e->getMessage());
     }
 }

 // Fonction pour récupérer tous les quiz
 public function getAllQuizzes()
 {
     try {
         $sql = "SELECT * FROM quiz";
         $stmt = $this->db->query($sql);
         return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourner tous les quiz
     } catch (PDOException $e) {
         return null; // Si une erreur survient, retourner null
     }
 }

 // Fonction pour mettre à jour un quiz
 public function updateQuiz(Quiz $quiz, $questions)
 {
     try {
         // Mettre à jour les informations du quiz
         $sql = "UPDATE quiz SET id_formation = :id_formation, titre = :titre, description = :description, duree = :duree 
                 WHERE id_quiz = :id_quiz";
         $stmt = $this->db->prepare($sql);
         $stmt->execute([
             ':id_quiz' => $quiz->getIdQuiz(),
             ':id_formation' => $quiz->getIdFormation(),
             ':titre' => $quiz->getTitre(),
             ':description' => $quiz->getDescription(),
             ':duree' => $quiz->getDuree()
         ]);

         // Supprimer les anciennes questions
         $sql_delete_questions = "DELETE FROM questions WHERE id_quiz = :id_quiz";
         $stmt_delete = $this->db->prepare($sql_delete_questions);
         $stmt_delete->execute([':id_quiz' => $quiz->getIdQuiz()]);

         // Ajouter les nouvelles questions
         foreach ($questions as $question) {
             $this->addQuestion($quiz->getIdQuiz(), $question['texte_question'], $question['reponse_correcte'], $question['reponse_incorrecte1'], $question['reponse_incorrecte2']);
         }

         return true;
     } catch (PDOException $e) {
         return "Erreur lors de la mise à jour du quiz : " . $e->getMessage();
     }
 }

 // Fonction pour supprimer un quiz
 public function deleteQuiz($id_quiz)
 {
     try {
         // Supprimer les questions associées à ce quiz
         $sql_delete_questions = "DELETE FROM question WHERE id_quiz = :id_quiz";
         $stmt_delete = $this->db->prepare($sql_delete_questions);
         $stmt_delete->execute([':id_quiz' => $id_quiz]);

         // Supprimer le quiz
         $sql = "DELETE FROM quiz WHERE id_quiz = :id_quiz";
         $stmt = $this->db->prepare($sql);
         return $stmt->execute([':id_quiz' => $id_quiz]);
     } catch (PDOException $e) {
         return "Erreur lors de la suppression du quiz : " . $e->getMessage();
     }
 }
 public function getQuizById($id) {
        $query = $this->db->prepare("SELECT * FROM quiz WHERE id_quiz = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer les questions associées à un quiz
    public function getQuestionsByQuizId($quizId) {
        $query = $this->db->prepare("SELECT texte_question, reponse_correcte, reponse_incorrecte1, reponse_incorrecte2 FROM questions WHERE id_quiz = ?");
        $query->execute([$quizId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
