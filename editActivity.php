<?php
/* * Start session */
require_once 'header.php';

require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);

$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);
$principleID = $_SESSION['principleID'];

$activityPromptIsEmpty = false;
$activityNameIsEmpty = false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewPrinciple.php');
        exit;
    } else if ($_POST['name'] == "") {
        $activityNameIsEmpty = true;
    } else if($_POST['prompt']==""){
        $activityPromptIsEmpty = true;
    }else if ($_POST['activityID'] == "") {
        PermacultureDB::getInstance()->insert_activity($principleID, $_POST['name'], $_POST['prompt']);
        header('Location: viewPrinciple.php');
        exit;
    }else if ($_POST['activityID'] != "") {
        PermacultureDB::getInstance()->update_activity($_POST['activityID'], $_POST['name'], $_POST['prompt']);
        header('Location: viewPrinciple.php');
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="<?php echo $userThemeRef?>" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Permaculture Planner: Edit Activity </title>
    </head>
    <body>
        <script src ="js/validateFunctions.js"></script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $activity = array("activityID" => $_POST['activityID'],
                "name" => $_POST['name'],
                "prompt" => $_POST['prompt']);
        }else if (array_key_exists("activityID", $_GET)) {
            $activity = mysqli_fetch_array(PermacultureDB::getInstance()->get_activity_by_activity_id($_GET['activityID']));
        } else{
            $activity = array("activityID" => "", "name" => "", "prompt" => "");
        }
        ?>
        <form name="editActivity" action="editActivity.php" method="POST" style="text-align: center">
            <input type="hidden" name="principleID" value="<?php echo $principleID; ?>" />
            <input type="hidden" name="activityID" value="<?php echo $activity['activityID'];?>"/>
            <em>Name your Activity:</em> <input id="nameInput" type="text" name="name"  value="<?php echo $activity['name']; ?>" onkeyup="validate_name()"/><br/>
            <div id="nameTester"></div>
            <?php
            if($activityNameIsEmpty){
               echo "Please enter Activity name<br/>"; 
            }
            ?>
            <em/>What prompt would you like to provide? </em> <input id="promptInput" type="text" name="prompt" value="<?php echo $activity['prompt']; ?>" onkeyup="validate_prompt()"/><br/>
            <div id="promptTester"></div>
            <?php 
            if ($activityPromptIsEmpty){
                echo "Please enter prompt<br/>";
            }
            ?>
            <input type="submit" name="saveActivity" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html>
