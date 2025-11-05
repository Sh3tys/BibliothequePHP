<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'bibliotheque';
    private $user = 'shetys';
    private $pass = 'shetys123';
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
                $this->user,
                $this->pass
            );
        } catch (PDOException $e) {
            die('Connexion échouée: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>