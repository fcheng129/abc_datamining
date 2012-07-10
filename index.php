<?php
include_once("classes/class.ABCReader.php");
include_once("include/config.php");

$a= new ABCReader("m_tape437.xls");

$b= new ABCReader();

$b->processListFile();
// $a->process();
// $a->outputLosangelesResult(DATA_TABLE_PREFIX. date("Ymd"));
// $a->outputSanbarndardinoResult(DATA_TABLE_PREFIX. date("Ymd"));
// $a->outputVenturaResult(DATA_TABLE_PREFIX. date("Ymd"));

// $fp = fopen('data.txt', 'w');
// fwrite($fp, '1');
// fwrite($fp, '23');
// fclose($fp);

?>