    <?php 
    session_start();
    require 'db.php';

     if (!isset($_SESSION['user_id'])) {
         header("Location: login.php");
         exit();
     }

    $user_id = $_SESSION['user_id'];
    $id = $_GET['id'] ?? null;

    $sql = "SELECT * FROM transactions WHERE id = :id and user_id = :uid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':uid' => $user_id]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transaction) {
    die("Action non autorisée ou transaction introuvable.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
                WHERE id = :id AND user_id = :uid";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':uid' => $user_id,
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

    <?php include 'header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Modifier la transaction</h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?= $transaction['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Titre *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-pen"></i></span>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($transaction['titre']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Montant (MAD) *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-money-bill"></i></span>
                            <input type="number" step="0.01" name="amount" class="form-control" value="<?= htmlspecialchars($transaction['montant']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-sticky-note"></i></span>
                            <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($transaction['description']) ?>" placeholder="Description de la transaction...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catégorie</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($transaction['categorie']) ?>" placeholder="Ex: Alimentation, Transport...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Date d'opération</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                            <input type="date" name="dateO" class="form-control" value="<?= htmlspecialchars($transaction['date_operation']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Type de transaction *</label>
                        <select name="trans" class="form-select" required>
                            <option value="revenu" <?= $transaction['type_transaction'] === 'revenu' ? 'selected' : '' ?>>Revenu (Entrée)</option>
                            <option value="depense" <?= $transaction['type_transaction'] === 'depense' ? 'selected' : '' ?>>Dépense (Sortie)</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Enregistrer les modifications</button>
                        <a href="index.php" class="btn btn-light border">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>