<?php

require_once 'includes/backend.php';
if($Mubo->session->sessionExists())
    $Mubo->common->redirect("index.php");

if(isset($_POST["user"]) && isset($_POST["pass"]))
{
    $userpassword = $_POST["pass"];

    if(isset($_GET['page']) && !empty($_GET['page']))
        $redirect = $_GET['page'];
    else
        $redirect = "index.php";

    if(isset($_POST["remember"]) && $_POST["remember"] == "true")
        $result = $Mubo->session->loginRedirect($_POST["user"], $userpassword, $redirect, true);
    else
        $result = $Mubo->session->loginRedirect($_POST["user"], $userpassword, $redirect, false);
    $output = ($result) ? "You should be redirected to the index page. You may
        click <a href=\"index.php\">here</a> if you would like to skip
        ahead.":"Login failure. Please make sure your username and password are
        typed correctly. Also check your caps lock key.";

    $Mubo->page->displayPage($output, "Failed Login");


}
else
    $Mubo->common->redirect("index.php");


?>
