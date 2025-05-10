<?php
require_once 'Controllers/config.php'; // ou ajuste le chemin selon l'emplacement réel

$db = config::getConnexion();

if ($db) {
    echo "✅ Connexion réussie à la base de données StartLink !";
} else {
    echo "❌ Échec de connexion.";
}
