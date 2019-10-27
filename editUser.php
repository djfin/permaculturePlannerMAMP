<?php
/* * Start session */
session_start();

echo "<h3 style='text-align:center'> Permaculture Planner </h5>";
echo "<h5 style='text-align: center'> The all in one application for learning permaculture, and managing your own garden</h5><br/>";

require_once("Includes/db.php");
if(array_key_exists('backLocation', $_GET)){
    $backLocation = $_GET['backLocation'];
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        $backLocation=$_POST['backLocation'];
        header('Location: '.$backLocation);
        exit;
    }else if($_POST['username']=="" || $_POST['password'] == "" || $_POST['email'] == "" || $_POST['lastName'] == "" || $_POST['firstName'] == ""){
        
    }else if ($_POST['userID'] == "") {
        PermacultureDB::getInstance()->insert_user($_POST['username'], $_POST['password'],$_POST['email'], $_POST['firstName'],$_POST['lastName'], $_POST['theme']);
        $logonSuccess = (PermacultureDB::getInstance()->verify_user_credentials($_POST['username'], $_POST['password']));
        if ($logonSuccess == true) {
            session_start();
            $_SESSION['user'] = $_POST['username'];
            header('Location: viewProjects.php');
            exit;
        }
    }else if ($_POST['userID'] != "") {
        PermacultureDB::getInstance()->update_user($_POST['userID'], $_POST['username'], $_POST['password'], $_POST['email'], $_POST['firstName'] ,$_POST['lastName'], $_POST['theme']);
        session_start();
        $_SESSION['user'] = $_POST['username'];
        header('Location: viewProjects.php');
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="https://www.w3.org/StyleSheets/Core/Chocolate" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Permaculture Planner: Edit Account</title>
    </head>
    <body>
        <script src="js/validateFunctions.js"></script>
        <script src="js/uiFunctions.js"></script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $user = array("userID" => $_POST['userID'],
                "username" => $_POST['username'],
                "password" => $_POST['password'],
                "email" => $_POST['email'],
                "firstName" => $_POST['firstName'],
                "lastName" => $_POST['lastName'],
                "theme" => $_POST['theme']);
        }else if (array_key_exists("user", $_GET)) {
            $user = mysqli_fetch_array(PermacultureDB::getInstance()->get_user_by_user_id($_GET['user']));
        } else{
            $user = array("userID" => "", "username" => "", "password" => "", "email" => "", "firstName" => "", "lastName" => "", "theme" => "");
        }
        ?>
        <form name="editUser" action="editUser.php" method="POST" style="text-align: center">
            <input type="hidden" name="userID" value="<?php echo $user['userID']; ?>" />
            <em>What would you like your username to be? </em><input id="usernameInput" type="text" name="username"  value="<?php echo $user['username']; ?>" onkeyup="validate_username()"/><br/>
            <div id="usernameTester"></div>
            
            <em>Type your password:</em> <input id = "passwordInput" type="password" name="password" value="<?php echo $user['password']; ?>" onkeyup="validate_password()"/><br/>
            <div id="passwordTester"></div>
            
            <em>Provide an email:</em> <input id = "emailInput" type="text" name="email" value="<?php echo $user['email']; ?>" onkeyup="validate_email()"/><br/>
            <div id="emailTester"></div>
            
            <em>What is your first name?</em> <input id = "firstNameInput" type="text" name="firstName" value="<?php echo $user['firstName']; ?>" onkeyup="validate_first_name()"/><br/>
            <div id="firstNameTester"></div>
            
            <em>What is your last name?</em> <input id = "lastNameInput" type="text" name="lastName" value="<?php echo $user['lastName']; ?>" onkeyup="validate_last_name()"/><br/>
            <div id="lastNameTester"></div>
            <em> Please select your theme: </em><select id="themeSelect" name="theme" onchange="select_style(this.value)">
                <option id="chocolateOption" value="Chocolate">Chocolate</option>
                <option id="midnightOption" value="Midnight">Midnight</option>
                <option id="modernistOption" value="Modernist">Modernist</option>
                <option id="oldstyleOption" value="Oldstyle">Oldstyle</option>
                <option id="steelyOption" value="Steely">Steely</option>
                <option id="swissOption" value="Swiss">Swiss</option>
                <option id="traditionalOption" value="Traditional">Traditional</option>
                <option id="ultramarineOption" value="Ultramarine">Ultramarine</option>
            </select>
            </br>            
            <input type="submit" name="saveUser" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
            <input type="hidden" name="backLocation" value="<?php echo $backLocation;?>"/>
        </form>
        <p id="themeTest"></p>
        <script>
                var themeName = "<?php echo $user['theme'] ?>";
                switch(themeName){
                    case "Chocolate":
                    {
                        document.getElementById("chocolateOption").selected = "selected";
                        break;
                    }
                    case "Midnight":
                    {
                        document.getElementById("midnightOption").selected = "selected";
                        break;
                    }
                    case "Modernist":
                    {
                        document.getElementById("modernistOption").selected = "selected";
                        break;
                    }
                    case "Oldstyle":
                    {
                        document.getElementById("oldstyleOption").selected = "selected";
                        break;
                    }
                    case "Steely":
                    {
                        document.getElementById("steelyOption").selected = "selected";
                        break;
                    }
                    case "Swiss":
                    {
                        document.getElementById("swissOption").selected = "selected";
                        break;
                    }
                    case "Traditional":
                    {
                        document.getElementById("traditinoalOption").selected = "selected";
                        break;
                    }
                    case "Ultramarine":
                    {
                        document.getElementById("ultramarineOption").selected = "selected";
                        break;
                    }
                }
            select_style(themeName);
        </script>
    </body>
</html>
