<?php
session_start();
include('include/header.php');
include('include/navbar.php');

require_once realpath(__DIR__ . '/../../Controllers/config.php');
$db = config::getConnexion();

try {
    $stmt = $db->prepare(
        "SELECT id_quiz, titre, description, duree
           FROM quiz
        ORDER BY titre ASC"
    );
    $stmt->execute();
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">'
       . 'Erreur SQL : ' . htmlspecialchars($e->getMessage())
       . '</div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        .card-header {
            background: linear-gradient(45deg, #1565c0, #1e88e5);
            color: white;
            padding: 1rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-body {
            padding: 1.5rem;
        }
        .form-control {
            border-radius: 20px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 5px rgba(30, 136, 229, 0.5);
        }
        .table {
            width: 100%;
            margin-top: 1rem;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 0.75rem;
            text-align: center;
            border: 1px solid #ddd;
        }
        .table th {
            background-color:#1e88e5;
            color: white;
            font-weight: bold;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table tbody tr:hover {
            background-color: #e3f2fd;
        }
        .btn {
            padding: 0.4rem 0.75rem;
            border: none;
            border-radius: 5px;
            background-color: #1e88e5;
            color: white;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn:hover {
            background-color: #1565c0;
        }
        .btn:focus {
            outline: none;
        }
        .search-bar {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .search-bar input {
            flex: 1;
        }
    </style>
</head>
<body>

<div class="container my-5">
  <div class="card">
    <div class="card-header">
      Liste des Quiz
    </div>
    <div class="card-body">
      <div class="search-bar">
        <input type="text" id="quiz-search" class="form-control" placeholder="🔍 Rechercher un quiz...">
      </div>

      <table id="quiz-table" class="table">
        <thead>
          <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Durée</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($quizzes as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['titre']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td><?= htmlspecialchars($row['duree']) ?> min ⏱</td>
              <td>
                <form action="detail.php" method="POST">
                  <input type="hidden" name="id_quiz" value="<?= $row['id_quiz'] ?>">
                  <button type="submit" class="btn">✔ Voir Détails</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
document.getElementById('quiz-search')
  .addEventListener('input', function() {
    const filtre = this.value.toLowerCase();
    document.querySelectorAll('#quiz-table tbody tr')
      .forEach(row => {
        row.style.display = row.textContent
          .toLowerCase().includes(filtre) ? '' : 'none';
      });
});
</script>

</body>
</html>
<?php include('include/footer.php') ?>
