    <?php 

    session_start();
     if (!isset($_SESSION['user_id'])) {
       header("Location: login.php");
    exit();
    }

    require 'db.php';
    $current_user_id = $_SESSION['user_id'];
    $search = $_GET['search'] ?? '';
    $use_date_filter = empty($search);
    $date_debut = $_GET['date_debut'] ?? (!empty($search) ? '2020-01-01' : date('Y-m-01'));
    $date_fin = $_GET['date_fin'] ?? (!empty($search) ? date('Y-m-d') : date('Y-m-t'));

    $sql = "SELECT * FROM transactions WHERE user_id = :uid";
    if ($use_date_filter) {
        $sql .= " AND date_operation BETWEEN :date_debut AND :date_fin";
    }
    $sql .= " AND (titre LIKE :search OR description LIKE :search OR categorie LIKE :search) ORDER BY date_operation DESC";
    $stmt = $pdo->prepare($sql);
    $params = [
        ':uid' => $current_user_id,
        ':search' => "%$search%"
    ];
    if ($use_date_filter) {
        $params[':date_debut'] = $date_debut;
        $params[':date_fin'] = $date_fin;
    }
    $stmt->execute($params);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php
    $totalRevenus = 0;
    $totalDepenses = 0;
    foreach ($transactions as $t) {
    if ($t['type_transaction'] === 'revenu') {
        $totalRevenus += $t['montant'];
    } else {
        $totalDepenses += $t['montant'];
        }}
        $solde = $totalRevenus - $totalDepenses;
        ?>
        <?php 
        $dataDepenses = [];
        $dataRevenus = [];

        foreach ($transactions as $t) {
             $cat = !empty($t['categorie']) ? ucfirst(strtolower(trim($t['categorie']))) : 'Autre';
    
            if ($t['type_transaction'] === 'depense') {
                if (!isset($dataDepenses[$cat])) $dataDepenses[$cat] = 0;
                $dataDepenses[$cat] += $t['montant'];
            } else {
                    if (!isset($dataRevenus[$cat])) $dataRevenus[$cat] = 0;
                    $dataRevenus[$cat] += $t['montant'];
                }
        }
        //pour le graphique des Dépenses
        $labelsDepenses = array_keys($dataDepenses);
        $valuesDepenses = array_values($dataDepenses);

        //pour le graphique des Revenus
        $labelsRevenus = array_keys($dataRevenus);
        $valuesRevenus = array_values($dataRevenus);
    ?>
    
    <?php include 'header.php'; ?>

<style>
    .welcome-section {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--success-color));
    }

    .stat-card.success::before {
        background: linear-gradient(90deg, var(--success-color), #059669);
    }

    .stat-card.danger::before {
        background: linear-gradient(90deg, var(--danger-color), #dc2626);
    }

    .stat-card.primary::before {
        background: linear-gradient(90deg, var(--primary-color), #1d4ed8);
    }

    .transaction-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(37, 99, 235, 0.05);
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    .search-form {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>

<div class="welcome-section fade-in-up">
    <h1 class="display-4 fw-bold text-primary mb-3">Bienvenue dans votre Gestionnaire de Finances</h1>
    <p class="lead text-secondary">Suivez vos revenus, dépenses et solde en temps réel.</p>
</div>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-start border-success border-4 shadow-sm stat-card success fade-in-up">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-success fw-bold text-uppercase small">Total Revenus</div>
                        <div class="h4 mb-0 fw-bold"><?= number_format($totalRevenus, 2, ',', ' ') ?> MAD</div>
                    </div>
                    <i class="fa-solid fa-wallet fa-2x text-gray-300 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-start border-danger border-4 shadow-sm stat-card danger fade-in-up">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-danger fw-bold text-uppercase small">Total Dépenses</div>
                        <div class="h4 mb-0 fw-bold"><?= number_format($totalDepenses, 2, ',', ' ') ?> MAD</div>
                    </div>
                    <i class="fa-solid fa-cart-shopping fa-2x text-gray-300 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-start border-primary border-4 shadow-sm bg-primary text-white stat-card primary fade-in-up">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold">Solde Actuel</div>
                        <div class="h4 mb-0 fw-bold"><?= number_format($solde, 2, ',', ' ') ?> MAD</div>
                    </div>
                    <i class="fa-solid fa-scale-balanced fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- La liste des transactions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="addT.php" class="btn btn-success" title="Ajouter"><i class="fa-solid fa-plus"></i> Ajouter</a>
</div>

<!-- pour la recherche et le filtrage par date -->
<div class="card shadow mb-1 border-0 search-form fade-in-up">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Rechercher</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Titre, description, categorie ..." 
                           value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-dark">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Du</label>
                <input type="date" name="date_debut" class="form-control" 
                       value="<?= htmlspecialchars($date_debut) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Au</label>
                <input type="date" name="date_fin" class="form-control" 
                       value="<?= htmlspecialchars($date_fin) ?>">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-dark w-100">Filtrer</button>
            </div>
            <div class="col-md-1">
                <a href="index.php" class="btn btn-light border w-100">Reset</a>
            </div>
        </form>
    </div>
</div>
<!-- La liste -->
<div class="card shadow-sm transaction-table fade-in-up">
  <div class="card-body">
        <table class="table table-hover">
        <thead class="table-light">
        <tr>
            <th>Titre</th>
            <th>Montant</th>
            <th>Description</th>
            <th>Catégorie</th>
            <th>Date d'opération</th>
            <th>Type de transaction</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
    <?php foreach ($transactions as $trans): ?>
    <tr class="align-middle">
        <td class="fw-bold"><?= htmlspecialchars($trans['titre']) ?></td>
        
        <td class="fw-bold <?= $trans['type_transaction'] === 'depense' ? 'text-danger' : 'text-success' ?>">
            <?= $trans['type_transaction'] === 'depense' ? '-' : '+' ?> 
            <?= number_format($trans['montant'], 2, ',', ' ') ?> DH
        </td>
        
        <td class="text-muted small"><?= !empty($trans['description']) ? htmlspecialchars($trans['description']) : '<i class="text-muted">Aucune description</i>' ?>
        </td>
        
        <td>
            <span class="badge rounded-pill bg-secondary text-light">
                <?= htmlspecialchars($trans['categorie']) ?>
            </span>
        </td>
        
        <td><?= date('d/m/Y', strtotime($trans['date_operation'])) ?></td>
        
        <td>
            <?php if ($trans['type_transaction'] === 'depense'): ?>
                <span class="text-danger"><i class="bi bi-arrow-down-circle-fill text-danger"></i> Sortie</span>
            <?php else: ?>
                <span class="text-success"><i class="bi bi-arrow-up-circle-fill text-success"></i> Entrée</span>
            <?php endif; ?>
        </td>
        
        <td>
    <div class="btn-group" role="group">
        <a href="editT.php?id=<?= $trans['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
            <i class="bi bi-pencil-square"></i>
        </a>
        
        <a href="deleteT.php?id=<?= $trans['id'] ?>" 
           class="btn btn-sm btn-outline-danger" 
           onclick="confirmDelete(<?= $trans['id'] ?>, '<?= htmlspecialchars($trans['description']) ?>'); return false;"
           title="Supprimer">
            <i class="bi bi-trash"></i>
        </a>
    </div>
</td>
    </tr>
    <?php endforeach; ?>
</tbody>
    </table>
</div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Êtes-vous sûr de vouloir supprimer cette transaction ?</p>
                <p class="text-muted small mb-0" id="transactionDescription"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Annuler
                </button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>Supprimer
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Pour les graphiques -->
<div class="row mb-4 mt-4">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header bg-danger text-white fw-bold">Répartition des Dépenses</div>
            <div class="card-body">
                <canvas id="chartDepenses" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white fw-bold">Origine des Revenus</div>
            <div class="card-body">
                <canvas id="chartRevenus" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction de confirmation de suppression personnalisée
function confirmDelete(id, description) {
    document.getElementById('transactionDescription').textContent = '"' + description + '"';
    document.getElementById('confirmDeleteBtn').href = 'deleteT.php?id=' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des Dépenses
new Chart(document.getElementById('chartDepenses'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($labelsDepenses) ?>,
        datasets: [{
            data: <?= json_encode($valuesDepenses) ?>,
            backgroundColor: ['#FF6384', '#FF9F40', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

// Graphique des Revenus
new Chart(document.getElementById('chartRevenus'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($labelsRevenus) ?>,
        datasets: [{
            data: <?= json_encode($valuesRevenus) ?>,
            backgroundColor: ['#2ECC71', '#27AE60', '#16A085', '#A2D9CE', '#D4EFDF']
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
</script>
<?php include 'footer.php'; ?>
