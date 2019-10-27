<!DOCTYPE html>
<html>
  <head>
    <title>Setting up database</title>
  </head>
  <body>

    <h3>Setting up...</h3>

<?php // Example 26-3: setup.php
  require_once 'Includes/db.php';
  //Users
  PermacultureDB::getInstance()->query("CREATE TABLE IF NOT EXISTS users (
        username TEXT NOT NULL,
        password TEXT NOT NULL, 
        email TEXT NOT NULL, 
        lastName TEXT NOT NULL,
        firstName TEXT NOT NULL,
        theme TEXT NOT NULL,
        userID INT AUTO_INCREMENT PRIMARY KEY);");
  //projects
  PermacultureDB::getInstance()->query("CREATE TABLE IF NOT EXISTS projects (
        name TEXT NOT NULL,
        description TEXT NOT NULL, 
        userID INT NOT NULL, 
        FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE, 
        projectID INT AUTO_INCREMENT PRIMARY KEY);");
  //principles
  PermacultureDB::getInstance()->query("CREATE TABLE IF NOT EXISTS principles (
        name TEXT NOT NULL,
        description TEXT NOT NULL, 
        projectID INT NOT NULL, 
        FOREIGN KEY (projectID) REFERENCES projects(projectID) ON DELETE CASCADE, 
        principleID INT AUTO_INCREMENT PRIMARY KEY);");
  //activities
  PermacultureDB::getInstance()->query("CREATE TABLE IF NOT EXISTS activities (
        name TEXT NOT NULL,
        prompt TEXT NOT NULL,
        response TEXT,
        complete BOOLEAN NOT NULL,
        principleID INT NOT NULL,
        FOREIGN KEY (principleID) REFERENCES principles(principleID) ON DELETE CASCADE, 
        activityID INT AUTO_INCREMENT PRIMARY KEY);");
  //zones
  PermacultureDB::getInstance()->query("CREATE TABLE IF NOT EXISTS zones (
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        notes TEXT,
        projectID INT NOT NULL, 
        FOREIGN KEY (projectID) REFERENCES projects(projectID) ON DELETE CASCADE, 
        zoneID INT AUTO_INCREMENT PRIMARY KEY);");
  //garden beds
  PermacultureDB::getInstance()->query("CREATE TABLE IF NOT EXISTS gardenbeds (
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        zoneID INT NOT NULL, 
        FOREIGN KEY (zoneID) REFERENCES zones(zoneID) ON DELETE CASCADE, 
        gardenBedID INT AUTO_INCREMENT PRIMARY KEY);");
  // crops
  PermacultureDB::getInstance()->query("CREATE TABLE IF NOT EXISTS crops (
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        datePlanted DATE NOT NULL,
        gardenBedID INT NOT NULL, 
        FOREIGN KEY (gardenBedID) REFERENCES gardenbeds(gardenBedID) ON DELETE CASCADE, 
        cropID INT AUTO_INCREMENT PRIMARY KEY);");
  // tasks
  PermacultureDB::getInstance()->query("CREATE TABLE IF NOT EXISTS tasks (
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        complete BOOLEAN NOT NULL,
        gardenBedID INT NOT NULL, 
        FOREIGN KEY (gardenBedID) REFERENCES gardenbeds(gardenBedID) ON DELETE CASCADE, 
        cropID INT NOT NULL, 
        FOREIGN KEY (cropID) REFERENCES crops(cropID) ON DELETE CASCADE,
        taskID INT AUTO_INCREMENT PRIMARY KEY);");
  
  ?>

    <br>...done.
  </body>
</html>

