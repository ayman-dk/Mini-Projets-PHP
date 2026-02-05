<?php
session_start();
require 'includes/db.php';

$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - SaaS Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; height: 100vh; display: flex; align-items: center; }
        .card { border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4">
                <h3 class="text-center mb-4">Connexion Admin</h3>
                
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>