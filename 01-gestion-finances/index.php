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
    $sql = "SELECT * FROM transactions ORDER BY date_operation DESC";
    $stmt = $pdo->query($sql);
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

<div class="stats">
    <p>Revenus : <span style="color: green"><?= $totalRevenus ?> MAD</span></p>
    <p>Dépenses : <span style="color: red"><?= $totalDepenses ?> MAD</span></p>
    <hr>
    <h2>Solde : <?= $solde ?> MAD</h2>
</div>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Montant</th>
            <th>Description</th>
            <th>Catégorie</th>
            <th>Date d'opération</th>
            <th>Type de transaction</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($transactions as $trans): ?>
        <tr>
            <td><?php echo htmlspecialchars($trans['id']); ?></td>
            <td><?php echo htmlspecialchars($trans['titre']); ?></td>
            <td><?php echo htmlspecialchars($trans['montant']); ?></td>
            <td><?php echo htmlspecialchars($trans['description']); ?></td>
            <td><?php echo htmlspecialchars($trans['categorie']); ?></td>
            <td><?php echo htmlspecialchars($trans['date_operation']); ?></td>
            <td style="color: <?= $trans['type_transaction'] === 'revenu' ? 'green' : 'red' ?>;"><?php echo htmlspecialchars($trans['type_transaction']); ?></td>
            <td>
                <a href="editT.php?id=<?php echo $trans['id']; ?>">Modifier</a> | 
                <a href="deleteT.php?id=<?php echo $trans['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette transaction ?');">Supprimer</a>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="addT.php">Ajouter une transaction</a>
</body>
</html>