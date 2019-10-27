<?php
require_once 'header.php';


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
        <title>Permaculture Planner: Projects</title>
    </head>
     <body>
        <h3 style="text-align:center">Your Projects: </h3>
        <?php
        //$userID = PermacultureDB::getInstance()->get_user_id_by_username($_SESSION['user']);
        if(!$userID){
            exit("Error retrieving user. Please try again");
        }
        ?>
        <table style="margin-left: 30%">
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            <?php
            $result = PermacultureDB::getInstance()->get_projects_by_user_id($userID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td><em>" . htmlentities($row['name']) . "</em></td>";
                echo "<td>" . htmlentities($row['description']) . "</td>";
                $projectID= $row['projectid'];
            ?>
             <td>
                    <form name="editProject" action="editProject.php" method="GET">
                        <input type="hidden" name="projectid" value="<?php echo $projectID; ?>"/>
                        <input type="submit" name="editProject" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deleteProject" action="deleteProject.php" method="POST">
                        <input type="hidden" name="projectid" value="<?php echo $projectID; ?>"/>
                        <input type="submit" name="deleteProject" value="Delete"/>
                    </form>
                </td>
                <td>
                    <form name="ViewProject" action="viewProject.php" method="GET">
                        <input type="hidden" name="projectid" value="<?php echo $projectID; ?>"/>
                        <input type="submit" name="viewProject" value="View"/>
                    </form>
                </td>
                <?php
                echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="newProject" action="editProject.php" style="text-align:center">
            <input type="submit" value="Add New Project"/>
        </form>
    </body>
</html>
