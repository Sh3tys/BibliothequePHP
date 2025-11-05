<?php
session_start();
require_once 'classes/Utilisateur.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        $utilisateurModel = new Utilisateur();
        $user = $utilisateurModel->login($email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect.';
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
    <title>Connexion - Bibliothèque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>De retour - Connecte-toi !</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="form-section">
            <form method="POST">
                <div class="form-group">
                    <label>Adresse email:</label>
                    <input type="email" name="email" placeholder="ex: toto.Tata@exemple.com" required>
                </div>
                
                <div class="form-group">
                    <label>Mot de passe:</label>
                    <input type="password" name="password" placeholder="ex: *********" required>
                </div>
                
                <button type="submit">Se connecter</button>
            </form>
        </div>
        
        <p>Pas encore inscrit ? <a href="register.php"><strong>S'inscrire</strong></a></p>
    </div>
</body>
</html>
