<?php
session_start();
require 'includes/db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    // Hachage sécurisé du mot de passe
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Génération d'une clé API unique de 63 caractères
    $prefix = "sn_"; 
    $apiKey = $prefix . bin2hex(random_bytes(30));

    $sql = "INSERT INTO users (email, password, api_key) VALUES (:email, :pass, :key)";
    $stmt = $pdo->prepare($sql);

    try {
       $stmt->execute([
        ':email' => $email,
        ':pass'  => $password,
        ':key'   => $apiKey
        ]);
        $message = "<div class='alert alert-success'>Inscription réussie ! <a href='login.php'>Connectez-vous ici</a></div>";
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger'>Erreur : Cet email est peut-être déjà utilisé.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - SaaS Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .btn-primary { background: #667eea; border: none; }
        .btn-primary:hover { background: #5a6fd1; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h2 class="text-center mb-4">Créer un compte SaaS</h2>
                
                <?php echo $message; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Adresse Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nom@exemple.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                </form>
                
                <div class="text-center mt-3">
                    <small>Déjà un compte ? <a href="login.php">Se connecter</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>