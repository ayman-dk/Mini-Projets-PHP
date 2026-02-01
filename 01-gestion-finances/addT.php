<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <label for="titre">Titre:</label>
        <input type="text" id="titre" name="title" required>
        <br>
        <label for="montant">Montant:</label>
        <input type="number" id="montant" step="0.01" name="amount" required>
        <br>
        <label for="descr">Description:</label>
        <textarea  id="descr" name="description"></textarea>
        <br>
        <label for="cat">Catégorie:</label>
        <select id="cat" name="category">
            <option value="salaire">Salaire</option>
            <option value="nourriture">Nourriture</option>
            <option value="loyer">Loyer</option>
            <option value="divertissement">Divertissement</option>
            <option value="autre">Autre</option>
        </select>
        <br>
        <label for="dateO">Date d'opération:</label>
        <input type="date" id="dateO" name="dateO">
        <br>
        <label for="trans">Type de transaction:</label>
        <select id="trans" name="trans" required>
            <option value="revenu">Revenus</option>
            <option value="depense">Dépenses</option>
        </select>
        <br>
        <input type="submit" value="Ajouter">
    </form>

    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db.php';

    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $dateO = $_POST['dateO'];
    $trans = $_POST['trans']; 

    try {
        $sql = "INSERT INTO transactions (titre, montant, description, categorie, date_operation, type_transaction) 
                VALUES (:t, :m, :d, :c, :do, :tt)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':t'  => $title,
            ':m'  => $amount,
            ':d'  => $description,
            ':c'  => $category,
            ':do' => $dateO,
            ':tt' => $trans
        ]);

        header("Location: index.php");
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>
</body>
</html>