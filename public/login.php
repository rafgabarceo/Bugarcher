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
    <div class="grid"
    <form action="<?php echo htmlspecialchars($_['PHP_SELF'])?>" method='post'>
        <input type="text" name="email" placeholder="Email"/> 
        <input type="password" name="password" placeholder="Password"/>

    </form>
</div>
</body>
</html>