<?php
require_once 'db.php';

class Favoris {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function add($utilisateur_id, $livre_id) {
        $stmt = $this->pdo->prepare("INSERT INTO favoris (utilisateur_id, livre_id) VALUES (?, ?)");
        return $stmt->execute([$utilisateur_id, $livre_id]);
    }

    public function remove($utilisateur_id, $livre_id) {
        $stmt = $this->pdo->prepare("DELETE FROM favoris WHERE utilisateur_id = ? AND livre_id = ?");
        return $stmt->execute([$utilisateur_id, $livre_id]);
    }

    public function getByUser($utilisateur_id) {
        $stmt = $this->pdo->prepare("
            SELECT l.* FROM livres l
            INNER JOIN favoris f ON l.id = f.livre_id
            WHERE f.utilisateur_id = ?
            ORDER BY f.id DESC
        ");
        $stmt->execute([$utilisateur_id]);
        return $stmt->fetchAll();
    }

    public function isFavorite($utilisateur_id, $livre_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM favoris WHERE utilisateur_id = ? AND livre_id = ?");
        $stmt->execute([$utilisateur_id, $livre_id]);
        return $stmt->fetch() ? true : false;
    }
}
?>