<?php
/* * Start session 
session_start();
if (!array_key_exists("user", $_SESSION)) {
    header('Location: index.php');
    exit;
}*/
require 'header.php';
if(array_key_exists("projectid",$_SESSION)){
    $projectID=$_SESSION["projectid"];
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: viewProjects.php');
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
        <title>Permaculture Planner: View Project</title>
    </head>
    <body>
        <?php 
        if (array_key_exists("projectid", $_GET)) {
            $projectID = $_GET['projectid'];
            $_SESSION['projectid'] = $_GET['projectid'];
        }
        ?>
        <h3 style="text-align: center" >Your Zones: </h3></br>
        <table border="black" align="center">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Notes</th>
            </tr>
            <?php
            $result = PermacultureDB::getInstance()->get_zones_by_project_id($projectID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td><em>" . htmlentities($row['name']) . "</em></td>";
                echo "<td>" . htmlentities($row['description']) . "</td>";
                echo "<td>".htmlentities($row['notes'])."</td>";
                $zoneID= $row['zoneid'];
            ?>
             <td>
                    <form name="editZone" action="editZone.php" method="GET">
                        <input type="hidden" name="projectid" value="<?php echo $projectID?>"/>
                        <input type="hidden" name="zoneid" value="<?php echo $zoneID; ?>"/>
                        <input type="submit" name="editZone" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deleteZone" action="deleteZone.php" method="POST">
                        <input type="hidden" name="zoneid" value="<?php echo $zoneID; ?>"/>
                        <input type="submit" name="deleteZone" value="Delete"/>
                    </form>
                </td>
                <td>
                    <form name="ViewZone" action="viewZone.php" method="GET">
                        <input type="hidden" name="zoneid" value="<?php echo $zoneID; ?>"/>
                        <input type="submit" name="viewZone" value="View"/>
                    </form>
                </td>
                <?php
                echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="newZone" action="editZone.php" method="GET" style="text-align: center">
            <input type="hidden" name="projectid" value="<?php echo $projectID?>"/>
            <input type="submit" value="Add new Zone"/>
        </form>
        </br>
        <h3 style="text-align: center">Your Principles:</h3> </br>
        <table border="black">
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            <?php
            $result = PermacultureDB::getInstance()->get_principles_by_project_id($projectID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td><em>" . htmlentities($row['name']) . "</em></td>";
                echo "<td>" . htmlentities($row['description']) . "</td>";
                $principleID= $row['principleID'];
            ?>
             <td>
                    <form name="editPrinciple" action="editPrinciple.php" method="GET">
                        <input type="hidden" name="principleID" value="<?php echo $principleID; ?>"/>
                        <input type="submit" name="editPrinciple" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deletePrinciple" action="deletePrinciple.php" method="POST">
                        <input type="hidden" name="principleID" value="<?php echo $zoneID; ?>"/>
                        <input type="submit" name="deletePrinciple" value="Delete"/>
                    </form>
                </td>
                <td>
                    <form name="viewPrinciple" action="viewPrinciple.php" method="GET">
                        <input type="hidden" name="principleID" value="<?php echo $principleID; ?>"/>
                        <input type="submit" name="viewPrinciple" value="View"/>
                    </form>
                </td>
                <?php
                echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="newPrinciple" action="editPrinciple.php" method="GET" style="text-align: center">
            <input type="hidden" name="projectid" value="<?php echo $projectID?>"/>
            <input type="submit" value="Add new Principle"/>
        </form>
        <form name="back" action="viewProject.php" method="POST">
            <input type="submit" name="back" value="Back"/>
        </form>
    </body>
</html>
