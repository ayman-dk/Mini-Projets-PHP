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

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fa-solid fa-plus-circle"></i> Ajouter une transaction</h4>
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

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-check"></i> Enregistrer la transaction</button>
                        <a href="index.php" class="btn btn-light border">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>