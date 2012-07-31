<?php
require_once('classes/class.Login.php');
Login::validateSession();

include_once("classes/class.ABCReader.php");
include_once("include/config.php");

// $a= new ABCReader("m_tape437.xls");

$b= new ABCReader();
$b->outputResultList(date("Ymd"));
?>