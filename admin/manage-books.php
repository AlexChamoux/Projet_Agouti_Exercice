<?php
session_start();
include('includes/config.php');


if (!strlen($_SESSION['message']) == 0) {
    $message = $_SESSION['message'];
    error_log($message);
    echo "<script> window.addEventListener('load', () => {
        alert('" . $message . "');
        event.preventDefault();
        })</script>";
    $_SESSION['message'] = '';
}


$query = " SELECT tbk.id, tbk.BookName, tbk.ISBNNumber, tbk.BookPrice, tcg.CategoryName, taut.AuthorName
            FROM tblbooks tbk
            JOIN tblcategory tcg ON tbk.CatId = tcg.id
            JOIN tblauthors taut ON tbk.AuthorId = taut.id";
$stmt = $dbh->prepare($query);
$stmt->execute();

$results = $stmt->fetchAll();

if(isset($_GET['suppress'])){
    $id = valid_donnees($_GET['suppress']);
    
    if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id)){

    $dbh->exec("DELETE FROM tblbooks WHERE id=$id");
    header('location:manage-books.php');    
    }
};

?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion livres</title>
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
<div class="container">
        <div class="row">
            <div class="col">
                <h3>GESTION DES LIVRES</h3>
            </div>
        </div>
        <div class="row">
            <div class="w-100">
                <div class="container">
                    <div class="card bg-primary text-black">
                        <h2 class="card-header">Information livre</h2>
                    </div>    
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th class="fa-border">#</th>
                                    <th class="fa-border">Titre du livre</th>
                                    <th class="fa-border">Catégorie</th>
                                    <th class="fa-border">Nom de l'auteur</th>
                                    <th class="fa-border">Numéro ISBN</th>
                                    <th class="fa-border">Prix</th>
                                    <th class="fa-border">Action</th>
                                </tr>
                            </thead>
                            <tbody class="body">
                        <?php 
                            

                            foreach ($results as $result) { ?>
                            <tr>
                                <td class="fa-border"><?= $result['id'] ?></td>
                                <td class="fa-border"><?= $result['BookName'] ?></td>
                                <td class="fa-border"><?= $result['CategoryName'] ?></td>
                                <td class="fa-border"><?= $result['AuthorName'] ?></td>
                                <td class="fa-border"><?= $result['ISBNNumber'] ?></td>
                                <td class="fa-border"><?= $result['BookPrice'] ?></td>
                                <td class="fa-border"><a href="edit-book.php?edit=<?= $result['id'] ?>"><button name="edit" class="btn btn-info">Éditer</button></a>
                                <a href="manage-books.php?suppress=<?= $result['id'] ?>"><button type="submit" name="suppress" class="btn btn-danger" onclick="return confirm('Voulez vous vraiment supprimer ce livre')">Supprimer</button></a></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
</div>
    
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
