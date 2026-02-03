<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    session_start();
    require 'db.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $identifier = $_POST['identifier'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = :id OR email = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
        exit();
        } else {
        $error = "Identifiants incorrects.";
    }
    }
    ?>

    <?php include 'header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-4"><i class="fa-solid fa-lock text-primary"></i> Connexion</h3>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger p-2 small text-center"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nom d'utilisateur ou Email</label>
                        <input type="text" name="identifier" class="form-control" placeholder="Pseudo ou email..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-bold">Se connecter</button>
                    </div>
                </form>

                <p class="text-center mt-3 small">
                    Pas encore de compte ? <a href="register.php">Inscrivez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>