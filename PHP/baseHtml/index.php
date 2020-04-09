<?php
require_once 'src/views/elements/head.php';
require_once 'src/views/elements/navbar.php';
require_once 'src/views/elements/foot.php';
$page = "";
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "index";
}
head();
menu($page);
switch (mb_strtolower($page)) {
    case "index":
    default:
        include "src/views/main.php";
        break;
    case "location":
        include "src/views/location.php";
        break;
    case "contact":
        include "src/views/contact.php";
        break;
    case "detail":
        include "src/views/detail.php";
        break;
}
foot();