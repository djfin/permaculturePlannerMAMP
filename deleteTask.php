<?php
  require_once("Includes/db.php");
  
  PermacultureDB::getInstance()->delete_task($_POST['taskID']);
  header('Location: '.$_POST['backLocation'] );
?>

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

