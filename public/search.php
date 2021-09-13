<?php 

    $result = "";
    $query = $_GET['query'];
    $conn = new mysqli("127.0.0.1", "root", "", "bugtracker");
    if($conn->connect_errno){
        echo "<script type='text/JavaScript'> console.log ('Error in DB Connection...') </script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT `bug_id`, `project_id`, `status`, `information`, `hardware_information`,`created_at`, `bug`.`user_id`, `user`.`username` FROM `bug` 
            INNER JOIN `user` ON `bug`.`user_id`=`user`.`user_id` WHERE bug_id=?");

    if($stmt){
        $stmt->bind_param("i", $query);
        $stmt->execute();
    } else {
        echo "<script type='text/JavaScript'> console.log ('Error in prepared statement...".$conn->error."') </script>";
        exit();
    }

    $results = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    print_r($results);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php require_once("../resources/config.php"); 
           require_once("templates/header.php");
        
        ?>
    </head>
    <body>
        <div class="row">
            <div class="col m-5">
                <?php searchCard($results['bug_id'], $results['information'], $results['username'], $results['created_at']); ?>
            </div>
        </div>
        <script src="" async defer></script>
    </body>
</html>
<?php 
    function searchCard($title, $project, $username, $date){
    echo '
        <div class="card" style="width: 50rem;">
            <div class="card-header">
                <a href="/public/bugPage.php/?bug_id='.$title.'"><h4 class="card-title"> Bug ID: '.$title.'</h4></a>
                <h5 class="card-subtitle text-muted"> Filed by: '.$username.' and created on '.$date.'</h5>
            </div>
            <div class="card-text p-3"> 
                <p class="card-text">'.$project.'</p>
            </div>
        </div> ';     
    }
?>