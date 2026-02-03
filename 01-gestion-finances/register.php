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

<style>
    .form-card {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        border: none;
        overflow: hidden;
        animation: slideInUp 0.8s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-card .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        color: white;
        border-bottom: none;
        padding: 1.5rem;
        text-align: center;
    }

    .form-card .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .illustration {
        animation: bounceIn 1s ease-out;
    }

    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }

    .alert {
        border-radius: 8px;
        border: none;
    }
</style>

<div class="row justify-content-center py-5 align-items-center">
    <div class="col-md-6">
        <div class="text-center mb-4">
            <div class="illustration">
                <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="80" fill="url(#gradient1)" opacity="0.1"/>
                    <circle cx="100" cy="100" r="60" fill="url(#gradient2)" opacity="0.2"/>
                    <path d="M80 120 L100 140 L140 100" stroke="var(--primary-color)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="100" cy="80" r="20" fill="none" stroke="var(--primary-color)" stroke-width="4"/>
                    <circle cx="100" cy="75" r="8" fill="var(--primary-color)"/>
                    <defs>
                        <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:var(--primary-color);stop-opacity:0.3" />
                            <stop offset="100%" style="stop-color:var(--success-color);stop-opacity:0.1" />
                        </linearGradient>
                        <linearGradient id="gradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:var(--success-color);stop-opacity:0.3" />
                            <stop offset="100%" style="stop-color:var(--primary-color);stop-opacity:0.1" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <h3 class="mt-3 text-primary">Rejoignez-nous</h3>
            <p class="text-muted">Créez votre compte pour commencer à gérer vos finances efficacement.</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fa-solid fa-user-plus me-2"></i> Créer un compte</h4>
            </div>
            <div class="card-body">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger small p-2 text-center"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom d'utilisateur</label>
                        <input type="text" name="username" class="form-control" placeholder="Votre pseudo..." required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus me-2"></i> S'inscrire
                        </button>
                    </div>
                </form>
                
                <p class="text-center mt-3 small">
                    Déjà inscrit ? <a href="login.php" class="text-primary fw-bold">Connectez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
