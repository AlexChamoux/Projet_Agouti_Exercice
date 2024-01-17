<?php 
session_start();

include('includes/config.php');
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero ISBN du livre*/
// On prepare la requete de recherche du livre correspondnat
// On execute la requete
// Si un resultat est trouve
	// On affiche le nom du livre
	// On active le bouton de soumission du formulaire
// Sinon
	// On affiche que "ISBN est non valide"
	// On desactive le bouton de soumission du formulaire 

		$isbnNumber = $_GET['isbnNumber'];
		error_log($isbnNumber);

		// exécuter la requête SQL pour récupérer les données de la table "tblreaders" avec les colonnes "ReaderId" et "FullName"
		$query = "SELECT * FROM tblbooks WHERE ISBNNumber = :isbnNumber";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':isbnNumber', $isbnNumber, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch();
		error_log(print_r($result, 1));
		
		if($result){
			echo json_encode(['name' => "{$result['BookName']}"]);			
		}else{
			echo json_encode(['name' => "Le livre n'est plus dispo, voyez."]);
		}


?>
