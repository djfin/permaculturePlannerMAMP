/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function validate_complete()
{   
    var element = document.getElementById('completeInput');
    if(element.value === "1"){
        element.checked=true;
    }
}
function validate_crop()
{
    var element = document.getElementById("cropInput").value;
    if (element === "")
    {
        var e = document.getElementById('cropTester');
        e.innerHTML = "<p><font color='red'>Please enter a crop </font></p>";
    }else{
        var e = document.getElementById('cropTester');
        e.innerHTML = "";

    }
}
function validate_name()
{
    var element = document.getElementById("nameInput").value;
    if (element ==="")
        {
            var e = document.getElementById('nameTester');
            e.innerHTML = "<p style='text-align:center'><font color='red'>Please enter a name </font></p>";
        }else{
            var e = document.getElementById('nameTester');
            e.innerHTML = "";
        }
}
function validate_description()
{
    var element = document.getElementById("descriptionInput").value;
    if (element === "")
    {
        var e = document.getElementById('descriptionTester');
        e.innerHTML = "<p style='text-align:center'><font color='red'>Please enter a descrption </font></p>";
    }else{
        var e = document.getElementById('descriptionTester');
        e.innerHTML = "";

    }
}

function validate_date_planted()
{
    var element = document.getElementById("datePlantedInput").value;
    if(element === "")
    {
        var e = document.getElementById('datePlantedTester');
        e.innerHTML = "<p style='text-align:center'><font color='red'>Please enter a date </font></p>";
    }else{
        var e = document.getElementById('datePlantedTester');
        e.innerHTML = "";
    }
}
function validate_prompt()
{
    var element = document.getElementById("promptInput").value;
    if(element === "")
    {
        var e = document.getElementById('promptTester');
        e.innerHTML = "<p style='text-align:center'><font color='red'>Please enter a date </font></p>";
    }else{
        var e = document.getElementById('promptTester');
        e.innerHTML = "";
    }
}
function validate_username()
{
    var element = document.getElementById("usernameInput").value;
    if(element === "")
    {
        var e = document.getElementById('usernameTester');
        e.innerHTML = "<p style='text-align:center'><font color='red'>Please enter a username </font></p>";
    }else{
        var e = document.getElementById('usernameTester');
        e.innerHTML = "";
    }
}
function validate_password()
{
    var element = document.getElementById("passwordInput").value;
    var specRegex = /^[A-Za-z0-9]+$/
    var numRegex = /[0-9]/
    if(element === "" || specRegex.test(element) || !numRegex.test(element) || element.length<8)
    {
        var e = document.getElementById('passwordTester');
        e.innerHTML = "<p style='text-align:center'><font color='red'>Password must contain a number, special character, and be at least 8 characters long </font></p>";
    }else{
        var e = document.getElementById('passwordTester');
        e.innerHTML = "";
    }
}
function validate_email()
{
    var element = document.getElementById("emailInput").value;
    //regex pattern taken from w3resource.com 
    var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
    if(element === "" || !regex.test(element))
    {
        var e = document.getElementById('emailTester');
        e.innerHTML = "<p style='text-align:center'><font color='red'>Please enter a valid email </font></p>";
    }else{
        var e = document.getElementById('emailTester');
        e.innerHTML = "";
    }
}
function validate_first_name()
{
    var element = document.getElementById("firstNameInput").value;
    if(element === "")
    {
        var e = document.getElementById('firstNameTester');
        e.innerHTML = "<p style='text-align:center'><font color='red'>Please enter your first name </font></p>";
    }else{
        var e = document.getElementById('firstNameTester');
        e.innerHTML = "";
    }
}
function validate_last_name()
{
    var element = document.getElementById("lastNameInput").value;
    if(element === "")
    {
        var e = document.getElementById('lastNameTester');
        e.innerHTML = "<p style='text-align:center'><font color='red'>Please enter your last name </font></p>";
    }else{
        var e = document.getElementById('lastNameTester');
        e.innerHTML = "";
    }
}
    

