<?php
// WP делает некоторые проверки и подгружает только самое необходимое для подключения к БД
require_once( 'E:/OSPanel/domains/reyting/wp-load.php' );

require ABSPATH . '/vendor/autoload.php';

$files        = scandir( 'E:\OSPanel\domains\windows.parser\converted10k' );
$importedfile = 'E:\OSPanel\domains\windows.parser\converted10k\documents_part1_0.xlsx';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $importedfile );
$sheetData   = $spreadsheet->getActiveSheet()->toArray();

function userCreation($lawyer ) {
	preg_match( '/[А-ЯІIЇЄ]\.[А-ЯІIЇЄ]/u', $lawyer, $firstname );
	preg_match( '/[А-ЯІIЇЄ][а-яіiїє|А-ЯІIЇЄ]+/u', $lawyer, $lastname );
	$lastnametrans = transliterator_transliterate( 'Ukrainian-Latin/BGN', $lastname[0] );
	$username = mb_strtolower($lastnametrans) . rand( 0, 9 );
	$email    = $lastnametrans . '@mentorsflow.com';
	$uid      = wp_insert_user( array(
		'first_name'    => $firstname[0],
		'last_name'     => $lastname[0],
		'user_email'    => $email,
		'user_login'    => $username,
		'user_nicename' => $username,
		'display_name'  => $lawyer,
	) );

	$user = new WP_User( $uid );

	$user->add_role( 'lawyer' );
	$user->remove_role( 'author' );

	return $uid;
}

function writeJusticeKind($userpageid, $justice_kind) {
	$justice_kinds = get_post_meta( $userpageid, 'justice_kind' );
	$arr = ['1'=>'civil', '2'=>'criminal', '3'=>'commercial'];

	if ( ! $justice_kinds[ $justice_kind ] ) {
		update_post_meta( $userpageid, 'justice_kind', $justice_kind );
	}

	if($arr[$justice_kind]) {
		$current = (int) get_post_meta($userpageid, 'justice_'.$justice_kind.'_count', true);
		if(!$current) {
			$current = 1;
		}else {
			$current+=1;
		}
		update_post_meta($userpageid, 'justice_'.$justice_kind.'_count', $current);
	}
}

$startTime = microtime(true); //get time in micro seconds(1 millionth)
foreach ( $sheetData as $field ) {
	$lawyer       = $field[12];
	$casenum      = $field[5];
	$justice_kind = $field[3];

	if ( ! $lawyer || $casenum === 'cause_num' ) {
		continue;
	}

	$args = array(
		'search'        => "$lawyer", // or login or nicename in this example
		'search_fields' => array( 'display_name' )
	);

	$users = new WP_User_Query( $args );

	$users = $users->get_results();

	if ( !isset( $users[0] ) ) {
		$uid = userCreation($lawyer);
	}else {
		$uid = $users[0]->ID;
	}

	$userpageid = getUserPage($uid);

	writeJusticeKind($userpageid, $justice_kind);

	$wpdb->insert( 'lawyers_cases', [ 'lawyer_id' => $uid, 'scase' => $casenum ] );

	var_dump($lawyer);
}
$endTime = microtime(true);

echo "seconds to execute:". ($endTime-$startTime)*100000;

?>