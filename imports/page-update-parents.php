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
$spreadsheet = $reader->load( __DIR__ . "/kku.xlsx" );

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

$prearr = [];
$i      = 0;
foreach ( $data as $item ) {
	$elem         = (object) $item;
	$prearr[ $item[5] ?? $item[4] ?? $item[2] ] = $i;
	$i++;
}

unset($data[0]);
// Child Terms
echo '<ul>';
for ( $i = 0; $i < count( $data ); $i ++ ) {
	echo '<li>';
	$termname =  $data[$i][4] ?? $data[$i][3] ?? $data[$i][1];
	$parentname = $data[$i][2];
	if ($parentname == 'Кримінальний кодекс 2001 року' || mb_strlen($parentname) === 0 || $parentname == null) {
		$parentid = 7167;
	}else {
		$parentid = get_term_by('name', $parentname, TAXNAME)->term_id;
	}

	if ($parentid == null) $parentid = 7167;

	$tterm = get_term_by( 'name', $termname, TAXNAME );
	if ($tterm) {
		wp_update_term( $tterm->term_id, TAXNAME, [
			'parent' => $parentid
		] );
	}
	//var_dump($parentid);
	echo '</li>';
}
echo '</ul>';