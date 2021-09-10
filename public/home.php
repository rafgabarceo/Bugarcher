<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>

<?php 

    session_start();
    require_once("../resources/config.php");
    require_once("templates/header.php");

    if(!isset($_SESSION["email"])){
        echo "<h1> You are not logged in! </h1>";
        die();
    } 

    // require_once("templates/loggedheader.php");

    $conn = new mysqli("127.0.0.1", "root", "", "bugtracker");

    if(!$conn){
        echo "Error connecting to database. Error: ".$conn->error;
        die();
    }

    // get all the user information, including the bugs that they have issued. print_r to verfy information.
    // gets the information about the user. 
    $stmt = $conn->prepare("SELECT `username`, `role` FROM user WHERE `email`=?");
    if($stmt){
        $stmt->bind_param("s", $_SESSION["email"]);
        $stmt->execute();
    } else {
        print_r($conn->error);
        var_dump($stmt);
    }

    $userDetails = $stmt->get_result()->fetch_array();
    
    unset($stmt);

    $stmt = $conn->prepare("SELECT `project_id`, `description`, `project_id` FROM project WHERE `user_id` = (SELECT `user_id` FROM user WHERE `email`=?)");
    if($stmt){
        $stmt->bind_param("s", $_SESSION["email"]);
        $stmt->execute();
    } else {
        var_dump($stmt);
    }

    $projectDetails = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    unset($stmt);

    $stmt = $conn->prepare("SELECT `bug_id`, `created_at`, `project_id`, `status`, `title` FROM bug WHERE `user_id` = (SELECT `user_id` FROM user WHERE `email`=?)");
    if($stmt){
        $stmt->bind_param("s", $_SESSION["email"]);
        $stmt->execute();
    } else {
        print_r($conn->error);
    }
    
    $bugDetails = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    unset($stmt);

    $conn->close();
?>
<body>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <?php echo '<h1>'.$userDetails['username'].'</h1>';?>
                    </div>
                    <div class="card-content p-3">
                        This is where various options such as add new bug, etc etc should be.
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card p-2">
                    <div class="card-header mb-2">
                        <h1>
                            <?php echo "Logged in at ".date('Y/m/d @ h:i:sa')."";?>
                        </h1>
                    </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h2>
                                    Projects
                                </h2>
                            </div>
                            <div class="card-content p-3">
                                The list of projects go here. 
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h2>
                                    Bugs
                                </h2>
                            </div>
                            <div class="card-content p-3">
                                The list of recent bugs go here.
                            </div>
                        </div>
                    </div>
                </div>
                </div> 
            </div>
        </div>
    </div>
</body>
</html>