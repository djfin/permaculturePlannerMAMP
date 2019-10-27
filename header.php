<?php
session_start();
require_once 'Includes/db.php';
if (array_key_exists("user", $_SESSION)) {
    $user = $_SESSION['user'];
    $userID = PermacultureDB::getInstance()->get_user_id_by_username($user);
    $pageName = basename($_SERVER['PHP_SELF']);
    echo <<<_INIT
    <h5 style='text-align:left'> Logged in as: $user </h5>
    <table style='text-align:left'>        
        <tr><td><form name="logoutForm" action="logout.php">
            <input type="submit" name="logoutButton" value="Logout"/>
        </form></td>
        <td><form name="editUser" action="editUser.php" method="GET" style="text-align: left">
            <input type="submit" value="Edit Account"/>
            <input type="hidden" name="user" value="$userID"/>
            <input type="hidden" name="backLocation" value="$pageName"/>
        </form></td></tr>
    </table>
    <h3 style='text-align:center'> Permaculture Planner </h3>
    <h5 style='text-align:center'> The all in one application for learning permaculture and managing your own garden</h5><br/>
_INIT;
} else {
    header('Location: index.php');
    exit;
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

