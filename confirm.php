<?php
require_once "includes/backend.php";

//checks to see if a token is passed
if(isset($_GET['tok']))
{
    //confirm user in the user registration with the token
    $obj= new UserRegistration($Mubo->db); //create user registration object
    $result = $obj->confirmToken($_GET['tok']); //recieve boolean value of confirming the token

    //get an appropriate message for the result
    $message = ($result) ? "You have successfully confirmed your account. You may now login to MuBo!" : "You have presented an invalid token! Please contact support if you believe this is a mistake.";
    $page = $message; //place it on the page
}
else //if no token is passed redirect user to the index page
    $Mubo->common->redirect("index.php");

$Mubo->page->displayPage($page, "Confirm");//display the page
