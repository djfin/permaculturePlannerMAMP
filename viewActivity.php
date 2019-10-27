<?php
/* * Start session */
require_once 'header.php';
require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);


$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewPrinciple.php');
        exit;
    } else if ($_POST['activityID'] != "") {
        if($_POST['complete']=="0"){
            $completeConverter=1;
        }else {
            $completeConverter=0;
        }
        echo $completeConverter;
        PermacultureDB::getInstance()->update_activity_response_by_id($_POST['activityID'],$_POST['response'], $completeConverter);
        header('Location: viewPrinciple.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <link id="styleID" rel="stylesheet" href="<?php echo $userThemeRef?>" type="text/css">
        <meta charset="UTF-8">
        <title>Permaculture Planner: View Activity</title>
    </head>
    <body>
        <script src="js/validateFunctions.js"></script>
        <?php
        if(array_key_exists("activityID", $_GET)){
            $activity = mysqli_fetch_array(PermacultureDB::getInstance()->get_activity_by_activity_id($_GET['activityID']));
        }else{
            echo "error getting activity";
        }
        echo "<h3 style='text-align:center'>".$activity['name']."</h3>";
        echo "<h5>Prompt: </h5><h6>".$activity['prompt']."</h6>";
        ?>
        <form name="activityResponse" action="viewActivity.php" method="POST" style="text-align: center">
            <input type="hidden" name="activityID" value="<?php echo $activity['activityID']?>"/>
            <h5 style="text-align: center">Mark as complete? <input id ="completeInput" type="checkbox" name="complete" value=<?php echo $activity['complete'];?> /> </h5>
            <textarea name="response" cols ="70" rows="20"><?php echo $activity['response']?></textarea><br>
            <input type="submit" name="saveRespone" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
        <script>
            validate_complete();
        </script>
    </body>
</html>
