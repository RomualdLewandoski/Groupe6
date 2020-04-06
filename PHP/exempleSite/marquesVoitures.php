<?php
$array = array(
    "model 3" => "tesla",
    "clito" => "renault",
    "500Max" => "fiat",
    "r5" => "renault",
    "grand voyager" => "chrisler",
    "picaso" => "citroen",
    "r8" => "audi",
    "x5" => "BMW",
    "DMC-12" => "DeLorean"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exemple site</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
</head>
<body>
<h1>Liste des marques de voitures</h1>
<table class="table">
    <thead>
    <tr>
        <th scope="col">nom Voiture</th>
        <th scope="col">marque Voiture</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($array as $key => $value) {
        echo "<tr>
                <td>" . $key . "</td>
                <td>" . $value . "</td>
               </tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>
