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
    $sql = "SELECT * FROM transactions WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_GET['id']]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <form action="" method="POST">
        <input type="hidden" name="id" value="<?= $transaction['id'] ?>">
        <label for="titre">Titre:</label>
        <input type="text" id="titre" name="title" value="<?= htmlspecialchars($transaction['titre']) ?>" required>
        <br>
        <label for="montant">Montant:</label>
        <input type="number" id="montant" step="0.01" name="amount" value="<?= htmlspecialchars($transaction['montant']) ?>" required>
        <br>
        <label for="descr">Description:</label>
        <textarea  id="descr" name="description"><?= htmlspecialchars($transaction['description']) ?></textarea>
        <br>
        <label for="cat">Catégorie:</label>
        <select id="cat" name="category">
            <option value="salaire" <?= $transaction['categorie'] === 'salaire' ? 'selected' : '' ?>>Salaire</option>
            <option value="nourriture" <?= $transaction['categorie'] === 'nourriture' ? 'selected' : '' ?>>Nourriture</option>
            <option value="loyer" <?= $transaction['categorie'] === 'loyer' ? 'selected' : '' ?>>Loyer</option>
            <option value="divertissement" <?= $transaction['categorie'] === 'divertissement' ? 'selected' : '' ?>>Divertissement</option>
            <option value="autre" <?= $transaction['categorie'] === 'autre' ? 'selected' : '' ?>>Autre</option>
        </select>
        <br>
        <label for="dateO">Date d'opération:</label>
        <input type="date" id="dateO" name="dateO" value="<?= htmlspecialchars($transaction['date_operation']) ?>">
        <br>
        <label for="trans">Type de transaction:</label>
        <select id="trans" name="trans" required>
            <option value="revenu" <?= $transaction['type_transaction'] === 'revenu' ? 'selected' : '' ?>>Revenus</option>
            <option value="depense" <?= $transaction['type_transaction'] === 'depense' ? 'selected' : '' ?>>Dépenses</option>
        </select>
        <br>
        <input type="submit" value="Modifier">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db.php';

    $id = $_POST['id'];
    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $dateO = $_POST['dateO'];
    $trans = $_POST['trans']; 

    try {
        $sql = "UPDATE transactions 
                SET titre = :t, montant = :m, description = :d, categorie = :c, date_operation = :do, type_transaction = :tt 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':t'  => $title,
            ':m'  => $amount,
            ':d'  => $description,
            ':c'  => $category,
            ':do' => $dateO,
            ':tt' => $trans
        ]);

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
    }
    }
    ?>
</body>
</html>