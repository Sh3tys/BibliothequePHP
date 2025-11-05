<?php
require_once 'db.php';

class Livre {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM livres ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM livres WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($titre, $auteur, $utilisateur_id) {
        $stmt = $this->pdo->prepare("INSERT INTO livres (titre, auteur, utilisateur_id) VALUES (?, ?, ?)");
        return $stmt->execute([$titre, $auteur, $utilisateur_id]);
    }

    public function search($recherche) {
        $stmt = $this->pdo->prepare("SELECT * FROM livres WHERE titre LIKE ? OR auteur LIKE ? ORDER BY id DESC");
        $search = "%$recherche%";
        $stmt->execute([$search, $search]);
        return $stmt->fetchAll();
    }
}
?>