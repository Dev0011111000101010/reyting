<?php
// WP делает некоторые проверки и подгружает только самое необходимое для подключения к БД
require_once( 'E:/OSPanel/domains/reyting/wp-load.php' );

require ABSPATH . '/vendor/autoload.php';

$files        = scandir( 'E:\OSPanel\domains\windows.parser\converted10k' );
$importedfile = 'E:\OSPanel\domains\reyting\wp-content\themes\referendum\json_parts\cause_categories.xlsx';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $importedfile );
$sheetData   = $spreadsheet->getActiveSheet()->toArray();

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

function writeJusticeKind( $userpageid, $justice_kind ) {
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
}

unset( $sheetData[0] );

$startTime  = microtime( true ); //get time in micro seconds(1 millionth)
$categories = [
	6  => 'Кримінальні справи (з 01.01.2019)',
	1  => 'Адміністративні справи (до 01.01.2019)',
	2  => 'Адміністративні справи (з 01.01.2019)',
	3  => 'Господарські справи (до 01.01.2019)',
	4  => 'Господарські справи (з 01.01.2019)',
	5  => 'Кримінальні справи (до 01.01.2019)',
	7  => 'Окремі процесуальні питання',
	8  => 'Справи про адмінправопорушення (до 01.01.2019)',
	9  => 'Справи про адмінправопорушення (з 01.01.2019)',
	10 => 'Цивільні справи (до 01.01.2019)',
	11 => 'Цивільні справи (з 01.01.2019)'
];
$data       = '';
$dataarr    = [];
$part       = '0';
foreach ( $sheetData as $field ) {
	if ( in_array( $field[1], $categories ) ) {
		$part             = array_search( $field[1], $categories );
		$dataarr[ $part ] = [];
	}
	@array_push( $dataarr[ $part ], [ $field[0], $field[1] ] );
}

foreach ( $dataarr as $key => $value ) {
	if ( $key == '0' ) {
		continue;
	}

	$itemval = '';
	foreach ( $value as $item ) {
		if($categories[$key] == $item[1]) continue;
		$term    = get_term_by( 'name', $item[1], 'decisions_branch' );
		$itemval .= '<option class="sf-level-0 sf-item-' . $term->term_id . '" data-sf-count="0" data-sf-depth="1" value="' . $term->slug . '">' . $item[1] . '</option>';
	}
	file_put_contents(__DIR__.'/newjsonparts/branch'.$key.'.php', $itemval);
}
$endTime = microtime( true );

echo "seconds to execute:" . ( $endTime - $startTime ) * 100000;

?>