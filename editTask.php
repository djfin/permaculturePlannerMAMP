<?php
/* * Start session */
require_once 'header.php';

require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);

$cropID=null;
$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);
$gardenBedID = $_SESSION['gardenBedID'];
if(array_key_exists("cropID", $_GET)){
    $cropID = $_GET['cropID'];
}

$taskDescriptionIsEmpty = false;
$taskNameIsEmpty = false;
$completeIsEmpty =false;
$completeIsInvalid=false;
$cropNameIsEmpty = false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header($_POST['backLocation']);
        exit;
    }else if ($_POST['name'] == "") {
        $taskNameIsEmpty = true;
    } else if($_POST['description']==""){
        $taskDescriptionIsEmpty = true;
    }else if ($_POST['taskID'] == "") {
        if($_POST['complete']=="0"){
            $completeConverter=1;
        }else{
            $completeConverter=0;
        }
        if($_POST['cropID']!=""){
            PermacultureDB::getInstance()->insert_task($_POST['name'], $_POST['description'], $completeConverter,$_POST['cropID']);
            header('Location:viewCrop.php');
            exit;
        }else{
            echo $_POST['cropName'];
            $newTaskCropID = PermacultureDB::getInstance()->get_crop_id_by_crop_name($_POST['cropName']);
            PermacultureDB::getInstance()->insert_task( $_POST['name'], $_POST['description'], $completeConverter, $newTaskCropID);
            header('Location: viewGardenBed.php');
            exit;
        }
    }else if ($_POST['taskID'] != "") {
        if($_POST['complete']=="0"){
            $completeConverter=1;
        }else {
            $completeConverter=0;
        }
        PermacultureDB::getInstance()->update_task($_POST['taskID'], $_POST['name'], $_POST['description'], $completeConverter);
        if($_POST['cropID']!=null){
            header('Location:viewCrop.php');
            exit;
        }else{
            header('Location: viewGardenBed.php');
            exit;
        }
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="<?php echo $userThemeRef?>" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Permaculture Planner: Edit Task</title>
    </head>
    <body>
        <script src="js/validateFunctions.js"></script>
        <?php
        if(array_key_exists("backLocation",$_GET)){
            $backLocation = 'Location: '.$_GET['backLocation'];
        }
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $task = array("taskID" => $_POST['taskID'],
                "name" => $_POST['name'],
                "description" => $_POST['description'],
                "complete" => $_POST['complete'], 
                "cropName" => $_POST['cropName']);
        }else if (array_key_exists("taskID", $_GET)) {
            $task = mysqli_fetch_array(PermacultureDB::getInstance()->get_task_by_task_id($_GET['taskID']));
            $task['cropName']=PermacultureDB::getInstance()->get_crop_name_by_crop_id($task['cropID']);
        } else{
            if($cropID!=null){
                $task = array("taskID" => "", "name" => "", "description" => "", "complete" => "", "cropName" => PermacultureDB::getInstance()->get_crop_name_by_crop_id($cropID));
            }else{
                $task = array("taskID" => "", "name" => "", "description" => "", "complete" => "", "cropName" => "");
            }
        }
        ?>
        <form name="editTask" action="editTask.php" method="POST" style="text-align: center">
            <input type="hidden" name="taskID" value="<?php echo $task['taskID']; ?>" />
            <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID;?>"/>
            <input type="hidden" name="backLocation" value="<?php echo $backLocation;?>"/>
            <em>Name your Task:</em> <input id = "nameInput" type="text" name="name"  value="<?php echo $task['name'];?>" onkeyup="validate_name()" /><br/>
            <div id="nameTester"></div>
            <em>Describe your Task: </em> <input id = "descriptionInput" type="text" name="description" value="<?php echo $task['description']; ?>" onkeyup="validate_description()"/><br/>
            <div id="descriptionTester"></div>
            <div id="cropTester"></div>
            <?php if ($task['cropName']=="")
            {
                echo "<em>What crop is this for? </em><select id='cropInput' name='cropName'>"; 
                if($cropID==null){
                    $crops = PermacultureDB::getInstance()->get_crops_by_garden_bed_id($gardenBedID);
                    while($row=mysqli_fetch_array($crops)){
                        echo "<option value='".$row['name']."'>".$row['name']."</option>";
                    }
                }
            echo "</select> <br/>";
            }else{
                echo "<em>This task is for your ".$task['cropName']."</em><br/>";
                echo "<input id='cropInput' name='cropName' type='hidden' value='".$task['cropName']."'/>";
            }
            ?>
            <em>Is this task complete?</em> <input id ="completeInput" type="checkbox" name="complete" value=<?php echo $task['complete'];?> /> <br/>  
            <div id="completeTester"></div>
            <input type="submit" name="saveTask" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
            <input type="hidden" name="cropID" value="<?php echo $cropID;?>"/>
        </form>
        <script>
                validate_complete();
        </script>
    </body>
</html>
