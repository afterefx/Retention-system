<?php

ini_set("display_errors", 1);//display any errors that come up
require_once "classes/class.Mubo.php"; //include the backend classes

$Mubo = new Mubo(); //start the mubo backend

$Mubo->session->sessionStart(); //start a session

?>
