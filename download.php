<?php
require_once('classes/class.Login.php');
Login::validateSession();

include_once("classes/class.ABCReader.php");
include_once("include/config.php");

$b= new ABCReader();
$b->downloading();
?>