<?php
/* * Start session */
require_once 'header.php';

require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);

$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);
$zoneID = $_SESSION['zoneid'];

$gardenBedDescriptionIsEmpty = false;
$gardenBedNameIsEmpty = false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewZone.php');
        exit;
    } else if ($_POST['name'] == "") {
        $gardenBedNameIsEmpty = true;
    } else if($_POST['description']==""){
        $gardenBedDescriptionIsEmpty = true;
    }else if ($_POST['gardenBedID'] == "") {
        PermacultureDB::getInstance()->insert_garden_bed($zoneID, $_POST['name'], $_POST['description']);
        header('Location: viewZone.php');
        exit;
    }else if ($_POST['gardenBedID'] != "") {
        PermacultureDB::getInstance()->update_garden_bed($_POST['gardenBedID'], $_POST['name'], $_POST['description']);
        header('Location: viewZone.php');
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="<?php echo $userThemeRef?>" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Permaculture Planner: Edit Garden Bed</title>
    </head>
    <body>
        <script src="js/validateFunctions.js"></script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $gardenBed = array("gardenBedID" => $_POST['gardenBedID'],
                "name" => $_POST['name'],
                "description" => $_POST['description']);
        }else if (array_key_exists("gardenBedID", $_GET)) {
            $gardenBed = mysqli_fetch_array(PermacultureDB::getInstance()->get_garden_bed_by_garden_bed_id($_GET['gardenBedID']));
        } else{
            $gardenBed = array("gardenBedID" => "", "name" => "", "description" => "");
        }
        ?>
        <form name="editGardenBed" action="editGardenBed.php" method="POST" style="text-align: center">
            <input type="hidden" name="zoneid" value="<?php echo $zoneID; ?>" />
            <input type="hidden" name="gardenBedID" value="<?php echo $gardenBed['gardenBedID'];?>"/>
            <em>Name your Garden Bed:</em> <input id="nameInput" type="text" name="name"  value="<?php echo $gardenBed['name']; ?>" onkeyup="validate_name()"/><br/>
            <div id="nameTester"></div>
            <?php
            if($gardenBedNameIsEmpty){
               echo "Please enter Garden Bed name<br/>"; 
            }
            ?>
            <em>Describe your Garden Bed:</em>  <input id ="descriptionInput"type="text" name="description" value="<?php echo $gardenBed['description']; ?>" onkeyup="validate_description()"/><br/>
            <div id="descriptionTester"></div>
            <?php 
            if ($gardenBedDescriptionIsEmpty){
                echo "Please enter description<br/>";
            }
            ?>
            <input type="submit" name="saveGardenBed" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html>
