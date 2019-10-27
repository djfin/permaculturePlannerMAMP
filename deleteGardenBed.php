<?php
  require_once("Includes/db.php");
  
  PermacultureDB::getInstance()->delete_garden_bed($_POST['gardenBedID']);
  header('Location: viewZone.php' );
?>
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

