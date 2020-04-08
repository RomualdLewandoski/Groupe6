<?php
require_once "src/views/elements/head.php";
require_once "src/views/elements/footer.php";
require_once "src/config/config.php";
require_once "src/model/database.php";
if (!isset($_GET['page'])) {
    $page = "list";
} else {
    $page = $_GET['page'];
}
head();
switch (mb_strtolower($page)) {
    case 'list':
    default:
        include "src/views/productsList.php";
        break;
    case 'add':
        include "src/views/addProduct.php";
        break;
    case 'edit':
        include "src/views/editProduct.php";
        break;
    case 'delete':
        //page de suppression d'un produit action pour suppression produit
        break;
    case 'read':
        include "src/views/readProduct.php";
        break;
    case 'cat_manage':
        include "src/views/catManage.php";
        break;
    case 'delete_cat':
        //page delete d'une categorie
        break;
}
foot();

