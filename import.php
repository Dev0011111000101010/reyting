<?php
// указываем, что нам нужен минимум от WP
define( 'WP_USE_THEMES', false );

// подгружаем среду WordPress
// WP делает некоторые проверки и подгружает только самое необходимое для подключения к БД
require_once( 'E:/OSPanel/domains/reyting/wp-load.php' );

require ABSPATH . '/vendor/autoload.php';

$files        = scandir( 'E:\OSPanel\domains\windows.parser\converted10k' );
$importedfile = 'E:\OSPanel\domains\windows.parser\converted10k\documents_part1_0.xlsx';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($importedfile);
$sheetData = $spreadsheet->getActiveSheet()->toArray();

foreach ( $sheetData as $field ) {
	$lawyer = $field[12];
	if(!$lawyer) continue;
	var_dump($lawyer);
}

?>