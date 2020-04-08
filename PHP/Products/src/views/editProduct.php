<?php
if (!isset($_GET['productId'])) {
    header('Location: /&?response=missingIdEdit');
} else {
    $idProduct = htmlspecialchars(trim($_GET['productId']));
    if (!is_numeric($idProduct)) {
        header('Location: /&?response=notNumId');
    } else {
        $db = getDb();
        if (isset($_GET['action']) AND $_GET['action'] == "edit") {
            if (isset($_POST['nameProduct']) && isset($_POST['descriptionProduct']) && isset($_POST['priceProduct']) && isset($_POST['catProducts'])) {
                $productName = htmlspecialchars(trim($_POST['nameProduct']));
                $productDesc = trim($_POST['descriptionProduct']);
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
        $sqlSelect = "SELECT products.*, categories.name as catName FROM products INNER JOIN categories on products.category_id = categories.id  WHERE products.id = ?";
        $reqSelect = $db->prepare($sqlSelect);
        $reqSelect->bindParam(1, $idProduct);
        $reqSelect->execute();
        $nbresult = $reqSelect->rowCount();
        if ($nbresult == 0) {
            header('Location: /&?response=notExistEdit');
        } else {
            $data = $reqSelect->fetchObject();
            $sqlSelectCat = "SELECT * FROM categories ORDER BY id ASC";
            $reqSelectCat = $db->prepare($sqlSelectCat);
            $reqSelectCat->execute();
            $dataCat = $reqSelectCat->fetchAll(5);
        }
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
                    <script src="./assets/ckeditorfull/ckeditor.js" type="text/javascript"></script>
                    <textarea id="descriptionProduct" name="descriptionProduct" class="note-codable"
                              style="height: 270px;"><?= $data->description ?></textarea>
                    <script>
                        CKEDITOR.replace('descriptionProduct', {
                            removePlugins: 'sourcearea, about, forms, iframe, save, preview, print, templates'
                        });
                    </script>
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
<script>
    var autoCmd = [
        <?php
        $str = '';
        foreach ($dataCat as $cat) {
            $str .= '"' . $cat->name . '",';
        }
        echo substr($str, 0, -1);
        ?>
    ]

    function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function (e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) {
                return false;
            }
            currentFocus = -1;
            isOpened = true;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function (e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function (e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                console.log(currentFocus)
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (isOpened) {
                        event.preventDefault()
                        if (x) x[currentFocus].click();
                        isOpened = false;
                    }

                }
            }
        });

        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
            isOpened = false;
        }

        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    autocomplete(document.getElementById("catProducts"), autoCmd);
</script>