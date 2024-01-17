<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login  
      header('location:../adminlogin.php');
  }else{

      $query = "SELECT * FROM tblissuedbookdetails";
      $stmt = $dbh->prepare($query);
      $stmt->execute();
      $results = $stmt->fetchAll();

      $id = valid_donnees($_GET['edit']);      
      error_log($id);
      
      if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id)){

            if(isset($_POST['update'])){
                  $returnStatus = valid_donnees($_POST['radio']);
                  error_log($returnStatus);
                  $returnDate = date('Y-m-d H:i:s');
                  error_log($returnDate);
                  $valid = array('0', '1', '2');

                  if(($returnStatus!==FALSE)
                  && in_array($returnStatus, $valid)){

                  $query = "UPDATE tblissuedbookdetails SET ReturnDate = :returnDate, ReturnStatus = :returnStatus WHERE id = $id";
                  $stmt = $dbh->prepare($query);
                  $stmt->bindParam(':returnDate', $returnDate, PDO::PARAM_STR);
                  $stmt->bindParam(':returnStatus', $returnStatus, PDO::PARAM_STR);
                  $stmt->execute();

                  $_SESSION['message'] = "La sortie du livre a été mise à jour avec succès";
                  header('location:manage-issued-books.php');

                  }
            }else{
                  echo "<script>alert('Le formulaire n'a pas été transmis.')</script>";
            }
      }
  }
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Sorties</title>
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
                  <h3>EDITER UN RETOUR</h3>
            </div>
      </div>
      <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                  <div class="container">
                        <div class="card bg-primary text-black">
                              <h2 class="card-header">Information sortie</h2>
                        </div>
                        <div class="card-body border p-4">
                              <form method="post">
                                    <div class="form-group">
                                          <label>Rendu</label>
                                          <input type="radio" name="radio" value="1">
                                          <label>Non rendu</label>
                                          <input type="radio" name="radio" value="0">
                                    </div>
                                    <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
                              </form>
                        </div>
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
