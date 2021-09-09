<?php 

    session_start();
    if(isset($_SESSION["email"])){
        header("Location: home.php");
    } 

?>

<?php 

    $emailErr = $passwordErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty($_POST["email"])){
            $emailErr = "Email field is empty.";
        }
        if(empty($_POST["password"])){
            $passwordErr = "Password field is empty. ";
        }

        if(dbCheck($_POST["email"], $_POST["password"])){
            $_SESSION["email"] = $_POST["email"]; // sets the SESSION variable.
            header("Location: home.php");
        }
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
                <input type="text" class="form-control" name="email" placeholder="Email"/> <?php echo '<i class="text-danger">'.$emailErr.'</i>'; ?>
            </div>
            <div class="col-sm">
                <input type="password" class="form-control" name="password" placeholder="Password"/> <?php echo '<i class="text-danger">'.$passwordError.'</i>'; ?>
            </div>
            <div class="col-sm">
                <input type="submit" placeholder="Login"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<?php 

    function formatData($info)
    {
        $data = trim($info);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }    

    function dbCheck($email, $password){

        $conn = new mysqli("127.0.0.1", "root", "", "bugtracker");
        $stmt = $conn->prepare("SELECT `email`, `password` FROM user WHERE `email`=?");

        if($stmt){
            $stmt->bind_param('s', $email);
            $stmt->execute();
        } else {
            echo "Error!";
        }

        $result = $stmt->get_result()->fetch_array();
        $passwordhash = $result['password'];

    $conn->close();
    return password_verify($password, $passwordhash);
    }
?>