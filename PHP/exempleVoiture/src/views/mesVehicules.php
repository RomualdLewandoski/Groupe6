<?php
require_once '../config/config.php';
require_once 'elements/head.php';
require_once 'elements/footer.php';
require_once '../models/connect.php';

head();
$db = connection();
if (isset($_POST['model']) AND isset($_POST['marque'])) {
    $model = htmlspecialchars(trim($_POST['model']));
    $marque = htmlspecialchars(trim($_POST['marque']));
    $selectMarque = "SELECT nomMarque FROM marque WHERE nomMarque = ?";
    $selectModel = "SELECT nomModele FROM modele WHERE nomModele = ?";
    $selectVoiture = "SELECT * FROM vehicule WHERE modele.nomModele = ? INNER JOIN modele on vehicule.modele_idModele = modele.idModel ";
    $insertMarque = "INSERT INTO marque (nomMarque) VALUES (?)";
    $insertModel = "INSERT INTO modele (nomModele) VALUES (?)";
    $insertVoiture = "INSERT INTO vehicule (modele_idModele, marque_idMarque) 
                        VALUES ((SELECT modele.idModele FROM modele WHERE modele.nomModele = ?), (SELECT marque.idMarque FROM marque WHERE marque.nomMarque = ?))";

    /**
     * SELECT BRAND TO PREVENT DOUBLE ENTRY ON DATABASE
     */
    $req = $db->prepare($selectMarque);
    $req->bindParam(1, $marque);
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $nbMarque = $req->rowCount();

    /**
     * IF NOTHING WAS FOUND WE ADD THE BRAND
     */
    if ($nbMarque == 0) {
        $req = $db->prepare($insertMarque);
        $req->bindParam(1, $marque);
        try {
            $req->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * SELECT MODELE TO PREVENT DOUBLE ENTRY ON DATABASE
     */
    $req = $db->prepare($selectModel);
    $req->bindParam(1, $model);
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $nbModel = $req->rowCount();

    /**
     * IF NOTHING WAS FOUND WE ADD THE MODELE
     */
    if ($nbModel == 0) {
        $req = $db->prepare($insertModel);
        $req->bindParam(1, $model);
        try {
            $req->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * SELECT CAR TO PREVENT DOUBLE ENTRY ON DATABASE
     */
    $req = $db->prepare($selectVoiture);
    $req->bindParam(1, $model);
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $nbVoiture = $req->rowCount();

    /**
     * IF NOTHING WAS FOUND WE ADD TO VEHICULE
     */
    if ($nbVoiture == 0) {
        $req = $db->prepare($insertVoiture);
        $req->bindParam(1, $model);
        $req->bindParam(2, $marque);
        try {
            $req->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
$selectVehicles = "SELECT modele.nomModele as modele, marque.nomMarque as marque FROM vehicule INNER JOIN modele ON vehicule.modele_idModele = modele.idModele INNER JOIN marque ON vehicule.marque_idMarque = marque.idMarque";
$select = $db->prepare($selectVehicles);
try {
    $select->execute();
    $data = $select->fetchAll(5);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

    <h1>Liste de mes véhicules</h1>
    <hr>
    <table class="table table-hover mt-5 mb-5">
        <thead class="thead-dark">
        <tr>
            <th>Marque</th>
            <th>Modèle</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <?php
                foreach ($data as $voiture){
            ?>
            <td><?= $voiture->marque ?></td>
            <td><?= $voiture->modele ?></td>
        </tr>
        <?php
        }
        ?>

        </tbody>
    </table>
    <div>
        <a href="../../index.php">
            <button type="button" class="btn btn-outline-dark">
                Accueil
            </button>
        </a>
    </div>
<?php footer();