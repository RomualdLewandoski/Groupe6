<?php
if (!isset($_GET['productId'])) {
    header('Location: /&?response=missingIdEdit');
} else {
    $idProduct = htmlspecialchars(trim($_GET['productId']));
    if (!is_numeric($idProduct)) {
        header('Location: /&?response=notNumId');
    } else {
        $db = getDb();
        $sqlSelect = "SELECT products.*, categories.name as catName FROM products INNER JOIN categories on products.category_id = categories.id  WHERE products.id = ?";
        $reqSelect = $db->prepare($sqlSelect);
        $reqSelect->bindParam(1, $idProduct);
        $reqSelect->execute();
        $nbresult = $reqSelect->rowCount();
        if ($nbresult == 0) {
            header('Location: /&?response=notExistEdit');
        } else {
            if (isset($_GET['action']) AND $_GET['action'] == "edit") {
                if (isset($_POST['nameProduct']) && isset($_POST['descriptionProduct']) && isset($_POST['priceProduct']) && isset($_POST['catProducts'])) {
                    $productName = htmlspecialchars(trim($_POST['nameProduct']));
                    $productDesc = htmlspecialchars(trim($_POST['descriptionProduct']));
                    $productPrice = htmlspecialchars(trim($_POST['priceProduct']));
                    $productCat = htmlspecialchars(trim($_POST['catProducts']));
                    if (empty($productName) || empty($productDesc) || $productPrice == '' || empty($productCat)) {
                        echo "Des champs sont manquants dans le formulaire d'édition de produit";
                    } else {
                        $sqlSelectCatByName = "SELECT * FROM categories WHERE name = ?";
                        $reqSelectCatByName = $db->prepare($sqlSelectCatByName);
                        $reqSelectCatByName->bindParam(1, $productCat);
                        $reqSelectCatByName->execute();
                        $nbResultCat = $reqSelectCatByName->rowCount();
                        $lastIdCat = 0;
                        if ($nbResultCat == 0) {
                            $sqlInsertCat = "INSERT INTO categories (name, created) VALUES(?, now())";
                            $reqInsert = $db->prepare($sqlInsertCat);
                            $reqInsert->bindParam(1, $productCat);
                            $reqInsert->execute();
                            $lastIdCat = $db->lastInsertId();
                        } else {
                            $dataCat = $reqSelectCatByName->fetchObject();
                            $lastIdCat = $dataCat->id;
                        }
                        $sqlUpdate = "UPDATE products SET name = ? , description = ?, price = ? , category_id = ? WHERE id = ?";
                        $reqUpdate = $db->prepare($sqlUpdate);
                        $reqUpdate->bindParam(1, $productName);
                        $reqUpdate->bindParam(2, $productDesc);
                        $reqUpdate->bindParam(3, $productPrice);
                        $reqUpdate->bindParam(4, $lastIdCat);
                        $reqUpdate->bindParam(5, $idProduct);
                        try {
                            $reqUpdate->execute();
                            header('Location: /&?response=successEdit');
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    }
                } else {
                    echo "Des champs sont manquants dans le formulaire d'édition de produit";
                }
            }
        }
        $data = $reqSelect->fetchObject();
        $sqlSelectCat = "SELECT * FROM categories ORDER BY id ASC";
        $reqSelectCat = $db->prepare($sqlSelectCat);
        $reqSelectCat->execute();
        $dataCat = $reqSelectCat->fetchAll(5);
    }
}
?>
<h2 class="mb-4">Editer un produit</h2>
<div class="text-center">
    <a href="/">
        <button class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i> Retour à la liste des produits</button>
    </a>
    <a href="/&?page=cat_manage">
        <button class="btn btn-warning"><i class="fas fa-cogs"></i> Gérer les catégories</button>
    </a>
</div>
<div class="card mt-5">
    <form method="post" action="/&?page=edit&productId=<?= $data->id ?>&action=edit" autocomplete="off">
        <div class="card-body">
            <div class="form-group row">
                <label for="nameProduct" class="col-sm-2 col-form-label">Nom du Produit</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nameProduct" name="nameProduct" placeholder="produit"
                           value="<?= $data->name ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="descriptionProduct" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                    <textarea id="descriptionProduct" name="descriptionProduct"
                              class="note-codable w-100"><?= $data->description ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="priceProduct" class="col-sm-2 col-form-label">Prix du Produit</label>
                <div class="col-sm-10">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">€</div>
                        </div>
                        <input type="number" class="form-control" id="priceProduct" name="priceProduct"
                               placeholder="prix" value="<?= $data->price ?>">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="catProducts" class="col-sm-2 col-form-label">
                    Catégorie
                </label>
                <div class="col-sm-10">
                    <input type="text" name="catProducts" id="catProducts" class="form-control"
                           value="<?= $data->catName ?>">
                </div>
            </div>


        </div>
        <div class="card-footer text-center">
            <button class="btn btn-warning" type="submit"><i class="fas fa-cogs"></i> Editer le produit</button>
            <button class="btn btn-danger" type="reset"><i class="fas fa-trash"></i> Reset</button>
        </div>
    </form>
</div>
