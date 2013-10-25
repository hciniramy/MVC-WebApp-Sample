<?php

require_once 'controllers/FrontController.class.php' ; // Our front controller class

$front = new FrontController() ; // Instance of the frontController
$front->dispatch() ;
$front->display() ; // Display the vue

?>
