<?php
  require_once("Includes/db.php");
  
  PermacultureDB::getInstance()->delete_activity($_POST['activityID']);
  header('Location: viewPrinciple.php' );
?>

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

