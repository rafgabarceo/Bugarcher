<?php 

    session_start();
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
    print_r($userDetails);
    
    unset($stmt);

    $stmt = $conn->prepare("SELECT `project_id`, `description`, `project_id` FROM project WHERE `user_id` = (SELECT `user_id` FROM user WHERE `email`=?)");
    if($stmt){
        $stmt->bind_param("s", $_SESSION["email"]);
        $stmt->execute();
    } else {
        var_dump($stmt);
    }

    $projectDetails = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    print_r($projectDetails);

    unset($stmt);

    $stmt = $conn->prepare("SELECT `bug_id`, `created_at`, `project_id`, `status`, `title` FROM bug WHERE `user_id` = (SELECT `user_id` FROM user WHERE `email`=?)");
    if($stmt){
        $stmt->bind_param("s", $_SESSION["email"]);
        $stmt->execute();
    } else {
        print_r($conn->error);
    }
    
    $bugDetails = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo "<br/><br/>";
    foreach($bugDetails as $detail){
        print_r($detail);
    }

    unset($stmt);
?>