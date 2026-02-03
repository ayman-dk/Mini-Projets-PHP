    <?php 

    session_start();
     if (!isset($_SESSION['user_id'])) {
       header("Location: login.php");
    exit();
    }

    require 'db.php';
    $current_user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM transactions WHERE user_id = :uid ORDER BY date_operation DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uid' => $current_user_id]);
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
    <?php include 'header.php'; ?>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-start border-success border-4 shadow-sm">
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
        <div class="card border-start border-danger border-4 shadow-sm">
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
        <div class="card border-start border-primary border-4 shadow-sm bg-primary text-white">
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


<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Liste des Transactions</h2>
<a href="addT.php" class="btn btn-success" title="Ajouter"><i class="fa-solid fa-plus"></i> Ajouter</a>
</div>
<div class="card shadow-sm">
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
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette transaction ?');"
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
<?php include 'footer.php'; ?>
