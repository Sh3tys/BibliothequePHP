<?php
session_start();
require_once 'classes/Livre.php';

$livreModel = new Livre();
$recherche = $_GET['recherche'] ?? '';

if ($recherche) {
    $livres = $livreModel->search($recherche);
} else {
    $livres = $livreModel->getAll();
}

$lastThreeBooks = array_slice($livres, -3);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Bibliothèque</h1>

        <form method="GET" class="search-form">
            <input type="text" name="recherche" placeholder="Rechercher un livre (Auteur / Titre)" value="<?= $recherche ?>" required>
            <button type="submit">Rechercher</button>
            <?php if ($recherche): ?>
                <a href="index.php">Reset</a>
            <?php endif; ?>
        </form>

        <div class="nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Ma Bibliothèque</a>
                <a href="logout.php">Déconnexion</a>
            <?php endif; ?>
        </div>        

        <h2>Nos trois dernier Livres</h2>
        <table>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
            </tr>
            <?php foreach ($lastThreeBooks as $livre): ?>
            <tr>
                <td><?= $livre['titre'] ?></td>
                <td><?= $livre['auteur'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <span>Plus de <strong><?= count($livres) ?></strong>  livres disponibles.</span>
        <div class="nav">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="register.php">S'inscrire</a>
                <a href="login.php">Se connecter</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
