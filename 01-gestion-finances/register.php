<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php
   require 'db.php';
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $passw = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Adresse email invalide.";
    } else {
        try {
            $sql = "INSERT INTO users (username, email, password) VALUES (:u, :em, :pw)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['u' => $user, 'em' => $email, 'pw' => $passw]);

            session_start();
            $_SESSION['user_id'] = $pdo->lastInsertId(); 
            $_SESSION['username'] = $user;
            header('Location: index.php');
            exit();
        }catch(PDOException $e){
            $error = "Nom d'utilisateur ou email déjà utilisé.";
        }
    }
    }
    ?>

    <?php include 'header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Créer un compte</h3>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger small p-2"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nom d'utilisateur</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold">S'inscrire</button>
                    </div>
                </form>
                
                <p class="text-center mt-3 small">
                    Déjà inscrit ? <a href="login.php">Connectez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
