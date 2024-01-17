<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
     // On le redirige vers la page de login  
     header('location:../adminlogin.php');
 }else{
     // Sinon
     // Apres soumission du formulaire de categorie

     //On récupère l'identifiant
     if(isset($_GET['edit'])){
         $id = valid_donnees($_GET['edit']);
         
               if(isset($_POST['update']) == TRUE){
                         // On recupere le nom,
                         $AuthorName = valid_donnees($_POST['name']);
                         // le status
                         $Status = valid_donnees($_POST['radio']);
                         $valid = array('1', '0');

                         if(!empty($AuthorName) && ($Status!==FALSE) && !empty($id)
                         && strlen($AuthorName) <= 40
                         && preg_match("#^[A-Za-z '-]+$#", $AuthorName)
                         && in_array($Status ,$valid)
                         && preg_match("#^[0-9]{1,}#",$id)){
                         
                         $query = "UPDATE tblauthors SET AuthorName = :AuthorName, Status = :Status WHERE id = $id";
                         $stmt = $dbh->prepare($query);
                         $stmt->bindParam(':AuthorName', $AuthorName, PDO::PARAM_STR);
                         $stmt->bindParam(':Status', $Status, PDO::PARAM_INT);
                         $stmt->execute();
               
                         // On stocke dans $_SESSION le message correspondant au resultat de l'operation
                         $_SESSION['message'] = "L\'auteur a été mise à jour avec succès";
               
                         header('location:manage-authors.php');
                         }
                         
                    }else{
                         echo "<script>alert('Le formulaire n'a pas été transmis.')</script>";
                    }
     };
}
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Auteurs</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="container">
        <div class="row">
            <div class="col">
            <h3>ÉDITER L'AUTEUR</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <div class="container">
                    <div class="card bg-primary text-black">
                        <h2 class="card-header">Information auteur</h2>
                    </div>
                    <div class="card-body border p-4">
                         <!-- On affiche ici le formulaire d'édition -->
                         <form method="post">
                              <div class="form-group">
                                   <label>Nom</label>
                                   <input type="text" name="name" pattern="^[A-Za-z '-]+$" maxlength="40" required>
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
                              <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
                         </form>
                    </div>
          </div>
     </div>
</div>
     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
