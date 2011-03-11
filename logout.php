<?php

require_once 'includes/backend.php';

//check to see if there is a session
if($Mubo->session->sessionStart())
    $Mubo->session->logout(); //logout the session
$Mubo->common->redirect("index.php"); //redirect the user back to the index page

?>


