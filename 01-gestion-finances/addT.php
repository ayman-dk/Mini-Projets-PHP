 <?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db.php';

    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $dateO = $_POST['dateO'];
    $trans = $_POST['trans']; 
    $user_id = $_SESSION['user_id'];

    try {
        $sql = "INSERT INTO transactions (titre, montant, description, categorie, date_operation, type_transaction, user_id) 
                VALUES (:t, :m, :d, :c, :do, :tt, :uid)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':t'    => $title,
            ':m'    => $amount,
            ':d'    => $description,
            ':c'    => $category,
            ':do'   => $dateO,
            ':tt'   => $trans,
            ':uid'  => $user_id
        ]);

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
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
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
        border-bottom: none;
        padding: 1.5rem;
    }

    .form-card .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .input-group-text {
        background: var(--light-bg);
        border-color: #e2e8f0;
        color: var(--primary-color);
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        transform: translateY(-1px);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-light {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-light:hover {
        background: #f8f9fa;
        transform: translateY(-1px);
    }

    .input-group {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
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
</style>

<div class="row justify-content-center py-5 align-items-center">
    <div class="col-md-6">
        <div class="text-center mb-4">
            <div class="illustration">
                <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="80" fill="url(#gradient1)" opacity="0.1"/>
                    <circle cx="100" cy="100" r="60" fill="url(#gradient2)" opacity="0.2"/>
                    <path d="M70 100 L90 120 L130 80" stroke="var(--success-color)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="100" cy="100" r="8" fill="var(--success-color)"/>
                    <defs>
                        <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:var(--success-color);stop-opacity:0.3" />
                            <stop offset="100%" style="stop-color:var(--primary-color);stop-opacity:0.1" />
                        </linearGradient>
                        <linearGradient id="gradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:var(--primary-color);stop-opacity:0.3" />
                            <stop offset="100%" style="stop-color:var(--success-color);stop-opacity:0.1" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <h3 class="mt-3 text-primary">Ajoutez une nouvelle transaction</h3>
            <p class="text-muted">Remplissez les détails ci-dessous pour enregistrer votre transaction financière.</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fa-solid fa-plus-circle me-2"></i> Ajouter une transaction</h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Titre *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-pen"></i></span>
                            <input type="text" name="title" class="form-control" placeholder="Ex: Salaire, Loyer..." required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Montant (MAD) *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-money-bill"></i></span>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-sticky-note"></i></span>
                            <input type="text" name="description" class="form-control" placeholder="Description de la transaction...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catégorie</label>
                        <div class="input-group">
                          <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                          <input type="text" name="category" class="form-control" list="categorySuggestions" placeholder="Ex: Alimentation...">
                            <datalist id="categorySuggestions">
                                <option value="Alimentation">
                                <option value="Loyer">
                                <option value="Transport">
                                <option value="Loisirs">
                                <option value="Santé">
                                <option value="Salaire">
                                <option value="Sport">
                            </datalist>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Date d'opération *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                            <input type="date" name="dateO" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Type de transaction *</label>
                        <select name="trans" class="form-select" required>
                            <option value="revenu">Revenu (Entrée)</option>
                            <option value="depense">Dépense (Sortie)</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-check me-2"></i> Enregistrer la transaction
                        </button>
                        <a href="index.php" class="btn btn-light">
                            <i class="fa-solid fa-times me-2"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>