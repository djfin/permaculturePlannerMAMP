<?php
/* * Start session */
require_once 'header.php';
if(array_key_exists("zoneid",$_SESSION)){
    $zoneID=$_SESSION["zoneid"];
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
        <title>Permaculture Planner: View Zone</title>
    </head>
    <body>
        <?php 
        if (array_key_exists("zoneid", $_GET)) {
            $zoneID = $_GET['zoneid'];
            $_SESSION['zoneid'] = $_GET['zoneid'];
        }
        ?>
        <h3 style="text-align: center">Your Garden Beds: </h3>
        <table style="margin-left: 30%">
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            <?php
            $result = PermacultureDB::getInstance()->get_garden_beds_by_zone_id($zoneID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td><em>" . htmlentities($row['name']) . "</em></td>";
                echo "<td>" . htmlentities($row['description']) . "</td>";
                $gardenBedID= $row['gardenBedID'];
            ?>
             <td>
                    <form name="editGardenBed" action="editGardenBed.php" method="GET">
                        <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID;?>"/>
                        <input type="hidden" name="zoneid" value="<?php echo $zoneID; ?>"/>
                        <input type="submit" name="editGardenBed" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deleteGardenBed" action="deleteGardenBed.php" method="POST">
                        <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID; ?>"/>
                        <input type="submit" name="deleteGardenBed" value="Delete"/>
                    </form>
                </td>
                <td>
                    <form name="ViewGardenBed" action="viewGardenBed.php" method="GET">
                        <input type="hidden" name="gardenBedID" value="<?php echo $gardenBedID; ?>"/>
                        <input type="submit" name="viewGardenBed" value="View"/>
                    </form>
                </td>
                <?php
                echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="newGardenBed" action="editGardenBed.php" method="GET" style="text-align: center">
            <input type="hidden" name="zoneid" value="<?php echo $zoneID?>"/>
            <input type="submit" value="Add new Garden Bed"/>
        </form>
        <form name="back" action="viewZone.php" method="POST">
            <input type="submit" name="back" value="Back"/>
        </form>
    </body>
</html>