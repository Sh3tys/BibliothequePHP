<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'classes/Livre.php';
require_once 'classes/Favoris.php';
require_once 'classes/Utilisateur.php';

$livreModel = new Livre();
$favorisModel = new Favoris();
$utilisateurModel = new Utilisateur();

$error = '';
$success = '';
$recherche = $_GET['recherche'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add_livre') {
        $titre = $_POST['titre'] ?? '';
        $auteur = $_POST['auteur'] ?? '';
        
        if ($titre && $auteur) {
            if ($livreModel->create($titre, $auteur, $_SESSION['user_id'])) {
                $success = 'Livre ajouté avec succès !';
            }
        } else {
            $error = "Le titre et l'auteur sont obligatoires.";
        }
    }
    
    if ($action == 'add_favori') {
        $livre_id = $_POST['livre_id'] ?? 0;
        if ($livre_id) {
            $favorisModel->add($_SESSION['user_id'], $livre_id);
            $success = 'Livre ajouté aux favoris !';
        }
    }
    
    if ($action == 'remove_favori') {
        $livre_id = $_POST['livre_id'] ?? 0;
        if ($livre_id) {
            $favorisModel->remove($_SESSION['user_id'], $livre_id);
            $success = 'Livre retiré des favoris !';
        }
    }
    
    if ($action == 'edit_compte') {
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        
        if ($nom && $email) {
            if ($utilisateurModel->update($_SESSION['user_id'], $nom, $email)) {
                $_SESSION['user_nom'] = $nom;
                $success = 'Compte modifié avec succès !';
            }
        } else {
            $error = "Le nom et l'email sont obligatoires.";
        }
    }
}

if ($recherche) {
    $livres = $livreModel->search($recherche);
} else {
    $livres = $livreModel->getAll();
}

$mesFavoris = $favorisModel->getByUser($_SESSION['user_id']);
$user = $utilisateurModel->getById($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>
    <div x-data="{ editProfile: false, addLivre: false}">
        <div class="container">
            <h1>Bienvenue <?= $_SESSION['user_nom'] ?></h1>
            
            <div class="nav">
                <a href="#" @click.prevent="editProfile = !editProfile">⚙ Modification</a>
                <a href="index.php">Accueil</a>
                <a href="#" @click.prevent="addLivre = !addLivre">+ Suggérer un Livre</a>
            </div>

            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <div x-show="editProfile" class="contenaire">
                <div class="form-section">
                    <h2>Éditer mon compte</h2>
                    <form method="POST">
                        <input type="hidden" name="action" value="edit_compte">
                        <div class="form-group">
                            <label>Nom:</label>
                            <input type="text" name="nom" value="<?= $user['nom'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" value="<?= $user['email'] ?>" required>
                        </div>
                        <button type="submit">Modifier</button>
                    </form>
                </div>
            </div>

            <div x-show="addLivre" class="contenaire">
                <div class="form-section">
                    <h2>Ajouter un livre</h2>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_livre">
                        <div class="form-group">
                            <label>Titre:</label>
                            <input type="text" name="titre" required>
                        </div>
                        <div class="form-group">
                            <label>Auteur:</label>
                            <input type="text" name="auteur" required>
                        </div>
                        <button type="submit">Ajouter le livre</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container">
            <h2>Livres disponibles</h2>
            <table>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?= $livre['titre'] ?></td>
                    <td><?= $livre['auteur'] ?></td>
                    <td>
                        <?php if (!$favorisModel->isFavorite($_SESSION['user_id'], $livre['id'])): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="add_favori">
                                <input type="hidden" name="livre_id" value="<?= $livre['id'] ?>">
                                <button type="submit">Ajouter aux favoris</button>
                            </form>
                        <?php else: ?>
                            Déjà dans les favoris
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <br>
            <br>

            <form method="GET" class="search-form">
                <input type="text" name="recherche" placeholder="Rechercher un livre..." value="<?= $recherche ?>">
                <button type="submit">Rechercher</button>
                <?php if ($recherche): ?>
                    <a href="dashboard.php">Réinitialiser</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div x-show="<?= count($mesFavoris) ?> > 0" class="container">
            <h2>Mes Favoris</h2>
            <table>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($mesFavoris as $favori): ?>
                <tr>
                    <td><?= $favori['titre'] ?></td>
                    <td><?= $favori['auteur'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="remove_favori">
                            <input type="hidden" name="livre_id" value="<?= $favori['id'] ?>">
                            <button type="submit">Retirer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>  

        <div class="contenaire">
            
        </div>
                
        <div class="container">            
            <div class="nav">
                <br><br>
                <a href="logout.php">Déconnexion</a>
            </div>
            <p>Devenir VIP - <a href="https://sh3tys.github.io/Portfolio" target="_blank"><strong>ICI</strong></a></p>
        </div>
    </div>
</body>
</html>
