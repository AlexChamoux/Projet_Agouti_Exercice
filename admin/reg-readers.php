<?php
// On démarre ou on récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0){
	// Si l'utilisateur n'est plus logué
	// On le redirige vers la page de login
	header('location:../adminlogin.php');
}else{

    $query = "SELECT * FROM tblreaders";
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_GET['actif'])){
        $id= valid_donnees($_GET['actif']);
        $Status=1;
        $valid = array('1', '0');
        
        if(!empty($id) && ($Status!==FALSE)
        && preg_match("#^[0-9]{1,}#",$id)
        && in_array($Status, $valid)){

        $query = "UPDATE tblreaders SET Status = :Status WHERE id = $id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':Status', $Status, PDO::PARAM_STR);
        $stmt->execute();

        header('location:reg-readers.php');

        }
    }

    if(isset($_GET['inactif'])){
        $id= valid_donnees($_GET['inactif']);
        $Status=0;
        $valid = array('1', '0');

        if(!empty($id) && ($Status!==FALSE)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id)
        && in_array($Status, $valid)){

        $query = "UPDATE tblreaders SET Status = :Status WHERE id = $id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':Status', $Status, PDO::PARAM_STR);
        $stmt->execute();

        header('location:reg-readers.php');
        }
    }

    if(isset($_GET['suppress'])){
        $id= valid_donnees($_GET['suppress']);
        $Status=2;
        $valid = array('1', '0');

        if(!empty($id) &&($Status!==FALSE)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id)
        && in_array($$Status, $valid)){

        $query = "UPDATE tblreaders SET Status = :Status WHERE id = $id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':Status', $Status, PDO::PARAM_STR);
        $stmt->execute();

        header('location:reg-readers.php');

        }
    }



}

// Si l'utilisateur n'est logué ($_SESSION['alogin'] est vide)
// On le redirige vers la page d'accueil
// Sinon on affiche la liste des lecteurs de la table tblreaders

// Lors d'un click sur un bouton "inactif", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['inid']
// et on met à jour le statut (0) dans la table tblreaders pour cet identifiant de lecteur

// Lors d'un click sur un bouton "actif", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['id']
// et on met à jour le statut (1) dans  table tblreaders pour cet identifiant de lecteur

// Lors d'un click sur un bouton "supprimer", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['del']
// et on met à jour le statut (2) dans la table tblreaders pour cet identifiant de lecteur

// On récupère tous les lecteurs dans la base de données
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Reg lecteurs</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!--On inclue ici le menu de navigation includes/header.php-->
    <?php include('includes/header.php'); ?>
    <!-- Titre de la page (Gestion du Registre des lecteurs) -->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>GESTION DU REGISTRE DES LECTEURS</h3>
            </div>
        </div>  
        <div class="row">
            <div class="w-100">
                <div class="container">
                    <div class="card bg-primary text-black">
                        <h2 class="card-header">Registre lecteurs</h2>
                    </div>  
                        <table class="w-100">
                            <thead>
                                    <tr>
                                    <th class="fa-border">#</th>
                                    <th class="fa-border">Id lecteurs</th>
                                    <th class="fa-border">Nom</th>
                                    <th class="fa-border">Email</th>
                                    <th class="fa-border">Portable</th>
                                    <th class="fa-border">Date d'enregistrement</th>
                                    <th class="fa-border">Status</th>
                                    <th class="fa-border">Action</th>
                                    </tr>
                            </thead>
                            <tbody class="body">
                        <?php
                            foreach ($results as $result) { ?>
                            <tr>
                                    <td class="fa-border"><?= $result['id'] ?></td>
                                    <td class="fa-border"><?= $result['ReaderId'] ?></td>
                                    <td class="fa-border"><?= $result['FullName'] ?></td>
                                    <td class="fa-border"><?= $result['EmailId'] ?></td>
                                    <td class="fa-border"><?= $result['MobileNumber'] ?></td>
                                    <td class="fa-border"><?= $result['RegDate'] ?></td>
                                    <td class="fa-border"><?= $result['Status'] == 0 ? "Bloqué(e)" : ($result['Status'] == 1 ? "Actif" : ($result['Status'] == 2 ? "Supprimé(e)" : "Statut inconnu")) ?>
                                    </td>
                                    <td class="fa-border"><?php if (($result['Status'] == 0) ||($result['Status'] == 1)){ ?>
                                        <?php if ($result['Status'] == 0){ ?>
                                            <a href="reg-readers.php?actif=<?php echo ($result['id']) ?>"><button type="submit" name="actif" class="btn btn-info">Actif</button></a>
                                            <?php } ?>
                                        <?php if ($result['Status'] == 1){ ?>
                                            <a href="reg-readers.php?inactif=<?php echo ($result['id']) ?>"><button type="submit" name="inactif" class="btn btn-warning">Inactif</button></a>
                                            <?php } ?>
                                            <a href="reg-readers.php?suppress=<?php echo ($result['id']) ?>"><button type="submit" name="suppress" class="btn btn-danger">Supprimer</button></a>
                                            <?php }else{}
                                            ?>                          
                                    </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <!--On insère ici le tableau des lecteurs.
       On gère l'affichage des boutons Actif/Inactif/Supprimer en fonction de la valeur du statut du lecteur -->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>