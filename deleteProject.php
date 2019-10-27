<?php
  require_once("Includes/db.php");
  
  PermacultureDB::getInstance()->delete_project($_POST['projectid']);
  header('Location: viewProjects.php' );
?>

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

