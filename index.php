<?php 

    session_start();
    if(isset($_SESSION["email"])){
        header("Location: public/home.php");
    }


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>LBYCPG2 Bug Tracker</title>
        <?php require_once("resources/config.php"); ?>
        <?php require_once("public/templates/header.php");?>
        <?php require_once("public/templates/landing.php");?>

    <body>
        <?php 
            $var = $_SERVER["DOCUMENT_ROOT"];
            echo $var;
        ?> 
        <?php require_once("public/templates/footer.php");?>
    </body>
</html>