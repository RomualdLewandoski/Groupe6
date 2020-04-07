<?php
require_once 'src/views/elements/head.php';
require_once 'src/views/elements/footer.php';

head();
?>
    <h1>Site de mes véhicules</h1>
    <hr>
    <div>

        <a href="src/views/mesVehicules.php">
            <button type="button" class="btn btn-outline-dark">
                Mes véhicules
            </button>
        </a>
        <form class="form-row mt-5" method="post" action="src/views/mesVehicules.php">
            <div class="form-group col-lg-6">
                <label for="message">Le modèle</label>
                <input type="text" class="form-control" id="message" name="model">
            </div>
            <div class="form-group col-lg-6">
                <label for="message">La marque</label>
                <input type="text" class="form-control" id="message" name="marque">
            </div>
            <br>
            <button type="submit" class="btn btn-outline-dark">Envoyer</button>
        </form>

    </div>
<?php
footer();
?>