<!DOCTYPE html>
<html>
<head>
    <?php 
    session_start();
    require_once("../resources/config.php");
    require_once("templates/header.php"); 
    ?>
</head>
<body>
<?php

    if(!empty($_GET['bug_id'])){

        $id = htmlspecialchars($_GET['bug_id']);
        $conn = new mysqli("127.0.0.1", "root", "", "bugtracker");
        if(!$conn){
            echo "Error in database. Refer: ".$conn->error;
            die();
        } 
            $stmt = $conn->prepare("SELECT bug.*, `username` FROM bug, user WHERE bug_id=? AND email=?");
        if($stmt){
            $stmt->bind_param("is", $id, $_SESSION["email"]);
            $stmt->execute();
        } else {
            echo "Error in statement prepartion. Refer: ".$conn->error;
        }
        $bugInfo = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    } else {
        echo "Erorr in query.";
        header("Location: /public/home.php");
    }
?>
<div class="container m-5">
    <div class="row">
        <div class="card p-3">
            <div class="card-header">
                <h1 class="card-title mb-2"><i>Bug Id: <?php echo $bugInfo['bug_id']?></i></h1>
                <h6 class="card-subtitle mb-2 text-muted">Bug filed at <?php echo "".$bugInfo['created_at']." by user ".$bugInfo['username'];?></h6>
            </div>
                <h4 class='card-title text-muted mt-3'>Information</h4>
                <p class="card-text"><?php echo $bugInfo['information']; ?></p>
                <h4 class='card-title text-muted mt-3'>Hardware Information</h4>
                <p class="card-text"><?php echo $bugInfo['hardware_information']; ?></p>
        </div>
    </div>
</div>
</body>
</html>