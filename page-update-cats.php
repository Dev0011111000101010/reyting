<?php
get_header();
define( 'TAXNAME', 'decisions_branch' );
require ABSPATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$spreadsheet = $reader->load( __DIR__ . "/documents_part1_0.xlsx" );

$data = $spreadsheet->getActiveSheet()->toArray();
set_time_limit( 0 );

function translit( $value ) {
	$converter = array(
		'а' => 'a',
		'б' => 'b',
		'в' => 'v',
		'г' => 'g',
		'д' => 'd',
		'е' => 'e',
		'ё' => 'e',
		'ж' => 'zh',
		'з' => 'z',
		'и' => 'i',
		'й' => 'i',
		'к' => 'k',
		'л' => 'l',
		'м' => 'm',
		'н' => 'n',
		'о' => 'o',
		'п' => 'p',
		'р' => 'r',
		'с' => 's',
		'т' => 't',
		'у' => 'u',
		'ф' => 'f',
		'х' => 'kh',
		'ц' => 'c',
		'ч' => 'ch',
		'ш' => 'sh',
		'щ' => 'shch',
		'ь' => '',
		'ы' => 'y',
		'ъ' => '',
		'є' => 'ie',
		'ю' => 'yu',
		'я' => 'ya',
		'і' => 'i',
		'ї' => 'i',

		'А' => 'A',
		'Б' => 'B',
		'В' => 'V',
		'Г' => 'G',
		'Д' => 'D',
		'Е' => 'E',
		'Є' => 'Ye',
		'Ж' => 'Zh',
		'З' => 'Z',
		'И' => 'I',
		'Й' => 'Y',
		'К' => 'K',
		'Л' => 'L',
		'М' => 'M',
		'Н' => 'N',
		'О' => 'O',
		'П' => 'P',
		'Р' => 'R',
		'С' => 'S',
		'Т' => 'T',
		'У' => 'U',
		'Ф' => 'F',
		'Х' => 'Kh',
		'Ц' => 'C',
		'Ч' => 'Ch',
		'Ш' => 'Sh',
		'Щ' => 'Shch',
		'Ь' => '',
		'Ы' => 'Y',
		'Ъ' => '',
		'Э' => 'E',
		'Ю' => 'Yu',
		'Я' => 'Ya',
		'I' => 'I',
		'Ї' => 'Yi'
	);

	$value = strtr( $value, $converter );

	return $value;
}

function userCreation( $lawyer ) {
	preg_match( '/[А-ЯІIЇЄ]\.[А-ЯІIЇЄ]/u', $lawyer, $firstname );
	preg_match( '/[А-ЯІIЇЄ][а-яіiїє|А-ЯІIЇЄ]+/u', $lawyer, $lastname );
	$username = mb_strtolower( translit( $lastname[0] ) ) . rand( 0, 9 );
	$email    = translit( $lastname[0] ) . '@mentorsflow.com';
	$userinfo = array(
		'first_name'    => $firstname[0],
		'last_name'     => $lastname[0],
		'user_email'    => $email,
		'user_login'    => $username,
		'user_nicename' => $username,
		'display_name'  => $lawyer,
	);

	$uid = wp_insert_user( $userinfo );

	if ( is_wp_error( $uid ) && $uid->get_error_code() == 'existing_user_email' ) {
		$uid = get_user_by( 'email', $email )->ID;
	} elseif ( is_wp_error( $uid ) && $uid->get_error_code() == 'existing_user_login' ) {
		$uid = get_user_by( 'login', $email )->ID;
	}

	$user = new WP_User( $uid );

	$user->add_role( 'lawyer' );
	$user->remove_role( 'author' );

	return $uid;
}

function writeJusticeKind( $userpageid, $justice_kind, $category_code ) {
	$justice_kinds = get_post_meta( $userpageid, 'justice_kind' );
	$arr           = [ '1' => 'civil', '2' => 'criminal', '3' => 'commercial' ];

	if ( ! $justice_kinds[ $justice_kind ] ) {
		update_post_meta( $userpageid, 'justice_kind', $justice_kind );
	}

	if ( $arr[ $justice_kind ] ) {
		$current = (int) get_post_meta( $userpageid, 'justice_' . $justice_kind . '_count', true );
		if ( ! $current ) {
			$current = 1;
		} else {
			$current += 1;
		}
		update_post_meta( $userpageid, 'justice_' . $justice_kind . '_count', $current );
	}

	$terms = get_terms( array(
		'taxonomy'   => 'decisions_branch',
		'meta_key'   => 'real_id',
		'meta_value' => $category_code,
		'hide_empty' => false
	) );

	$term = $terms[0]->term_id;

	var_dump( $terms );

	wp_set_post_terms( $userpageid, $term, 'decisions_branch', true );
}

global $wpdb;
$startTime = microtime( true ); //get time in micro seconds(1 millionth)
foreach ( $data as $field ) {
	$lawyer        = $field[12];
	$casenum       = $field[5];
	$justice_kind  = $field[3];
	$category_code = $field[4];

	if ( ! $lawyer || $casenum === 'cause_num' ) {
		continue;
	}

	$args = array(
		'search'        => strval( $lawyer ), // or login or nicename in this example
		'search_fields' => array( 'display_name' )
	);

	$users = new WP_User_Query( $args );

	$users = $users->get_results();

	if ( ! count( $users ) ) {
		$uid = userCreation( $lawyer );
	} else {
		$uid = $users[0]->ID;
	}

	if ( ! $uid ) {
		continue;
	}
	$userpageid = getUserPage( $uid );

	writeJusticeKind( $userpageid, $justice_kind, $category_code );
//
//	$lawyers_cases = [ 'lawyer_id' => $uid, 'scase' => $casenum ];
//	$wpdb->insert( 'lawyers_cases',  $lawyers_cases);
//
//	var_dump($lawyers_cases);
}
$endTime = microtime( true );

echo "seconds to execute:" . ( $endTime - $startTime ) * 100000;
