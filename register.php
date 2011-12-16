<?php
require_once 'includes/backend.php';

//If an user is already logged in they
//don't need to register for the site.
if($Mubo->session->sessionExists())
    $Mubo->common->redirect("index.php");

//captcha stuff and adding user function if post is set{{{1
require_once('includes/recaptchalib.php');
$publickey = "6Lf7qL4SAAAAALIUBvR6kHdteLbMQuxf-OTnJoo-";
$privatekey = "6Lf7qL4SAAAAAAilwX1LE7N0gCX_difN1Sjxp69w";
//the response from reCAPTCHA
$resp = null;
//the error code from reCAPTCHA, if any
$error = null;
// was there a reCAPTCHA response?
if (isset($_POST["recaptcha_response_field"]) && $_POST["recaptcha_response_field"])
{
        //check the answer and find if it was correct
        $resp = recaptcha_check_answer($privatekey,
                                    $_SERVER["REMOTE_ADDR"],
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"]);

        //check to see if captcha reponse was valid
        if($resp->is_valid)
        {
            //check to see that all fields are set
            if(isset($_POST['username']) && isset($_POST['password'])
                && isset($_POST['firstname']) && isset($_POST['lastname'])
                && isset($_POST['time']) && isset($_POST['email']) )
                {

                    $emailAddy = $_POST['email'];//get the email
                    $obj = new UserRegistration($Mubo->db); //get an user registration obj

                    //add the user to teh user registration table
                    $result = $obj->addUser($_POST['time'], $_POST['email'],
                                 $_POST['username'], $_POST['password'],
                                 $_POST['firstname'], $_POST['lastname'],
                                 $_POST['role']);
                    $result = ($result === FALSE) ? "There was an error" : "Success,"; //start creating appropriate result message

                    //then append the appropriate message depending on the role
                    $result .= " you will be notified when your account has been approved by an administrator.";
                    $Mubo->page->setNotification($result);//put the text into the notification area of the website
                    $passedUsername= $passedFName= $passedLName= $passedEmail='';//empty out all of the strings that were passed
                }
        }
        else//set the strings to what was passed incase there was an error and the fields will be filled
        {
                //set the error code so that we can display it
                $error = $resp->error;
                $passedUsername=$_POST['username'];
                $passedFName=$_POST['firstname'];
                $passedLName=$_POST['lastname'];
                $passedEmail=$_POST['email'];
        }
}
//if the user information was passed but the captcha was incorrect it will auto
//fill the fields again
elseif(isset($_POST['username']) && isset($_POST['firstname']) && isset($_POST['lastname'])
                && isset($_POST['time'])
                && isset($_POST['email']) )
{
    $passedUsername=$_POST['username'];
    $passedFName=$_POST['firstname'];
    $passedLName=$_POST['lastname'];
    $passedEmail=$_POST['email'];
}
else //initialize these variables with empty strings so that we prevent errors
{
    $passedUsername= $passedFName= $passedLName= $passedEmail='';
}
//end captcha setup }}}1

//javascript {{{1
//this will validate the user input as it is being entered before submission
$header =<<<JAVASCRIPT
<script type="text/javascript">

    //variables used to keep status of valid information in a field
    var pass = true;
    var first = true;
    var last = true;
    var email = true;
    var eMatch = true;

    //show loading animation
    function loading(username)
    { document.getElementById("availability").innerHTML= "<img src=\"images/load.gif\" />"; }

    //ajax call to see if username is avaiable
    function checkForUsername(str)
    {//{{{2
        if (str=="")//initialize the inner HTML to nothing
        {
            document.getElementById("availability").innerHTML="";
            return;
        }

        //if an xmlhttp request is called from the window create one
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else //or make one for ie6 and ie5
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        //when a result is received put it into the inner HTML of the
        //availability id
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById("availability").innerHTML=xmlhttp.responseText;
            }
        }
        //create a post request
        xmlhttp.open("POST","requestHandler/checkUsername.php",true);
        //make the headers and say that we are going to submit a form
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        //send our arguments
        xmlhttp.send("q="+str);
    }//}}}2

    //validate that the passwords in the password field and confirm password
    //field match
    function checkPassMatch()
    {//{{{2

        //if the password field and the confirm password field are empty then
        //the inner html needs should be blank
        if((document.getElementById("password").value=="" ||
            document.getElementById("password").value==null) &&
            (document.getElementById("confirmPass").value=="" || document.getElementById("confirmPass").value==null))
                document.getElementById("passMatch").innerHTML="";

        //if the strings match each other, then send output that they do
        else if(document.getElementById("password").value==document.getElementById("confirmPass").value)
        {
            document.getElementById("passMatch").innerHTML="<img src=\"images/check.png\" /><i>Passwords match</i>";
            pass=true;
            enableSubmit();
        }

        //else show the user that they do not match
        else
        {
            document.getElementById("passMatch").innerHTML="<img src=\"images/x.png\" /><b>Passwords do not match</b>";
            pass=false;
            disableSubmit();
        }

    }//}}}2

    //validate that the firstname falls within the speicifed regex
    function checkFirstName()
    {//{{{2

        //regular expression to test against
        var regTest = /^[a-zA-Z']([A-Za-z'\-]*[A-Za-z]+$)|((\s[A-Za-z][A-Za-z'\-]*[A-Za-z])+$)/;
        //check to see if the strings are empty
        if((document.getElementById("firstname").value=="" || document.getElementById("firstname").value==null))
                document.getElementById("validFirst").innerHTML="";//make a blank string to show the user

        //if the regular expression test succeeds show a checkmark
        else if(regTest.test(document.getElementById("firstname").value))
        {
            document.getElementById("validFirst").innerHTML="<img src=\"images/check.png\" />";
            first=true;
            enableSubmit();
        }

        //if the regular expression test does not succeed show an X
        else
        {
            document.getElementById("validFirst").innerHTML="<img src=\"images/x.png\" /><b>Invalid characters</b>";
            first=false;
            disableSubmit();
        }

    }//}}}2

    //validate that the last name falls within the speicifed regex
    function checkLastName()
    {//{{{2
        //regular expression to test against
        var regTest = /^[a-zA-Z']([A-Za-z'\-]*[A-Za-z]+$)|((\s[A-Za-z][A-Za-z'\-]*[A-Za-z])+$)/;
        //check to see if the strings are empty
        if((document.getElementById("lastname").value=="" || document.getElementById("lastname").value==null))
                document.getElementById("validLast").innerHTML="";

        //if the regular expression test succeeds show a checkmark
        else if(regTest.test(document.getElementById("lastname").value))
        {
            document.getElementById("validLast").innerHTML="<img src=\"images/check.png\" />";
            last=true;
            enableSubmit();
        }

        //if the regular expression test does not succeed show an X
        else
        {
            document.getElementById("validLast").innerHTML="<img src=\"images/x.png\" /><b>Invalid characters</b>";
            last=false;
            disableSubmit();
        }

    }//}}}2


    function checkEmail()
    {//{{{2
        //regex to test against
        var regTest = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
        //check to see if the strings are empty
        if((document.getElementById("email").value=="" || document.getElementById("email").value==null))
                document.getElementById("validEmail").innerHTML="";

        //if the regular expression test succeeds show a checkmark
        else if(regTest.test(document.getElementById("email").value))
        {
            document.getElementById("validEmail").innerHTML="<img src=\"images/check.png\" />";
            email=true;
            enableSubmit();
        }

        //if the regular expression test does not succeed show an X
        else
        {
            document.getElementById("validEmail").innerHTML="<img src=\"images/x.png\" /><b>Invalid email</b>";
            email=false;
            disableSubmit();
        }
    }//}}}2

    //validate that the emails in the email field and confirm email
    //field match
    function checkEmailMatch()
    {//{{{2
        //if the email field and the confirm email field are empty then
        //the inner html needs should be blank
        if((document.getElementById("email").value=="" || document.getElementById("email").value==null) &&
            (document.getElementById("confirmEmail").value=="" || document.getElementById("confirmEmail").value==null))
                document.getElementById("emailMatch").innerHTML="";

        //if the strings match each other, then send output that they do
        else if(document.getElementById("email").value==document.getElementById("confirmEmail").value)
        {
            document.getElementById("emailMatch").innerHTML="<img src=\"images/check.png\" /><i>Emails match</i>";
            eMatch=true;
            enableSubmit();
        }

        //else show the user that they do not match
        else
        {
            document.getElementById("emailMatch").innerHTML="<img src=\"images/x.png\" /><b>Emails do not match</b>";
            eMatch=false;
            disableSubmit();
        }

    }//}}}2

    //enables the submit button
    function enableSubmit()
    {//{{{2
        if(pass && first && last && email && eMatch)
            document.getElementById("submitButton").disabled=false;
    }//}}}2

    //disables the submit button
    function disableSubmit()
    {//{{{2
        if(!pass || !first || !last || !email || !eMatch)
            document.getElementById("submitButton").disabled=true;
    }//}}}2

</script>
JAVASCRIPT;
//}}}1
//end javascript }}}1

//insert the javascript into the header area of the webpage to be created
$Mubo->page->setHeader($header);

//get the current unix epoch timestamp
$time = time();

//put captcha stuff into a variable
$muboCaptcha = recaptcha_get_html($publickey, $error);

//start adding html to the pages
$page =<<<HTML

<span id="title">Registration</span><br />
<form method="post" action="register.php" id="form">
    <table>
        <tr>
            <td>Username:</td><td><input type="text" name="username" value="$passedUsername" onchange="checkForUsername(this.value)"  onkeyup="loading(this)" /></td>
            <td><span id="availability"></span></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" id="password" name="password" /></td>
            <td><span id="passMatch"></span></td>
        </tr>
        <tr>
            <td>Confirm:</td>
            <td><input type="password" id="confirmPass" name="confirmPass" onchange="checkPassMatch()" /></td>
        </tr>
        <tr>
            <td>First Name:</td>
            <td><input type="text" id="firstname" name="firstname" value="$passedFName" onchange="checkFirstName()" /></td>
            <td><span id="validFirst" /></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><input type="text" id="lastname" name="lastname" value="$passedLName" onchange="checkLastName()" /></td>
            <td><span id="validLast" /></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><input type="text" id="email" name="email" value="$passedEmail" onchange="checkEmail()" /></td>
            <td><span id="validEmail" /></td>
        </tr>
        <tr>
            <td>Re-enter Email:</td>
            <td><input type="text" id="confirmEmail" name="confirmEmail" onchange="checkEmailMatch()" /></td>
            <td><span id="emailMatch"></span></td>
            <td><span id="disValue"></span></td>
        </tr>
        </table>
        <br />
        <table id="role">
        <tr><td>User</td><td>Manager</td></tr>
        <tr>
            <td><input type="radio" name="role" value="user" /></td>
            <td><input type="radio" name="role" value="manager" /></td>
        <input type="hidden" name="time" value="$time" />
    </table>
    $muboCaptcha
    <br />
    <input type="submit" id="submitButton" /> <input type="reset" />
</form>
<br />
HTML;


//display the page
$Mubo->page->displayPage($page, "Register");

?>
