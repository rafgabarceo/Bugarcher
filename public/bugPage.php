<!-- I'm planning to make the query of comments simple. To reduce the workload, the query will simply be sorted by the date then outputted as cards in the program.

Here is a good example of what we are trying to emulate: https://bugzilla.redhat.com/show_bug.cgi?id=1855976

-->
<!DOCTYPE html>
<html>
<head>
    <title>Bug page</title>
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
        $_SESSION['prevBug'] = $bugInfo;

        unset($stmt);
        
    } elseif(isset($_SESSION['prevBug'])){
        $bugInfo = $_SESSION['prevBug'];
    } else {
       echo "Erorr in query.";
       header("Location: /public/home.php");
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['description'])){
        $conn = new mysqli("127.0.0.1", "root", "", "bugtracker");
        if(!$conn){
            echo "Error in connection to DB in post request. Aborting due to error: ".$conn->error;
            die();
        } 
        $stmt = $conn->prepare("INSERT INTO comment (`description`, `user_id`, `bug_id`, `created_at`) VALUES (?, (SELECT user_id FROM user WHERE email=?), ?, (SELECT CURRENT_TIMESTAMP()))");
        if($stmt){
            $stmt->bind_param("ssi", $_POST["description"], $_SESSION['email'], $bugInfo["bug_id"]);
            $stmt->execute();
        } else {
            echo "Error in statement preparation. Error: ".$conn->error;
        }
    }


?>
<div class="container-fluid p-5">
    <div class="row">
        <div class="col col-lg-9">
            <div class="card p-3 mb-3">
                <div class="card-header">
                    <h1 class="card-title mb-2"><i>Bug Id: <?php echo $bugInfo['bug_id']?></i></h1>
                    <h6 class="card-subtitle mb-2 text-muted">Bug filed at <?php echo "".$bugInfo['created_at']." by user ".$bugInfo['username'];?></h6>
                </div>
                    <h4 class='card-title text-muted mt-3'>Information</h4>
                    <p class="card-text"><?php echo $bugInfo['information']; ?></p>
                    <h4 class='card-title text-muted mt-3'>Hardware Information</h4>
                    <p class="card-text"><?php echo $bugInfo['hardware_information']; ?></p>
            </div>
                <?php 

                    $stmt = $conn->prepare("SELECT * FROM comment WHERE bug_id=?");
                    if($stmt){
                        $stmt->bind_param("i", $bugInfo['bug_id']);
                        $stmt->execute();
                    } else {
                        echo "Error in statement prepation. Refer: ".$conn->error;
                    }


                    $comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    
                    unset($stmt);

                    foreach($comments as $comment){
                        $stmt = $conn->prepare("SELECT username FROM user WHERE `user_id`=?");
                        $userId = $comment['user_id'];
                        if($stmt){
                            $stmt->bind_param("i", $userId);
                            $stmt->execute();
                        } else {
                            echo "Error in statement preparation.";
                        }
                        $username = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);

                        echo "<div class='card mb-3'>
                            <div class='card-header'>
                                ".$username['username']." 
                            </div>
                            <div class='card-text p-3'>
                                ".$comment['description']."
                            </div>
                        
                        
                        </div>";
                    }
                ?>
        </div>
        <div class="col">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method='post'>
            <div class="card position-fixed">
                <div class="card-header mb-3">
                    <h4 class="card-title p-3 mt-2">New Comment</h4>
                </div>
                <textarea class='m-2' wrap='hard' rows='10' name='description'></textarea>
            <?php 
            
                    if(!isset($_SESSION['email'])){
                        echo "<input class='btn btn-primary m-2' value='Login to comment' disabled></input>";
                    } else {

                        echo "<input class='btn btn-primary m-2' type='submit' value='Comment'></input>";
                    }
            
            ?>
            </div>
            </form>
        </div>
    </div>
    <div class="row">
        
    </div>
</div>
</body>
</html>