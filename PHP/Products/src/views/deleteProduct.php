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
            $sqlDelete = "DELETE FROM products WHERE id = ?";
            $reqDelete = $db->prepare($sqlDelete);
            $reqDelete->bindParam(1, $idProduct);
            try {
                $reqDelete->execute();
                header('Location: /&?response=successDelete');
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}