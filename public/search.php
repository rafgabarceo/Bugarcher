<?php 

    $result = "";
    $query = $_POST["query"];
    $conn = new mysqli("127.0.0.1", "root", "", "bugtracker");
    if($conn->connect_errno){
        echo "<script type='text/JavaScript'> console.log ('Error in DB Connection...') </script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT `bug_id`, `project_id`, `branch`, `status`, `information`, `hardware_information`, `bug`.`user_id`, `user`.`username` FROM `bug` 
            INNER JOIN `user` ON `bug`.`user_id`=`user`.`user_id` WHERE bug_id=?");

    if($stmt){
        $stmt->bind_param("i", $query);
        $stmt->execute();
    } else {
        echo "<script type='text/JavaScript'> console.log ('Error in prepared statement...') </script>";
        exit();
    }

    $results = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <?php require_once("../resources/config.php"); 
            require_once("../resources/templates/header.php");
        
        ?>
    </head>
    <body>
        <div class="row">
            <?php searchCard($results['username'], "", "", ""); ?>
        </div>
        <script src="" async defer></script>
    </body>
</html>
<?php 
    function searchCard($title, $project, $username, $id){
    echo '
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">'.$title.'</h4>
                <p class="card-text">$project</p>
            </div>
        </div> ';     
    }
?>