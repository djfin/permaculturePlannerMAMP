<?php
/* * Start session */
require_once 'header.php';

require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);

$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);
$projectID = $_SESSION['projectid'];

$zoneDescriptionIsEmpty = false;
$zoneNameIsEmpty = false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewProject.php');
        exit;
    } else if ($_POST['name'] == "") {
        $zoneNameIsEmpty = true;
    } else if($_POST['description']==""){
        $zoneDescriptionIsEmpty = true;
    }else if ($_POST['zoneid'] == "") {
        PermacultureDB::getInstance()->insert_zone($projectID, $_POST['name'], $_POST['description'], $_POST['notes']);
        header('Location: viewProject.php');
        exit;
    }else if ($_POST['zoneid'] != "") {
        PermacultureDB::getInstance()->update_zone($_POST['zoneid'], $_POST['name'], $_POST['description'], $_POST['notes']);
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
        <title>Permaculture Planner: Edit Zone</title>
    </head>
    <body>
        <script src="js/validateFunctions.js"></script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $zone = array("zoneid" => $_POST['zoneid'],
                "name" => $_POST['name'],
                "description" => $_POST['description'],
                "notes" => $_POST['notes']);
        }else if (array_key_exists("zoneid", $_GET)) {
            $zone = mysqli_fetch_array(PermacultureDB::getInstance()->get_zone_by_zone_id($_GET['zoneid']));
        } else{
            $zone = array("zoneid" => "", "name" => "", "description" => "", "notes" => "");
        }
        ?>
        <form name="editZone" action="editZone.php" method="POST" style="text-align: center">
            <input type="hidden" name="zoneid" value="<?php echo $zone['zoneid']; ?>" />
            <input type="hidden" name="projectID" value="<?php echo $projectID;?>"/>
            <em>Name your zone:</em> <input id="nameInput" type="text" name="name"  value="<?php echo $zone['name']; ?>" onkeyup="validate_name()"/></br>
            <div id="nameTester"></div>
            <?php
            if($zoneNameIsEmpty){
               echo "Please enter Zone name<br/>"; 
            }
            ?>
            <em>Describe your Zone:</em> <input id="descriptionInput" type="text" name="description" value="<?php echo $zone['description']; ?>" onkeyup="validate_description()"/><br/>
            <div id="descriptionTester"></div>
            <?php 
            if ($zoneDescriptionIsEmpty){
                echo "Please enter description<br/>";
            }
            ?>
            <em> Are there any notes you want to add about this zone?</em><input id="notesInput" type="text" name="notes" value="<?php echo $zone['notes'];?>"/><br/>
            <input type="submit" name="saveZone" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html>
