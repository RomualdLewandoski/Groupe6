<?php
$marqueVoitures = array("BMW", "AUDI", "FORD", "RENAULT", "FIAT", "CITROEN", "TESLA");
foreach ($marqueVoitures as $marque):
    echo $marque . "<br>";
endforeach;

$telephones = array(
    "s9" => "Samsung",
    "xr" => "Apple",
    "s10+" => "Samsung",
    "5s" => "Apple"
);

foreach ($telephones as $model => $marque):
        echo "Le téléphone ".$model." est de marque ".$marque."<br>";
endforeach;
