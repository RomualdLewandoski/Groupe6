<?php
include_once 'elements/head.php';
include_once 'elements/footer.php';
require '../config/config.php';
require '../models/connect.php';

head();

$db = connection();


if (isset($_POST['model']) && isset($_POST['marque'])) {

    /*
     * Verification si les champs sont vides
     */

    if (empty($_POST['model']) || empty($_POST['marque'])) {
        header('Location: ../../index.php?empty=true');
    } else {

        /*
         * Vérification si la marque et le model existe déja
         */

        $sqlModelExiste =  "SELECT COUNT(*)
                            FROM vehicule
                            INNER JOIN marque ON vehicule.marque_idMarque = marque.idMarque
                            INNER JOIN modele ON vehicule.modele_idModele = modele.idModele
                            WHERE modele.nomModele = :model
                            AND marque.nomMarque = :marque";
        $reqVerif = $db->prepare($sqlModelExiste);
        $reqVerif->bindParam(':marque', $marque);
        $reqVerif->bindParam(':model', $model);
        $reqVerif->execute();

        $modelMarqueExiste = $reqVerif->rowCount();

        if($reqVerif->rowCount() > 0){
            header('Location: ../../index.php?existe=true');
        }else {


            /* protection des insertions et création des variables permetant de stocker
            * les donées reçues de index.php
            */
            $model = htmlspecialchars(trim($_POST['model']));
            $marque = htmlspecialchars(trim($_POST['marque']));

            // dans un 1er temps il faut ajouter les données reçues dans ma base de données
            // requetes insert
            // D'abord je procède à l'insertion dans les tables qui n'ont pas de clefs étrangéres

            /*
             * j'ajoute la marque dans ma base de données
             */
            $insertMarqueSql = "INSERT INTO marque (nomMarque) VALUES (:isertmarque)";
            $reqMarque = $db->prepare($insertMarqueSql);
            $reqMarque->bindParam(':isertmarque', $marque);
            $reqMarque->execute();

            $lastInsertIdMarque = $db->lastInsertId();

            /*
            * j'ajoute le modele dans ma base de données
            */
            $insertModelSql = "INSERT INTO modele (nomModele) VALUES (:insertModele)";
            $reqModele = $db->prepare($insertModelSql);
            $reqModele->bindParam(':insertModele', $model);
            $reqModele->execute();

            $lastInsertIdModele = $db->lastInsertId();


            $insertVoitureSql = "INSERT INTO vehicule (modele_idModele, marque_idMarque) 
                        VALUES ($lastInsertIdMarque, $lastInsertIdModele)";
            $req = $db->prepare($insertVoitureSql);
            //$req->bindParam(':insertModele', $model);
            $req->execute();
            //$lastInsertIdMarque = $db->lastInsertId();


            // requetes Select de tous les vehicules, marques et models

            $selectVoiture = "SELECT * 
                      FROM vehicule 
                      INNER JOIN modele ON vehicule.modele_idModele = modele.idModele 
                      INNER JOIN marque ON vehicule.marque_idMarque = marque.idMarque
                      ";

            $reqVehicule = $db->prepare($selectVoiture);
            $reqVehicule->execute();

            /*
             * Je crée un tableau qui me permettera de stocker les resultats de la requete précedente
             */
            $listeVehicules = array();

            /*
             * tant qu'il y a des resultats dans ma recherche, je les mets dans mon tableau listeVehicules
             */
            while ($data = $reqVehicule->fetchObject()) {
                array_push($listeVehicules, $data);
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

                    /*
                     * j'affiche les resultats placés dans mon tableau listeVehicules
                     */
                    foreach ($listeVehicules

                    as $vehicule){
                    ?>
                    <a href="detailProduit.php?marque">
                        <td><?= $vehicule->nomMarque ?></td>
                    </a>
                    <td><?= $vehicule->nomModele ?></td>
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

            <?php
        }
    }
}
footer();