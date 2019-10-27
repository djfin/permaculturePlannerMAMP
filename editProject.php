<?php
/* * Start session */
require_once 'header.php';

require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);

$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);

$projectDescriptionIsEmpty = false;
$projectNameIsEmpty = false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewProjects.php');
        exit;
    } else if ($_POST['name'] == "") {
        $projectNameIsEmpty = true;
    } else if($_POST['description']==""){
        $projectDescriptionIsEmpty = true;
    }else if ($_POST['projectid'] == "") {
        $project= PermacultureDB::getInstance()->insert_project($userID, $_POST['name'], $_POST['description']);
        PermacultureDB::getInstance()->setup_principles_by_project_id($project);
        PermacultureDB::getInstance()->setup_zones_by_project_id($project);
        header('Location: viewProjects.php');
        exit;
    }else if ($_POST['projectid'] != "") {
        PermacultureDB::getInstance()->update_project($_POST['projectid'], $_POST['name'], $_POST['description']);
        header('Location: viewProjects.php');
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="<?php echo $userThemeRef?>" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Permaculture Planner: Edit Project</title>
    </head>
    <body>
        <script src="js/validateFunctions.js"> </script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $project = array("projectid" => $_POST['projectid'],
                "name" => $_POST['name'],
                "description" => $_POST['description']);    
        }else if (array_key_exists("projectid", $_GET)) {
            $project = mysqli_fetch_array(PermacultureDB::getInstance()->get_project_by_project_id($_GET['projectid']));
        } else{
            $project = array("projectid" => "", "name" => "", "description" => "");
        }
        ?>
        <form name="editProject" action="editProject.php" method="POST" style="text-align: center">
            <input type="hidden" name="projectid" value="<?php echo $project['projectid']; ?>" />
            <em>Name your project:</em> <input id = "nameInput" type="text" name="name"  value="<?php echo $project['name']; ?>" onkeyup="validate_name()"/><br/>
            <div id ="nameTester"></div>
            <?php
            if($projectNameIsEmpty){
               echo "Please enter project name<br/>"; 
            }
            ?>
            <em>Describe your project?</em> <input id="descriptionInput" type="text" name="description" value="<?php echo $project['description']; ?>" onkeyup="validate_description()"/><br/>
            <div id ="descriptionTester"></div>
            <?php 
            if ($projectDescriptionIsEmpty){
                echo "Please enter description<br/>";
            }
            ?>
            <input type="submit" name="saveProject" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html>
