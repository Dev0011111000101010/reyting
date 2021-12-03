<?php
get_header();
require ABSPATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$spreadsheet = $reader->load( __DIR__ . "/parsed/documents_part1_0.xlsx" );

$data = $spreadsheet->getActiveSheet()->toArray();
set_time_limit( 0 );
for ( $i = 0; $i < count( $data ); $i ++ ) {
	if ( ! $data[ $i ][12] ) {
		continue;
	}
	if ( ! $data[ $i ][4] ) {
		continue;
	}

	$createnewuser = true;
	$lawyer_name   = $data[ $i ][12];

	// Check if user exist
	$args  = array(
		'search'        => $lawyer_name, // or login or nicename in this example
		'search_fields' => array( 'user_login', 'user_nicename', 'display_name' )
	);
	$users = new WP_User_Query( $args );
	if ( isset( $users->results[0] ) ) {
		$createnewuser = $users->results[0]->ID;
	}

	$surname = explode( ' ', $lawyer_name )[0];
	$name    = explode( ' ', $lawyer_name )[1];

	$cat = $data[ $i ][4];

	if ( ! is_numeric( $createnewuser ) ) {
		$sanitized_user_login = sanitize_user( $surname );
		$userarr              = array(
			'first_name'   => $name,
			'last_name'    => $surname,
			'display_name' => $lawyer_name,
			'user_login'   => $sanitized_user_login,
			'user_email'   => $sanitized_user_login . '@mentorsflow.com',
		);

		$transliteratedString = transliterator_transliterate( 'Ukrainian-Latin/BGN', $surname );
		//$user = wp_insert_user($userarr);
	}

	var_dump($surname);
}

get_footer();
?>