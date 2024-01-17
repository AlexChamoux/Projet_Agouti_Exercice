<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['login']) && $_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// A faire :
// Apres la soumission du formulaire de login (plus bas dans ce fichier)
if(isset($_POST['login'])){
    // On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
    // $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas
    if ($_POST['vercode'] != $_SESSION['vercode']) {
        echo "<script>alert('Le code de vérification est incorrect. Veuillez réessayer.')</script>";
    }else{
        // Le code est correct, on peut continuer
        // On recupere le nom de l'utilisateur saisi dans le formulaire
        $UserName = valid_donnees($_POST['name']);
        $password = valid_donnees($_POST['password']);

        if(!empty($UserName)
        && strlen($UserName) <= 40
        && strlen($password) <= 20
        && preg_match("#^[A-Za-z0-9 '-]+$#", $UserName)
        && preg_match("#^[A-Za-z0-9.+_@!?&§%]+$#", $password)){

        
            // On construit la requete qui permet de retrouver l'utilisateur a partir de son nom et de son mot de passe
            // depuis la table admin
            $query ="SELECT UserName, Password FROM admin WHERE UserName = :UserName";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':UserName', $UserName, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_OBJ);
            error_log(print_r($result, 1));
            // Si le resultat de recherche n'est pas vide 
            if(!empty($result) && password_verify($_POST['password'], $result->Password)){
                // On stocke le nom de l'utilisateur  $_POST['username'] en session $_SESSION
                $_SESSION['alogin'] = $_POST['name'];
                // On redirige l'utilisateur vers le tableau de bord administration (n'existe pas encore)
                header("location:admin/dashboard.php");

                $_SESSION['message'] = '';
        
            }else{
                // sinon le login est refuse. On le signal par une popup
                echo "<script>alert('Nom d\'utilisateur ou mot de passe incorrect. Veuillez réessayer.');</script>";
            }
        }

    }
}





?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
    <div class="row">
			<div class="col">
				<h3>LOGIN ADMINISTRATEUR</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="adminlogin.php">
                    <div class="form-group">
						<label>Entrez votre nom complet</label>
						<input type="text" name="name" placeholder="Entrez votre nom complet" pattern="^[A-Za-z0-9 '-]+$" maxlength="40" required>
					</div>
                    <div class="form-group">
						<label>Mot de passe :</label>
						<input type="password" name="password" class="password" placeholder="Entrez votre mot de passe" pattern="^[A-Za-z0-9.+_@!?&§%]+$" maxlength="20" required>
					</div>
                    <div class="form-group">
						<label>Code de vérification</label>
						<input type="text" name="vercode" placeholder="Entrez le code captcha" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
					</div>

					<button type="submit" name="login" class="btn btn-info">LOGIN</button>
				</form>
			</div>
		</div>
        <!--On affiche le titre de la page-->

        <!--On affiche le formulaire de login-->

        <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>