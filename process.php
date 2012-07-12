<?php
require_once('classes/class.Login.php');
Login::validateSession(30* 60, 'http://'. $_SERVER['SERVER_NAME']. '/login.php');

include_once("classes/class.ABCReader.php");
include_once("include/config.php");

// $a= new ABCReader("m_tape437.xls");

$b= new ABCReader();
$b->processListFile();
echo "Output Files: <br />";
$b->outputLosangelesResultList(DATA_TABLE_PREFIX. date("Ymd"));
$b->outputSanbarndardinoResultList(DATA_TABLE_PREFIX. date("Ymd"));
$b->outputVenturaResultList(DATA_TABLE_PREFIX. date("Ymd"));
// $a->process();
// $a->outputLosangelesResult(DATA_TABLE_PREFIX. date("Ymd"));
// $a->outputSanbarndardinoResult(DATA_TABLE_PREFIX. date("Ymd"));
// $a->outputVenturaResult(DATA_TABLE_PREFIX. date("Ymd"));

// $fp = fopen('data.txt', 'w');
// fwrite($fp, '1');
// fwrite($fp, '23');
// fclose($fp);

?>