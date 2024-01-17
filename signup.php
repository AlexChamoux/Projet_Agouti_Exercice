<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Après la soumission du formulaire de compte (plus bas dans ce fichier)
// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
if(isset($_POST['login'])){

    if ($_POST['vercode'] != $_SESSION['vercode']) {
		// Le code est incorrect on informe l'utilisateur par une fenetre pop_up
		echo "<script>alert('Code de vérification incorrect')</script>";
	} else {        

            //On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur cree.
            $ad = file_get_contents('readerid.txt');
            $id = valid_donnees($ad);
            // On incrémente de 1 la valeur lue
            $id++;
            // On ouvre le fichier readerid.txt en écriture
            $readerid = fopen('readerid.txt', 'w');
            // On écrit dans ce fichier la nouvelle valeur
            fwrite($readerid, $id);
            // On referme le fichier
            fclose($readerid);            

            $ReaderId = $id;
            // On récupère le nom saisi par le lecteur
            $FullName = valid_donnees($_POST['name']);
            // On récupère le numéro de portable
            $MobileNumber = valid_donnees($_POST['portable']);
            // On récupère l'email
            $EmailId = valid_donnees($_POST['email']);
            // On récupère le mot de passe
            $Password = valid_donnees($_POST['password']);
            // On encode le mot de passe
            $passwordHash = password_hash($Password, PASSWORD_DEFAULT);
            // On fixe le statut du lecteur à 1 par défaut (actif)
            $Status = 1;

            if (!empty($ReaderId) && !empty($FullName) && !empty($MobileNumber) && !empty($EmailId) &&!empty($Password)
            && strlen($ReaderId) <=6
            && strlen($FullName) <= 40
            && strlen($MobileNumber) <=10
            && strlen($EmailId) <= 40
            && strlen($Password) <= 20
            && preg_match("#^[A-Z0-9]+$#", $ReaderId)
            && preg_match("#^[A-Za-z0-9 '-]+$#", $FullName)
            && preg_match("#^[0-9]+$#", $MobileNumber)
            && preg_match("#^[A-Za-z]+@{1}[A-Za-z]+\.{1}[A-Za-z]{2,}$#", $EmailId)
            && preg_match("#^[A-Za-z0-9.+_@!?&§%]+$#", $Password)){

                // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders
                // On éxecute la requete
                $query = "INSERT INTO tblreaders(ReaderId, FullName, MobileNumber, EmailId, Password, Status) VALUES(:ReaderId, :FullName, :MobileNumber, :EmailId, :Password, :Status)";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':ReaderId', $ReaderId, PDO::PARAM_STR);
                $stmt->bindParam(':FullName', $FullName, PDO::PARAM_STR);
                $stmt->bindParam(':MobileNumber', $MobileNumber, PDO::PARAM_STR);
                $stmt->bindParam(':EmailId', $EmailId, PDO::PARAM_STR);
                $stmt->bindParam(':Password', $passwordHash, PDO::PARAM_STR);
                $stmt->bindParam(':Status', $Status, PDO::PARAM_STR);
                $stmt->execute();


                // On récupère le dernier id inséré en bd (fonction lastInsertId)
                $last_id = $dbh->lastInsertId();

                if($last_id){
                    // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée, et on affiche l'identifiant lecteur (valeur de $hit[0])
                    echo "<script>alert('Votre compte a été créé avec succès. Votre identifiant lecteur est $id.')</script>";
                    header('location:index.php');
                }else{
                    // Sinon on affiche qu'il y a eu un problème
                    echo "<script>alert('Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.')</script>";
                }
            }
    }
};

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliotheque en ligne | Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        // On cree une fonction valid() sans paramètre qui renvoie 
        // TRUE si les mots de passe saisis dans le formulaire sont identiques
        // FALSE sinon
        function valid() {
            let password = document.querySelector(".password").value;
            let confirm_password = document.querySelector(".confirm_password").value;
            if (password != confirm_password) {
            alert("Les mots de passe ne sont pas identiques !");
            return false;
            } else {
            return true;
            }
        };

        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
        // Cette fonction effectue un appel AJAX vers check_availability.php¨
        function check_availability(email) {
            /*console.log(email);*/
            let mail = new Request(`check_availability.php?email=${email}`, {
                        method: 'GET'
                        });
           

            fetch(mail)
                .then(response => response.json())
                .then(data => {
                    /*console.log(data);*/
                    if (data.exists) {
                        $('#result').html('Cet email est déjà utilisé.');
                        $('#signup-btn').prop('disabled', true);
                    } else {
                        $('#result').html('');
                        $('#signup-btn').prop('disabled', false);
                    }
                  
                })
                /*.catch(error => console.error("Erreur : " + error));*/
        };

    </script>
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : CREER UN COMPTE-->
    <div class="container">
		<div class="row">
			<div class="col">
				<h3>CRÉER UN COMPTE</h3>
			</div>
		</div>
        <div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="signup.php" onsubmit="return valid()">
					<div class="form-group">
						<label>Entrez votre nom complet</label>
						<input type="text" name="name" placeholder="Entrez votre nom complet" pattern="^[A-Za-z0-9 '-]+$" maxlength="40" required>
					</div>
                    <div class="form-group">
						<label>Portable :</label>
						<input type="text" name="portable" placeholder="Entrez votre numéro de portable" pattern="^[0-9]+$" maxlength="10" required>
					</div>
                    <div class="form-group">
						<label>Email :</label>
						<input type="text" name="email" placeholder="Entrez votre adresse email" onBlur="check_availability(this.value)" pattern="^[A-Za-z]+@{1}[A-Za-z]+\.{1}[A-Za-z]{2,}$" maxlength="40" required>
					</div>
                    <div class="form-group">
						<label>Mot de passe :</label>
						<input type="password" name="password" class="password" placeholder="Entrez votre mot de passe" pattern="^[A-Za-z0-9.+_@!?&§%]+$" maxlength="20" required>
					</div>
                    <div class="form-group">
						<label>Confirmez mot de passe :</label>
						<input type="password" name="password" class="confirm_password" placeholder="Confirmez votre mot de passe" pattern="^[A-Za-z0-9.+_@!?&§%]+$" maxlength="20" required>
					</div>
                    <div class="form-group">
						<label>Code de vérification</label>
						<input type="text" name="vercode" placeholder="Entrez le code captcha" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
					</div>
                    <button type="submit" name="login" class="btn btn-info">ENREGISTRER</button>
                </form>
            </div>
        </div>        
    </div>
    <!--On affiche le formulaire de creation de compte-->
    <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
    <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid(); -->
    <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->



    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>