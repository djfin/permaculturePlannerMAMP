<?php
/* * Start session */
require_once 'header.php';

require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);

$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);
$gardenBedID = $_SESSION['gardenBedID'];

$cropDescriptionIsEmpty = false;
$cropNameIsEmpty = false;
$datePlantedIsEmpty =false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewGardenBed.php');
        exit;
    } else if ($_POST['name'] == "") {
        $cropNameIsEmpty = true;
    } else if($_POST['description']==""){
        $cropDescriptionIsEmpty = true;
    }else if($_POST['datePlanted']==""){
        $datePlantedIsEmpty = true;
    }else if ($_POST['cropID'] == "") {
        PermacultureDB::getInstance()->insert_crop($gardenBedID, $_POST['name'], $_POST['description'], $_POST['datePlanted']);
        header('Location: viewGardenBed.php');
        exit;
    }else if ($_POST['cropID'] != "") {
        PermacultureDB::getInstance()->update_crop($_POST['cropID'], $_POST['name'], $_POST['description'], $_POST['datePlanted']);
        header('Location: viewGardenBed.php');
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="<?php echo $userThemeRef?>" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
         $(function() {
            $( "#datePlantedInput" ).datepicker();
         });
      </script>
        <title>Permaculture Planner: Edit Crop</title>
        
    </head>
    <body>
        <script src="js/validateFunctions.js"></script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $crop = array("cropID" => $_POST['cropID'],
                "name" => $_POST['name'],
                "description" => $_POST['description'],
                "datePlanted" => $_POST['datePlanted']);
        }else if (array_key_exists("cropID", $_GET)) {
            $crop = mysqli_fetch_array(PermacultureDB::getInstance()->get_crop_by_crop_id($_GET['cropID']));
        } else{
            $crop = array("cropID" => "", "name" => "", "description" => "", "datePlanted" => "");
        }
        ?>
        <form name="editCrop" action="editCrop.php" method="POST" style="text-align: center">
            <input type="hidden" name="cropID" value="<?php echo $crop['cropID']; ?>" />
            <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID;?>"/>
            <em>Name your Crop: </em><input id="nameInput"type="text" name="name"  value="<?php echo $crop['name']; ?>" onkeyup="validate_name()"/><br/>
            <div id="nameTester"></div>
            <?php
            if($cropNameIsEmpty){
               echo "Please enter Crop name<br/>"; 
            }
            ?>
            <em>Describe your Crop:</em>  <input id = "descriptionInput" type="text" name="description" value="<?php echo $crop['description']; ?>" onkeyup="validate_description()"/><br/>
            <div id="descriptionTester"></div>
            <?php 
            if ($cropDescriptionIsEmpty){
                echo "Please enter description<br/>";
            }
            ?>
            <em>When did you plant this Crop? </em><input id="datePlantedInput" type="text" name="datePlanted" value="<?php echo $crop['datePlanted']?>" onkeyup="validate_date_planted()"/><br/>
            <div id="datePlantedTester"></div>
            <?php 
            if($datePlantedIsEmpty){
                echo "Please enter the date you planted this crop<br/>";
            }
            ?>
            <input type="submit" name="saveCrop" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html>
