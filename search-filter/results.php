<?php
/**
 * Search & Filter Pro
 *
 * Sample Results Template
 *
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      https://searchandfilter.com
 * @copyright 2018 Search & Filter
 *
 * Note: these templates are not full page templates, rather
 * just an encaspulation of the your results loop which should
 * be inserted in to other pages by using a shortcode - think
 * of it as a template part
 *
 * This template is an absolute base example showing you what
 * you can do, for more customisation see the WordPress docs
 * and using template tags -
 *
 * http://codex.wordpress.org/Template_Tags
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $query->have_posts() ) {
	?>

        <div class="found-results">
            Найдено <?php echo $query->found_posts; ?> Результатов<br/>
        </div>

    <div class="pagination">

		<?php
		/* example code for using the wp_pagenavi plugin */
		if ( function_exists( 'wp_pagenavi' ) ) {
			echo "<br />";
			echo '<nav>';
			wp_pagenavi( array( 'query' => $query ) );
			echo '</nav>';
		}
		?>
    </div>

	<?php
	while ( $query->have_posts() ) {
		$query->the_post();

		?>
		<?php get_template_part( 'template-parts/content', 'post' ); ?>
        <?php
	}
	?>

    <div class="pagination text-center">
        <?php
		/* example code for using the wp_pagenavi plugin */
		if ( function_exists( 'wp_pagenavi' ) ) {
			echo "<br />";
			wp_pagenavi( array( 'query' => $query ) );
		}
		?>
    </div>
	<?php
} else {
	echo "Результатов не найдено!";
}
?>