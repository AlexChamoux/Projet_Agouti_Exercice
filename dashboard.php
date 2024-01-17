<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de données
include('includes/config.php');


if(strlen($_SESSION['login'])==0) {
	// Si l'utilisateur est déconnecté
	// L'utilisateur est renvoyé vers la page de login : index.php
  header('location:index.php');
} else {
	// On récupère l'identifiant du lecteur dans le tableau $_SESSION
	$reader_id = $_SESSION['rdid'];
	// On veut savoir combien de livres ce lecteur a emprunte
	// On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
	$query = "SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReaderID = :ReaderId/* AND ReturnStatus = 0 AND ReturnStatus = 1*/";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ReaderId', $reader_id, PDO::PARAM_STR);
    $stmt->execute();

    // On stocke le résultat dans une variable
    $total_books = $stmt->fetch(PDO::FETCH_ASSOC);
	error_log(print_r($total_books));
	
	// On veut savoir combien de livres ce lecteur n'a pas rendu
	// On construit la requete qui permet de compter combien de livres sont associés à ce lecteur avec le ReturnStatus à 0 
    $query = "SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReaderID = :ReaderId AND ReturnStatus = 0";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ReaderId', $reader_id, PDO::PARAM_STR);
    $stmt->execute();

    // On stocke le résultat dans une variable
    $books_not_returned = $stmt->fetch(PDO::PARAM_STR);
	error_log(print_r($books_not_returned));
	

?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
<?php include('includes/header.php');?>
<!-- On affiche le titre de la page : Tableau de bord utilisateur-->
<div class="container">
        <div class="row">
            <div class="col">
                <h3>Tableau de bord utilisateur</h3>
            </div>
        </div>

        <!-- On affiche la carte des livres empruntés par le lecteur -->
        <div class="books d-flex">
            <div class="col fa-border fa-5x fa fa-book fa-align-justify fa-fw" id="total-books">
                    <h5>Livres empruntés</h5>
                    <p><?php echo $total_books['COUNT(*)'];?></p>
            </div>        

            <!-- On affiche la carte des livres non rendus par le lecteur -->
            <div class="col fa-border fa-5x fa fa-recycle fa-fw"> 
                <h5>Livres non encore rendus</h5>
                <p><?php echo $books_not_returned['COUNT(*)'];?></p>
            </div>
        </div>
    </div>

<?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
