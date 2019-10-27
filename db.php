<?php

class PermacultureDB extends mysqli{
    private static $instance = null;
    private $user = "permacultureUser";
    private $pass = "mypass";
    private $dbName = "permacultureProject";
    private $dbHost = "localhost";
    private $con = null;

    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    // private constructor
    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }
    public function verify_user_credentials($name, $password) {
        $name = $this->real_escape_string($name);
        $password = $this->real_escape_string($password);
        $result = $this->query("SELECT 1 FROM users WHERE username = '"
                        . $name . "' AND password = '" . $password . "'");
        return $result->data_seek(0);
    }
    public function get_user_id_by_username($username){
        $username = $this->real_escape_string($username);
        $user = $this->query("SELECT userID FROM users WHERE username = '".$username."'");
        if ($user->num_rows > 0){
            $row = $user->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function insert_user($username, $password, $email, $firstName, $lastName, $theme) {
        $username = $this->real_escape_string($username);
        $password = $this->real_escape_string($password);
        $email= $this->real_escape_string($email);
        $firstName = $this->real_escape_string($firstName);
        $lastName = $this->real_escape_string($lastName);
        $theme = $this->real_escape_string($theme);
        $this->query("INSERT INTO users (username, password, email, firstName, lastName, theme) VALUES ('".$username."','".$password."','".$email."','".$firstName."','".$lastName."', '".$theme."')");
    }
    public function update_user($userID, $username, $password, $email, $firstName, $lastName, $theme) {
        $username = $this->real_escape_string($username);
        $password = $this->real_escape_string($password);
        $email= $this->real_escape_string($email);
        $firstName = $this->real_escape_string($firstName);
        $lastName = $this->real_escape_string($lastName);
        $theme = $this->real_escape_string($theme);
        $this->query("UPDATE users SET username = '".$username."', password = '".$password."', email = '".$email."', firstName = '".$firstName."', lastName = '".$lastName."', theme = '".$theme."' WHERE userID =".$userID);
    }
    
    public function get_user_by_user_id($userID) {
        return $this->query("SELECT userID, username, password, email, firstName, lastName, theme FROM users WHERE userID =". $userID);
    }
    public function get_user_by_username($username) {
        return $this->query("SELECT userID, username, password, email, firstName, lastName, theme FROM users WHERE username ='". $username."'");
    }
    public function get_theme_by_username($username) {
        $username = $this->real_escape_string($username);
        $result = $this->query("SELECT theme FROM users WHERE username = '". $username."'");
        $row = mysqli_fetch_array($result);
        return $row['theme'];
        
    }
    public function get_projects_by_user_id($userID){
        return $this->query("SELECT projectid, name, description FROM projects WHERE userID=" . $userID);
    }
    public function insert_project($userID, $projectName, $projectDescription) {
        $projectName = $this->real_escape_string($projectName);
        $projectDescription = $this->real_escape_string($projectDescription);
        $this->query("INSERT INTO projects (name, description, userID) VALUES ('".$projectName."','".$projectDescription."',".$userID.")");
        $result = mysqli_fetch_array($this->query("SELECT projectID FROM projects WHERE name='".$projectName."'"));
        return $result[0];
        
    }
    public function update_project($projectID, $projectName, $projectDescription) {
        $projectName = $this->real_escape_string($projectName);
        $projectDescription = $this->real_escape_string($projectDescription);
        $this->query("UPDATE projects SET name = '".$projectName."', description = '".$projectDescription."' WHERE projectID = ".$projectID);
    }
    
    public function get_project_by_project_id($projectID) {
        return $this->query("SELECT projectid, name, description FROM projects WHERE projectID =". $projectID);
    }
    public function delete_project($projectID) {
        $this->query("DELETE FROM projects WHERE projectID = ".$projectID);
    }
    public function get_zones_by_project_id($projectID){
        return $this->query("SELECT zoneid, name, description, notes FROM zones WHERE projectID =".$projectID);
    }
    public function get_principles_by_project_id($projectID){
        return $this->query("SELECT principleID, name, description FROM principles WHERE projectID =".$projectID);
    }
    public function insert_zone($projectID, $zoneName, $zoneDescription, $zoneNotes) {
        $zoneName = $this->real_escape_string($zoneName);
        $zoneDescription = $this->real_escape_string($zoneDescription);
        $zoneNotes = $this->real_escape_string($zoneNotes);
        $this->query("INSERT INTO zones (name, description, notes, projectID) VALUES ('".$zoneName."', '".$zoneDescription."', '".$zoneNotes."', ".$projectID.")");
    }
    public function update_zone($zoneID, $zoneName, $zoneDescription, $zoneNotes) {
        $zoneName = $this->real_escape_string($zoneName);
        $zoneDescription = $this->real_escape_string($zoneDescription);
        $this->query("UPDATE zones SET name = '".$zoneName."', description = '".$zoneDescription."', notes='".$zoneNotes."' WHERE zoneID = ".$zoneID);
    }
    
    public function get_zone_by_zone_id($zoneID) {
        return $this->query("SELECT zoneid, name, description, notes FROM zones WHERE zoneID =". $zoneID);
    }
    public function delete_zone($zoneID){
        $this->query("DELETE FROM zones WHERE zoneID= ".$zoneID);
    }
    public function delete_principle($principleID){
        $this->query("DELETE FROM principles WHERE principleID=".$principleID);
    }
    public function insert_principle($projectID, $principleName, $principleDescription) {
        $principleName = $this->real_escape_string($principleName);
        $principleDescription = $this->real_escape_string($principleDescription);
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('".$principleName."', '".$principleDescription."', ".$projectID.")");
    }
    public function update_principle($principleID, $principleName, $principleDescription) {
        $principleName = $this->real_escape_string($principleName);
        $principleDescription = $this->real_escape_string($principleDescription);
        $this->query("UPDATE principles SET name = '".$principleName."', description = '".$principleDescription."' WHERE principleID = ".$principleID);
    }
    
    public function get_principle_by_principle_id($principleID) {
        return $this->query("SELECT principleID, name, description FROM principles WHERE principleID =". $principleID);
    }
    public function get_garden_beds_by_zone_id($zoneID){
        return $this->query("SELECT gardenBedID, name, description FROM gardenbeds WHERE zoneID =".$zoneID);
    }
    public function delete_garden_bed($gardenBedID){
        $this->query("DELETE FROM gardenbeds WHERE gardenBedID=".$gardenBedID);
    }
    public function insert_garden_bed($zoneID, $gardenBedName, $gardenBedDescription) {
        $gardenBedName = $this->real_escape_string($gardenBedName);
        $gardenBedDescription = $this->real_escape_string($gardenBedDescription);
        $this->query("INSERT INTO gardenbeds (name, description, zoneID) VALUES ('".$gardenBedName."', '".$gardenBedDescription."', ".$zoneID.")");
    }
    public function update_garden_bed($gardenBedID, $gardenBedName, $gardenBedDescription) {
        $gardenBedName = $this->real_escape_string($gardenBedName);
        $gardenBedDescription = $this->real_escape_string($gardenBedDescription);
        $this->query("UPDATE gardenbeds SET name = '".$gardenBedName."', description = '".$gardenBedDescription."' WHERE gardenBedID = ".$gardenBedID);
    }
    
    public function get_garden_bed_by_garden_bed_id($gardenBedID) {
        return $this->query("SELECT gardenBedID, name, description FROM gardenbeds WHERE gardenBedID =". $gardenBedID);
    }
    function format_date_for_sql($date) {
        if ($date == "")
            return null;
        else {
            $dateParts = date_parse($date);
            return $dateParts['month'] * 100 + $dateParts['day'] + $dateParts['year'] * 10000;
        }
    }
    public function get_crops_by_garden_bed_id($gardenBedID){
        return $this->query("SELECT cropID, name, description, datePlanted FROM crops WHERE gardenBedID =".$gardenBedID);
    }
    public function delete_crop($cropID){
        $this->query("DELETE FROM crops WHERE cropID=".$cropID);
    }
    public function insert_crop($gardenBedID, $cropName, $cropDescription, $datePlanted) {
        $cropName = $this->real_escape_string($cropName);
        $cropDescription = $this->real_escape_string($cropDescription);
        $this->query("INSERT INTO crops (name, description, datePlanted, gardenBedID) VALUES ('".$cropName."', '".$cropDescription."', ".$this->format_date_for_sql($datePlanted).", ".$gardenBedID.")");
    }
    public function update_crop($cropID, $cropName, $cropDescription, $datePlanted) {
        $cropName = $this->real_escape_string($cropName);
        $cropDescription = $this->real_escape_string($cropDescription);
        $this->query("UPDATE crops SET name = '".$cropName."', description = '".$cropDescription."', datePlanted =".$this->format_date_for_sql($datePlanted)." WHERE cropID = ".$cropID);
    }
    
    public function get_crop_by_crop_id($cropID) {
        return $this->query("SELECT cropID, name, description, datePlanted FROM crops WHERE cropID =". $cropID);
    }
    public function get_tasks_by_garden_bed_id($gardenBedID){
        return $this->query("SELECT taskID, name, description, complete, cropID FROM tasks WHERE gardenBedID =".$gardenBedID);
    }
    public function translate_complete_boolean($complete){
        if($complete == 0){
            return "No";
        }else{
            return "Yes";
        }
    }
    public function get_crop_name_by_crop_id($cropID){
        $result = $this->get_crop_by_crop_id($cropID);
        $row = mysqli_fetch_array($result);
        $cropName = $row['name'];
        return $cropName;
    }
    public function get_crop_id_by_crop_name($cropName){
        $result = $this->query("SELECT cropID FROM crops WHERE name= '".$cropName."'");
        $row= mysqli_fetch_array($result);
        $cropID = $row['cropID'];
        return $cropID;
    }
    public function get_tasks_size_by_crop_id($cropID){
        $size = mysqli_fetch_array($this->query("SELECT COUNT(*) FROM tasks WHERE cropID=".$cropID));
        return $size[0];
    }
    public function get_tasks_progress_by_crop_id($cropID){
        $progress = mysqli_fetch_array($this->query("SELECT COUNT(IF(complete=1,1,NULL)) FROM tasks WHERE cropID=".$cropID));
        return $progress[0];
    }
    public function delete_task($taskID){
        $this->query("DELETE FROM tasks WHERE taskID=".$taskID);
    }
    public function insert_task($taskName, $taskDescription, $complete, $cropID) {
        $taskName = $this->real_escape_string($taskName);
        $taskDescription = $this->real_escape_string($taskDescription);
        $result = $this->query("SELECT gardenBedID FROM crops WHERE cropID=".$cropID);
        $row = mysqli_fetch_array($result);
        $gardenBedID = $row['gardenBedID'];
        $this->query("INSERT INTO tasks (name, description, complete, cropID, gardenBedID) VALUES ('".$taskName."', '".$taskDescription."', ".$complete.", ".$cropID.", ".$gardenBedID.")");
    }
    public function update_task($taskID, $taskName, $taskDescription, $complete) {
        $taskName = $this->real_escape_string($taskName);
        $taskDescription = $this->real_escape_string($taskDescription);
        $this->query("UPDATE tasks SET name = '".$taskName."', description = '".$taskDescription."', complete =".$complete." WHERE taskID = ".$taskID);
    }
    
    public function get_task_by_task_id($taskID) {
        return $this->query("SELECT taskID, name, description, complete, cropID FROM tasks WHERE taskID =". $taskID);
    }
    public function get_tasks_by_crop_id($cropID){
        return $this->query("SELECT taskID, name, description, complete FROM tasks WHERE cropID=".$cropID);
    }
    public function get_tasks_size_by_garden_bed_id($gardenBedID){
        $size = mysqli_fetch_array($this->query("SELECT COUNT(*) FROM tasks WHERE gardenBedID=".$gardenBedID));
        return $size[0];
    }
    public function get_tasks_progress_by_garden_bed_id($gardenBedID){
        $progress = mysqli_fetch_array($this->query("SELECT COUNT(IF(complete=1,1,NULL)) FROM tasks WHERE gardenBedID=".$gardenBedID));
        return $progress[0];
    }
    public function get_activities_by_principle_id($principleID){
        return $this->query("SELECT activityID, name, prompt, complete FROM activities WHERE principleID=".$principleID);
    }
    public function insert_activity($principleID, $activityName, $activityPrompt) {
        $activityName = $this->real_escape_string($activityName);
        $activityPrompt = $this->real_escape_string($activityPrompt);
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('".$activityName."', '".$activityPrompt."', 0,  ".$principleID.")");
    }
    public function update_activity($activityID, $activityName, $activityPrompt) {
        $activityName = $this->real_escape_string($activityName);
        $activityPrompt = $this->real_escape_string($activityPrompt);
        $this->query("UPDATE activities SET name = '".$activityName."', prompt = '".$activityPrompt."' WHERE activityID = ".$activityID);
    }
    public function get_activity_by_activity_id($activityID) {
        return $this->query("SELECT activityID, name, prompt, response, complete, principleID FROM activities WHERE activityID =". $activityID);
    }
    public function update_activity_response_by_id($activityID,$activityResponse, $complete){
        $activityResponse = $this->real_escape_string($activityResponse);
        $this->query("UPDATE activities SET response = '".$activityResponse."', complete=".$complete." WHERE activityID =".$activityID);
    }
    public function delete_activity($activityID){
        $this->query("DELETE FROM activities WHERE activityID=".$activityID);
    }
    public function get_activities_size_by_principle_id($principleID){
        $size = mysqli_fetch_array($this->query("SELECT COUNT(*) FROM activities WHERE principleID=".$principleID));
        return $size[0];
    }
    public function get_activities_progress_by_principle_id($principleID){
        $progress = mysqli_fetch_array($this->query("SELECT COUNT(IF(complete=1,1,NULL)) FROM activities WHERE principleID=".$principleID));
        return $progress[0];
    }
    public function get_style($styleName){
        switch($styleName)
        {
            case "Chocolate":
                return "https://www.w3.org/StyleSheets/Core/Chocolate";
            case "Midnight":
                return "https://www.w3.org/StyleSheets/Core/Midnight";
            case "Modernist":
                return "https://www.w3.org/StyleSheets/Core/Modernist";
            case "Oldstyle":
                return "https://www.w3.org/StyleSheets/Core/Oldstyle";
            case "Steely":
                return "https://www.w3.org/StyleSheets/Core/Steely";
            case "Swiss":
                return "https://www.w3.org/StyleSheets/Core/Swiss";
            case "Traditional":
                return "https://www.w3.org/StyleSheets/Core/Traditional";
            case "Ultramarine":
                return "https://www.w3.org/StyleSheets/Core/Ultramarine";   
        } 
    }
    
    //setup new project functions 
    
    public function setup_principles_by_project_id($projectID){
        //Observe and Interact
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#1 Observe and Interact', 'By taking the time to engage with nature we can design solutions that suit our particular situation.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#1 Observe and Interact'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Beginning Reflection Activity', 'Sit in the space you intend to plan your garden for 30 minutes to an hour. Journal what you see, hear, smell, and feel.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Mapping', 'Draw a map of the proposed space. View Aranyas online resources for tips.', 0, ".$principleID.")");
        
        //Catch and Store Energy
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#2 Catch and Store Energy', 'By developing systems that collect resources when they are abundant, we can use them in times of need.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#2 Catch and Store Energy'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Soil Test', 'Take a soil sample in the proposed garden space. If it is a large area consider taking more than one in different locations. Send these in to a local lab to find out the health of your soil.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Party Time', 'Permaculture is about more than the land. it involves the people around you as well. Throw a party or event with some friends.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Party Reflection', 'Think about the event you planned. In what ways did it bring people together. What would you change to make things better next time?', 0, ".$principleID.")");
        
        //Obtain a Yield
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#3 Obtain a Yield', 'Ensure that you are getting truly useful rewards as part of the work that you are doing.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#3 Obtain a Yield'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Different Types of Yield', 'Share a meal with a close friend. Although this may seem unproductive, think about the ways you and your friend benefit from the experience.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Quick Yields', 'Make a small herb garden in a window sill. This is by no means a large garden but it will provide for you nonetheless. Appreciate even small gifts.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Volunteering', 'Find a local farm or urban garden to volunteer at. Try to make some connections with other members of your local food community.', 0, ".$principleID.")");
        
        //Apply Self Regulation and Accept Feedback
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#4 Apply Self Regulation and Accept Feedback', 'We need to discourage inappropriate activity to ensure that systems can continue to function well.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#4 Apply Self Regulation and Accept Feedback'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Clean House', 'For a week, try to give up something you know is dragging you down, whether it be social media, ice cream, or some other habit. Each day log the ways you feel the effects of giving this up.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Soil Research', 'Rsearch what the results of your soil test mean and what crops are best accustomed to it. Research different ways to naturally bring more nutrients into the soil and log them here.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Harvest Log', 'By now you are likely obtaining some sort of harvest. Even if it is just herbs, log the weights of everything you harvest and when.', 0, ".$principleID.")");
        
        //Use and Value Renewable Resources and Services
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#5 Use and Value Renewable Resources and Services', 'Make the best use of nature’s abundance to reduce our consumptive behaviour and dependence on non-renewable resources.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#5 Use and Value Renewable Resources and Services'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Sleep Log', 'We often do not think of ourselves as renewable resources, but your energy is certainly a resource. Log how much and how well you sleep for week and how you feel as a result each day.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Composting', 'Research different methods of composting to find one that best fits your situation. Implement that solution in your home.', 0, ".$principleID.")");
        
        //Produce No Waste
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#6 Produce No Waste', 'By valuing and making use of all the resources that are available to us, nothing goes to waste.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#6 Produce No Waste'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Waste Log', 'Weigh all of your waste for a week. Log the weights and ways you can reduce your total amount of waste here.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Food Rescue', 'Voltuneer with a local food rescue. Think about how some waste is unnecessary, and ways to prevent that kind of waste.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Waste Meal', 'Make a meal completely from items normally considered waste. Example include carrot tops, broccoli stalks, or chicken bones.', 0, ".$principleID.")");
        
        //Design from Pattern to Details
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#7 Design From Patterns to Details', 'By stepping back, we can observe patterns in nature and society. These can form the backbone of our designs, with the details filled in as we go.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#7 Design From Patterns to Details'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Your Own Patterns', 'Look back at your sleep and waste log. See if you can observe any patterns in yourself.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Natural Patterns', 'Look for some examples of patterns occurring in your environment, both natural and man made. Some examples include a spiral, net, or wave', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Apply a Pattern', 'Try the process of designing from pattern to details. Pick a pattern for a space you can design and then drill down into the details.', 0, ".$principleID.")");
        
        //Integrate rather than Segregate
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#8 Integrate Rather Then Segregate', 'By putting the right things in the right place, relationships develop between them and they support each other.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#8 Integrate Rather Then Segregate'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Random Assembly', 'Write down different aspects of your life on notecards and shuffle. Divide the stacks into two face down piles. Pick up a card from each side and try to write down connections betwee the two. Repeat until the stacks are empty.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Needs and Yields Analysis', 'All things have both needs and yields. By having elements in our system that provide for the needs of other elements, we can move closer to a closed loop system. Thnk about the elements of your garden and where the needs of one element can be met by the yields of another.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Attend a Meeting', 'Attend your local community meeting (neighborhood, apartment complex, dorm, etc). Try to take not of what groups may be underrepresented or completely missing. Make a plan for integrating the missing pieces.', 0, ".$principleID.")");
        
        //Use small and slow solutions
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#9 Use Small and Slow Solutions', 'Small and slow systems are easier to maintain than big ones, making better use of local resources and produce more sustainable outcomes.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#9 Use Small and Slow Solutions'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Respond to Feedback', 'Look back at the activities from principle #4. Make a long term plan to address any feedback.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Long Term Yields', 'In principle #3 we implemented something to return yields in the short term. Now plan an element to your garden that will return in the long term, such as a tree or perrenial.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Small and Slow Application', 'Look back at the plans you made for principles #7 and #8. How can you apply the principle of small and slow solutions to those plans.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Reflect', 'You have come a long way since this project began. Take a moment to relfect on why you have chosen to undertake this project.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Climate', 'Your local climate can be very particular to an area. Research your climate and how that impacts the crops you plant.', 0, ".$principleID.")");
        
        //Use and Value Diversity
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#10 Use and Value Diversity', 'Diversity reduces vulnerability to a variety of threats and takes advantage of the unique nature of the environment in which it resides.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#10 Use and Value Diversity'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Diversity in Your Life', 'Think about the ways in which your life may be lacking diversity. Journal about ways you can bring more in.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Crop Diversity', 'Today we are often presented with only one variety of a crop. Pick a common crop and research the myriad of varieties that exist.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Biodiversity and Cultural Diversity', 'The incredible amount of diversity in food has helped to create an incredible amount of cultural diversity as well. Pick a specific crop and research how it helped to form of culture and its food ways.', 0, ".$principleID.")");
        
        //Use Edges and Value the Marginal
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#11 Use Edges and Value the Marginal', 'The interface between things is where the most interesting events take place. These are often the most valuable, diverse and productive elements in the system.', ".$projectID.")");
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#11 Use Edges and Value the Marginal'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Zones and Sectors', 'In your garden management there are defined zones for you to place garden beds in. Do a zones and sectors analysis of your garden.', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Zone 00', 'Zone 00 is often defined as the self. What parts of you are the most marginal, and how can you integrate them more fully?', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('The Land', 'What aspects of your land are most marginal. How can you make use of and value those transitional spaces?', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('The People', 'Find a local organization that supports a marginalized group in society such as the elderly, LGBTQIA+, women, children, or people experiencing poverty. Volunteer and reflect on the experience.', 0, ".$principleID.")");
        
        //Creatively Use and Respond to Change
        $this->query("INSERT INTO principles (name, description, projectID) VALUES ('#12 Creatively Use and Respond to Change', 'We can have a positive impact on inevitable change by carefully observing, and then intervening at the right time.', ".$projectID.")"); 
        $result = mysqli_fetch_array($this->query("SELECT principleID FROM principles WHERE projectID=".$projectID." AND name='#12 Creatively Use and Respond to Change'"));
        $principleID=$result[0];
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('The Self', 'Look back at all the activities in which you looked deeper into yourself and made plans to change things. What worked? What still needs to change? How have you changed over this process?', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('The Land', Look back at your harvest log. Note what crops did well and what crops were not as succesful. Brainstorm why a certain crop may have failed. Was it the climate, or simply the placement in the garden?', 0, ".$principleID.")");
        $this->query("INSERT INTO activities (name, prompt, complete, principleID) VALUES ('Conclusion', 'How is having learned permaculture helping you to use and creatively respond to change?', 0, ".$principleID.")");
         
        
    }
    public function setup_zones_by_project_id($projectID){
        $this->query("INSERT INTO zones (name, description, projectID) VALUES ('Zone 1', 'This is the area closest to the center of actvity. Plant the crops that need the most attention here.', ".$projectID.")");
        $this->query("INSERT INTO zones (name, description, projectID) VALUES ('Zone 2', 'This is the area just beyond Zone 1. These crops are still intensely cultivated and are often annuals.', ".$projectID.")");
        $this->query("INSERT INTO zones (name, description, projectID) VALUES ('Zone 3', 'This area acts as a windbreak for more sensitive crops. Hardy trees, shrubs, and animals are good here.', ".$projectID.")");
        $this->query("INSERT INTO zones (name, description, projectID) VALUES ('Zone 4', 'This area is for long term development. Plant timber trees andother crops that require minimal attention here.', ".$projectID.")");
        $this->query("INSERT INTO zones (name, description, projectID) VALUES ('Zone 5', 'Uncultivated transitional area', ".$projectID.")");
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

