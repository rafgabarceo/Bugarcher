<?php 

    session_start();
    if(isset($_SESSION["username"])){
        header("Location: home.php");
    } 

?>
<!DOCTYPE html>
<head>
    <title>
        Login
    </title>
    <?php require_once("../resources/config.php");
    require_once("templates/header.php"); ?>
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <div class="row">
        <form action="<?php echo htmlspecialchars($_['PHP_SELF'])?>" method='post'>
            <div class="col-sm">
                <input type="text" name="email" placeholder="Email"/> <?php echo $emaillErr ?>
            </div>
            <div class="col-sm">
                <input type="password" name="password" placeholder="Password"/> <?php echo $passwordErr; ?>
            </div>
            <div class="col-sm">
                <input type="submit"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<?php 




?>