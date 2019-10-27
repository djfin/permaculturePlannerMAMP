<?php
/* * Start session */
require_once 'header.php';
if(array_key_exists("gardenBedID",$_SESSION)){
    $gardenBedID=$_SESSION["gardenBedID"];
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewZone.php');
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
        <title>Permaculture Planner: View Garden Bed</title>
    </head>
    <body>
        <?php 
        if (array_key_exists("gardenBedID", $_GET)) {
            $gardenBedID = $_GET['gardenBedID'];
            $_SESSION['gardenBedID'] = $_GET['gardenBedID'];
        }
        ?>
        <h3 style="text-align: center">Your Crops: </h3>
        <table style="margin-left: 30%">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Date Planted</th>
            </tr>
            <?php
            $result = PermacultureDB::getInstance()->get_crops_by_garden_bed_id($gardenBedID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td><em>" . htmlentities($row['name']) . "</em></td>";
                echo "<td>" . htmlentities($row['description']) . "</td>";
                echo "<td>" . htmlentities($row['datePlanted']). "</td>";
                $cropID= $row['cropID'];
            ?>
             <td>
                    <form name="editCrop" action="editCrop.php" method="GET">
                        <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID;?>"/>
                        <input type="hidden" name="cropID" value="<?php echo $cropID; ?>"/>
                        <input type="submit" name="editCrop" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deleteCrop" action="deleteCrop.php" method="POST">
                        <input type="hidden" name="cropID" value="<?php echo $cropID; ?>"/>
                        <input type="submit" name="deleteCrop" value="Delete"/>
                    </form>
                </td>
                <td>
                    <form name="ViewCrop" action="viewCrop.php" method="GET">
                        <input type="hidden" name="cropID" value="<?php echo $cropID; ?>"/>
                        <input type="submit" name="viewCrop" value="View"/>
                    </form>
                </td>
                <?php
                echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="newCrop" action="editCrop.php" method="GET" style="text-align: center">
            <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID?>"/>
            <input type="submit" value="Add New Crop"/>
        </form>
        </br>
        <h3 style="text-align: center">Your Tasks: </h3>
        <?php 
        $tasksSize= PermacultureDB::getInstance()->get_tasks_size_by_garden_bed_id($gardenBedID);
        $taskProgress= PermacultureDB::getInstance()->get_tasks_progress_by_garden_bed_id($gardenBedID);
        ?>
        <h5 style="text-align: center">Your current progress: <progress value="<?php echo $taskProgress;?>" max="<?php echo $tasksSize;?>"></progress> </h3>
        <table style="margin-left: 25%">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Complete?</th>
                <th>Crop</th>
            </tr>
            <?php
            $result = PermacultureDB::getInstance()->get_tasks_by_garden_bed_id($gardenBedID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td><em>" . htmlentities($row['name']) . "</em></td>";
                echo "<td>" . htmlentities($row['description']) . "</td>";
                $completeString = PermacultureDB::getInstance()->translate_complete_boolean($row['complete']);
                echo "<td>" . htmlentities($completeString). "</td>";
                $cropName = PermacultureDB::getInstance()->get_crop_name_by_crop_id($row['cropID']);
                echo "<td>" . htmlentities($cropName)."</td>";
                $taskID= $row['taskID'];
            ?>
             <td>
                    <form name="editTask" action="editTask.php" method="GET">
                        <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID;?>"/>
                        <input type="hidden" name="taskID" value="<?php echo $taskID; ?>"/>
                        <input type="hidden" name="backLocation" value="viewGardenBed.php"/>
                        <input type="submit" name="editTask" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deleteTask" action="deleteTask.php" method="POST">
                        <input type="hidden" name="taskID" value="<?php echo $taskID; ?>"/>
                        <input type="hidden" name="backLocation" value="viewGardenBed.php"/>
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
            <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID?>"/>
            <input type="hidden" name="backLocation" value="viewGardenBed.php"/>
            <input type="submit" value="Add New Task"/>
        </form>
        <form name="back" action="viewGardenBed.php" method="POST">
            <input type="submit" name="back" value="Back"/>
        </form>
    </body>
</html>
