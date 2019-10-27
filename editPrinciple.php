<?php
/* * Start session */
require_once 'header.php';

require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);

$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);
$projectID = $_SESSION['projectid']; 

$principleDescriptionIsEmpty = false;
$principleNameIsEmpty = false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewProject.php');
        exit;
    } else if ($_POST['name'] == "") {
        $principleNameIsEmpty = true;
    } else if($_POST['description']==""){
        $principleDescriptionIsEmpty = true;
    }else if ($_POST['principleID'] == "") {
        PermacultureDB::getInstance()->insert_principle($projectID, $_POST['name'], $_POST['description']);
        header('Location: viewProject.php');
        exit;
    }else if ($_POST['principleID'] != "") {
        PermacultureDB::getInstance()->update_principle($_POST['principleID'], $_POST['name'], $_POST['description']);
        header('Location: viewProject.php');
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="<?php echo $userThemeRef?>" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Permaculture Planner: Edit Principle</title>
    </head>
    <body>
        <script src="js/validateFunctions.js"></script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $principle = array("principleID" => $_POST['principleID'],
                "name" => $_POST['name'],
                "description" => $_POST['description']);
        }else if (array_key_exists("principleID", $_GET)) {
            $principle = mysqli_fetch_array(PermacultureDB::getInstance()->get_principle_by_principle_id($_GET['principleID']));
        } else{
            $principle = array("principleID" => "", "name" => "", "description" => "");
        }
        ?>
        <form name="editPrinciple" action="editPrinciple.php" method="POST" style="text-align: center">
            <input type="hidden" name="principleID" value="<?php echo $principle['principleID']; ?>" />
            <input type="hidden" name="projectID" value="<?php echo $projectID;?>"/>
            <em>Name your Principle:</em> <input id="nameInput" type="text" name="name"  value="<?php echo $principle['name']; ?>" onkeyup="validate_name()"/><br/>
            <div id="nameTester"></div>
            <?php
            if($principleNameIsEmpty){
               echo "Please enter Principle name<br/>"; 
            }
            ?>
            <em>Describe your Principle: </em><input id = "descriptionInput" type="text" name="description" value="<?php echo $principle['description']; ?>" onkeyup="validate_description()"/><br/>
            <div id="descriptionTester"></div>
            <?php 
            if ($principleDescriptionIsEmpty){
                echo "Please enter description<br/>";
            }
            ?>
            <input type="submit" name="savePrinciple" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html>
