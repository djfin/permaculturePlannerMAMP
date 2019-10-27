<?php
/* * Start session */
require_once 'header.php';
if(array_key_exists("cropID",$_SESSION)){
    $cropID=$_SESSION["cropID"];
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewGardenBed.php');
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
        <title>Permaculture Planner: View Crop</title>
    </head>
    <body>
        <?php 
        if (array_key_exists("cropID", $_GET)) {
            $cropID = $_GET['cropID'];
            $_SESSION['cropID'] = $_GET['cropID'];
        }
        ?>
        <h3 style="text-align: center">Your Tasks:</h3> 
        <?php 
        $tasksSize= PermacultureDB::getInstance()->get_tasks_size_by_crop_id($cropID);
        $taskProgress= PermacultureDB::getInstance()->get_tasks_progress_by_crop_id($cropID);
        ?>
        <h5 style="text-align: center">Your current progress: <progress value="<?php echo $taskProgress;?>" max="<?php echo $tasksSize;?>"></progress></h5>
        <table style="margin-left: 31%">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Complete?</th>
            </tr>
            <?php
            $result = PermacultureDB::getInstance()->get_tasks_by_crop_id($cropID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td><em>" . htmlentities($row['name']) . "</em></td>";
                echo "<td>" . htmlentities($row['description']) . "</td>";
                $completeString = PermacultureDB::getInstance()->translate_complete_boolean($row['complete']);
                echo "<td>" . htmlentities($completeString). "</td>";
                $taskID= $row['taskID'];
            ?>
             <td>
                    <form name="editTask" action="editTask.php" method="GET">
                        <input type="hidden" name="cropID" value="<?php echo $cropID;?>"/>
                        <input type="hidden" name="taskID" value="<?php echo $taskID; ?>"/>
                        <input type="hidden" name="backLocation" value="viewCrop.php"/>
                        <input type="submit" name="editTask" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deleteTask" action="deleteTask.php" method="POST">
                        <input type="hidden" name="taskID" value="<?php echo $taskID; ?>"/>
                        <input type="hidden" name="backLocation" value="viewCrop.php"/>
                        <input type="submit" name="deleteTask" value="Delete"/>
                    </form>
                </td>
                <?php
                echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="newTask" action="editTask.php" method="GET" style="text-align: center">
            <input type="hidden" name="cropID" value="<?php echo $cropID?>"/>
            <input type="hidden" name="backLocation" value="viewCrop.php"/>
            <input type="submit" value="Add New Task"/>
        </form>
        <form name="back" action="viewCrop.php" method="POST">
            <input type="submit" name="back" value="Back"/>
        </form>
    </body>
</html>
