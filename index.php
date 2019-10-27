<?php 
require_once("Includes/db.php");

echo "<h3 style='text-align:center'> Permaculture Planner </h5>";
echo "<h5 style='text-align: center'> The all in one application for learning permaculture, and managing your own garden</h5><br/>";
$logonSuccess = false;

// verify user's credentials
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $logonSuccess = (PermacultureDB::getInstance()->verify_user_credentials($_POST['user'], $_POST['userpassword']));
    if ($logonSuccess == true) {
        session_start();
        $_SESSION['user'] = $_POST['user'];
        header('Location: viewProjects.php');
        exit;
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="https://www.w3.org/StyleSheets/Core/Modernist" type="text/css">
        <meta charset="UTF-8">
        <title>Permaculture Planner Login</title>
    </head>
    <body>
        <form name="logon" action="index.php" method="POST" style="text-align: center">
            Username: <input type="text" name="user"/>
            Password  <input type="password" name="userpassword"/>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (!$logonSuccess)
                    echo "Invalid name and/or password";
            }
            ?>
            <input type="submit" value="View My Projects"/>
        </form>
        <form name="newAccount" action="editUser.php" method="GET" style="text-align: center">
            Don't have an account yet? <input type="submit" value="Create Account"/>
            <input type="hidden" name="backLocation" value="index.php"/>
        </form>
    </body>
</html>

