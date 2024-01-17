<?php
session_start();

include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    // Si l'utilisateur n'est plus logué
    // On le redirige vers la page de login
    header('location:../adminlogin.php');
}else{
    // Sinon on peut continuer. Après soumission du formulaire de creation

    // On recupere le nom et le statut de la categorie
    if(isset($_POST['creer'])){
        $CategoryName = valid_donnees($_POST['CategoryName']);
        $Status = valid_donnees($_POST['radio']);
        error_log($_POST['radio']);
        $valid = array('1', '0');

        if(!empty($CategoryName)
        && strlen($CategoryName) <= 20        
        && preg_match("#^[A-Za-z0-9 '-]+$#", $CategoryName)
        && in_array($Status, $valid)){
        
        // On prepare la requete d'insertion dans la table tblcategory
        $query = "INSERT INTO tblcategory (CategoryName, Status) VALUES (:CategoryName, :Status)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':CategoryName', $CategoryName, PDO::PARAM_STR);
        $stmt->bindParam(':Status', $Status, PDO::PARAM_INT);
        $stmt->execute();
        
        // On execute la requete

        // On stocke dans $_SESSION le message correspondant au resultat de l'operation
        $_SESSION['message'] = "La catégorie a été créée avec succès";
        header('location:manage-categories.php');
        }
    }

}

?>





<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page-->
    <div class="container">
        <div class="row">
            <div class="col">
            <h3>AJOUTER UNE CATÉGORIE</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <div class="container">
                    <div class="card bg-primary text-black">
                        <h2 class="card-header">Information catégorie</h2>
                    </div>
                    <div class="card-body border p-4">
                        <form method="post" action="add-category.php">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="CategoryName" pattern="^[A-Za-z0-9 '-]+$" maxlength="20" required>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="radio" value="1">
                                    <label class="form-check-label" for="radio1"> Active</label>
                                </div>
                                <br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="radio" value="0">
                                    <label class="from-check-label" for="radio2">Inactive</label>
                                </div>
                            </div>
                            <button type="submit" name="creer" class="btn btn-info">Créer</button>
                        </form>
                    </div>
                    
                

                </div>
            </div>
        </div>
    </div>
    <!-- On affiche le formulaire de creation-->
    <!-- Par defaut, la categorie est active-->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>