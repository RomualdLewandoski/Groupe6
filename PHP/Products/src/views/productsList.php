<?php
$db = getDb();

$sqlSelect = "SELECT products.*, categories.name as catName FROM products INNER JOIN categories on products.category_id = categories.id";
$reqSelect = $db->prepare($sqlSelect);
$reqSelect->execute();
$data = $reqSelect->fetchAll(5);
?>
<h2 class="mb-4">Products</h2>
<div class="text-center">
    <a href="/&?page=add">
        <button class="btn btn-outline-dark"><i class="fas fa-cart-plus"></i> Ajouter un produit</button>
    </a>
    <a href="/&?page=cat_manage">
        <button class="btn btn-warning"><i class="fas fa-cogs"></i> Gérer les catégories</button>
    </a>
</div>
<table class="table table-hover mt-5">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Nom</th>
        <th scope="col">Price</th>
        <th scope="col">Category</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($data as $product) {
        ?>
        <tr>
            <th scope="row"><?= $product->id ?></th>
            <td><?= $product->name ?></td>
            <td><?= $product->price ?>€</td>
            <td><?= $product->catName ?></td>
            <td>
                <a href="/&?page=read&productId=<?= $product->id?>">
                    <button class="btn btn-primary" type="button"><i class="fa fa-bars" aria-hidden="true"></i> Lire
                    </button>
                </a>
                <a href="/&?page=edit&productId=<?= $product->id?>">
                    <button class="btn btn-warning" type="submit"><i class="fa fa-spinner" aria-hidden="true"></i>
                        Modifier
                    </button>
                </a>
                <a href="/&?page=delete&productId=<?= $product->id?>">
                    <button class="btn btn-danger" type="submit"><i class="fa fa-minus-square" aria-hidden="true"></i>
                        Supprimer
                    </button>
                </a>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
