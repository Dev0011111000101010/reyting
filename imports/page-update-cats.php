<?php
get_header();
define('TAXNAME', 'decisions_branch');
if ( ! function_exists( 'mb_ucfirst' ) ) {
	function mb_ucfirst( $string, $enc = 'UTF-8' ) {
		return mb_strtoupper( mb_substr( $string, 0, 1, $enc ), $enc ) .
		       mb_substr( $string, 1, mb_strlen( $string, $enc ), $enc );
	}
}
require ABSPATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$spreadsheet = $reader->load( __DIR__ . "/kku2.xlsx" );

$data = $spreadsheet->getActiveSheet()->toArray();
set_time_limit( 0 );

//for ( $i = 0; $i < count( $data ); $i ++ ) {
//	$parentname = mb_ucfirst( mb_strtolower( $data[ $i ][2] ) );
//	if ( $parentname ) {
//		$parent = @get_term_by( 'name', $parentname, TAXNAME )->term_id;
//		var_dump( $parent );
//	}
//}

/* Import parents */

$terms = get_terms(
	array(
		'taxonomy'   => TAXNAME,
		'hide_empty' => false
	)
);

foreach ( $terms as $term ) {
	wp_delete_term( $term->term_id, TAXNAME );
}

$topparent = wp_insert_term( 'Кримінальний кодекс', TAXNAME )['term_id'];

$prearr = [];
$i      = 0;
foreach ( $data as $item ) {
	$elem         = (object) $item;
	$prearr[ $item[5] ?? $item[4] ?? $item[2] ] = $i;
	$i++;
}

// Child Terms
echo '<ul>';
for ( $i = 0; $i < count( $data ); $i ++ ) {
	echo '<li>';
	$termname =  $data[$i][4] ?? $data[$i][3] ?? $data[$i][1];
	$parentname = $data[$i][2];

	var_dump($i);
	var_dump($termname);
	$tterm = get_term_by( 'name', $termname, TAXNAME );
	if ($tterm) {
		$created = [
			'term_id' => $tterm->term_id
		];
		add_term_meta($created['term_id'], 'real_id', $data[$i][0]);
	}else {
		$arr = [
			'parent' => $topparent,
			'description' => $data[$i][3] ?? $data[$i][1]
		];

		$created = wp_insert_term(mb_convert_encoding($termname, 'UTF-8', 'auto'), TAXNAME, $arr);
	}

	if(is_wp_error($created)) {
		print_r( $created->get_error_code() );
	}else {
		add_term_meta((int) $created['term_id'], 'real_id', $data[$i][0], true);
	}
	echo '</li>';
}
echo '</ul>';