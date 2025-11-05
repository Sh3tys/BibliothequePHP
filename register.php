<?php
session_start();
require_once 'classes/Utilisateur.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($nom && $email && $password) {
        $utilisateurModel = new Utilisateur();
        if ($utilisateurModel->register($nom, $email, $password)) {
            $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
            header('Location: login.php');
            exit;
        } else {
            $error = 'Erreur lors de l\'inscription. L\'email existe peut-être déjà.';
        }
    } else {
        $error = 'Tous les champs sont obligatoires.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Bibliothèque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenue - Inscription</h1> 
       
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        
        <div class="form-section">
            <form method="POST">
                <div class="form-group">
                    <label>Nom complet:</label>
                    <input type="text" name="nom" placeholder="ex: Toto TATA" required>
                </div>
                
                <div class="form-group">
                    <label>Adresse email:</label>
                    <input type="email" name="email" placeholder="ex: toto.Tata@exemple.com" required>
                </div>
                
                <div class="form-group">
                    <label>Mot de passe:</label>
                    <input type="password" name="password" placeholder="ex: **********" required>
                </div>
                
                <button type="submit">S'inscrire</button>
            </form>
        </div>
        
        <p>Déjà inscrit ? <a href="login.php"><strong>Se connecter</strong></a></p>
    </div>
</body>
</html>