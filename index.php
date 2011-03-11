<?php
require_once 'includes/backend.php';

//check to see if a session exists
if($Mubo->session->sessionExists())
{
        $name = $Mubo->session->user->getFirstName(); //get the users firstname
        $page = "Hi $name<br />";//tell them hello
}
else
    //show a default page
    $page = "This is the index page content and you are not logged in.";

$Mubo->page->displayPage($page, "Home"); //output the page

?>
