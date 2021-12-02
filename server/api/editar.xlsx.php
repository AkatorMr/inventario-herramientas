<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../lib/Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../lib/Classes/PHPExcel/IOFactory.php';

// Create new PHPExcel object
// echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
/*
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");

*/
$objReader = PHPExcel_IOFactory::createReader('Excel2007');

$objPHPExcel = $objReader->load("E:\\Preventivo-Predictivo\\11. Inventario de Herramientas\\Herramientas REV. 06.xlsx");
$valu=66;
$objPHPExcel->getActiveSheet()->setCellValue('A10', $value);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save("E:\\Preventivo-Predictivo\\11. Inventario de Herramientas\\Herramientas REV. 06-5.xlsx");


?>