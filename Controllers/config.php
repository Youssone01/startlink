<?php
declare(strict_types=1);

// config.php
// Classe de gestion de la connexion PDO à la base de données StartLink

class config
{
    /** @var \PDO|null */
    private static ?PDO $pdo = null;

    /**
     * Retourne une instance unique de PDO connectée à la base StartLink
     *
     * @return \PDO
     */
    public static function getConnexion(): PDO
    {
        if (self::$pdo === null) {
            $host     = 'localhost';
            $dbname   = 'StartLink';
            $username = 'root';
            $password = '';
            $charset  = 'utf8mb4';

            // Data Source Name
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

            // Options PDO
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                // En cas d'échec de connexion, on arrête et on affiche l'erreur
                die('Erreur de connexion PDO : ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
