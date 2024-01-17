<?php 
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');


if(strlen($_SESSION['login'])==0){
    // Si l'utilisateur n'est plus logué
    // On le redirige vers la page de login
    header('location:index.php');
}else{
    // Sinon on peut continuer. Après soumission du formulaire de profil
    
    $ReaderId = $_SESSION['rdid'];

    $query = "SELECT * FROM tblreaders WHERE ReaderId = :ReaderId";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ReaderId', $ReaderId, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result){
        error_log(print_r($result, 1));
        // On recupere l'id du lecteur (cle secondaire)
        $id = $result['id'];
        // On recupere le nom complet du lecteur
        $FullName = $result['FullName'];
        // On recupere le numero de portable
        $MobileNumber = $result['MobileNumber'];
        // On récupère l'e-mail
		$EmailId = $result['EmailId'];
		//On récupère la date d'enregistrement
		$RegDate = $result['RegDate'];
		//On récupère la date de la dernière Update
		$UpdateDate = $result['UpdateDate'];
		//On récupère le status du lecteur
		if ($result['Status'] == 1) {
			$Status = 'actif';
		} else {
			$Status = 'inactif';
		};	


        if(!isset($_POST['submit'])){
            echo "<script>alert('Le formulaire n'a pas été soumis, un problème est survenue.')</script>";
        }else{

            //On récupère le nom complet modifié par l'utilisateur
			$fullName = valid_donnees($_POST["nom"]);
			error_log($FullName);
			//On récupère le numéro de mobile modifié par l'utilisateur
			$mobileNumber = valid_donnees($_POST["portable"]);
			error_log($MobileNumber);
			//On récupère l'emial modifié par l'utilisateur
			$emailId = valid_donnees($_POST["email"]);
			error_log($EmailId);
			
			if(!empty($fullName) && !empty($mobileNumber) && !empty($emailId)
			&& strlen($fullName) <= 40
			&& strlen($mobileNumber) <= 10
			&& strlen($emailId) <= 40
			&& preg_match("#^[A-Za-z0-9 '-]+$#", $fullName)
			&& preg_match("#^[0-9]+$#", $mobileNumber)
			&& preg_match("#^[A-Za-z]+@{1}[A-Za-z]+\.{1}[A-Za-z]{2,}$#", $emailId)
			&& filter_var($emailId, FILTER_VALIDATE_EMAIL)){

            // On update la table tblreaders avec ces valeurs
            $query = "UPDATE tblreaders SET FullName = :FullName, MobileNumber = :MobileNumber, EmailId = :EmailId WHERE ReaderId = :ReaderId";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':ReaderId', $ReaderId, PDO::PARAM_STR);
            $stmt->bindParam(':FullName', $fullName, PDO::PARAM_STR);
            $stmt->bindParam(':MobileNumber', $mobileNumber, PDO::PARAM_STR);
            $stmt->bindParam(':EmailId', $emailId, PDO::PARAM_STR);
            $stmt->execute();

            echo "<script>alert('Vos données ont bien été mises à jour.')</script>";
			}
        }

    }
}
	

    	

        

        

		
        // On informe l'utilisateur du resultat de l'operation


	// On souhaite voir la fiche de lecteur courant.
	// On recupere l'id de session dans $_SESSION

	// On prepare la requete permettant d'obtenir 

?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

</head>
<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
<?php include('includes/header.php');?>
<!--On affiche le titre de la page : EDITION DU PROFIL-->
<h1>MON COMPTE</h1>
	<!--On affiche le formulaire. J'aurais pu mettre ees div simple avec des paragraphes pour les inputs non-modifiables, mais ça m'a permis d'apprendre qu'on pouvait utiliser le "readonly" -->
	<form method="post" action="my-profile.php">
		<!--On affiche l'identifiant - non editable-->
		<div>
			<label for="id">Identifiant:</label>
			<input type="text" id="id" name="id" value ="<?php echo $ReaderId; ?>" readonly>
		</div>
        <!--On affiche la date d'enregistrement - non editable-->
	<div>
		<label for="date_enregistrement">Date d'enregistrement:</label>
		<input type="text" id="date_enregistrement" name="date_enregistrement" value ="<?php echo $RegDate; ?>" readonly>
	</div>

	<!--On affiche la date de derniere mise a jour - non editable-->
	<div>
		<label for="date_maj">Date de dernière mise à jour:</label>
		<input type="text" id="date_maj" name="date_maj" value ="<?php echo $UpdateDate; ?>" readonly>
	</div>

	<!--On affiche la statut du lecteur - non editable-->
	<div>
		<label for="statut_lecteur">Statut du lecteur:</label>
		<input type="text" id="statut_lecteur" name="statut_lecteur" value ="<?php echo htmlspecialchars($Status); ?>" readonly>
	</div>

	<!--On affiche le nom complet - editable-->
    <div class="form-group">
	<label for="nom_complet">Nom complet :</label>
	<input type="text" class="form-control" id="nom_complet" name="nom" value="<?php echo $FullName; ?>" pattern="^[A-Za-z0-9 '-]+$" maxlength="40" required>
</div>

<!--On affiche le numéro de portable- editable--> 
<div class="form-group">
	<label for="numero_portable">Numéro de portable :</label>
	<input type="text" class="form-control" id="numero_portable" name="portable" value="<?php echo $MobileNumber; ?>" pattern="^[0-9]+$" maxlength="10" required>
</div>

<!--On affiche l'email- editable--> 
<div class="form-group">
	<label for="email">Email :</label>
	<input type="email" class="form-control" id="email" name="email" value="<?php echo $EmailId; ?>" pattern="^[A-Za-z]+@{1}[A-Za-z]+\.{1}[A-Za-z]{2,}$" maxlength="40" required>
</div>

<!-- On inclut le champ caché pour l'ID du lecteur -->
<input type="hidden" name="id_lecteur" value="<?php echo $id; ?>">

<!-- Boutons de soumission et d'annulation -->
<div class="form-group">
	<button type="submit" class="btn btn-primary" name="submit">Enregistrer</button>
	<a href="profil.php" class="btn btn-danger">Annuler</a>
</div>
 
    <?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
