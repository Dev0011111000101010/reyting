<?php
get_header();

require ABSPATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Xlsx1;

//$tr = new \Stichoza\GoogleTranslate\GoogleTranslate('ru'); // Translates into English

$reader      = new Xlsx();
$spreadsheet = $reader->load( __DIR__ . "/kku.xlsx" );

$data = $spreadsheet->getActiveSheet()->toArray();
set_time_limit( 0 );

try {
	$db = new PDO( "sqlite:" . __DIR__ . "/dbtoimport/results.db" );
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch ( Exception $e ) {
	echo "Unable to connect";
	echo $e->getMessage();
	exit;
}

//echo "Connected to the database";
$res = $db->query( 'SELECT DISTINCT category_code FROM documents WHERE justice_kind = 2' );
//var_dump($data);
$resarr = $res->fetchAll(PDO::FETCH_COLUMN, 0);
//$resarr = $res->fetchColumn(1);

$iterator = 0;
$count = 0;
$count2 = 0;
//var_dump($data);


foreach ($data as $row) {
//	var_dump($row);
	$iterator++;
	if ($iterator === 1) continue;
	if (in_array((string) $row[0], $resarr)) {
		$count++;
	} else {
		//var_dump($row[0]);
		unset($data[$iterator - 1]);
	}
}

var_dump($data);

$newspreadsheet = new Spreadsheet();
$newsheet = $newspreadsheet->getActiveSheet();
$newsheet->fromArray($data, NULL, 'A1');

$writer = new Xlsx1($newspreadsheet);
$writer->save(__DIR__ . '/newxlsx.xlsx');
//var_dump($translatearr);

//$apiKey = 'AIzaSyBfZEy4PS55MIWCsVhnKoEdNgDiMIYdjvA';
//$url = 'https://www.googleapis.com/language/translate/v2/languages?key=' . $apiKey;
//
//$handle = curl_init($url);
//curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);     //We want the result to be saved into variable, not printed out
//$response = curl_exec($handle);
//curl_close($handle);



?>


<?php
get_footer();
?>