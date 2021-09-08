<!DOCTYPE html>
<html>
<?php

$conn = new mysqli("127.0.0.1", "root", "", "bugtracker");
if (!$conn) {
    echo "<script type='text/JavaScript'> console.log('Error in DB connection.')</script>";
    die();
}
$userErr = $emailErr = $passwordErr = $confErr = $roleErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email field is empty.";
        $validate++;
    }
    if (empty($_POST["password"]) || (strcmp($_POST["password"], $_POST["passwordConf"]) != 0)) {
        $passwordErr = "Please check your password field again.";
        $validate++;
    }
    if (empty($_POST["username"])) {
        $userErr = "Username field is empty.";
        $validate++;
    }
    if ($_POST["role"] == "Role") {
        $roleErr = "Invalid role";
    }


    $stmt = $conn->prepare("SELECT `user_id` FROM user WHERE `email`=? || `username`=?");
    if ($stmt) {
        $stmt->bind_param("ss", $_POST["email"], $_POST["username"]);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_array();
        if (!empty($result)) {
            $userErr = "Not available. Either email or username already in use.";
            $validate++;
        }
    }

    $validate = 0b000;
    unset($stmt);
    if ($validate == 0b000) {
        $pass = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO user (`user_id`,`email`, `password`, `username`, `role`) VALUES (NULL, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $_POST["email"], $pass, $_POST["username"], $_POST["role"]);
            if($stmt->execute()){
                header("Location: login.php");
            } else {
                echo "<script type='text/JavaScript'>console.log('Error in execution of query.</script>";
            }
        } else {
            echo $conn->error;
        }
    }
}
?>

<head>
    <title>
        Registeration
    </title>
    <?php require_once("../resources/config.php");
    require_once("templates/header.php"); ?>
</head>
<div class="container">
    <h1>Registration</h1>
    <div class="row">
        <form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>'>
            <div class="col">
                <input type="text" class="form-control" name="username" placeholder="Username" /><?php echo '<i class="text-danger">' . $userErr . '</i>'; ?>
            </div>
            <div class="col">
                <select class="custom-select" name="role">
                    <option selected>Roles</option>
                    <option value="developer">Developer</option>
                    <option value="viewer">Viewer</option>
                </select><?php echo '<i class="text-danger">' . $roleErr . '</i>'; ?>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="email" placeholder="Email" /> <?php echo '<i class="text-danger">' . $emailErr . '</i>'; ?>
            </div>
            <div class="col-sm">
                <input type="password" class="form-control" name="password" placeholder="Password" /> <?php echo '<i class="text-danger">' . $passwordErr . '</i>'; ?>
                <input type="password" class="form-control" name="passwordConf" placeholder="Confirm Password" /> <?php echo '<i class="text-danger">' . $confErr . '</i>'; ?>
            </div>
            <div class="col-sm">
                <input type="submit" placeholder="Login" />
            </div>
        </form>
    </div>
</div>
</body>

</html>