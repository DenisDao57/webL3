<html>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css">
<?php
session_start();
include 'navbar/Header_nolog.php'; // On ajoute le header

?>

<body class="text-center">
<h1 style="margin-top:2%;" class="font-weight-normal">Inscription</h1>
    <form action="test/Register_test.php" method="post">
        <?php
        if (isset($_GET["mail"])){
            if ($_GET["mail"]=="f"){
                echo "<div style='color:red;'>Mail déjà utilisé!</div>";
            }
        }
        ?>
        <div class="form-group">
            <input name="mail" type="email" class="form-control" id="InputEmail1" aria-describedby="emailHelp" placeholder="E-mail">
        </div>
        <div class="form-group">
            <input name="pwd" type="password" class="form-control" id="InputPassword1" placeholder="Mot de passe">
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</body>

</html>