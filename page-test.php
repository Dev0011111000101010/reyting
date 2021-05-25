<?php get_header(); ?>
<?php 
$termoriginal = 'countries';
$termcopyto = 'strana_golosovaniia';
$countriesterms = get_terms( array(
	'taxonomy'   => $termoriginal,
	'hide_empty' => false
) );

foreach ( $countriesterms as $term ) {
	if(!$term->parent) $parent = false;
	if ( $term->parent ) {
		$parentterm = get_term_by( 'id', $term->parent, $termcopyto );
		if ( ! $parentterm ) {
			$parentterm = get_term_by( 'id', $term->parent, $termoriginal );
			wp_insert_term( $parentterm->name, $termcopyto,
				array(
					'slug' => $parentterm->slug,
				) );
		}
		$parent = get_term_by( 'slug', get_term_by( 'id', $term->parent, $termoriginal )->slug, $termcopyto )->term_id;
	}
	$arr = array(
		'slug'   => $term->slug,
	);
	if($parent) {
		$arr['parent'] = $parent;
	}
	wp_insert_term( $term->name, $termcopyto,$arr);
}
?>
<?php get_footer(); ?>
