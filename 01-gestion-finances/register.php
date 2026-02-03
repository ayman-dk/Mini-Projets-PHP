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
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username']);
    $email = trim($_POST['email']);
    $passw = $_POST['password'];
    $confirmPassw = $_POST['confirm_password'];

    // Validation du mot de passe
    if (strlen($passw) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif (!preg_match('/[A-Z]/', $passw)) {
        $error = "Le mot de passe doit contenir au moins une lettre majuscule.";
    } elseif (!preg_match('/[a-z]/', $passw)) {
        $error = "Le mot de passe doit contenir au moins une lettre minuscule.";
    } elseif (!preg_match('/[0-9]/', $passw)) {
        $error = "Le mot de passe doit contenir au moins un chiffre.";
    } elseif ($passw !== $confirmPassw) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } else {
        try {
            $hashedPass = password_hash($passw, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (username, email, password) VALUES (:u, :em, :pw)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['u' => $user, 'em' => $email, 'pw' => $hashedPass]);

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

    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    .password-strength .requirement {
        display: flex;
        align-items: center;
        margin-bottom: 0.25rem;
    }

    .password-strength .requirement i {
        margin-right: 0.5rem;
        width: 1rem;
    }

    .password-strength .valid {
        color: var(--success-color);
    }

    .password-strength .invalid {
        color: var(--danger-color);
    }

    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
        animation: slideDown 0.5s ease-out;
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--danger-color);
    }

    .alert-danger::before {
        background: var(--danger-color);
    }

    .alert-success::before {
        background: var(--success-color);
    }

    .alert-warning::before {
        background: var(--warning-color);
    }

    .alert-info::before {
        background: var(--primary-color);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
                        <input type="password" name="password" id="password" class="form-control" placeholder="Générez un mot de passe fort" required>
                        <small class="form-text text-muted">
                            Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule et un chiffre.
                        </small>
                        <div class="password-strength" id="password-strength">
                            <div class="requirement" id="length">
                                <i class="fa-solid fa-times invalid"></i>
                                Au moins 8 caractères
                            </div>
                            <div class="requirement" id="uppercase">
                                <i class="fa-solid fa-times invalid"></i>
                                Une lettre majuscule
                            </div>
                            <div class="requirement" id="lowercase">
                                <i class="fa-solid fa-times invalid"></i>
                                Une lettre minuscule
                            </div>
                            <div class="requirement" id="number">
                                <i class="fa-solid fa-times invalid"></i>
                                Un chiffre
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirmer le mot de passe</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirmez votre mot de passe" required>
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

<script>
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password)
    };

    Object.keys(requirements).forEach(req => {
        const element = document.getElementById(req);
        const icon = element.querySelector('i');
        if (requirements[req]) {
            icon.className = 'fa-solid fa-check valid';
        } else {
            icon.className = 'fa-solid fa-times invalid';
        }
    });
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>
