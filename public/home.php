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
        echo "<h1> You are not logged in! </h2>";
        die();
    } 
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

        $stmt = $conn->prepare("SELECT `project_id`, `title`,`description`, `project_id` FROM project WHERE `user_id` = (SELECT `user_id` FROM user WHERE `email`=?)");
        if($stmt){
            $stmt->bind_param("s", $_SESSION["email"]);
            $stmt->execute();
        } else {
            var_dump($stmt);
        }

        $projectDetails = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        unset($stmt);

        $stmt = $conn->prepare("SELECT `bug_id`, `information`,`created_at`, `project_id`, `status`, `title`, `closed_at` FROM bug WHERE `user_id` = (SELECT `user_id` FROM user WHERE `email`=?)");
        if($stmt){
            $stmt->bind_param("s", $_SESSION["email"]);
            $stmt->execute();
        } else {
            print_r($conn->error);
        }
        
        $bugDetails = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        unset($stmt);

    $bugTitleErr = $hwinfoErr = $infoErr = "";

    // require_once("templates/loggedheader.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $validation = 0b000;
        if(empty($_POST["project_id"])){
            $bugTitleErr = "Required";
            $validation++;
        } 
        if(empty($_POST["hardware_information"])){
            $hwinfoErr = "Required";
            $validation++;
        } 
        if(empty($_POST["title"])){
            $infoErr = "Required";
            $validation++;
        }

        if($validation == 0b000){
            $stmt = $conn->prepare("INSERT INTO bug (`project_id`, `status`, `information`, `hardware_information`, `user_id`, `created_at`, `title`)
            VALUES (?, 'open', ?, ?, (SELECT `user_id` FROM user WHERE `email`=? ), (SELECT CURRENT_TIMESTAMP()), ?)");
            if($stmt){
                $stmt->bind_param("issss", $_POST["project_id"], $_POST["information"], $_POST["hardware_information"], $_SESSION["email"], $_POST["title"]);
                echo '<script type="text/JavaScript"> console.log("Executing db statement") </script>';
                $stmt->execute();
            } else {
                print_r($conn->error);
                die();
            }
        }
    } else {
        

    }
    $conn->close();
?>
<body>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <?php echo '<h1>'.$userDetails['username'].'</h1>';?>
                        <a href="https://google.com"><button type="button" class="btn btn-sm btn-primary">New Project</button></a>
                    </div>
                    <div class="card-content p-3">
                        <div class="row">
                            <div class="col">
                                <h3 class="d-inline">Report bug</h3> <?php echo "<i class='d-inline text-danger'>".$bugTitleErr."</i>"?> 
                            </div>
                            <div class="col">
                                <h3 class="d-inline">Project</h3>
                            </div>
                        </div>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method='post'>
                        <div class="row">
                                <div class="col">
                                    <input type="text" name="title" class="form-control form-control-lg" placeholder="New Bug Title"/>
                                </div>
                                <div class="col">
                                    <?php 
                                    
                                        echo "<select class='form-control' name='project_id'>"; 

                                        foreach($projectDetails as $project){
                                            echo "<option value='".$project["project_id"]."'>".$project["title"]."</option>";
                                        }

                                        echo "</select>";
                                        
                                    ?>
                                </div>
                            <div class="row p-3">
                                <div class="col">
                                    <h3 class="d-inline">Hardware information</h3> <?php echo "<i class='d-inline text-danger'>".$hwinfoErr."</i>"?>
                                    <textarea type="text" name ="hardware_information" class="form-control" rows=5 placeholder="Hardware information. Include operating system, etc. etc."></textarea> 
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col">
                                    <h3 class='d-inline'>
                                        Information
                                    </h3> <?php echo "<i class='d-inline text-danger'>".$infoErr."</i>"?>
                                    <textarea type="text" name="information" class="form-control" rows=10 placeholder="Bug information"></textarea> 
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary" value="File bug"/>
                            </form> 
                        </div>
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
                                <?php 
                                        echo "<ul class='list-group list-group-flush'>";
                                        foreach($projectDetails as $project){
                                            echo "<li class='list-group-item' data-toggle='tooltip' data-placement='top' title='".$project['description']."'>".$project['title']."</li>";
                                        }
                                        echo "</ul>";
                                
                                ?> 
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h2>
                                        Bugs
                                    </h2>
                                </div>
                                <div class="card-content p-3">
                                    <?php 
                                            foreach($bugDetails as $bug){
                                                echo "<div class='container-fluid'>";
                                                echo "<li class='list-group-item' data-toggle='tooltip' data-placement='top' title='".$bug['information']."'><h5 class='p-3'>".$bug['title']."</h5>";
                                                if($bug["status"] == "open"){
                                                    echo "<button type='button' class='btn btn-warning m-2' onclick='window.open(`/public/bugPage.php/?bug_id=".$bug['bug_id']."`)'>Open</button>";
                                                    echo "<button type='button' class='btn btn-secondary m-2'>Belongs to project #".$bug["project_id"]."</button>";
                                                    echo "<i>Bug filed at ".$bug["created_at"]."</i>";
                                                } else {
                                                    echo "<button type='button' class='btn btn-success ml-auto' '>Closed</button>";
                                                    echo "<i>Bug filed at ".$bug["created_at"]." and closed at ".$bug["closed_at"]."</i>";
                                                }
                                                echo "</li></div>";
                                            }
                                    
                                    ?>
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