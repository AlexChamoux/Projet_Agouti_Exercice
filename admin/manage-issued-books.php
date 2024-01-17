<?php
session_start();

include('includes/config.php');

if (!strlen($_SESSION['message']) == 0) {
    $message = $_SESSION['message'];
    echo "<script> window.addEventListener('load', () => {
        alert('" . $message . "');
        event.preventDefault();
        })</script>";
    $_SESSION['message'] = '';
}


$query = " SELECT tibkdt.id, trd.FullName, tbk.BookName, tbk.ISBNNumber, tibkdt.IssuesDate, tibkdt.ReturnDate, tibkdt.ReturnStatus
            FROM tblissuedbookdetails tibkdt
            JOIN tblreaders trd ON tibkdt.ReaderId = trd.ReaderId
            JOIN tblbooks tbk ON tibkdt.BookId = tbk.id";
$stmt = $dbh->prepare($query);
$stmt->execute();

$results = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des sorties</title>
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
     <!-- CONTENT-WRAPPER SECTION END-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>GESTION DES SORTIES</h3>
            </div>
        </div>
        <div class="row">
            <div class="w-100">
                <div class="container">
                    <div class="card bg-primary text-black">
                        <h2 class="card-header">Sorties</h2>
                    </div>
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th class="fa-border">#</th>
                                    <th class="fa-border">Lecteur</th>
                                    <th class="fa-border">Titre</th>
                                    <th class="fa-border">ISBN</th>
                                    <th class="fa-border">Sortie le</th>
                                    <th class="fa-border">Retourné le</th>
                                    <th class="fa-border">Action</th>
                                </tr>
                            </thead>
                            <tbody class="body">
                        <?php
                            

                            foreach ($results as $result) { ?>
                            <tr>
                                <td class="fa-border"><?= $result['id'] ?></td>
                                <td class="fa-border"><?= $result['FullName'] ?></td>
                                <td class="fa-border"><?= $result['BookName'] ?></td>
                                <td class="fa-border"><?= $result['ISBNNumber'] ?></td>
                                <td class="fa-border"><?= $result['IssuesDate'] ?></td>
                                <td class="fa-border"><?= ($result['ReturnDate'] && $result['ReturnStatus'] == 1) ? $result['ReturnDate'] : 'Non retourné' ?></td>
                                <td class="fa-border"><a href="edit-issue-book.php?edit=<?= $result['id'] ?>"><button name="edit" class="btn btn-info">Éditer</button></a>
                                </td>
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

