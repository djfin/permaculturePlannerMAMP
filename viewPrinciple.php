<?php
/* * Start session */
require_once 'header.php';
if(array_key_exists("principleID",$_SESSION)){
    $principleID=$_SESSION["principleID"];
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewProject.php');
        exit;
    }
}

require_once("Includes/db.php");
$userTheme = PermacultureDB::getInstance()->get_theme_by_username($_SESSION['user']);
$userThemeRef = PermacultureDB::getInstance()->get_style($userTheme);
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
        <title>Permaculture Planner: View Principle</title>
    </head>
    <body>
        <?php 
        if (array_key_exists("principleID", $_GET)) {
            $principleID = $_GET['principleID'];
            $_SESSION['principleID'] = $_GET['principleID'];
        }
        ?>
        <h3 style="text-align: center">Your Activities:</h3> 
         <?php 
        $activitiesSize= PermacultureDB::getInstance()->get_activities_size_by_principle_id($principleID);
        $activitiesProgress= PermacultureDB::getInstance()->get_activities_progress_by_principle_id($principleID);
        ?>
        <h5 style="text-align: center">Your current progress:<h7> <progress value="<?php echo $activitiesProgress;?>" max="<?php echo $activitiesSize;?>"></progress></h5>
        <table border="black">
            <tr>
                <th>Name</th>
                <th>Prompt</th>
                <th>Complete?</th>
            </tr>
            <?php
            $result = PermacultureDB::getInstance()->get_activities_by_principle_id($principleID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td><em>" . htmlentities($row['name']) . "</em></td>";
                echo "<td>" . htmlentities($row['prompt']) . "</td>";
                $completeString = PermacultureDB::getInstance()->translate_complete_boolean($row['complete']);
                echo "<td>" . htmlentities($completeString). "</td>";
                $activityID= $row['activityID'];
            ?>
             <td>
                    <form name="editActivity" action="editActivity.php" method="GET">
                        <input type="hidden" name="principleID" value="<?php echo $principleID;?>"/>
                        <input type="hidden" name="activityID" value="<?php echo $activityID; ?>"/>
                        <input type="submit" name="editActivity" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deleteActivity" action="deleteActivity.php" method="POST">
                        <input type="hidden" name="activityID" value="<?php echo $activityID; ?>"/>
                        <input type="submit" name="deleteActivity" value="Delete"/>
                    </form>
                </td>
                <td>
                    <form name="viewActivity" action="viewActivity.php" method="GET">
                        <input type="hidden" name="activityID" value="<?php echo $activityID; ?>"/>
                        <input type="submit" name="viewActivity" value="View and Respond"/>
                    </form>
                </td>
                <?php
                echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="newActivity" action="editActivity.php" method="GET" style="text-align: center">
            <input type="hidden" name="principleID" value="<?php echo $principleID?>"/>
            <input type="submit" value="Add new Activity"/>
        </form>
                <form name="back" action="viewPrinciple.php" method="POST">
            <input type="submit" name="back" value="Back"/>
        </form>
    </body>
</html>